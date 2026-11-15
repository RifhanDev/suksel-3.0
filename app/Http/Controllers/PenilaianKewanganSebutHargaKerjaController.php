<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Http\Controllers\Concerns\RestrictsTenderByRole;
use App\Models\BonSaham;
use App\Models\Jawatankuasa;
use App\Models\KewanganKerjaHeader;
use App\Models\KewanganKerjaItem;
use App\Models\LembaranImbangan;
use App\Models\PenyataBank;
use App\Models\PenyediaanIklan;
use App\Models\PenyediaanMesyuaratMeeting;
use App\Models\TenderKakitanganTeknikal;
use App\Models\TenderKakitanganTeknikalDokumen;
use App\Models\TenderKewanganEvaluation;
use App\Models\TenderKewanganKerjaEvaluation;
use App\Models\TenderKewanganLaporan;
use App\Models\TenderKewanganProgress;
use App\Models\TenderPrestasiKerja;
use App\Models\TenderVendorDokumenFile;
use App\Models\TenderVendorDokumenResponse;
use App\Models\TenderVendorFormPayload;
use App\Services\PenilaianKewanganWorkflowResolver;
use App\Services\StosBackendClient;
use App\Services\VendorDokumenResponseService;
use App\Services\VendorFormPayloadService;
use App\Support\ChecklistMechanism;
use App\Support\PenilaianKewanganKerjaCalculator;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderCode;
use App\TenderVendor;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenilaianKewanganSebutHargaKerjaController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;
    use RestrictsTenderByRole;

    protected array $financialCommitteeJenis = ['fin', 'eval', 'harga'];

    public function __construct(
        protected PenilaianKewanganKerjaCalculator $calculator
    ) {
        $this->menuMiddleware('FinancialEvaluation:list');
    }

    /**
     * Vendors selected at the submitted Cut Off stage who passed Penilaian Teknikal.
     * TenderVendor::kod_pembekal remains the designated tender-specific vendor code.
     */
    protected function loadEligibleParticipants(?Tender $tender, ?bool $includeFinanciallyEliminated = null): Collection
    {
        if (! $tender) {
            return collect();
        }

        $includeFinanciallyEliminated ??= (int) ($tender->status_process_id ?? 0)
            > TenderProcessStatus::penilaianKewanganListStatus();

        $cutOffRefs = TenderVendor::submittedCutOffVendorRefs($tender->id);
        if ($cutOffRefs === []) {
            return collect();
        }

        $numericRefs = array_values(array_filter($cutOffRefs, fn ($ref) => ctype_digit($ref)));
        $query = $tender->participants()
            ->with(['vendor.cidbGrades.code', 'vendor.cidbGrades.children.code'])
            ->where('participate', 1)
            ->where('submitted', 1)
            ->where(function ($q) use ($cutOffRefs, $numericRefs) {
                $q->whereIn('kod_pembekal', $cutOffRefs);

                if ($numericRefs !== []) {
                    $q->orWhereIn('vendor_id', array_map('intval', $numericRefs));
                }

                $q->orWhereHas('vendor', fn ($vendor) => $vendor->whereIn('registration', $cutOffRefs));
            });

        if ($includeFinanciallyEliminated) {
            // Keep vendors eliminated during/after financial evaluation in the history view.
            $query->where(function ($q) {
                $q->whereNull('eliminated_process_id')
                    ->orWhere('eliminated_process_id', '>', TenderProcessStatus::PENILAIAN_TEKNIKAL);
            })->where(function ($q) {
                $q->where('cancel_fg', 0)
                    ->orWhere('eliminated_process_id', '>', TenderProcessStatus::PENILAIAN_TEKNIKAL);
            });
        } else {
            $query->where('cancel_fg', 0)->whereNull('eliminated_process_id');
        }

        $participants = $query->orderBy('id')->get();

        return $this->resolveAndSortParticipantsByHargaTawaran($tender, $participants);
    }

    /**
     * Checklist items that require an officer decision in Step 1.
     * Keep this source and filtering aligned with the checklist rendered by show().
     */
    protected function requiredStep1ChecklistIds(Tender $tender): array
    {
        $items = collect(TenderDokumenPresenter::for($tender)->items('admin'))
            ->filter(function ($item) {
                $action = strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? $item['mechanism'] ?? ''));
                return $action !== 'muat turun';
            })
            ->values()
            ->map(fn ($item, $idx) => $item['uuid'] ?? ('doc-' . ($idx + 1)))
            ->values();

        if ($items->isEmpty()) {
            $items = KewanganKerjaItem::query()
                ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
                ->where(function ($query) {
                    $query->whereNull('mechanism')->orWhere('mechanism', '!=', 'ptj_muat_naik')
                        ->orWhereNull('vendor_action')->orWhere('vendor_action', '!=', 'muat_turun');
                })
                ->orderBy('sort_order')
                ->pluck('uuid');
        }

        return $items->filter()->unique()->values()->all();
    }

    /** All required Step 1 checklist decisions exist for every eligible vendor. */
    protected function step1EvaluationsComplete(Tender $tender, Collection $participants, array $checklistIds): bool
    {
        if ($participants->isEmpty() || empty($checklistIds)) {
            return false;
        }

        $vendorIds = $participants->pluck('vendor_id')->map(fn ($id) => (int) $id);
        $evaluated = TenderKewanganEvaluation::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->whereIn('checklist_item_uuid', $checklistIds)
            ->whereNotNull('status_pematuhan')
            ->get(['vendor_id', 'checklist_item_uuid'])
            ->groupBy('vendor_id');

        return $participants->every(function ($participant) use ($evaluated, $checklistIds) {
            return ($evaluated->get($participant->vendor_id, collect())
                ->pluck('checklist_item_uuid')->unique()->count()) === count($checklistIds);
        });
    }

    /** Return vendors who completed and passed every required Step 1 item. */
    protected function step1PassedVendorIds(Tender $tender, Collection $participants, array $checklistIds): array
    {
        if ($participants->isEmpty() || empty($checklistIds)) {
            return [];
        }

        $evaluations = TenderKewanganEvaluation::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $participants->pluck('vendor_id'))
            ->whereIn('checklist_item_uuid', $checklistIds)
            ->get()
            ->groupBy('vendor_id');

        return $participants->filter(function ($participant) use ($evaluations, $checklistIds) {
            $vendorEvaluations = $evaluations->get($participant->vendor_id, collect())
                ->keyBy('checklist_item_uuid');

            if ($vendorEvaluations->count() < count($checklistIds)) {
                return false;
            }

            foreach ($checklistIds as $checklistId) {
                $evaluation = $vendorEvaluations->get($checklistId);
                if (! $evaluation || $evaluation->status_pematuhan === null || (int) $evaluation->status_pematuhan !== 1) {
                    return false;
                }
            }

            return true;
        })->pluck('vendor_id')->map(fn ($id) => (int) $id)->values()->all();
    }

    /** Verify each passed vendor has saved an officer decision for the given step. */
    protected function vendorStepEvaluationsComplete(Tender $tender, array $vendorIds, string $borangCode): bool
    {
        if (empty($vendorIds)) {
            return true;
        }

        $evaluatedVendorIds = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', $borangCode)
            ->whereIn('vendor_id', $vendorIds)
            ->whereNotNull('status_pematuhan')
            ->pluck('vendor_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return count(array_unique($evaluatedVendorIds)) === count(array_unique(array_map('intval', $vendorIds)));
    }

    /** Return vendors with a saved passing decision for a Type 2 evaluation step. */
    protected function stepPassedVendorIds(Tender $tender, array $vendorIds, string $borangCode): array
    {
        if (empty($vendorIds)) {
            return [];
        }

        return TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', $borangCode)
            ->whereIn('vendor_id', $vendorIds)
            ->where('status_pematuhan', 1)
            ->pluck('vendor_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Display the dedicated Type 2 (Sebut Harga Kerja) evaluation interface.
     */
    public function show(string $tender_no)
    {
        $tender = Tender::query()
            ->with(['tenderer', 'kategoriPerolehan', 'kategoriPerolehanDetail', 'kaedahPerolehan'])
            ->where(function ($q) use ($tender_no) {
                $q->where('no_tender', $tender_no)
                  ->orWhere('ref_number', $tender_no)
                  ->orWhere('uuid', $tender_no);
                if (is_numeric($tender_no)) {
                    $q->orWhere('id', (int) $tender_no);
                }
            })
            ->first();

        if (! $tender) {
            $tender = $this->resolveTenderByIdentifier($tender_no);
        }

        if (! $tender) {
            return redirect()->route('penilaianKewangan')
                ->with('error', 'Maklumat perolehan Sebut Harga Kerja tidak ditemui.');
        }

        // Validate committee role
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);

        // Verify workflow classification
        $resolver = app(PenilaianKewanganWorkflowResolver::class);
        $workflow = $resolver->classify($tender);

        if ($workflow !== PenilaianKewanganWorkflowResolver::WORKFLOW_SEBUT_HARGA_KERJA) {
            return redirect()->route('penilaianKewangan.show', $tender->uuid ?: $tender->id);
        }

        $currentStatus = (int) ($tender->status_process_id ?? 0);
        if ($currentStatus < TenderProcessStatus::penilaianKewanganListStatus() || $currentStatus > TenderProcessStatus::SELESAI) {
            return redirect()->route('penilaianKewangan')
                ->with('error', 'Rekod Penilaian Kewangan Sebut Harga Kerja hanya boleh dilihat dari status 10 hingga tender selesai.');
        }

        $readOnly = $currentStatus > TenderProcessStatus::penilaianKewanganListStatus();

        // 1. Tender Display Metadata
        $no_tender_display = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;
        $tajuk_display = $tender->name ?? '-';
        $ptj_display = $tender->tenderer->name ?? '-';
        $tempoh_sah_laku = 90;
        $submissionDate = $tender->submission_datetime ? Carbon::parse($tender->submission_datetime) : null;
        $sah_laku_tamat = $submissionDate ? $submissionDate->copy()->addDays($tempoh_sah_laku)->format('d/m/Y') : '-';
        $status_label = $readOnly ? 'Selesai Penilaian' : 'Menunggu Penilaian Kewangan & Kerja';

        $anggaranVal = (float) ($tender->anggaran_jabatan ?? $tender->harga_indikatif ?? $tender->price ?? 500000);
        $anggaran_display = 'RM ' . number_format($anggaranVal, 2);

        // 1.5% Minimum Capital threshold (Treasury Circular PK 2.1)
        $minCapitalThreshold = PenilaianKewanganKerjaCalculator::DEFAULT_MIN_CAPITAL_PERCENT;
        $minCapitalRequired = $anggaranVal * ($minCapitalThreshold / 100);
        $min_modal_display = 'RM ' . number_format($minCapitalRequired, 2);

        // 2. Workflow Progress State
        $progress = TenderKewanganProgress::query()->firstOrNew(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $step1Confirmed = $progress->isStep1Confirmed();
        $step2Confirmed = $progress->isStep2Confirmed();
        $step3Confirmed = $progress->isStep3Confirmed();

        // 3. Checklist Items (Kategori Kerja)
        $tenderDokumen = TenderDokumenPresenter::for($tender);
        $rawChecklistItems = $tenderDokumen->items('admin');

        // Filter out download-only items
        $checklistDocs = collect($rawChecklistItems)
            ->filter(function ($item) {
                $action = strtolower(trim($item['tindakan'] ?? $item['mekanisma'] ?? $item['mechanism'] ?? ''));
                return $action !== 'muat turun';
            })
            ->values()
            ->map(function ($item, $idx) use ($tender) {
                $uuid = $item['uuid'] ?? ('doc-' . ($idx + 1));
                $title = $item['title'] ?? $item['nama'] ?? ('Dokumen ' . ($idx + 1));

                $itemAction = $item['action'] ?? null;
                $rawTindakan = $item['tindakan'] ?? $item['mechanism'] ?? $item['mekanisma'] ?? '';
                $rawLower = strtolower(trim((string) $rawTindakan));

                // 1. Accurately resolve action and human-readable mechanism
                if ($itemAction === 'view_specification' || in_array($rawLower, ['spesifikasi', 'specification'], true)) {
                    $action = 'view_specification';
                    $mechanism = 'Spesifikasi';
                } elseif ($itemAction === 'online_form' || in_array($rawLower, ['borang atas talian', 'online_form', 'borang_atas_talian'], true)) {
                    $action = 'online_form';
                    $mechanism = 'Borang Atas Talian';
                } elseif (in_array($rawLower, ['muat turun dan muat naik', 'download_upload'], true)) {
                    $action = 'download_upload';
                    $mechanism = 'Muat Turun dan Muat Naik';
                } else {
                    $action = $itemAction ?: 'vendor_upload';
                    $mechanism = 'Muat Naik Dokumen';
                }

                $formUrl = $item['admin_content']['form']['url'] ?? null;
                $formKey = $item['admin_content']['form']['form_key'] ?? null;

                if (! $formUrl && $action === 'online_form') {
                    $formKey = \App\Support\OnlineFormRegistry::formKeyFor($title);
                    if ($formKey) {
                        $formUrl = \App\Support\TenderDokumenFormRoutes::resolve('/' . str_replace('_', '-', $formKey), $tender);
                    }
                }

                return [
                    'id'        => $uuid,
                    'uuid'      => $uuid,
                    'title'     => $title,
                    'mekanisma' => $mechanism,
                    'action'    => $action,
                    'is_bq_template' => ! empty($item['admin_content']['bq_template_mode']),
                    'form_url'  => $formUrl,
                    'form_key'  => $formKey,
                    'required'  => true,
                ];
            })
            ->all();

        // If no items found from presenter, load directly from KewanganKerjaItem
        if (empty($checklistDocs)) {
            $dbItems = KewanganKerjaItem::query()
                ->whereHas('header', fn ($q) => $q->where('tender_id', $tender->id))
                ->where(function ($query) {
                    $query->whereNull('mechanism')->orWhere('mechanism', '!=', 'ptj_muat_naik')
                        ->orWhereNull('vendor_action')->orWhere('vendor_action', '!=', 'muat_turun');
                })
                ->orderBy('sort_order')
                ->get();

            if ($dbItems->isNotEmpty()) {
                $checklistDocs = $dbItems->map(fn ($item, $idx) => [
                    'id'        => $item->uuid,
                    'uuid'      => $item->uuid,
                    'title'     => $item->title ?? ('Dokumen ' . ($idx + 1)),
                    'mekanisma' => match ($item->mechanism) {
                        'spesifikasi' => 'Spesifikasi',
                        'online_form', 'borang_atas_talian' => 'Borang Atas Talian',
                        default => 'Muat Naik Dokumen',
                    },
                    'action'    => match ($item->mechanism) {
                        'spesifikasi' => 'view_specification',
                        'online_form', 'borang_atas_talian' => 'online_form',
                        default => 'vendor_upload',
                    },
                    'form_url'  => \App\Support\TenderDokumenFormRoutes::resolve($item->action_url, $tender),
                    'form_key'  => \App\Support\OnlineFormRegistry::formKeyFor($item->title ?? ''),
                    'required'  => true,
                ])->all();
            }
        }

        // 4. Participating Vendors (strictly eligible from preceding processes)
        $participants = $this->loadEligibleParticipants($tender);

        $vendorIds = $participants->pluck('vendor_id');

        // Batch load Step 1 evaluations
        $step1Evaluations = TenderKewanganEvaluation::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->groupBy('vendor_id');

        // Batch load Step 2 & 3 evaluations
        $step2Evaluations = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', 'sh_kemampuan_kewangan')
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->keyBy('vendor_id');

        $step3Evaluations = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', 'sh_penilaian_kerja')
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->keyBy('vendor_id');

        // Batch load uploaded files for Step 1
        $uploadedFiles = TenderVendorDokumenFile::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->groupBy(fn ($f) => $f->vendor_id . '_' . $f->checklist_item_uuid);

        // Batch load form payloads
        $formPayloads = TenderVendorFormPayload::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->groupBy('vendor_id');

        // Batch load Lembaran Imbangan
        $lembaranDb = LembaranImbangan::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->keyBy('vendor_id');

        // Tender Penyata Bank configuration/record
        $penyataBankDb = PenyataBank::query()
            ->with(['bulans'])
            ->where('tender_id', $tender->id)
            ->first();

        // Batch load Penyata Bank Files to resolve physical file downloads
        $penyataBankFilesDb = $penyataBankDb ? \Illuminate\Support\Facades\DB::table('penyata_bank_files')
            ->where('penyata_bank_id', $penyataBankDb->id)
            ->get() : collect();

        // Batch load Bon & Saham
        $bonSahamDb = BonSaham::query()
            ->with(['accounts'])
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->keyBy('vendor_id');

        // Batch load Prestasi Kerja
        $prestasiDb = TenderPrestasiKerja::query()
            ->with(['items'])
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->get()
            ->keyBy('vendor_id');

        // Batch load Kakitangan Teknikal
        $kakitanganDb = TenderKakitanganTeknikal::query()
            ->with(['dokumens'])
            ->whereIn('vendor_id', $vendorIds)
            ->where(function ($q) use ($tender) {
                $q->where('tender_uuid', $tender->uuid)
                  ->orWhere('tender_uuid', (string) $tender->id);
            })
            ->get()
            ->groupBy('vendor_id');

        // Batch load general documents for Kakitangan Teknikal (KWSP, SOCSO, etc.)
        $generalKakitanganDocsDb = TenderKakitanganTeknikalDokumen::query()
            ->whereIn('vendor_id', $vendorIds)
            ->where(function ($q) use ($tender) {
                $q->where('tender_uuid', $tender->uuid)
                  ->orWhere('tender_uuid', (string) $tender->id);
            })
            ->where(function ($q) {
                $q->whereNull('kakitangan_uuid')->orWhere('kakitangan_uuid', '');
            })
            ->get()
            ->groupBy('vendor_id');

        // Construct unified vendor array
        $vendors = [];
        foreach ($participants as $idx => $p) {
            $vId = $p->vendor_id;
            $vendor = $p->vendor;

            $kod = $p->kod_pembekal ?: '-';
            $vendorName = $vendor->name ?? $vendor->company_name ?? ('Petender ' . $kod);
            $ssm = $vendor->registration_number ?? $vendor->ssm_number ?? $vendor->ssm ?? '-';

            // Read CIDB grade and specializations from the vendor's registered CIDB codes.
            $cidbGradeRows = $vendor?->cidbGrades ?? collect();
            $cidbGradeDisplay = $cidbGradeRows
                ->map(function ($grade) {
                    $gradeCode = $grade->code->code ?? $grade->code->name ?? null;
                    if (! $gradeCode) {
                        return null;
                    }

                    $specializations = $grade->children
                        ->map(fn ($child) => $child->code->code ?? null)
                        ->filter()
                        ->unique()
                        ->sort()
                        ->values();

                    return $gradeCode . ($specializations->isNotEmpty()
                        ? ' (' . $specializations->implode(', ') . ')'
                        : '');
                })
                ->filter()
                ->unique()
                ->values();
            $cidbDisplay = $cidbGradeDisplay->isNotEmpty()
                ? 'CIDB Gred ' . $cidbGradeDisplay->implode('; ')
                : 'CIDB Gred: Tiada Rekod';

            $hargaTawaran = $p->harga_tawaran_resolved ?? ((float) ($p->harga_tawaran ?? $p->bid_price ?? $p->price ?? 0));
            $hargaTawaranDisp = $p->harga_tawaran_fmt ?? ($hargaTawaran > 0 ? ('RM ' . number_format($hargaTawaran, 2)) : '-');
            $tempohSiap = $p->tempoh_siap ? ($p->tempoh_siap . ' Minggu') : '16 Minggu';

            // --- STEP 1: Docs Evaluation Map ---
            $vStep1Evals = $step1Evaluations->get($vId, collect())->keyBy('checklist_item_uuid');
            $docsMap = [];
            $hasAnyFail = false;
            $evaluatedCount = 0;

            foreach ($checklistDocs as $doc) {
                $docUuid = $doc['uuid'];
                $evalRow = $vStep1Evals->get($docUuid);

                // Find uploaded files (strictly scoped to this vendor: $vId)
                $filesGroup = $uploadedFiles->get($vId . '_' . $docUuid, collect());
                $uploadedFilesList = $filesGroup->map(fn ($f) => [
                    'url'           => $f->url(),
                    'name'          => $f->original_name,
                    'original_name' => $f->original_name,
                    'uuid'          => $f->uuid,
                    'size'          => $f->size,
                    'mime_type'     => $f->mime_type,
                    'is_pdf'        => strtolower((string) $f->mime_type) === 'application/pdf'
                        || strtolower(pathinfo((string) $f->original_name, PATHINFO_EXTENSION)) === 'pdf',
                ])->values()->all();

                $firstFile = $filesGroup->first();
                $fileUrl = null;
                $fileName = null;

                if ($firstFile) {
                    $fileUrl = $firstFile->url();
                    $fileName = $firstFile->original_name;
                }

                // If online form or if form URL exists, build vendor-specific form view URL
                $formUrl = $doc['form_url'] ?? null;
                if ($formUrl) {
                    $sep = str_contains($formUrl, '?') ? '&' : '?';
                    if (! str_contains($formUrl, 'vendor_id=')) {
                        $formUrl .= $sep . 'vendor_id=' . $vId;
                        $sep = '&';
                    }
                    if (! str_contains($formUrl, 'modal=1')) {
                        $formUrl .= $sep . 'modal=1';
                        $sep = '&';
                    }
                    if (! str_contains($formUrl, 'mode=view')) {
                        $formUrl .= $sep . 'mode=view';
                    }
                }

                // If action is view_specification, generate specification preview URL
                if (($doc['action'] ?? '') === 'view_specification') {
                    $formUrl = route('tenderDokumen.specificationForm', [
                        'tender'    => $tender->id,
                        'itemUuid'  => $docUuid,
                        'vendor_id' => $vId,
                        'modal'     => 1,
                        'mode'      => 'view',
                        'summary'   => 'dokumentasi',
                    ]);
                }

                // If no uploaded file but formUrl exists, use formUrl for preview
                if (! $fileUrl && $formUrl) {
                    $fileUrl = $formUrl;
                    $fileName = $doc['title'];
                }

                $isEvaluated = $evalRow !== null && $evalRow->status_pematuhan !== null;
                $statusVal = ! $isEvaluated
                    ? null
                    : (((int) $evalRow->status_pematuhan === 1) ? 'sempurna' : 'tidak_sempurna');
                $noteVal = $evalRow?->catatan ?? '';

                if ($isEvaluated) {
                    $evaluatedCount++;
                    if ((int) $evalRow->status_pematuhan === 0) {
                        $hasAnyFail = true;
                    }
                }

                $isSpec = (($doc['action'] ?? '') === 'view_specification');
                $isOnlineForm = (($doc['action'] ?? '') === 'online_form') || !empty($formUrl);
                $hasFileOrSubmission = ($firstFile !== null) || !empty($formUrl) || $isSpec;

                $docsMap[$docUuid] = [
                    'status'    => $statusVal,
                    'evaluated' => $isEvaluated,
                    'catatan'   => $noteVal,
                    'file_url'  => $fileUrl,
                    'file_name' => $fileName,
                    'files'     => $uploadedFilesList,
                    'has_file'  => $hasFileOrSubmission,
                    'form_url'  => $formUrl,
                    'is_form'   => $isOnlineForm,
                    'is_spec'   => $isSpec,
                ];
            }

            $step1Complete = $evaluatedCount === count($checklistDocs) && count($checklistDocs) > 0;
            $step1Status = ! $step1Complete
                ? 'menunggu_penilaian'
                : ($hasAnyFail ? 'tidak_sempurna' : 'sempurna');
            $step1Catatan = ! $step1Complete
                ? 'Penilaian dokumen mandatori masih belum lengkap.'
                : ($hasAnyFail
                    ? 'Gagal mematuhi satu atau lebih dokumen mandatori.'
                    : 'Semua dokumen mandatori lengkap dan teratur.');

            // --- STEP 2: Kemampuan Kewangan Data ---
            $vPayloads = $formPayloads->get($vId, collect())->keyBy('form_key');

            // Lembaran Imbangan
            $lObj = $lembaranDb->get($vId);
            $lPayload = $vPayloads->get('lembaran_imbangan')?->payload ?? [];
            $lembaranData = [
                'aset_tetap'             => $lObj->aset_tetap ?? $lPayload['aset_tetap'] ?? 0,
                'aset_semasa'            => $lObj->aset_semasa ?? $lPayload['aset_semasa'] ?? 0,
                'liabiliti_semasa'       => $lObj->liabiliti_semasa ?? $lPayload['liabiliti_semasa'] ?? 0,
                'liabiliti_tetap'        => $lObj->liabiliti_tetap ?? $lPayload['liabiliti_tetap'] ?? 0,
                'wang_tunai'             => $lObj->wang_tunai ?? $lPayload['wang_tunai'] ?? 0,
                'baki_kemudahan_kredit'  => $lObj->baki_kemudahan_kredit ?? $lPayload['baki_kemudahan_kredit'] ?? 0,
            ];

            // Penyata Bank
            $pbObj = $penyataBankDb;
            $pbPayload = $vPayloads->get('penyata_bank')?->payload ?? [];
            $pbAccounts = [];
            $pbGrandTotal = 0.0;
            $bankFiles = [];

            // 1. Prioritize vendor's actual submission from TenderVendorFormPayload
            $rawAccounts = [];
            if (! empty($pbPayload['accounts']) && is_array($pbPayload['accounts'])) {
                $rawAccounts = $pbPayload['accounts'];
            } elseif ($pbObj && ! empty($pbObj->accounts) && is_array($pbObj->accounts)) {
                $rawAccounts = $pbObj->accounts;
            } elseif ($pbObj && $pbObj->bulans && $pbObj->bulans->count() > 0) {
                $rawAccounts = [[
                    'bank_name' => 'BANK 1',
                    'bulans'    => $pbObj->bulans->map(fn ($b) => [
                        'bulan'  => $b->bulan,
                        'tahun'  => $b->tahun,
                        'jumlah' => (float) $b->jumlah,
                    ])->all(),
                ]];
            }

            foreach ($rawAccounts as $aIdx => $acc) {
                $mAmounts = [0.0, 0.0, 0.0];
                $bNames = [];
                $bulans = $acc['bulans'] ?? [];

                if (empty($bulans) && ! empty($pbPayload['bulans']) && is_array($pbPayload['bulans'])) {
                    $bulans = $pbPayload['bulans'];
                }

                foreach ($bulans as $bIdx => $bVal) {
                    $amt = (float) (is_array($bVal) ? ($bVal['jumlah'] ?? $bVal['amount'] ?? 0) : $bVal);
                    if ($bIdx < 3) {
                        $mAmounts[$bIdx] = $amt;
                    }
                    if (is_array($bVal) && isset($bVal['bulan'])) {
                        $monthNum = (int) $bVal['bulan'];
                        $yearNum = $bVal['tahun'] ?? '';
                        $bNames[] = 'Bulan ' . ($bIdx + 1) . ' (' . $monthNum . '/' . $yearNum . ')';
                    }
                }

                $accTotal = (float) ($acc['jumlah_keseluruhan'] ?? $acc['total'] ?? array_sum($mAmounts));
                $accPurata = (float) ($acc['purata'] ?? ($accTotal > 0 ? $accTotal / 3 : 0));
                $pbGrandTotal += $accTotal;

                // Extract and resolve account files
                $accFiles = [];
                if (! empty($acc['files']) && is_array($acc['files'])) {
                    foreach ($acc['files'] as $f) {
                        if (! is_array($f)) continue;
                        $fname = $f['original_name'] ?? $f['name'] ?? 'Penyata Bank';
                        $furl = $f['url'] ?? null;
                        $fuuid = $f['uuid'] ?? null;

                        $matchedUuid = $fuuid
                            ? $penyataBankFilesDb->first(fn ($candidate) => $candidate->uuid === $fuuid)
                            : null;
                        if ($matchedUuid) {
                            $belongsToAccount = (int) $matchedUuid->vendor_id === (int) $vId
                                && ($matchedUuid->account_uuid
                                    ? $matchedUuid->account_uuid === ($acc['account_uuid'] ?? null)
                                    : (int) ($matchedUuid->account_index ?? 0) === (int) $aIdx);
                            if (! $belongsToAccount) {
                                continue;
                            }
                            $fname = $matchedUuid->original_name ?: $fname;
                            $fuuid = $matchedUuid->uuid;
                            $furl = route('tenderDokumen.download', $fuuid);
                        }

                        if (! $furl && $fuuid) {
                            $furl = route('tenderDokumen.download', $fuuid);
                        }
                        if (! $furl && ! empty($f['path'])) {
                            $cleanPath = ltrim(str_replace(['\\', 'public/'], ['/', ''], (string) $f['path']), '/');
                            $furl = route('tenderDokumen.streamByPath', ['path' => $cleanPath, 'name' => $fname]);
                        }
                        if (! $furl) {
                            $matched = $penyataBankFilesDb->first(function ($candidate) use ($fname, $vId, $acc, $aIdx) {
                                $sameAccount = $candidate->account_uuid
                                    ? $candidate->account_uuid === ($acc['account_uuid'] ?? null)
                                    : (int) ($candidate->account_index ?? 0) === (int) $aIdx;
                                return (int) $candidate->vendor_id === (int) $vId
                                    && $sameAccount
                                    && strcasecmp((string) $candidate->original_name, (string) $fname) === 0;
                            });
                        } else {
                            $matched = null;
                        }
                        if ($matched) {
                            $fuuid = $matched->uuid;
                            $furl = route('tenderDokumen.download', $matched->uuid);
                        }

                        $fileItem = [
                            'name'          => $fname,
                            'original_name' => $fname,
                            'url'           => $furl,
                            'uuid'          => $fuuid,
                            'size'          => $f['size'] ?? null,
                            'account_index' => $aIdx,
                            'account_uuid'  => $acc['account_uuid'] ?? null,
                            'account_label' => 'Akaun ' . ($aIdx + 1) . ' - ' . ($acc['bank_name'] ?? ('BANK ' . ($aIdx + 1))),
                        ];
                        $accFiles[] = $fileItem;
                        $bankFiles[] = $fileItem;
                    }
                }

                $pbAccounts[] = [
                    'bank_name'          => $acc['bank_name'] ?? ('BANK ' . ($aIdx + 1)),
                    'account_no'         => $acc['account_no'] ?? $acc['no_akaun'] ?? ('Akaun ' . ($aIdx + 1)),
                    'bulans'             => $bulans,
                    'bulan_names'        => $bNames,
                    'monthly_amounts'    => $mAmounts,
                    'total'              => $accTotal,
                    'jumlah_keseluruhan' => $accTotal,
                    'purata'             => $accPurata,
                    'files'              => $accFiles,
                ];
            }

            // Top-level files in vendor's $pbPayload (strictly scoped to this vendor)
            foreach (['files', 'dokumen', 'dokumen_sokongan', 'lampiran'] as $fKey) {
                if (! empty($pbPayload[$fKey]) && is_array($pbPayload[$fKey])) {
                    foreach ($pbPayload[$fKey] as $f) {
                        if (! is_array($f)) continue;
                        $fname = $f['original_name'] ?? $f['name'] ?? 'Penyata Bank';
                        $furl = $f['url'] ?? null;
                        $fuuid = $f['uuid'] ?? null;
                        if (! $furl && $fuuid) {
                            $furl = route('tenderDokumen.download', $fuuid);
                        }
                        if (! $furl) {
                            $matched = $penyataBankFilesDb->first(fn ($candidate) =>
                                (int) $candidate->vendor_id === (int) $vId
                                && strcasecmp((string) $candidate->original_name, (string) $fname) === 0
                            );
                        } else {
                            $matched = null;
                        }
                        if ($matched) {
                            $fuuid = $matched->uuid;
                            $furl = route('tenderDokumen.download', $matched->uuid);
                        }
                        $bankFiles[] = [
                            'name'          => $fname,
                            'original_name' => $fname,
                            'url'           => $furl,
                            'uuid'          => $fuuid,
                            'size'          => $f['size'] ?? null,
                        ];
                    }
                }
            }

            // Deduplicate files for this vendor
            $bankFiles = collect($bankFiles)->unique(fn ($f) => ($f['uuid'] ?: ($f['url'] . '|' . $f['name'])))->values()->all();

            $bankFileUrl = $bankFiles[0]['url'] ?? null;
            $bankFileName = $bankFiles[0]['name'] ?? null;

            // Fallback to Step 1 checklist if vendor uploaded bank statement under checklist
            if (empty($bankFiles)) {
                foreach ($docsMap as $dItem) {
                    if (! empty($dItem['file_url']) && ! empty($dItem['file_name']) && stripos($dItem['file_name'], 'bank') !== false) {
                        $bankFileUrl = $dItem['file_url'];
                        $bankFileName = $dItem['file_name'];
                        $bankFiles = [[
                            'name'          => $bankFileName,
                            'original_name' => $bankFileName,
                            'url'           => $bankFileUrl,
                            'uuid'          => null,
                            'size'          => null,
                        ]];
                        break;
                    }
                }
            }

            $penyataBankData = [
                'grand_total' => $pbGrandTotal,
                'accounts'    => $pbAccounts,
            ];

            // Bon & Saham
            $bsObj = $bonSahamDb->get($vId);
            $bsPayload = $vPayloads->get('bon_saham')?->payload ?? [];
            $bsAccounts = [];
            $bsGrandTotal = 0.0;

            if ($bsObj && $bsObj->accounts && $bsObj->accounts->count() > 0) {
                foreach ($bsObj->accounts as $bsAcc) {
                    $bsAccounts[] = [
                        'bank_institusi' => strtoupper($bsAcc->bank_institusi ?? '-'),
                        'jumlah_deposit' => (float) $bsAcc->jumlah_deposit,
                    ];
                }
                $bsGrandTotal = (float) ($bsObj->jumlah_keseluruhan ?? array_sum(array_column($bsAccounts, 'jumlah_deposit')));
            } elseif (! empty($bsPayload['accounts']) && is_array($bsPayload['accounts'])) {
                foreach ($bsPayload['accounts'] as $bsAcc) {
                    $bsAccounts[] = [
                        'bank_institusi' => strtoupper($bsAcc['bank_institusi'] ?? '-'),
                        'jumlah_deposit' => (float) ($bsAcc['jumlah_deposit'] ?? 0),
                    ];
                }
                $bsGrandTotal = (float) ($bsPayload['grand_total'] ?? array_sum(array_column($bsAccounts, 'jumlah_deposit')));
            }

            $bonSahamData = [
                'grand_total' => $bsGrandTotal,
                'accounts'    => $bsAccounts,
            ];

            // Calculate metrics
            $kewanganMetrics = $this->calculator->calculateKemampuanKewangan(
                $lembaranData,
                $penyataBankData,
                $bonSahamData,
                $anggaranVal,
                $minCapitalThreshold
            );

            // Step 2 Evaluation Record
            $s2Eval = $step2Evaluations->get($vId);
            $s2Evaluated = $s2Eval !== null && $s2Eval->status_pematuhan !== null;
            $s2Status = ! $s2Evaluated
                ? null
                : (((int) $s2Eval->status_pematuhan === 1) ? 'memuaskan' : 'tidak_memuaskan');
            $s2Catatan = $s2Evaluated
                ? ($s2Eval->catatan ?? '')
                : '';

            // --- STEP 3: Penilaian Kerja Data ---
            // A. Kerja Semasa
            $pObj = $prestasiDb->get($vId);
            $pItems = $pObj ? $pObj->items->toArray() : ($vPayloads->get('prestasi_kerja')?->payload['items'] ?? []);
            $kerjaSemasaMetrics = $this->calculator->summarizeKerjaSemasa($pItems);

            // Extract document for kerja semasa if uploaded
            $ksDocUrl = null;
            $ksDocName = null;
            $pPayloadDoc = $vPayloads->get('prestasi_kerja')?->payload['dokumen'] ?? null;
            if (! empty($pPayloadDoc) && is_array($pPayloadDoc)) {
                $firstDoc = reset($pPayloadDoc);
                if (is_array($firstDoc)) {
                    $ksDocUrl = $firstDoc['url'] ?? null;
                    $ksDocName = $firstDoc['original_name'] ?? $firstDoc['name'] ?? 'Dokumen Kerja Semasa';
                }
            }
            if (! $ksDocUrl) {
                foreach ($docsMap as $dItem) {
                    if (! empty($dItem['file_url']) && ! empty($dItem['file_name']) && (stripos($dItem['file_name'], 'prestasi') !== false || stripos($dItem['file_name'], 'kerja') !== false)) {
                        $ksDocUrl = $dItem['file_url'];
                        $ksDocName = $dItem['file_name'];
                        break;
                    }
                }
            }
            $kerjaSemasaMetrics['dokumen_url'] = $ksDocUrl;
            $kerjaSemasaMetrics['dokumen_name'] = $ksDocName;

            // B. Pengalaman Kerja
            $pkPayload = app(VendorFormPayloadService::class)->get($tender, (int) $vId, 'pengalaman_kerja');
            $pkItems = $pkPayload['items'] ?? [];
            if (empty($pkItems)) {
                try {
                    $apiPath = 'pengalaman-kerja/' . $tender->uuid . '?vendor_id=' . $vId;
                    $resp = app(StosBackendClient::class)->get($apiPath);
                    if ($resp->successful()) {
                        $apiData = $resp->json('data');
                        $pkItems = is_array($apiData) ? ($apiData['items'] ?? $apiData) : [];
                    }
                } catch (\Throwable $e) {
                    // Fallback silently without throwing cURL error
                }
            }
            $pengalamanMetrics = $this->calculator->summarizePengalamanKerja($pkItems);

            // C. Kakitangan Teknikal
            $staffCollection = $kakitanganDb->get($vId, collect());
            $staffRaw = $staffCollection->map(fn ($s) => [
                'nama_pegawai'       => $s->nama_pegawai,
                'kategori'           => $s->kategori,
                'tahap_pendidikan'   => $s->tahap_pendidikan ?? '-',
                'jawatan'            => $s->jawatan ?? $s->sijil_professional ?? '-',
                'sijil_professional' => $s->sijil_professional ?? '-',
                'jumlah_pengalaman'  => $s->jumlah_pengalaman,
                'dokumens'           => $s->dokumens->map(fn ($d) => [
                    'original_name' => $d->original_name,
                    'file_url'      => $d->url,
                    'mime_type'     => $d->mime_type,
                ])->toArray(),
            ])->toArray();

            // Fallback to form payload if no rows in DB table
            if (empty($staffRaw) && ! empty($vPayloads->get('kakitangan_teknikal')?->payload['items'])) {
                $payloadStaff = $vPayloads->get('kakitangan_teknikal')->payload['items'];
                if (is_array($payloadStaff)) {
                    $staffRaw = array_map(fn ($s) => [
                        'nama_pegawai'       => $s['nama_pegawai'] ?? $s['nama'] ?? '-',
                        'kategori'           => $s['kategori'] ?? 'Kategori B',
                        'tahap_pendidikan'   => $s['tahap_pendidikan'] ?? '-',
                        'jawatan'            => $s['jawatan'] ?? $s['sijil_professional'] ?? '-',
                        'sijil_professional' => $s['sijil_professional'] ?? '-',
                        'jumlah_pengalaman'  => (int) ($s['jumlah_pengalaman'] ?? 0),
                        'dokumens'           => $s['dokumens'] ?? [],
                    ], $payloadStaff);
                }
            }

            $genDocsCollection = $generalKakitanganDocsDb->get($vId, collect());
            $genDocsRaw = $genDocsCollection->map(fn ($d) => [
                'original_name' => $d->original_name,
                'file_url'      => $d->url,
                'mime_type'     => $d->mime_type,
            ])->toArray();

            $kakitanganMetrics = $this->calculator->summarizeKakitanganTeknikal($staffRaw);
            $kakitanganMetrics['general_dokumens'] = $genDocsRaw;

            // Step 3 Evaluation Record
            $s3Eval = $step3Evaluations->get($vId);
            $s3Evaluated = $s3Eval !== null && $s3Eval->status_pematuhan !== null;
            $s3Status = ! $s3Evaluated
                ? null
                : (((int) $s3Eval->status_pematuhan === 1) ? 'memuaskan' : 'tidak_memuaskan');
            $s3Catatan = $s3Evaluated ? ($s3Eval->catatan ?? '') : '';

            $vendors[] = [
                'id'              => $vId,
                'bil'             => $idx + 1,
                'rank'            => $idx + 1,
                'kod'             => $kod,
                'kod_pembekal'    => $kod,
                'name'            => $vendorName,
                'ssm'             => $ssm,
                'cidb'            => $cidbDisplay,
                'harga_tawaran'   => $hargaTawaranDisp,
                'harga_raw'       => $hargaTawaran,
                'tempoh_siap'     => $tempohSiap,
                'step1'           => [
                    'status'  => $step1Status,
                    'docs'    => $docsMap,
                    'catatan' => $step1Catatan,
                ],
                'step2'           => [
                    'status'              => $s2Status,
                    'catatan'             => $s2Catatan,
                    'modal_pusingan'      => $kewanganMetrics['modal_pusingan_disp'],
                    'purata_bank'         => $kewanganMetrics['purata_bank_disp'],
                    'wang_tangan'         => $kewanganMetrics['wang_tangan_disp'],
                    'bon_saham'           => $kewanganMetrics['bon_saham_total_disp'],
                    'jumlah_modal'        => $kewanganMetrics['jumlah_modal_disp'],
                    'modal_minimum'       => $kewanganMetrics['modal_minimum_disp'],
                    'surplus'             => $kewanganMetrics['surplus_modal_disp'],
                    'is_cukup_modal'      => $kewanganMetrics['is_cukup_modal'],
                    'metrics'             => $kewanganMetrics,
                    'bon_saham_accounts'  => $bsAccounts,
                    'bank_file_url'       => $bankFileUrl,
                    'bank_file_name'      => $bankFileName,
                    'bank_files'          => $bankFiles,
                ],
                'step3'           => [
                    'status'          => $s3Status,
                    'catatan'         => $s3Catatan,
                    'baki_kerja'      => $kerjaSemasaMetrics['total_baki_kerja_disp'],
                    'projek_terbesar' => $kerjaSemasaMetrics['projek_terbesar_disp'],
                    'kakitangan'      => $kakitanganMetrics['bil_kakitangan'] . ' Orang',
                    'kerja_semasa'    => $kerjaSemasaMetrics,
                    'pengalaman'      => $pengalamanMetrics,
                    'kakitangan_data' => $kakitanganMetrics,
                ],
            ];
        }

        // 5. Existing Report
        $laporan = TenderKewanganLaporan::query()->where('tender_id', $tender->id)->first();

        // Unified payload for Blade hydration
        $type2Data = [
            'readOnly' => $readOnly,
            'tender' => [
                'id'                => $tender->id,
                'uuid'              => $tender->uuid,
                'no_tender'         => $no_tender_display,
                'name'              => $tajuk_display,
                'ptj'               => $ptj_display,
                'anggaran_val'      => $anggaranVal,
                'anggaran_disp'     => $anggaran_display,
                'min_modal_disp'    => $min_modal_display,
                'min_modal_pct'     => $minCapitalThreshold,
                'status_process_id' => $tender->status_process_id,
            ],
            'progress' => [
                'currentStep'     => $progress->current_step ?? 1,
                'step1Confirmed'  => $step1Confirmed,
                'step2Confirmed'  => $step2Confirmed,
                'step3Confirmed'  => $step3Confirmed,
            ],
            'checklistDocs' => $checklistDocs,
            'vendors'       => $vendors,
            'laporan'       => [
                'catatan_peringkat1'      => $laporan?->catatan_peringkat1 ?? '',
                'catatan_peringkat2'      => $laporan?->catatan_peringkat2 ?? '',
                'catatan_peringkat3'      => $laporan?->catatan_peringkat3 ?? '',
                'pengesyoran_justifikasi' => $laporan?->pengesyoran_justifikasi ?? [],
                'status'                  => $laporan?->status ?? 'draft',
            ],
            'routes' => [
                'simpanDokumen'     => route('penilaianKewanganSebutHargaKerja.simpanDokumen'),
                'sahkanLangkah1'    => route('penilaianKewanganSebutHargaKerja.sahkanLangkah1'),
                'simpanKewangan'    => route('penilaianKewanganSebutHargaKerja.simpanKewangan'),
                'sahkanLangkah2'    => route('penilaianKewanganSebutHargaKerja.sahkanLangkah2'),
                'simpanKerja'       => route('penilaianKewanganSebutHargaKerja.simpanKerja'),
                'sahkanLangkah3'    => route('penilaianKewanganSebutHargaKerja.sahkanLangkah3'),
                'simpanLaporanDraf' => route('penilaianKewanganSebutHargaKerja.simpanLaporanDraf'),
                'hantar'            => route('penilaianKewanganSebutHargaKerja.hantar'),
                'cetakLaporan'      => route('penilaianKewanganSebutHargaKerja.cetakLaporan', $tender->uuid ?: $tender->id),
            ],
        ];

        return view('newModule.penilaian_kewangan.sebut_harga_kerja.show', compact(
            'tender',
            'no_tender_display',
            'tajuk_display',
            'ptj_display',
            'tempoh_sah_laku',
            'sah_laku_tamat',
            'status_label',
            'anggaran_display',
            'min_modal_display',
            'type2Data'
        ));
    }

    private function ensureFinancialEvaluationEditable(Tender $tender): ?JsonResponse
    {
        if ((int) ($tender->status_process_id ?? 0) === TenderProcessStatus::penilaianKewanganListStatus()) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => 'Penilaian Kewangan telah selesai. Rekod ini hanya boleh dilihat dan tidak boleh dikemaskini.',
        ], 422);
    }

    /**
     * Save Step 1 checklist evaluations for one or all vendors.
     */
    public function simpanDokumen(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id'           => 'required|integer',
            'doc_id'              => 'required|string',
            'decisions'           => 'required|array',
            'decisions.*.vendor_id' => 'required|integer',
            'decisions.*.status'    => 'required|in:sempurna,tidak_sempurna',
            'decisions.*.catatan'   => 'nullable|string|max:2000',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);
        if ($locked = $this->ensureFinancialEvaluationEditable($tender)) return $locked;

        $progress = TenderKewanganProgress::query()->where('tender_id', $tender->id)->first();
        if ($progress?->isStep1Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Langkah 1 telah disahkan dan dikunci. Penilaian dokumen tidak boleh diubah lagi.',
            ], 422);
        }

        $docId = $request->input('doc_id');
        $userId = Auth::id();

        $disqualifiedVendorIds = TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where(function ($q) {
                $q->where('cancel_fg', 1)
                  ->orWhereIn('eliminated_process_id', [
                      TenderProcessStatus::PENILAIAN_PEMBUKA,
                      TenderProcessStatus::PENILAIAN_TEKNIKAL,
                  ]);
            })
            ->pluck('vendor_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        DB::transaction(function () use ($tender, $docId, $request, $userId, $disqualifiedVendorIds) {
            foreach ($request->input('decisions') as $row) {
                $vendorId = (int) $row['vendor_id'];
                if (in_array($vendorId, $disqualifiedVendorIds, true)) {
                    continue;
                }

                $statusInt = ($row['status'] === 'sempurna') ? 1 : 0;
                $catatan = trim((string) ($row['catatan'] ?? ''));

                TenderKewanganEvaluation::query()->updateOrCreate(
                    [
                        'tender_id'           => $tender->id,
                        'vendor_id'           => $vendorId,
                        'checklist_item_uuid' => $docId,
                    ],
                    [
                        'status_pematuhan' => $statusInt,
                        'catatan'          => $catatan ?: null,
                        'updated_by'       => $userId,
                    ]
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Penilaian semakan dokumen berjaya disimpan.',
        ]);
    }

    /**
     * Confirm and lock Step 1 (Pematuhan Dokumentasi).
     */
    public function sahkanLangkah1(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id' => 'required|integer',
            'confirmed' => 'required|boolean',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);
        if ($locked = $this->ensureFinancialEvaluationEditable($tender)) return $locked;

        if ($request->boolean('confirmed')) {
            $participants = $this->loadEligibleParticipants($tender);
            $checklistIds = $this->requiredStep1ChecklistIds($tender);
            if (! $this->step1EvaluationsComplete($tender, $participants, $checklistIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sila lengkapkan penilaian bagi setiap dokumen untuk semua petender sebelum mengesahkan Langkah 1.',
                ], 422);
            }
        }

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $confirmed = $request->boolean('confirmed');
        if (! $confirmed && $progress->isStep1Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Langkah 1 telah disahkan dan dikunci.',
            ], 422);
        }

        if ($confirmed) {
            $progress->step1_confirmed_at = now();
            $progress->step1_confirmed_by = Auth::id();
            $progress->current_step = max($progress->current_step, 2);
        } else {
            $progress->step1_confirmed_at = null;
            $progress->step1_confirmed_by = null;
            $progress->step2_confirmed_at = null;
            $progress->step2_confirmed_by = null;
            $progress->step3_confirmed_at = null;
            $progress->step3_confirmed_by = null;
            $progress->current_step = 1;
        }
        $progress->save();

        return response()->json([
            'success' => true,
            'message' => $confirmed ? 'Langkah 1 berjaya disahkan dan dikunci.' : 'Pengesahan Langkah 1 dibatalkan.',
        ]);
    }

    /**
     * Save Step 2 financial capability decision for a specific vendor.
     */
    public function simpanKewangan(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'decision'  => 'required|in:memuaskan,tidak_memuaskan',
            'catatan'   => 'nullable|string|max:2000',
            'payload'   => 'nullable|array',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);
        if ($locked = $this->ensureFinancialEvaluationEditable($tender)) return $locked;

        $vendorId = (int) $request->input('vendor_id');
        $isDisqualified = TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where(function ($q) {
                $q->where('cancel_fg', 1)
                  ->orWhereIn('eliminated_process_id', [
                      TenderProcessStatus::PENILAIAN_PEMBUKA,
                      TenderProcessStatus::PENILAIAN_TEKNIKAL,
                  ]);
            })
            ->exists();

        if ($isDisqualified) {
            return response()->json([
                'success' => false,
                'message' => 'Petender ini tidak layak untuk dinilai dalam Penilaian Kewangan kerana telah disingkirkan pada peringkat terdahulu.',
            ], 422);
        }

        $progress = TenderKewanganProgress::query()->where('tender_id', $tender->id)->first();
        if (! $progress || ! $progress->isStep1Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Sila sahkan Langkah 1 sebelum menyimpan penilaian kemampuan kewangan.',
            ], 422);
        }

        $participants = $this->loadEligibleParticipants($tender);
        $checklistIds = $this->requiredStep1ChecklistIds($tender);
        $step1PassedVendorIds = $this->step1PassedVendorIds($tender, $participants, $checklistIds);
        if (! in_array($vendorId, $step1PassedVendorIds, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Petender ini tidak melepasi Langkah 1 dan tidak boleh dinilai dalam kemampuan kewangan.',
            ], 422);
        }

        if ($progress?->isStep2Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Langkah 2 telah disahkan dan dikunci. Penilaian kemampuan kewangan tidak boleh diubah lagi.',
            ], 422);
        }

        $statusInt = ($request->input('decision') === 'memuaskan') ? 1 : 0;
        $userId = Auth::id();

        TenderKewanganKerjaEvaluation::query()->updateOrCreate(
            [
                'tender_id'   => $tender->id,
                'vendor_id'   => $vendorId,
                'borang_code' => 'sh_kemampuan_kewangan',
            ],
            [
                'status_pematuhan' => $statusInt,
                'catatan'          => trim((string) $request->input('catatan', '')) ?: null,
                'payload'          => $request->input('payload'),
                'updated_by'       => $userId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Keputusan kemampuan kewangan petender telah dikemaskini.',
        ]);
    }

    /**
     * Confirm and lock Step 2 (Kemampuan Kewangan).
     */
    public function sahkanLangkah2(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id' => 'required|integer',
            'confirmed' => 'required|boolean',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);
        if ($locked = $this->ensureFinancialEvaluationEditable($tender)) return $locked;

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        if (! $progress->isStep1Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Sila sahkan Langkah 1 terlebih dahulu.',
            ], 422);
        }

        if (! $request->boolean('confirmed') && $progress->isStep2Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Langkah 2 telah disahkan dan dikunci.',
            ], 422);
        }

        if ($request->boolean('confirmed')) {
            $participants = $this->loadEligibleParticipants($tender);
            $checklistIds = $this->requiredStep1ChecklistIds($tender);
            if (! $this->step1EvaluationsComplete($tender, $participants, $checklistIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah 1 mempunyai penilaian yang belum lengkap. Sila semak semua dokumen bagi setiap petender.',
                ], 422);
            }

            $passedVendorIds = $this->step1PassedVendorIds($tender, $participants, $checklistIds);
            if (! $this->vendorStepEvaluationsComplete($tender, $passedVendorIds, 'sh_kemampuan_kewangan')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sila simpan keputusan kemampuan kewangan bagi setiap petender yang melepasi Langkah 1 sebelum mengesahkan Langkah 2.',
                ], 422);
            }
        }

        $confirmed = $request->boolean('confirmed');
        if ($confirmed) {
            $progress->step2_confirmed_at = now();
            $progress->step2_confirmed_by = Auth::id();
            $progress->current_step = max($progress->current_step, 3);
        } else {
            $progress->step2_confirmed_at = null;
            $progress->step2_confirmed_by = null;
            $progress->step3_confirmed_at = null;
            $progress->step3_confirmed_by = null;
            $progress->current_step = 2;
        }
        $progress->save();

        return response()->json([
            'success' => true,
            'message' => $confirmed ? 'Langkah 2 berjaya disahkan dan dikunci.' : 'Pengesahan Langkah 2 dibatalkan.',
        ]);
    }

    /**
     * Save Step 3 work capability decision for a specific vendor.
     */
    public function simpanKerja(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'decision'  => 'required|in:memuaskan,tidak_memuaskan',
            'catatan'   => 'nullable|string|max:2000',
            'payload'   => 'nullable|array',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);
        if ($locked = $this->ensureFinancialEvaluationEditable($tender)) return $locked;

        $vendorId = (int) $request->input('vendor_id');
        $isDisqualified = TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where(function ($q) {
                $q->where('cancel_fg', 1)
                  ->orWhereIn('eliminated_process_id', [
                      TenderProcessStatus::PENILAIAN_PEMBUKA,
                      TenderProcessStatus::PENILAIAN_TEKNIKAL,
                  ]);
            })
            ->exists();

        if ($isDisqualified) {
            return response()->json([
                'success' => false,
                'message' => 'Petender ini tidak layak untuk dinilai dalam Penilaian Kewangan kerana telah disingkirkan pada peringkat terdahulu.',
            ], 422);
        }

        $progress = TenderKewanganProgress::query()->where('tender_id', $tender->id)->first();
        if (! $progress || ! $progress->isStep2Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Sila sahkan Langkah 2 sebelum menyimpan penilaian kerja.',
            ], 422);
        }

        $participants = $this->loadEligibleParticipants($tender);
        $checklistIds = $this->requiredStep1ChecklistIds($tender);
        $step1PassedVendorIds = $this->step1PassedVendorIds($tender, $participants, $checklistIds);
        $step2PassedVendorIds = $this->stepPassedVendorIds($tender, $step1PassedVendorIds, 'sh_kemampuan_kewangan');
        if (! in_array($vendorId, $step2PassedVendorIds, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Petender ini tidak melepasi Langkah 2 dan tidak boleh dinilai dalam penilaian kerja.',
            ], 422);
        }

        if ($progress?->isStep3Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Langkah 3 telah disahkan dan dikunci. Penilaian kerja tidak boleh diubah lagi.',
            ], 422);
        }

        $statusInt = ($request->input('decision') === 'memuaskan') ? 1 : 0;
        $userId = Auth::id();

        TenderKewanganKerjaEvaluation::query()->updateOrCreate(
            [
                'tender_id'   => $tender->id,
                'vendor_id'   => $vendorId,
                'borang_code' => 'sh_penilaian_kerja',
            ],
            [
                'status_pematuhan' => $statusInt,
                'catatan'          => trim((string) $request->input('catatan', '')) ?: null,
                'payload'          => $request->input('payload'),
                'updated_by'       => $userId,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Keputusan penilaian kerja petender telah dikemaskini.',
        ]);
    }

    /**
     * Confirm and lock Step 3 (Penilaian Kerja).
     */
    public function sahkanLangkah3(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id' => 'required|integer',
            'confirmed' => 'required|boolean',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);
        if ($locked = $this->ensureFinancialEvaluationEditable($tender)) return $locked;

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        if (! $progress->isStep2Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Sila sahkan Langkah 2 terlebih dahulu.',
            ], 422);
        }

        if (! $request->boolean('confirmed') && $progress->isStep3Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Langkah 3 telah disahkan dan dikunci.',
            ], 422);
        }

        if ($request->boolean('confirmed')) {
            $participants = $this->loadEligibleParticipants($tender);
            $checklistIds = $this->requiredStep1ChecklistIds($tender);
            if (! $this->step1EvaluationsComplete($tender, $participants, $checklistIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah 1 mempunyai penilaian yang belum lengkap. Sila lengkapkan semakan dokumentasi dahulu.',
                ], 422);
            }

            $step1PassedIds = $this->step1PassedVendorIds($tender, $participants, $checklistIds);
            if (! $this->vendorStepEvaluationsComplete($tender, $step1PassedIds, 'sh_kemampuan_kewangan')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah 2 mempunyai keputusan kemampuan kewangan yang belum disimpan.',
                ], 422);
            }

            $step2PassedIds = $this->stepPassedVendorIds($tender, $step1PassedIds, 'sh_kemampuan_kewangan');
            if (! $this->vendorStepEvaluationsComplete($tender, $step2PassedIds, 'sh_penilaian_kerja')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sila simpan keputusan penilaian kerja bagi setiap petender yang melepasi Langkah 2 sebelum mengesahkan Langkah 3.',
                ], 422);
            }
        }

        $confirmed = $request->boolean('confirmed');
        if ($confirmed) {
            $progress->step3_confirmed_at = now();
            $progress->step3_confirmed_by = Auth::id();
            $progress->current_step = max($progress->current_step, 4);
        } else {
            $progress->step3_confirmed_at = null;
            $progress->step3_confirmed_by = null;
            $progress->current_step = 3;
        }
        $progress->save();

        return response()->json([
            'success' => true,
            'message' => $confirmed ? 'Langkah 3 berjaya disahkan dan dikunci.' : 'Pengesahan Langkah 3 dibatalkan.',
        ]);
    }

    /**
     * Save draft evaluation report remarks.
     */
    public function simpanLaporanDraf(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id'               => 'required|integer',
            'catatan_peringkat1'     => 'nullable|string|max:5000',
            'catatan_peringkat2'     => 'nullable|string|max:5000',
            'catatan_peringkat3'     => 'nullable|string|max:5000',
            'pengesyoran_justifikasi' => 'nullable|array',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);
        if ($locked = $this->ensureFinancialEvaluationEditable($tender)) return $locked;

        $userId = Auth::id();

        $laporan = TenderKewanganLaporan::query()->firstOrNew([
            'tender_id' => $tender->id,
        ]);

        $laporan->fill([
            'catatan_peringkat1'     => $request->input('catatan_peringkat1'),
            'catatan_peringkat2'     => $request->input('catatan_peringkat2'),
            'catatan_peringkat3'     => $request->input('catatan_peringkat3'),
            'pengesyoran_justifikasi' => $request->input('pengesyoran_justifikasi', []),
            'status'                  => 'draft',
            'updated_by'              => $userId,
        ]);

        if (! $laporan->exists) {
            $laporan->created_by = $userId;
        }
        $laporan->save();

        return response()->json([
            'success' => true,
            'message' => 'Draf laporan penilaian kewangan dan kerja berjaya disimpan.',
        ]);
    }

    /**
     * Final submission of Penilaian Kewangan & Kerja (Advances tender status from 10 to 11).
     */
    public function hantar(Request $request): JsonResponse
    {
        $request->validate([
            'tender_id' => 'required|integer',
            'perakuan'  => 'required|accepted',
        ]);

        $tender = Tender::query()->findOrFail($request->input('tender_id'));
        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);

        if ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penilaianKewanganListStatus()) {
            return response()->json([
                'success' => false,
                'message' => 'Perolehan bukan pada peringkat Penilaian Kewangan (Status semasa: ' . TenderProcessStatus::label($tender->status_process_id) . ').',
            ], 422);
        }

        $progress = TenderKewanganProgress::query()->where('tender_id', $tender->id)->first();
        if (! $progress || ! $progress->isStep1Confirmed() || ! $progress->isStep2Confirmed() || ! $progress->isStep3Confirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Sila lengkapkan dan sahkan semua Langkah 1, 2, dan 3 sebelum menghantar penilaian akhir.',
            ], 422);
        }

        $participants = $this->loadEligibleParticipants($tender);
        $checklistIds = $this->requiredStep1ChecklistIds($tender);
        if (! $this->step1EvaluationsComplete($tender, $participants, $checklistIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Penilaian Langkah 1 belum lengkap untuk semua petender. Lengkapkan semua dokumen sebelum menghantar laporan.',
            ], 422);
        }

        $step1PassedIds = $this->step1PassedVendorIds($tender, $participants, $checklistIds);
        if (! $this->vendorStepEvaluationsComplete($tender, $step1PassedIds, 'sh_kemampuan_kewangan')) {
            return response()->json([
                'success' => false,
                'message' => 'Keputusan Langkah 2 belum lengkap untuk semua petender yang melepasi Langkah 1.',
            ], 422);
        }

        $step2PassedIds = $this->stepPassedVendorIds($tender, $step1PassedIds, 'sh_kemampuan_kewangan');
        if (! $this->vendorStepEvaluationsComplete($tender, $step2PassedIds, 'sh_penilaian_kerja')) {
            return response()->json([
                'success' => false,
                'message' => 'Keputusan Langkah 3 belum lengkap untuk semua petender yang melepasi Langkah 2.',
            ], 422);
        }

        $userId = Auth::id();
        $now = now();

        DB::transaction(function () use ($tender, $userId, $now) {
            // 1. Finalize Laporan
            $laporan = TenderKewanganLaporan::query()->firstOrNew(['tender_id' => $tender->id]);
            $laporan->fill([
                'status'       => 'submitted',
                'submitted_at' => $now,
                'submitted_by' => $userId,
                'updated_by'   => $userId,
            ]);
            if (! $laporan->exists) {
                $laporan->created_by = $userId;
            }
            $laporan->save();

            // 2. Identify qualified and disqualified vendors from eligible participants
            $participants = $this->loadEligibleParticipants($tender);
            $vendorIds = $participants->pluck('vendor_id');

            // Load evaluations
            $s1Evals = TenderKewanganEvaluation::query()
                ->where('tender_id', $tender->id)
                ->whereIn('vendor_id', $vendorIds)
                ->get()
                ->groupBy('vendor_id');

            $s2Evals = TenderKewanganKerjaEvaluation::query()
                ->where('tender_id', $tender->id)
                ->where('borang_code', 'sh_kemampuan_kewangan')
                ->whereIn('vendor_id', $vendorIds)
                ->get()
                ->keyBy('vendor_id');

            $s3Evals = TenderKewanganKerjaEvaluation::query()
                ->where('tender_id', $tender->id)
                ->where('borang_code', 'sh_penilaian_kerja')
                ->whereIn('vendor_id', $vendorIds)
                ->get()
                ->keyBy('vendor_id');

            foreach ($participants as $p) {
                $vId = $p->vendor_id;

                // Step 1: Check if any item failed
                $vDocs = $s1Evals->get($vId, collect());
                $failedDoc = $vDocs->contains(fn ($d) => (int) $d->status_pematuhan === 0);
                $passStep1 = (! $failedDoc && $vDocs->isNotEmpty());

                // Step 2: Check status
                $s2 = $s2Evals->get($vId);
                $passStep2 = ($s2 && (int) $s2->status_pematuhan === 1);

                // Step 3: Check status
                $s3 = $s3Evals->get($vId);
                $passStep3 = ($s3 && (int) $s3->status_pematuhan === 1);

                $isOverallPass = ($passStep1 && $passStep2 && $passStep3);

                if ($isOverallPass) {
                    $p->update([
                        'cancel_fg'             => 0,
                        'eliminated_process_id' => null,
                        'eliminated_reason'     => null,
                        'eliminated_at'         => null,
                    ]);
                } else {
                    $reasons = [];
                    if (! $passStep1) {
                        $reasons[] = 'Pematuhan Dokumentasi (Langkah 1)';
                    }
                    if (! $passStep2) {
                        $reasons[] = 'Kemampuan Kewangan (Langkah 2)';
                    }
                    if (! $passStep3) {
                        $reasons[] = 'Penilaian Kerja (Langkah 3)';
                    }

                    $reasonTxt = 'Tidak melepasi saringan peringkat: ' . implode(', ', $reasons);
                    $p->eliminate(TenderProcessStatus::PENILAIAN_KEWANGAN, $reasonTxt);
                }
            }

            // 3. Advance Tender Process Status from 10 to 11
            $this->advanceTenderProcess(
                $tender,
                TenderProcessStatus::PENILAIAN_KEWANGAN,
                TenderProcessStatus::penilaianKewanganListStatus()
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penilaian Kewangan & Kerja berjaya dihantar ke peringkat Urusetia / Lembaga Perolehan.',
        ]);
    }

    /**
     * Printable Laporan Jawatankuasa Penilaian Sebut Harga.
     */
    public function cetakLaporan(string $tender_no)
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);
        if (! $tender) {
            abort(404, 'Tender tidak ditemui.');
        }

        $this->assertCommitteeAppointment($tender, $this->financialCommitteeJenis);

        $jpJenis = $this->resolveJpJenis($tender);

        $mesyuarat = PenyediaanMesyuaratMeeting::query()
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $jpJenis)
            ->where('status', 'Dihantar')
            ->orderBy('tarikh_mesyuarat')
            ->get();

        // Only penyebut harga who submitted, lowest harga tawaran first, priced the same way as the evaluation screens.
        $petender = $this->resolveAndSortParticipantsByHargaTawaran(
            $tender,
            TenderVendor::query()->with('vendor')->where('tender_id', $tender->id)->where('submitted', 1)->get()
        );
        $berharga = $petender->filter(fn ($p) => $p->harga_tawaran_resolved !== null)->values();
        $cutOff = $this->loadCutOff($tender);
        $refsCutOff = TenderVendor::submittedCutOffVendorRefs($tender->id);
        // Penyebut harga ticked in the submitted cut-off, in harga tawaran order.
        $terpilih = $petender->filter(fn ($p) => in_array($p->kod_pembekal, $refsCutOff, true))->values();
        // Peringkat Kedua is Langkah 1: any document marked tidak sempurna fails, as in hantar().
        $dokumenEval = TenderKewanganEvaluation::query()->where('tender_id', $tender->id)->get()->groupBy('vendor_id');
        $peringkatKedua = $terpilih->map(function ($p, int $idx) use ($dokumenEval) {
            $evals = $dokumenEval->get($p->vendor_id, collect());
            $gagal = $evals->filter(fn ($e) => (int) $e->status_pematuhan === 0);

            return [
                'bil' => $idx + 1,
                'vendor_id' => $p->vendor_id,
                'kod_pembekal' => $p->kod_pembekal,
                'lulus' => $evals->isNotEmpty() && $gagal->isEmpty(),
                // Penyebut harga dropped before Kewangan (e.g. at Teknikal) have no evaluation, so their elimination reason shows.
                'ulasan' => $gagal->pluck('catatan')->filter()->join('; ') ?: ($p->eliminated_reason ?: 'Gagal mematuhi satu atau lebih dokumen mandatori.'),
            ];
        });
        $lulusKedua = $peringkatKedua->where('lulus', true);
        $modalMinimum = (float) ($tender->anggaran_jabatan ?? $tender->harga_indikatif ?? $tender->price ?? 0) * PenilaianKewanganKerjaCalculator::DEFAULT_MIN_CAPITAL_PERCENT / 100;
        $kewanganEval = TenderKewanganKerjaEvaluation::query()->where('tender_id', $tender->id)->where('borang_code', 'sh_kemampuan_kewangan')->get()->keyBy('vendor_id');
        $peringkatKetiga = $lulusKedua->values()->map(function (array $row, int $idx) use ($kewanganEval, $modalMinimum) {
            $eval = $kewanganEval->get($row['vendor_id']);

            return [
                'bil' => $idx + 1,
                'vendor_id' => $row['vendor_id'],
                'kod_pembekal' => $row['kod_pembekal'],
                'lulus' => $eval?->status_pematuhan === 1,
                'ulasan' => $eval?->catatan ?: 'Modal pusingan kurang daripada had minimum 1.5% (RM ' . number_format($modalMinimum, 2) . ').',
            ];
        });
        $lulusKetiga = $peringkatKetiga->where('lulus', true);
        $kerjaEval = TenderKewanganKerjaEvaluation::query()->where('tender_id', $tender->id)->where('borang_code', 'sh_penilaian_kerja')->get()->keyBy('vendor_id');
        $peringkatKeempat = $lulusKetiga->values()->map(function (array $row, int $idx) use ($kerjaEval) {
            $eval = $kerjaEval->get($row['vendor_id']);

            return [
                'bil' => $idx + 1,
                'vendor_id' => $row['vendor_id'],
                'kod_pembekal' => $row['kod_pembekal'],
                'lulus' => $eval?->status_pematuhan === 1,
                'ulasan' => $eval?->catatan ?: 'Penilaian kerja tidak memuaskan.',
            ];
        });
        $lulusKeempat = $peringkatKeempat->where('lulus', true);
        $disyorkan = $lulusKeempat->first();
        $hargaDisyorkan = (float) ($petender->firstWhere('vendor_id', $disyorkan['vendor_id'] ?? null)?->harga_tawaran_resolved ?? 0);
        $laporan = TenderKewanganLaporan::query()->where('tender_id', $tender->id)->first();

        return $this->laporanAtauWord(view('newModule.penilaian_kewangan.sebut_harga_kerja.laporan_cetak', [
            'tender' => $tender,
            'noTender' => $tender->no_tender ?: ($tender->ref_number ?: '-'),
            'latarBelakang' => $this->buildLatarBelakang($tender),
            'jpMembers' => $this->loadJpMembers($tender, $jpJenis),
            'bilanganMesyuarat' => $this->ejaBilangan($mesyuarat->count()),
            'tarikhMesyuarat' => $mesyuarat
                ->map(fn ($row) => $this->formatTarikhMalay(Carbon::parse($row->tarikh_mesyuarat)))
                ->join(', ', ' dan ') ?: null,
            'tarikhPemakluman' => $this->resolveTarikhPemakluman($tender, $jpJenis),
            'gredCidb' => $this->loadGredCidb($tender),
            'kodBidangCidb' => $this->flattenKodBidang($tender, 'cidb'),
            'kodBidangMof' => $this->flattenKodBidang($tender, 'mof'),
            // Matches canParticipate(): anything other than 'and' behaves as OR.
            'mofCidbRule' => strtoupper((string) ($tender->mof_cidb_rule ?: 'or')) === 'AND' ? 'DAN' : 'ATAU',
            // Kerja's Teknikal page is a spec and pricing table, so it shows as one fixed last row (analyst's call).
            'senaraiDokumen' => $this->loadDokumenKewangan($tender)
                ->map(fn ($item) => $item->title ?: '-')
                ->push('Dokumen Spesifikasi')
                ->map(fn (string $keterangan, int $idx) => [
                    'bil' => $idx + 1,
                    'keterangan' => $keterangan,
                    'lampiran' => 'Lampiran ' . chr(65 + $idx),
                ]),
            'bilanganPetender' => ucwords($this->ejaBilangan($petender->count())),
            'petenderTerendah' => $berharga->first(),
            'petenderTertinggi' => $berharga->count() > 1 ? $berharga->last() : null,
            'jadualHarga' => $petender->map(function ($p, int $idx) use ($cutOff) {
                $bwam = $cutOff->get((string) $p->kod_pembekal)['pct_bwam'] ?? null;

                return [
                    'bil' => $idx + 1,
                    'kod_pembekal' => $p->kod_pembekal ?: '-',
                    'harga' => $p->harga_tawaran_resolved !== null ? number_format($p->harga_tawaran_resolved, 2) : '-',
                    'bwam' => is_numeric($bwam) ? number_format($bwam * 100, 2) . '%' : ($bwam ?: '-'),
                ];
            }),
            'bilanganLulusCutOff' => $this->ejaBilangan($cutOff->where('pct_bwam', '!==', 'FREAK')->count()),
            'bilanganTerpilih' => $this->ejaBilangan($terpilih->count()),
            'kodTerpilih' => $terpilih->pluck('kod_pembekal')->join(', ', ', dan ') ?: '-',
            'peringkatKedua' => $peringkatKedua,
            'bilanganLulusKedua' => $this->ejaBilangan($lulusKedua->count()),
            'kodLulusKedua' => $lulusKedua->pluck('kod_pembekal')->join(', ', ', dan ') ?: '-',
            'modalMinimum' => number_format($modalMinimum, 2),
            'peringkatKetiga' => $peringkatKetiga,
            'bilanganLulusKetiga' => $this->ejaBilangan($lulusKetiga->count()),
            'kodLulusKetiga' => $lulusKetiga->pluck('kod_pembekal')->join(', ', ' dan ') ?: '-',
            'peringkatKeempat' => $peringkatKeempat,
            'bilanganLulusKeempat' => $this->ejaBilangan($lulusKeempat->count()),
            'kodLulusKeempat' => $lulusKeempat->pluck('kod_pembekal')->join(', ', ' dan ') ?: '-',
            'disyorkan' => $disyorkan ? [
                'kod_pembekal' => $disyorkan['kod_pembekal'],
                'harga' => number_format($hargaDisyorkan, 2),
                'harga_perkataan' => $this->ejaRinggit($hargaDisyorkan),
            ] : null,
            'justifikasiSyor' => trim((string) $laporan?->catatan_peringkat1),
            'syaratTambahan' => trim((string) $laporan?->catatan_peringkat3),
            'tarikhPenilaian' => $laporan?->updated_at ? $this->formatTarikhMalay(Carbon::parse($laporan->updated_at)) : null,
        ]), $tender);
    }

    /** ?format=word downloads the same report as a Word document. */
    private function laporanAtauWord(\Illuminate\Contracts\View\View $laporan, Tender $tender)
    {
        if (request()->query('format') !== 'word') {
            return $laporan;
        }

        $tajuk = 'Laporan Jawatankuasa Penilaian - ' . str_replace(['/', '\\'], '-', $tender->no_tender ?: ($tender->ref_number ?: (string) $tender->id));

        return response(\App\Support\LaporanPenilaianWord::convert($laporan->render(), $tajuk))
            ->header('Content-Type', 'application/vnd.ms-word; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $tajuk . '.doc"');
    }

    /** Ringgit amount in Malay words, e.g. "Seratus Ribu Dan Sen Lima Puluh". */
    private function ejaRinggit(float $amount): string
    {
        $eja = new \NumberFormatter('ms', \NumberFormatter::SPELLOUT);
        $jumlahSen = (int) round($amount * 100);
        $sen = $jumlahSen % 100;

        return ucwords($eja->format(intdiv($jumlahSen, 100)) . ($sen > 0 ? ' dan sen ' . $eja->format($sen) : ''));
    }

    /** Malay number in words with its digits, e.g. "empat (4)". */
    private function ejaBilangan(int $bilangan): string
    {
        return (new \NumberFormatter('ms', \NumberFormatter::SPELLOUT))->format($bilangan) . " ({$bilangan})";
    }

    /** STOS cut-off rows keyed by kod pembekal, without the AJ row; empty when STOS can't be reached. */
    private function loadCutOff(Tender $tender): Collection
    {
        try {
            $response = app(StosBackendClient::class)->getCutOffTender($tender->uuid);
        } catch (\Throwable $e) {
            return collect();
        }

        return collect($response->successful() ? $response->json('data.vendors_cutoff') : [])
            ->reject(fn ($row) => ! empty($row['is_aj']))
            ->keyBy(fn ($row) => (string) ($row['ruj'] ?? ''));
    }

    /** The Senarai Semak Kewangan Kerja documents; PTJ files the petender only downloads are left out, as in the Kewangan report. */
    private function loadDokumenKewangan(Tender $tender): Collection
    {
        $header = KewanganKerjaHeader::query()
            ->where('tender_id', $tender->id)
            ->with('items')
            ->first();

        return collect($header?->items ?? [])
            ->reject(fn ($item) => $item->mechanism === ChecklistMechanism::PTJ_MUAT_NAIK
                && $item->vendor_action === ChecklistMechanism::VENDOR_ACTION_MUAT_TURUN)
            ->values();
    }

    private function loadGredCidb(Tender $tender): Collection
    {
        return TenderCode::query()
            ->with('code')
            ->where('tender_id', $tender->id)
            ->where('code_type', 'cidb-g')
            ->orderBy('id')
            ->get()
            ->filter(fn (TenderCode $row) => $row->code)
            ->map(fn (TenderCode $row) => $row->code->code)
            ->values();
    }

    /** Each row carries the operator to the next: inner_rule within a group, join_rule between groups. */
    private function flattenKodBidang(Tender $tender, string $codeType): Collection
    {
        $groups = TenderCode::query()
            ->with('code')
            ->where('tender_id', $tender->id)
            ->where('code_type', $codeType)
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->filter(fn (TenderCode $row) => $row->code)
            ->groupBy('order')
            ->values();

        $rows = collect();

        foreach ($groups as $groupIndex => $group) {
            $items = $group->values();

            foreach ($items as $itemIndex => $row) {
                $isLastInGroup = $itemIndex === $items->count() - 1;
                $isLastGroup = $groupIndex === $groups->count() - 1;

                $operator = null;
                if (! $isLastInGroup) {
                    $operator = $row->inner_rule;
                } elseif (! $isLastGroup) {
                    $operator = $row->join_rule;
                }

                $rows->push([
                    'kod' => $row->code->code,
                    'keterangan' => preg_replace('/\s+/', ' ', trim((string) $row->code->name)),
                    'operator' => $operator ? ($operator === 'and' ? 'DAN' : 'ATAU') : null,
                ]);
            }
        }

        return $rows;
    }

    /** 'fin' on 2-peringkat; 1-peringkat has no such tab and uses 'eval'/'harga'. */
    private function resolveJpJenis(Tender $tender): array
    {
        $hasFin = Jawatankuasa::query()
            ->where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', 'fin')
            ->whereNotNull('user_id')
            ->exists();

        return $hasFin ? ['fin'] : $this->financialCommitteeJenis;
    }

    private function loadJpMembers(Tender $tender, array $jenisList): Collection
    {
        $perananLabels = ['1' => 'Pengerusi', '2' => 'Setiausaha', '3' => 'Ahli'];

        return Jawatankuasa::query()
            ->with('user.organizationunit')
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $jenisList)
            ->whereNotNull('user_id')
            ->orderBy('peranan')
            ->orderBy('id')
            ->get()
            ->filter(fn (Jawatankuasa $row) => $row->user)
            ->values()
            ->map(fn (Jawatankuasa $row) => [
                'peranan_label' => $perananLabels[(string) $row->peranan] ?? 'Ahli',
                'name' => $row->user->name,
                'jawatan' => $row->user->jawatan ?: null,
                'department' => $row->user->department ?: null,
                'agensi' => optional($row->user->organizationunit)->name,
                'tarikh_lantikan' => optional($row->dihantar_pemakluman_pada)->format('d.m.Y') ?: '-',
            ]);
    }

    private function resolveTarikhPemakluman(Tender $tender, array $jenisList): ?string
    {
        $tarikh = Jawatankuasa::query()
            ->where('tender_id', $tender->id)
            ->whereIn('jenis_jawatankuasa', $jenisList)
            ->whereNotNull('dihantar_pemakluman_pada')
            ->min('dihantar_pemakluman_pada');

        return $tarikh ? $this->formatTarikhMalay(Carbon::parse($tarikh)) : null;
    }

    /** @return array<string, mixed> */
    private function buildLatarBelakang(Tender $tender): array
    {
        $iklanRecord = PenyediaanIklan::query()->where('tender_id', $tender->id)->first();
        $iklanMeta = ($iklanRecord && is_array($iklanRecord->meta)) ? ($iklanRecord->meta['iklan'] ?? []) : [];
        $tempohSahLaku = $iklanMeta['tempoh_sah_laku'] ?? null;
        $tarikhSahLaku = ($tempohSahLaku && $tender->submission_datetime)
            ? $this->formatTarikhMalay(Carbon::parse($tender->submission_datetime)->addDays((int) $tempohSahLaku))
            : null;

        $anggaran = $tender->anggaran_jabatan;
        $jenis = ($tender->invitation || (int) $tender->jenis_tender_id === 3) ? 'Terhad' : 'Terbuka';
        $bilanganDokumen = TenderVendor::query()->where('tender_id', $tender->id)->where('submitted', 1)->count();

        return [
            'agensi_pelaksana' => $tender->tenderer?->name ?: '-',
            'kaedah_perolehan' => 'Sebut Harga ' . $jenis . ' Melalui Sistem Perolehan Selangor',
            'anggaran_jabatan' => (float) $anggaran > 0 ? 'RM' . number_format((float) $anggaran, 2) : '-',
            'tarikh_iklan' => $this->formatJulatTarikhMalay($tender->advertise_start_date, $tender->advertise_stop_date),
            'tarikh_jual' => $this->formatJulatTarikhMalay($tender->document_start_date, $tender->document_stop_date),
            'tarikh_tutup' => $tender->submission_datetime ? $this->formatTarikhMalay(Carbon::parse($tender->submission_datetime)) : '-',
            'masa_tutup' => $tender->masa_tutup_display,
            'tempoh_sah_laku' => $tarikhSahLaku ? $tempohSahLaku . ' hari selepas tarikh tutup sebut harga (' . $tarikhSahLaku . ')' : '-',
            'bilangan_dokumen' => ucwords((new \NumberFormatter('ms', \NumberFormatter::SPELLOUT))->format($bilanganDokumen)) . " ({$bilanganDokumen})",
        ];
    }

    private function formatJulatTarikhMalay($mula, $tamat): string
    {
        if (! $mula || ! $tamat) {
            return '-';
        }

        return $this->formatTarikhMalay(Carbon::parse($mula)) . ' – ' . $this->formatTarikhMalay(Carbon::parse($tamat));
    }

    private function formatTarikhMalay(Carbon $date): string
    {
        $bulanMs = ['', 'Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember'];

        return $date->day . ' ' . $bulanMs[$date->month] . ' ' . $date->year;
    }

    /**
     * Resolve actual numeric tender price across all sources and sort participants ascending by Harga Tawaran.
     * Lowest price vendor appears first (Bil 1). Secondary tie-breaker is vendor company name, then ID.
     */
    public function resolveAndSortParticipantsByHargaTawaran(?Tender $tender, Collection $participants): Collection
    {
        if (! $tender || $participants->isEmpty()) {
            return $participants;
        }

        $vendorIds = $participants->pluck('vendor_id')->filter()->unique()->all();

        // Retrieve specification responses in case harga_tawaran is not directly recorded on tender_vendors
        $specResponses = TenderVendorDokumenResponse::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $vendorIds)
            ->where('response_type', 'specification')
            ->get()
            ->groupBy('vendor_id');

        $docResponseService = app(VendorDokumenResponseService::class);

        foreach ($participants as $p) {
            $vId = (int) $p->vendor_id;
            $harga = null;

            // 1. Direct harga_tawaran or price on tender_vendors
            if (filled($p->harga_tawaran) && (float) $p->harga_tawaran > 0) {
                $harga = (float) $p->harga_tawaran;
            } elseif (filled($p->price) && (float) $p->price > 0) {
                $harga = (float) $p->price;
            }

            // 2. Fallback to specification responses
            if ($harga === null || $harga <= 0) {
                $vResps = $specResponses->get($vId, collect());
                foreach ($vResps as $resp) {
                    $itemPrices = is_array($resp->payload) ? ($resp->payload['item_prices'] ?? []) : [];
                    if (! empty($itemPrices)) {
                        $calcTotal = $docResponseService->calculateSpecificationTotal($tender, $itemPrices, $resp->section ?: 'spesifikasi_kerja');
                        if ($calcTotal > 0) {
                            $harga = (float) $calcTotal;
                            break;
                        }
                    }
                }
            }

            // 3. Fallback to amount, bid_price, or tawaran_harga if present
            if (($harga === null || $harga <= 0) && filled($p->amount) && (float) $p->amount > 0) {
                $harga = (float) $p->amount;
            }

            if (($harga === null || $harga <= 0) && filled($p->bid_price) && (float) $p->bid_price > 0) {
                $harga = (float) $p->bid_price;
            }

            if (($harga === null || $harga <= 0) && isset($p->tawaran_harga) && (float) $p->tawaran_harga > 0) {
                $harga = (float) $p->tawaran_harga;
            }

            $p->harga_tawaran_resolved = ($harga !== null && $harga > 0) ? (float) $harga : null;
            $p->harga_tawaran_fmt = $p->harga_tawaran_resolved !== null
                ? ('RM ' . number_format($p->harga_tawaran_resolved, 2))
                : '-';
        }

        return $participants->sort(function ($a, $b) {
            $pA = ($a->harga_tawaran_resolved !== null && $a->harga_tawaran_resolved > 0)
                ? (float) $a->harga_tawaran_resolved
                : PHP_FLOAT_MAX;
            $pB = ($b->harga_tawaran_resolved !== null && $b->harga_tawaran_resolved > 0)
                ? (float) $b->harga_tawaran_resolved
                : PHP_FLOAT_MAX;

            if ($pA === $pB) {
                $nameA = mb_strtolower(trim((string) ($a->vendor->name ?? ($a->vendor->company_name ?? ''))));
                $nameB = mb_strtolower(trim((string) ($b->vendor->name ?? ($b->vendor->company_name ?? ''))));
                if ($nameA !== $nameB) {
                    return strcmp($nameA, $nameB);
                }
                return $a->id <=> $b->id;
            }

            return $pA <=> $pB;
        })->values();
    }
}
