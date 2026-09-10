<?php

namespace App\Http\Controllers;

use App\Services\StosBackendClient;
use App\Tender;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LantikanTerusController extends Controller
{
    public function __construct(private StosBackendClient $stos)
    {
        // Per-step permissions — no blanket DirectAppointment:list gate.
        // Pemilihan Syarikat is Admin + Agency Ketua Jabatan only (DirectAppointment:select).
    }

    public function index()
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:create')) {
            return $denied;
        }

        $projects = $this->fetchProjects();

        return view('newModule.lantikanTerus.cipta_projek_list', compact('projects'));
    }

    public function create()
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:create')) {
            return $denied;
        }

        $project = null;
        $kategoriPerolehan = \App\Models\Ref\RefKategoriJenisPerolehan::where('active', true)->get();

        return view('newModule.lantikanTerus.cipta_projek', compact('project', 'kategoriPerolehan'));
    }

    public function edit($id)
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:create')) {
            return $denied;
        }

        try {
            $response = $this->stos->getLantikanTerus((int) $id);
            if (! $response->successful()) {
                abort(404);
            }

            $json = $response->json();
            $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
            $kategoriPerolehan = \App\Models\Ref\RefKategoriJenisPerolehan::where('active', true)->get();

            return view('newModule.lantikanTerus.cipta_projek', compact('project', 'kategoriPerolehan'));
        } catch (\Throwable $e) {
            Log::error('Lantikan Terus edit failed', ['error' => $e->getMessage()]);
            abort(404);
        }
    }

    public function store(Request $request)
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:create')) {
            return $denied;
        }

        return $this->persist($request);
    }

    public function update(Request $request, $id)
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:create')) {
            return $denied;
        }

        return $this->persist($request, (int) $id);
    }

    public function sebutHargaIndex()
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectAppointment:quote')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['published_only' => 1]);

        return view('newModule.lantikanTerus.sebut_harga_list', compact('projects'));
    }

    public function sebutHargaShow($id)
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectAppointment:quote')) {
            return $denied;
        }

        try {
            $response = $this->stos->getLantikanTerus((int) $id);
            abort_unless($response->successful(), 404);

            $json = $response->json();
            $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
            $p = $project;
            $isPublic = ! auth()->check() || ! optional(auth()->user())->vendor;

            $ptjName = optional(\App\OrganizationUnit::find($project->ptj_id))->name ?? '-';
            $lokalitiName = optional(\App\Models\Ref\RefLokaliti::find($project->lokaliti_id))->name ?? '-';
            $kategoriName = optional(\App\Models\Ref\RefKategoriJenisPerolehan::find($project->kategori_perolehan))->name ?? '-';

            return view('newModule.lantikanTerus.sebut_harga', compact(
                'project',
                'p',
                'isPublic',
                'ptjName',
                'lokalitiName',
                'kategoriName'
            ));
        } catch (\Throwable $e) {
            abort(404);
        }
    }

    public function submitOffer(Request $request, $id)
    {
        if (! auth()->check() || ! auth()->user()->vendor) {
            return redirect()->back()->with('error', 'Hanya pengguna syarikat boleh menghantar tawaran.');
        }

        if (! $request->hasFile('muat_naik_bq') && ! $request->hasFile('bq')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sila muat naik Dokumen BQ yang telah dilengkapkan sebelum menghantar tawaran.');
        }

        $vendorId = (int) auth()->user()->vendor->id;
        $payload = [
            'vendor_id' => $vendorId,
            'harga_tawaran' => str_replace(',', '', (string) $request->input('harga_tawaran', 0)),
        ];

        if ((float) $payload['harga_tawaran'] <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Harga tawaran diperlukan sebelum menghantar.');
        }

        $files = [];
        if ($request->hasFile('muat_naik_bq')) {
            $files['muat_naik_bq'] = $request->file('muat_naik_bq');
        } elseif ($request->hasFile('bq')) {
            $files['bq'] = $request->file('bq');
        }

        try {
            $response = $this->stos->submitLantikanTerusOffer((int) $id, $payload, $files);
            if ($response->successful()) {
                return redirect()->route('sebutHargaTerus.index')
                    ->with('success', 'Tawaran berjaya dihantar.');
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal menghantar tawaran');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cutOffIndex()
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:cutoff')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['status_process_id' => 5]);

        return view('newModule.lantikanTerus.cut_off_list', compact('projects'));
    }

    public function cutOffShow($id)
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:cutoff')) {
            return $denied;
        }

        $response = $this->stos->getLantikanTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
        $p = $project;

        $offersResponse = $this->stos->getLantikanTerusOffers((int) $id);
        $suppliers = $this->mapOfferSuppliers(collect($offersResponse->json('data') ?? []));

        return view('newModule.lantikanTerus.cut_off', compact('project', 'suppliers', 'p'));
    }

    public function storeCutoff(Request $request, $id)
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:cutoff')) {
            return $denied;
        }

        try {
            $payload = [
                'offer_ids' => array_values(array_map('intval', (array) $request->input('offer_ids', []))),
                'uploaded_by' => auth()->id(),
            ];

            $files = [];
            if ($request->hasFile('jpict')) {
                $files['jpict'] = $request->file('jpict');
            }
            if ($request->hasFile('minit_bebas')) {
                $files['minit_bebas'] = $request->file('minit_bebas');
            }

            $response = $this->stos->cutoffLantikanTerus((int) $id, $payload, $files);

            if ($response->successful()) {
                return redirect()->route('cutOffTerus.index')
                    ->with('success', 'Cut-off berjaya diselesaikan.');
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal cut-off');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function pemilihanIndex()
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:select')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['status_process_id' => 31]);

        return view('newModule.lantikanTerus.pemilihan_syarikat_list', compact('projects'));
    }

    public function pemilihanShow($id)
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:select')) {
            return $denied;
        }

        $response = $this->stos->getLantikanTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
        $p = $project;

        $docs = collect($json['documents'] ?? []);
        $jpict = $docs->firstWhere('doc_type', 'jpict');
        $minit = $docs->firstWhere('doc_type', 'minit_bebas');
        $documents = (object) [
            'jpict' => (object) [
                'name' => is_array($jpict) ? ($jpict['original_name'] ?? $jpict['display_name'] ?? '-') : '-',
                'has_file' => is_array($jpict) && ! empty($jpict['file_path']),
            ],
            'minit_bebas' => (object) [
                'name' => is_array($minit) ? ($minit['original_name'] ?? $minit['display_name'] ?? '-') : '-',
                'has_file' => is_array($minit) && ! empty($minit['file_path']),
            ],
        ];

        $offersResponse = $this->stos->getLantikanTerusOffers((int) $id);
        $suppliers = $this->mapOfferSuppliers(
            collect($offersResponse->json('data') ?? [])->where('shortlisted', true)->values()
        );

        return view('newModule.lantikanTerus.pemilihan_syarikat', compact('project', 'suppliers', 'documents', 'p'));
    }

    public function storePemilihan(Request $request, $id)
    {
        if ($denied = $this->denyUnlessMenu('DirectAppointment:select')) {
            return $denied;
        }

        try {
            $response = $this->stos->selectLantikanTerusWinner((int) $id, [
                'offer_id' => $request->input('offer_id'),
            ]);

            if ($response->successful()) {
                return redirect()->route('pemilihanTerus.index')
                    ->with('success', 'Syarikat berjaya dipilih.');
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal memilih syarikat');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function keputusanIndex()
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectAppointment:decision')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['status_process_id' => 32]);

        return view('newModule.lantikanTerus.keputusan_syarikat_list', compact('projects'));
    }

    public function keputusanShow($id)
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectAppointment:decision')) {
            return $denied;
        }

        $response = $this->stos->getLantikanTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
        $p = $project;

        $offersResponse = $this->stos->getLantikanTerusOffers((int) $id);
        $selected = collect($offersResponse->json('data') ?? [])->firstWhere('selected', true);
        $selected = $selected ? (array) $selected : null;
        $vendorId = (int) ($selected['vendor_id'] ?? 0);

        $decision = $selected ? (object) [
            'company' => $this->resolveVendorName($vendorId),
            'harga_sst' => $selected['harga_tawaran'] ?? 0,
            'status' => $selected['decision'] ?? 'pending',
        ] : null;

        return view('newModule.lantikanTerus.keputusan_syarikat', compact('project', 'decision', 'p'));
    }

    public function downloadProjectDocument($id, string $docType)
    {
        if (! in_array($docType, ['bq', 'jpict', 'minit_bebas'], true)) {
            abort(404);
        }

        $allowed = $this->isVendorActor()
            ? ($docType === 'bq')
            : auth()->user()?->canAccessMenu('DirectAppointment:create')
                || auth()->user()?->canAccessMenu('DirectAppointment:cutoff')
                || auth()->user()?->canAccessMenu('DirectAppointment:select')
                || auth()->user()?->canAccessMenu('DirectAppointment:quote')
                || auth()->user()?->canAccessMenu('DirectAppointment:list');

        if (! $allowed) {
            return $this->_access_denied();
        }

        try {
            $response = $this->stos->downloadLantikanTerusDocument((int) $id, $docType);
            if (! $response->successful()) {
                abort(404, 'Fail tidak dijumpai.');
            }

            $filename = $this->extractDownloadFilename(
                $response->header('Content-Disposition'),
                $docType . '.pdf'
            );

            return response($response->body(), 200, [
                'Content-Type' => $response->header('Content-Type') ?: 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Throwable $e) {
            Log::error('Lantikan Terus document open failed', [
                'id' => $id,
                'doc_type' => $docType,
                'error' => $e->getMessage(),
            ]);
            abort(404, 'Fail tidak dijumpai.');
        }
    }

    public function downloadOfferBq($id, $offerId)
    {
        if ($denied = $this->denyUnlessMenuAny([
            'DirectAppointment:cutoff',
            'DirectAppointment:select',
        ])) {
            return $denied;
        }

        try {
            $response = $this->stos->downloadLantikanTerusOfferBq((int) $id, (int) $offerId);
            if (! $response->successful()) {
                abort(404, 'Fail BQ tidak dijumpai.');
            }

            $filename = $this->extractDownloadFilename(
                $response->header('Content-Disposition'),
                'Dokumen_BQ.pdf'
            );

            return response($response->body(), 200, [
                'Content-Type' => $response->header('Content-Type') ?: 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Throwable $e) {
            Log::error('Lantikan Terus offer BQ open failed', [
                'id' => $id,
                'offer_id' => $offerId,
                'error' => $e->getMessage(),
            ]);
            abort(404, 'Fail BQ tidak dijumpai.');
        }
    }

    public function storeKeputusan(Request $request, $id)
    {
        if (! auth()->check() || ! auth()->user()->vendor) {
            return redirect()->back()->with('error', 'Hanya pengguna syarikat boleh membuat keputusan.');
        }

        try {
            $response = $this->stos->keputusanLantikanTerus((int) $id, [
                'vendor_id' => auth()->user()->vendor->id,
                'decision' => $request->input('decision'),
            ]);

            if ($response->successful()) {
                return redirect()->route('keputusanTerus.index')
                    ->with('success', $response->json('message'));
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal menyimpan keputusan');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function downloadSuratSetujuTerima($id)
    {
        $pdfUrl = 'https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf';

        try {
            $pdfContent = file_get_contents($pdfUrl);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="Surat_Setuju_Terima_' . $id . '.pdf"');
        } catch (\Exception $e) {
            abort(500, 'Failed to download PDF');
        }
    }

    private function persist(Request $request, ?int $id = null)
    {
        if (! auth()->check()) {
            return $this->_access_denied();
        }

        $request->validate([
            'name' => 'required|string|max:500',
            'ref_number' => 'required|string|max:255',
            'ptj_id' => 'required',
            'harga_indikatif' => 'required',
            'sumber_peruntukan' => 'required|string',
            'terbuka_kepada' => 'required|string',
        ], [
            'name.required' => 'Tajuk Perolehan wajib diisi.',
            'ref_number.required' => 'No. Rujukan Fail wajib diisi.',
            'ptj_id.required' => 'PTJ wajib dipilih.',
            'harga_indikatif.required' => 'Harga Indikatif Jabatan wajib diisi.',
        ]);

        $payload = $this->buildPayload($request);
        $action = $request->input('action', 'draft');
        $payload['action'] = $action;

        $files = [];
        if ($request->hasFile('dokumen_bq')) {
            $files['dokumen_bq'] = $request->file('dokumen_bq');
        }
        unset($payload['dokumen_bq']);

        if ($action === 'publish') {
            $hasNewBq = isset($files['dokumen_bq']);
            $hasExistingBq = $id
                ? DB::table('lantikan_terus_documents')
                    ->where('tender_id', $id)
                    ->where('doc_type', 'bq')
                    ->exists()
                : false;

            if (! $hasNewBq && ! $hasExistingBq) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Dokumen BQ diperlukan sebelum diterbitkan. Sila muat naik BQ pada langkah Maklumat BQ.');
            }
        }

        try {
            if ($id) {
                $response = $this->stos->updateLantikanTerus($id, $payload, $files);
            } else {
                $response = $this->stos->createLantikanTerus($payload, $files);
            }

            if ($response->successful()) {
                $message = $action === 'publish'
                    ? 'Projek berjaya diterbitkan.'
                    : 'Projek berjaya disimpan sebagai draf.';

                // Terbitkan → senarai. Simpan → kekal di halaman kemaskini (atau cipta selepas id baharu).
                if ($action === 'publish') {
                    return redirect()->route('lantikan.index')->with('success', $message);
                }

                $savedId = $id
                    ?: (int) ($response->json('tender_id') ?? $response->json('data.id') ?? 0);

                if ($savedId > 0) {
                    return redirect()->route('lantikan.edit', [
                        'id' => $savedId,
                        'step' => min(3, max(1, (int) $request->input('wizard_step', 1))),
                    ])->with('success', $message);
                }

                return redirect()->route('lantikan.create')->with('success', $message);
            }

            Log::error('Lantikan Terus persist API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $apiError = $response->json('error');
            $message = $response->json('message') ?? 'Gagal menyimpan projek';
            if (is_string($apiError) && $apiError !== '') {
                if (str_contains($apiError, "Column 'name' cannot be null")) {
                    $message = 'Tajuk Perolehan wajib diisi sebelum menyimpan projek.';
                } elseif (str_contains($apiError, 'Dokumen BQ diperlukan')) {
                    $message = 'Dokumen BQ diperlukan sebelum diterbitkan. Sila muat naik BQ pada langkah Maklumat BQ.';
                } else {
                    $message = $apiError;
                }
            }
            if (is_string($response->json('message')) && str_contains((string) $response->json('message'), 'Dokumen BQ')) {
                $message = $response->json('message');
            }

            return redirect()->back()->withInput()->with('error', $message);
        } catch (\Throwable $e) {
            Log::error('Lantikan Terus persist failed', ['error' => $e->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan projek: ' . $e->getMessage());
        }
    }

    private function buildPayload(Request $request): array
    {
        $payload = $request->except(['_token', '_method', 'dokumen_bq', 'wizard_step']);
        $user = auth()->user();
        $payload['creator_id'] = $user->id;

        if (isset($payload['ptj_id']) && $user->hasRole('Admin')) {
            $payload['organization_unit_id'] = $payload['ptj_id'];
        } else {
            $payload['organization_unit_id'] = optional($user->organizationunit)->id ?? ($payload['ptj_id'] ?? null);
        }

        if (isset($payload['mof']) && is_array($payload['mof'])) {
            $mofCodes = [];
            foreach ($payload['mof'] as $index => $mofGroup) {
                if (isset($mofGroup['code']) && is_array($mofGroup['code'])) {
                    $joinRule = isset($payload['mof_logic_' . $index])
                        ? strtolower($payload['mof_logic_' . $index])
                        : 'and';

                    $mofCodes[] = [
                        'codes' => $mofGroup['code'],
                        'inner_rule' => strtolower($mofGroup['logic_mid'] ?? 'or'),
                        'join_rule' => $joinRule,
                    ];
                }
            }
            $payload['mof_codes'] = $mofCodes;
            unset($payload['mof']);
        }

        if (isset($payload['cidb']) && is_array($payload['cidb'])) {
            $cidbCodes = [];
            $cidbGrades = [];

            foreach ($payload['cidb'] as $index => $cidbGroup) {
                if (isset($cidbGroup['grade']) && is_array($cidbGroup['grade'])) {
                    $cidbGrades = array_merge($cidbGrades, $cidbGroup['grade']);
                }

                if (isset($cidbGroup['spec']) && is_array($cidbGroup['spec'])) {
                    $joinRule = isset($payload['cidb_logic_' . $index])
                        ? strtolower($payload['cidb_logic_' . $index])
                        : 'or';

                    $cidbCodes[] = [
                        'codes' => $cidbGroup['spec'],
                        'inner_rule' => strtolower($cidbGroup['logic_mid'] ?? 'and'),
                        'join_rule' => $joinRule,
                    ];
                }
            }

            if (count($cidbCodes) > 0) {
                $payload['cidb_codes'] = $cidbCodes;
            }
            if (count($cidbGrades) > 0) {
                $payload['cidb_grade'] = array_unique($cidbGrades);
            }
            unset($payload['cidb']);
        }

        foreach ($payload as $key => $value) {
            if (str_starts_with($key, 'mof_logic_') || str_starts_with($key, 'cidb_logic_')) {
                unset($payload[$key]);
            }
        }

        if (isset($payload['harga_indikatif'])) {
            $payload['harga_indikatif'] = str_replace(',', '', $payload['harga_indikatif']);
        }

        return $payload;
    }

    private function fetchProjects(array $query = [])
    {
        try {
            if (! $this->stos->isConfigured()) {
                return collect();
            }

            $response = $this->stos->listLantikanTerus($query);
            if (! $response->successful()) {
                return collect();
            }

            $data = $response->json('data');
            $rows = $data['data'] ?? $data ?? [];

            return collect($rows)->map(fn ($row) => $this->mapProject($row));
        } catch (\Throwable $e) {
            Log::warning('Failed fetching lantikan terus list', ['error' => $e->getMessage()]);

            $q = Tender::query()->where('type', 'lantikan_terus')->orderByDesc('id');

            if (isset($query['status_process_id'])) {
                $q->where('status_process_id', $query['status_process_id']);
            }
            if (! empty($query['published_only'])) {
                $q->where('status_process_id', '>=', 5);
            }

            return $q->get()->map(fn ($t) => $this->mapProject($t->toArray()));
        }
    }

    private function mapProject($data, array $documents = []): object
    {
        $data = (array) $data;
        $statusId = (int) ($data['status_process_id'] ?? 1);

        $status = match (true) {
            $statusId === 1 => 'draft',
            $statusId === 5 => 'submitted',
            $statusId === 31 => 'cutoff',
            $statusId === 32 => 'selected',
            $statusId === 33 => 'accepted',
            default => 'submitted',
        };

        $tarikhBuka = $data['advertise_start_date'] ?? null;
        $tarikhTutup = $data['advertise_stop_date'] ?? null;

        $bqDoc = collect($documents)->firstWhere('doc_type', 'bq');
        $bqFilename = null;
        if (is_array($bqDoc)) {
            $bqFilename = $bqDoc['display_name'] ?? $bqDoc['original_name'] ?? null;
        }

        $kodBidang = $this->mapKodBidang((array) ($data['codes'] ?? []));

        return (object) [
            'id' => $data['id'] ?? null,
            'name' => $data['name'] ?? '',
            'no_tender' => $data['no_tender'] ?? '',
            'ref_number' => $data['ref_number'] ?? '',
            'ptj_id' => $data['organization_unit_id'] ?? null,
            'harga_indikatif' => $data['harga_indikatif'] ?? null,
            'tarikh_buka' => $tarikhBuka ? Carbon::parse($tarikhBuka)->format('d/m/Y') : '',
            'tarikh_tutup' => $tarikhTutup ? Carbon::parse($tarikhTutup)->format('d/m/Y') : '',
            'tarikh' => isset($data['created_at']) ? Carbon::parse($data['created_at'])->format('d/m/Y') : '',
            'zon_lokasi' => ! empty($data['zon_lokasi']) ? '1' : '0',
            'lokaliti_id' => $data['lokaliti_id'] ?? null,
            'kategori_perolehan' => $data['kategori_perolehan_id'] ?? null,
            'sumber_peruntukan' => $data['sumber_peruntukan'] ?? 'pembangunan',
            'sumber_lain_text' => $data['sumber_lain_text'] ?? null,
            'terbuka_kepada' => $data['terbuka_kepada'] ?? 'semua',
            'status' => $status,
            'status_process_id' => $statusId,
            'bq_filename' => $bqFilename,
            'has_bq' => is_array($bqDoc) && ! empty($bqDoc['file_path']),
            'documents' => $documents,
            'mof' => $kodBidang['mof'],
            'cidb' => $kodBidang['cidb'],
            'mof_cidb_rule' => strtoupper((string) ($data['mof_cidb_rule'] ?? 'AND')) === 'OR' ? 'OR' : 'AND',
        ];
    }

    /** Rebuild the Kod Bidang rows (grouped by `order`) the form was saved with. */
    private function mapKodBidang(array $codes): array
    {
        $rows = collect($codes)->map(fn ($code) => (array) $code);

        $group = function (string $type, string $key, string $inner, string $join) use ($rows) {
            return $rows->where('code_type', $type)
                ->groupBy(fn (array $code) => (int) ($code['order'] ?? 1))
                ->sortKeys()
                ->map(fn ($items) => [
                    $key => $items->pluck('code_id')->map(fn ($id) => (int) $id)->values()->all(),
                    'logic_mid' => strtoupper((string) ($items->first()['inner_rule'] ?: $inner)),
                    'join_rule' => strtoupper((string) ($items->first()['join_rule'] ?: $join)),
                ])
                ->values()
                ->all();
        };

        $mof = $group('mof', 'code', 'or', 'and');
        $cidb = $group('cidb', 'spec', 'and', 'or');

        $grades = $rows->where('code_type', 'cidb-g')
            ->pluck('code_id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        // Gred CIDB hanya wujud pada baris pertama.
        if ($grades !== [] && $cidb === []) {
            $cidb[] = ['spec' => [], 'logic_mid' => 'AND', 'join_rule' => 'OR'];
        }
        if ($cidb !== []) {
            $cidb[0]['grade'] = $grades;
        }

        return ['mof' => $mof, 'cidb' => $cidb];
    }

    private function mapOfferSuppliers(Collection $offers): Collection
    {
        $vendorIds = $offers
            ->map(fn ($offer) => (int) (((array) $offer)['vendor_id'] ?? 0))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $vendorNames = $this->vendorNameMap($vendorIds);

        return $offers->map(function ($offer) use ($vendorNames) {
            $offer = (array) $offer;
            $vendorId = (int) ($offer['vendor_id'] ?? 0);

            return (object) [
                'id' => $offer['id'] ?? null,
                'vendor_id' => $vendorId ?: null,
                'name' => $vendorNames[$vendorId]
                    ?? ($vendorId ? 'Vendor #' . $vendorId : '-'),
                'harga_tawaran' => (float) ($offer['harga_tawaran'] ?? 0),
                'bq_filename' => $offer['bq_original_name'] ?? 'Dokumen BQ.pdf',
                'has_bq' => ! empty($offer['bq_path']),
            ];
        })->values();
    }

    /**
     * @param  list<int>  $vendorIds
     * @return array<int, string>
     */
    private function vendorNameMap(array $vendorIds): array
    {
        if ($vendorIds === []) {
            return [];
        }

        return Vendor::query()
            ->whereIn('id', $vendorIds)
            ->get(['id', 'name'])
            ->mapWithKeys(fn ($vendor) => [(int) $vendor->id => (string) $vendor->name])
            ->all();
    }

    private function resolveVendorName(int $vendorId): string
    {
        if ($vendorId <= 0) {
            return '-';
        }

        $name = Vendor::query()->where('id', $vendorId)->value('name');

        return $name ? (string) $name : ('Vendor #' . $vendorId);
    }

    private function extractDownloadFilename(?string $contentDisposition, string $fallback): string
    {
        if (! $contentDisposition) {
            return $fallback;
        }

        if (preg_match('/filename\*=UTF-8\'\'([^;]+)/i', $contentDisposition, $matches)) {
            return rawurldecode(trim($matches[1], " \t\"'"));
        }

        if (preg_match('/filename=\"?([^\";]+)\"?/i', $contentDisposition, $matches)) {
            return trim($matches[1], " \t\"'");
        }

        return $fallback;
    }

    /**
     * @param  list<string>  $permissions
     */
    private function denyUnlessMenuAny(array $permissions)
    {
        $user = auth()->user();
        if ($user) {
            foreach ($permissions as $permission) {
                if ($user->canAccessMenu($permission)) {
                    return null;
                }
            }
        }

        return $this->_access_denied();
    }

    private function isVendorActor(): bool
    {
        $user = auth()->user();

        return (bool) ($user && $user->vendor_id && $user->hasRole('Vendor'));
    }

    private function denyUnlessVendorOrMenu(string $permission)
    {
        if ($this->isVendorActor()) {
            return null;
        }

        return $this->denyUnlessMenu($permission);
    }
}
