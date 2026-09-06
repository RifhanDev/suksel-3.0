<?php

namespace App\Http\Controllers;

use App\Services\StosBackendClient;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembelianTerusController extends Controller
{
    public function __construct(private StosBackendClient $stos)
    {
        // Per-step permissions — no blanket DirectPurchase:list gate.
        // detailProject stays public (registered outside auth group).
    }

    public function index()
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:create')) {
            return $denied;
        }

        $projects = $this->fetchProjects();

        return view('newModule.pembelian_terus.cipta_projek_list', compact('projects'));
    }

    public function create()
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:create')) {
            return $denied;
        }

        $project = null;
        $items = collect();
        $kategoriPerolehan = \App\Models\Ref\RefKategoriJenisPerolehan::where('active', true)->get();

        return view('newModule.pembelian_terus.cipta_projek', compact('project', 'items', 'kategoriPerolehan'));
    }

    public function edit($id)
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:create')) {
            return $denied;
        }

        try {
            $response = $this->stos->getPembelianTerus((int) $id);
            if (! $response->successful()) {
                abort(404);
            }

            $json = $response->json();
            $data = $json['data'] ?? [];
            $project = $this->mapProject($data, $json['items'] ?? []);
            $items = collect($json['items'] ?? []);
            $kategoriPerolehan = \App\Models\Ref\RefKategoriJenisPerolehan::where('active', true)->get();

            return view('newModule.pembelian_terus.cipta_projek', compact('project', 'items', 'kategoriPerolehan'));
        } catch (\Throwable $e) {
            Log::error('Pembelian Terus edit failed', ['error' => $e->getMessage()]);
            abort(404);
        }
    }

    public function store(Request $request)
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:create')) {
            return $denied;
        }

        return $this->persist($request);
    }

    public function update(Request $request, $id)
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:create')) {
            return $denied;
        }

        return $this->persist($request, (int) $id);
    }

    public function quoteProject()
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectPurchase:quote')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['published_only' => 1]);

        // Syarikat only sees projects they are eligible for (MOF/CIDB/district rules).
        if ($this->isVendorActor()) {
            $vendorId = (int) auth()->user()->vendor_id;
            $projects = $projects->filter(function ($project) use ($vendorId) {
                return $this->vendorEligibleForProject((int) $project->id, $vendorId);
            })->values();
        }

        return view('newModule.pembelian_terus.sebut_harga_list', compact('projects'));
    }

    public function detailProject($id, Request $request)
    {
        try {
            $response = $this->stos->getPembelianTerus((int) $id);
            abort_unless($response->successful(), 404);

            $json = $response->json();
            $project = $this->mapProject($json['data'] ?? [], $json['items'] ?? []);
            $items = collect($json['items'] ?? []);
            $isPublic = ! auth()->check() || ! optional(auth()->user())->vendor;
            $p = $project;

            $ptjName = optional(\App\OrganizationUnit::find($project->ptj_id))->name ?? '-';
            $lokalitiName = optional(\App\Models\Ref\RefLokaliti::find($project->lokaliti_id))->name ?? '-';
            $kategoriName = optional(\App\Models\Ref\RefKategoriJenisPerolehan::find($project->kategori_perolehan))->name ?? '-';

            // Enrich MOF / CIDB labels for Paparan Projek (vendor-facing PT UI).
            $tender = Tender::with(['codes.code'])->find((int) $id);
            $mofLabels = [];
            $cidbGradeLabels = [];
            if ($tender) {
                $mofLabels = $tender->mof_codes
                    ->map(function ($row) {
                        $code = $row->code;
                        if (! $code) {
                            return null;
                        }

                        return $code->label2 ?? trim(($code->code ?? '') . ' - ' . ($code->name ?? ''));
                    })
                    ->filter()
                    ->values()
                    ->all();
                $cidbGradeLabels = $tender->cidb_grades
                    ->map(function ($row) {
                        $code = $row->code;
                        if (! $code) {
                            return null;
                        }

                        return $code->label2 ?? trim(($code->code ?? '') . ' - ' . ($code->name ?? ''));
                    })
                    ->filter()
                    ->values()
                    ->all();
            }

            $specifications = $items->values()->map(function ($item, $index) {
                $item = (array) $item;

                return [
                    'id' => $item['id'] ?? ($index + 1),
                    'item' => $item['nama_item'] ?? ($item['name'] ?? '-'),
                    'kuantiti' => $item['kuantiti'] ?? 0,
                    'sst' => (bool) ($item['sst'] ?? true),
                    'brand' => '',
                    'harga_seunit' => '',
                    'harga_keseluruhan' => '',
                    'harga_sst' => '',
                ];
            })->all();

            $vendorEligible = true;
            $eligibilityMessage = null;
            $canSubmitOffer = false;
            if (auth()->check() && auth()->user()->vendor_id) {
                $vendorEligible = $this->vendorEligibleForProject((int) $id, (int) auth()->user()->vendor_id);
                $canSubmitOffer = $this->isVendorActor() && $vendorEligible;
                if (! $vendorEligible) {
                    $eligibilityMessage = 'Syarikat anda tidak menepati syarat kelayakan (MOF/CIDB/daerah) bagi projek ini.';
                }
            }

            // Correct PT vendor UI (3 steps + item modal) — not the Lantikan-style sebut_harga.
            return view('newModule.pembelian_terus.details', compact(
                'project',
                'items',
                'specifications',
                'isPublic',
                'p',
                'ptjName',
                'lokalitiName',
                'kategoriName',
                'mofLabels',
                'cidbGradeLabels',
                'vendorEligible',
                'eligibilityMessage',
                'canSubmitOffer'
            ));
        } catch (\Throwable $e) {
            Log::error('Pembelian Terus detail failed', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            abort(404);
        }
    }

    public function submitOffer(Request $request, $id)
    {
        if (! auth()->check() || ! auth()->user()->vendor) {
            return redirect()->back()->with('error', 'Hanya pengguna syarikat boleh menghantar tawaran.');
        }

        if (! $this->isVendorActor() && ! auth()->user()->canAccessMenu('DirectPurchase:quote')) {
            return $this->_access_denied();
        }

        $vendorId = (int) auth()->user()->vendor->id;
        if (! $this->vendorEligibleForProject((int) $id, $vendorId)) {
            return redirect()->back()->with(
                'error',
                'Syarikat anda tidak menepati syarat kelayakan (MOF/CIDB/daerah) bagi projek ini.'
            );
        }

        // Backend expects:
        // - offer_items[{item_id, brand, harga_seunit}]
        // - quotation (file, optional)
        // - offer_items[i][dokumen_sokongan] (file per item, optional)
        $rawItems = $request->input('offer_items', $request->input('items', []));
        $offerItems = [];
        foreach ((array) $rawItems as $row) {
            $row = (array) $row;
            $itemId = (int) ($row['item_id'] ?? 0);
            if ($itemId <= 0) {
                continue;
            }

            $offerItems[] = [
                'item_id' => $itemId,
                'brand' => $row['brand'] ?? null,
                'harga_seunit' => (float) str_replace(',', '', (string) ($row['harga_seunit'] ?? 0)),
            ];
        }

        if (count($offerItems) === 0) {
            return redirect()->back()->with('error', 'Sila lengkapkan harga bagi setiap item sebelum menghantar.');
        }

        $payload = [
            'vendor_id' => $vendorId,
            'offer_items' => $offerItems,
        ];

        $files = [];
        if ($request->hasFile('quotation')) {
            $files['quotation'] = $request->file('quotation');
        }

        foreach ((array) $request->file('offer_items', []) as $index => $itemFiles) {
            if (! is_array($itemFiles)) {
                continue;
            }
            $dokumen = $itemFiles['dokumen_sokongan'] ?? $itemFiles['dokumen'] ?? null;
            if ($dokumen) {
                $files['offer_items[' . $index . '][dokumen_sokongan]'] = $dokumen;
            }
        }

        try {
            $response = $this->stos->submitPembelianTerusOffer((int) $id, $payload, $files);
            if ($response->successful()) {
                return redirect()->route('pembelianTerus.quoteProject')
                    ->with('success', 'Tawaran berjaya dihantar.');
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal menghantar tawaran');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cutOffProject()
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:cutoff')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['status_process_id' => 5]);

        return view('newModule.pembelian_terus.cut_off_list', compact('projects'));
    }

    public function cutOffDetails($id)
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:cutoff')) {
            return $denied;
        }

        $response = $this->stos->getPembelianTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['items'] ?? []);
        $p = $project;

        $offersResponse = $this->stos->getPembelianTerusOffers((int) $id);
        $offers = collect($offersResponse->json('data') ?? []);

        $suppliers = $offers->map(function ($offer) {
            $offer = (array) $offer;

            return (object) [
                'id' => $offer['id'] ?? null,
                'name' => 'Vendor #' . ($offer['vendor_id'] ?? '-'),
                'harga_tawaran' => $offer['total_harga_sst'] ?? 0,
                'bq_filename' => $offer['quotation_original_name'] ?? 'Quotation',
            ];
        });

        return view('newModule.pembelian_terus.cut_off', compact('project', 'offers', 'suppliers', 'p'));
    }

    public function storeCutoff(Request $request, $id)
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:cutoff')) {
            return $denied;
        }

        try {
            $payload = $request->all();
            $payload['uploaded_by'] = auth()->id();
            $response = $this->stos->cutoffPembelianTerus((int) $id, $payload);

            if ($response->successful()) {
                return redirect()->route('pembelianTerus.cutOffProject')
                    ->with('success', 'Cut-off berjaya diselesaikan.');
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal cut-off');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function pemilihanSyarikat()
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:select')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['status_process_id' => 31]);

        return view('newModule.pembelian_terus.pemilihan_syarikat_list', compact('projects'));
    }

    public function pemilihanSyarikatDetails($id)
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:select')) {
            return $denied;
        }

        $response = $this->stos->getPembelianTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['items'] ?? []);
        $p = $project;
        $documents = collect($json['documents'] ?? []);

        $offersResponse = $this->stos->getPembelianTerusOffers((int) $id);
        $suppliers = collect($offersResponse->json('data') ?? [])
            ->where('shortlisted', true)
            ->values()
            ->map(function ($offer) {
                $offer = (array) $offer;

                return (object) [
                    'id' => $offer['id'] ?? null,
                    'name' => 'Vendor #' . ($offer['vendor_id'] ?? '-'),
                    'harga_tawaran' => $offer['total_harga_sst'] ?? 0,
                    'harga_sst' => $offer['total_harga_sst'] ?? 0,
                ];
            });

        return view('newModule.pembelian_terus.pemilihan_syarikat_form', compact('project', 'suppliers', 'documents', 'p'));
    }

    public function storePemilihan(Request $request, $id)
    {
        if ($denied = $this->denyUnlessMenu('DirectPurchase:select')) {
            return $denied;
        }

        try {
            $response = $this->stos->selectPembelianTerusWinner((int) $id, [
                'offer_id' => $request->input('offer_id'),
            ]);

            if ($response->successful()) {
                return redirect()->route('pembelianTerus.pemilihanSyarikat')
                    ->with('success', 'Syarikat berjaya dipilih. Notifikasi akan dihantar.');
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal memilih syarikat');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function keputusanSyarikat()
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectPurchase:decision')) {
            return $denied;
        }

        $projects = $this->fetchProjects(['status_process_id' => 32]);

        // Selected vendor only sees projects where they won.
        if ($this->isVendorActor()) {
            $vendorId = (int) auth()->user()->vendor_id;
            $projects = $projects->filter(function ($project) use ($vendorId) {
                return $this->vendorIsSelectedWinner((int) $project->id, $vendorId);
            })->values();
        }

        return view('newModule.pembelian_terus.keputusan_syarikat_list', compact('projects'));
    }

    public function keputusanSyarikatDetails($id)
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectPurchase:decision')) {
            return $denied;
        }

        if ($this->isVendorActor()) {
            $vendorId = (int) auth()->user()->vendor_id;
            if (! $this->vendorIsSelectedWinner((int) $id, $vendorId)) {
                return $this->_access_denied();
            }
        }

        $response = $this->stos->getPembelianTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['items'] ?? []);
        $p = $project;

        $offersResponse = $this->stos->getPembelianTerusOffers((int) $id);
        $selected = collect($offersResponse->json('data') ?? [])->firstWhere('selected', true);
        $selected = $selected ? (array) $selected : null;

        $decision = $selected ? (object) [
            'company' => 'Vendor #' . ($selected['vendor_id'] ?? '-'),
            'harga_sst' => $selected['total_harga_sst'] ?? 0,
            'status' => $selected['decision'] ?? 'pending',
        ] : null;

        return view('newModule.pembelian_terus.keputusan_syarikat_form', compact('project', 'decision', 'p'));
    }

    public function storeKeputusan(Request $request, $id)
    {
        if (! auth()->check() || ! auth()->user()->vendor) {
            return redirect()->back()->with('error', 'Hanya pengguna syarikat boleh membuat keputusan.');
        }

        if (! $this->isVendorActor()) {
            return $this->_access_denied();
        }

        $vendorId = (int) auth()->user()->vendor->id;
        if (! $this->vendorIsSelectedWinner((int) $id, $vendorId)) {
            return $this->_access_denied();
        }

        try {
            $response = $this->stos->keputusanPembelianTerus((int) $id, [
                'vendor_id' => $vendorId,
                'decision' => $request->input('decision'),
            ]);

            if ($response->successful()) {
                return redirect()->route('pembelianTerus.keputusanSyarikat')
                    ->with('success', $response->json('message'));
            }

            return redirect()->back()->with('error', $response->json('message') ?? 'Gagal menyimpan keputusan');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function downloadSuratSetujuTerima($id)
    {
        if ($denied = $this->denyUnlessVendorOrMenu('DirectPurchase:decision')) {
            return $denied;
        }

        if ($this->isVendorActor()) {
            $vendorId = (int) auth()->user()->vendor_id;
            if (! $this->vendorIsSelectedWinner((int) $id, $vendorId)) {
                return $this->_access_denied();
            }
        }

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

        $payload = $this->buildPayload($request);
        $action = $request->input('action', 'draft');
        $payload['action'] = $action;

        try {
            if ($id) {
                $response = $this->stos->updatePembelianTerus($id, $payload);
            } else {
                $response = $this->stos->createPembelianTerus($payload);
            }

            if ($response->successful()) {
                $message = $action === 'publish'
                    ? 'Projek berjaya diterbitkan.'
                    : 'Projek berjaya disimpan sebagai draf.';

                // Terbitkan → senarai. Simpan → kekal di halaman kemaskini/cipta.
                if ($action === 'publish') {
                    return redirect()->route('pembelianTerus.createProject')
                        ->with('success', $message);
                }

                $savedId = $id
                    ?: (int) ($response->json('tender_id') ?? $response->json('data.id') ?? 0);

                if ($savedId > 0) {
                    return redirect()->route('pembelianTerus.edit', $savedId)
                        ->with('success', $message);
                }

                return redirect()->route('pembelianTerus.createProject')
                    ->with('success', $message);
            }

            Log::error('Pembelian Terus persist API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect()->back()->withInput()->with('error', $response->json('message') ?? 'Gagal menyimpan projek');
        } catch (\Throwable $e) {
            Log::error('Pembelian Terus persist failed', ['error' => $e->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan projek: ' . $e->getMessage());
        }
    }

    private function buildPayload(Request $request): array
    {
        $payload = $request->except(['_token', '_method']);
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

            $response = $this->stos->listPembelianTerus($query);
            if (! $response->successful()) {
                return collect();
            }

            $data = $response->json('data');
            $rows = $data['data'] ?? $data ?? [];

            return collect($rows)->map(function ($row) {
                return $this->mapProject($row);
            });
        } catch (\Throwable $e) {
            Log::warning('Failed fetching pembelian terus list', ['error' => $e->getMessage()]);

            // Fallback: query shared DB directly
            $q = Tender::query()->where('type', 'pembelian_terus')->orderByDesc('id');

            if (isset($query['status_process_id'])) {
                $q->where('status_process_id', $query['status_process_id']);
            }
            if (! empty($query['published_only'])) {
                $q->where('status_process_id', '>=', 5);
            }

            return $q->get()->map(fn ($t) => $this->mapProject($t->toArray()));
        }
    }

    private function mapProject($data, array $items = []): object
    {
        $data = (array) $data;
        $statusId = (int) ($data['status_process_id'] ?? 1);

        $status = match (true) {
            $statusId === 1 => 'draft',
            $statusId === 5 => 'published',
            $statusId === 31 => 'cutoff',
            $statusId === 32 => 'selected',
            $statusId === 33 => 'accepted',
            default => 'submitted',
        };

        $tarikhBuka = $data['advertise_start_date'] ?? null;
        $tarikhTutup = $data['advertise_stop_date'] ?? null;

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
            'items' => $items,
            'mof' => [],
            'cidb' => [],
        ];
    }

    private function isVendorActor(): bool
    {
        $user = auth()->user();

        return (bool) ($user && $user->vendor_id && $user->hasRole('Vendor'));
    }

    /**
     * Allow Vendor role OR a staff menu permission.
     */
    private function denyUnlessVendorOrMenu(string $permission)
    {
        if ($this->isVendorActor()) {
            return null;
        }

        return $this->denyUnlessMenu($permission);
    }

    /**
     * Reuse tender MOF/CIDB/district eligibility rules for pembelian terus projects.
     */
    private function vendorEligibleForProject(int $tenderId, int $vendorId): bool
    {
        $tender = Tender::with('codes')->find($tenderId);
        if (! $tender || $tender->type !== 'pembelian_terus') {
            // If type missing/legacy, still apply canParticipate when record exists.
            if (! $tender) {
                return false;
            }
        }

        try {
            return (bool) $tender->canParticipate($vendorId);
        } catch (\Throwable $e) {
            Log::warning('Pembelian Terus eligibility check failed', [
                'tender_id' => $tenderId,
                'vendor_id' => $vendorId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function vendorIsSelectedWinner(int $tenderId, int $vendorId): bool
    {
        try {
            $offersResponse = $this->stos->getPembelianTerusOffers($tenderId);
            if (! $offersResponse->successful()) {
                return false;
            }

            $selected = collect($offersResponse->json('data') ?? [])
                ->first(function ($offer) use ($vendorId) {
                    $offer = (array) $offer;

                    return ! empty($offer['selected']) && (int) ($offer['vendor_id'] ?? 0) === $vendorId;
                });

            return $selected !== null;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
