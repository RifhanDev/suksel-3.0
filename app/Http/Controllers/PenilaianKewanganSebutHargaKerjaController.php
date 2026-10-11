<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Http\Controllers\Concerns\RestrictsTenderByRole;
use App\Models\BonSaham;
use App\Models\KewanganKerjaItem;
use App\Models\LembaranImbangan;
use App\Models\PenyataBank;
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
use App\Support\PenilaianKewanganKerjaCalculator;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        // 1. Tender Display Metadata
        $no_tender_display = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;
        $tajuk_display = $tender->name ?? '-';
        $ptj_display = $tender->tenderer->name ?? '-';
        $tempoh_sah_laku = 90;
        $submissionDate = $tender->submission_datetime ? Carbon::parse($tender->submission_datetime) : null;
        $sah_laku_tamat = $submissionDate ? $submissionDate->copy()->addDays($tempoh_sah_laku)->format('d/m/Y') : '-';
        $status_label = ((int) $tender->status_process_id === TenderProcessStatus::penilaianKewanganListStatus())
            ? 'Menunggu Penilaian Kewangan & Kerja'
            : TenderProcessStatus::label($tender->status_process_id);

        $anggaranVal = (float) ($tender->anggaran_jabatan ?? $tender->harga_indikatif ?? $tender->price ?? 500000);
        $anggaran_display = 'RM ' . number_format($anggaranVal, 2);

        // 1.5% Minimum Capital threshold (Treasury Circular PK 2.1)
        $minCapitalThreshold = PenilaianKewanganKerjaCalculator::DEFAULT_MIN_CAPITAL_PERCENT;
        $minCapitalRequired = $anggaranVal * ($minCapitalThreshold / 100);
        $min_modal_display = 'RM ' . number_format($minCapitalRequired, 2);

        // 2. Workflow Progress State
        $progress = TenderKewanganProgress::query()->firstOrCreate(
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

        // 4. Participating Vendors
        $participants = $tender->participants()
            ->with(['vendor'])
            ->where('cancel_fg', 0)
            ->get();

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
            ->get()
            ->keyBy('original_name') : collect();

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
        $totalVendors = $participants->count();

        foreach ($participants as $idx => $p) {
            $vId = $p->vendor_id;
            $vendor = $p->vendor;

            $kod = $p->kod_pembekal ?: ('V' . ($idx + 1));
            $kodDisplay = $p->kod_pembekal ?: (($idx + 1) . '/' . $totalVendors);
            $vendorName = $vendor->name ?? $vendor->company_name ?? ('Petender ' . $kod);
            $ssm = $vendor->registration_number ?? $vendor->ssm_number ?? $vendor->ssm ?? '-';

            // CIDB Grade & Specialization
            $cidbGrade = $vendor->cidb_grade ?? $vendor->gred_cidb ?? 'G3';
            $cidbSpec = $vendor->cidb_specialization ?? 'CE21';
            $cidbDisplay = "CIDB Gred {$cidbGrade} ({$cidbSpec})";

            $hargaTawaran = (float) ($p->harga_tawaran ?? $p->bid_price ?? 0);
            $hargaTawaranDisp = $hargaTawaran > 0 ? ('RM ' . number_format($hargaTawaran, 2)) : '-';
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

                $statusVal = $evalRow ? (($evalRow->status_pematuhan === 1) ? 'sempurna' : 'tidak_sempurna') : 'sempurna';
                $noteVal = $evalRow?->catatan ?? '';

                if ($evalRow) {
                    $evaluatedCount++;
                    if ($evalRow->status_pematuhan === 0) {
                        $hasAnyFail = true;
                    }
                }

                $isSpec = (($doc['action'] ?? '') === 'view_specification');
                $isOnlineForm = (($doc['action'] ?? '') === 'online_form') || !empty($formUrl);
                $hasFileOrSubmission = ($firstFile !== null) || !empty($formUrl) || $isSpec;

                $docsMap[$docUuid] = [
                    'status'    => $statusVal,
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

            $step1Status = $hasAnyFail ? 'tidak_sempurna' : 'sempurna';
            $step1Catatan = $hasAnyFail
                ? 'Gagal mematuhi satu atau lebih dokumen mandatori.'
                : 'Semua dokumen mandatori lengkap dan teratur.';

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

                        if (! $furl && $fuuid) {
                            $furl = route('tenderDokumen.download', $fuuid);
                        }
                        if (! $furl && ! empty($f['path'])) {
                            $cleanPath = ltrim(str_replace(['\\', 'public/'], ['/', ''], (string) $f['path']), '/');
                            $furl = route('tenderDokumen.streamByPath', ['path' => $cleanPath, 'name' => $fname]);
                        }
                        if (! $furl && isset($penyataBankFilesDb[$fname])) {
                            $matched = $penyataBankFilesDb[$fname];
                            $fuuid = $matched->uuid;
                            $furl = route('tenderDokumen.download', $matched->uuid);
                        }

                        $fileItem = [
                            'name'          => $fname,
                            'original_name' => $fname,
                            'url'           => $furl,
                            'uuid'          => $fuuid,
                            'size'          => $f['size'] ?? null,
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
                        if (! $furl && isset($penyataBankFilesDb[$fname])) {
                            $matched = $penyataBankFilesDb[$fname];
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
            $s2Status = $s2Eval ? (($s2Eval->status_pematuhan === 1) ? 'memuaskan' : 'tidak_memuaskan') : ($kewanganMetrics['is_cukup_modal'] ? 'memuaskan' : 'tidak_memuaskan');
            $s2Catatan = $s2Eval?->catatan ?? ($kewanganMetrics['is_cukup_modal']
                ? "Modal pusingan dan aset cair mencukupi had minimum 1.5% ({$min_modal_display})."
                : "Modal pusingan kurang daripada had minimum 1.5% ({$min_modal_display}).");

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
            ])->toArray();

            $kakitanganMetrics = $this->calculator->summarizeKakitanganTeknikal($staffRaw);
            $kakitanganMetrics['general_dokumens'] = $genDocsRaw;

            // Step 3 Evaluation Record
            $s3Eval = $step3Evaluations->get($vId);
            $s3Status = $s3Eval ? (($s3Eval->status_pematuhan === 1) ? 'memuaskan' : 'tidak_memuaskan') : 'memuaskan';
            $s3Catatan = $s3Eval?->catatan ?? 'Beban kerja munasabah dan kakitangan teknikal mencukupi.';

            $vendors[] = [
                'id'              => $vId,
                'kod'             => $kodDisplay,
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

        $docId = $request->input('doc_id');
        $userId = Auth::id();

        DB::transaction(function () use ($tender, $docId, $request, $userId) {
            foreach ($request->input('decisions') as $row) {
                $statusInt = ($row['status'] === 'sempurna') ? 1 : 0;
                $catatan = trim((string) ($row['catatan'] ?? ''));

                TenderKewanganEvaluation::query()->updateOrCreate(
                    [
                        'tender_id'           => $tender->id,
                        'vendor_id'           => (int) $row['vendor_id'],
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

        $progress = TenderKewanganProgress::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['current_step' => 1]
        );

        $confirmed = $request->boolean('confirmed');
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

        $statusInt = ($request->input('decision') === 'memuaskan') ? 1 : 0;
        $userId = Auth::id();

        TenderKewanganKerjaEvaluation::query()->updateOrCreate(
            [
                'tender_id'   => $tender->id,
                'vendor_id'   => (int) $request->input('vendor_id'),
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

        $statusInt = ($request->input('decision') === 'memuaskan') ? 1 : 0;
        $userId = Auth::id();

        TenderKewanganKerjaEvaluation::query()->updateOrCreate(
            [
                'tender_id'   => $tender->id,
                'vendor_id'   => (int) $request->input('vendor_id'),
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

            // 2. Identify qualified and disqualified vendors
            $participants = $tender->participants()->where('cancel_fg', 0)->get();
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
     * Preview or print the financial evaluation report.
     */
    public function cetakLaporan(string $tender_no)
    {
        $tender = $this->resolveTenderByIdentifier($tender_no);
        if (! $tender) {
            abort(404, 'Tender tidak ditemui.');
        }

        // Redirect to show page with print trigger or draft preview
        return redirect()->route('penilaianKewanganSebutHargaKerja.show', $tender->uuid ?: $tender->id)
            ->with('info', 'Pratonton cetakan laporan.');
    }
}
