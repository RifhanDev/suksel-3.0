<?php

namespace App\Http\Controllers;

use App\Services\StosBackendClient;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LantikanTerusController extends Controller
{
    public function __construct(private StosBackendClient $stos)
    {
        // Keep staff menus gated; vendor sebut-harga view/submit must stay open
        // so Syarikat can use the Lantikan Terus BQ UI.
        $this->menuMiddleware('DirectAppointment:list', [
            'except' => ['sebutHargaShow', 'submitOffer', 'sebutHargaIndex'],
        ]);
    }

    public function index()
    {
        $projects = $this->fetchProjects();

        return view('newModule.lantikanTerus.cipta_projek_list', compact('projects'));
    }

    public function create()
    {
        $project = null;
        $kategoriPerolehan = \App\Models\Ref\RefKategoriJenisPerolehan::where('active', true)->get();

        return view('newModule.lantikanTerus.cipta_projek', compact('project', 'kategoriPerolehan'));
    }

    public function edit($id)
    {
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
        return $this->persist($request);
    }

    public function update(Request $request, $id)
    {
        return $this->persist($request, (int) $id);
    }

    public function sebutHargaIndex()
    {
        $projects = $this->fetchProjects(['published_only' => 1]);

        return view('newModule.lantikanTerus.sebut_harga_list', compact('projects'));
    }

    public function sebutHargaShow($id)
    {
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

        $vendorId = (int) auth()->user()->vendor->id;
        $payload = [
            'vendor_id' => $vendorId,
            'harga_tawaran' => str_replace(',', '', (string) $request->input('harga_tawaran', 0)),
        ];

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
        $projects = $this->fetchProjects(['status_process_id' => 5]);

        return view('newModule.lantikanTerus.cut_off_list', compact('projects'));
    }

    public function cutOffShow($id)
    {
        $response = $this->stos->getLantikanTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
        $p = $project;

        $offersResponse = $this->stos->getLantikanTerusOffers((int) $id);
        $suppliers = collect($offersResponse->json('data') ?? [])->map(function ($offer) {
            $offer = (array) $offer;

            return (object) [
                'id' => $offer['id'] ?? null,
                'name' => 'Vendor #' . ($offer['vendor_id'] ?? '-'),
                'harga_tawaran' => $offer['harga_tawaran'] ?? 0,
                'bq_filename' => $offer['bq_original_name'] ?? 'Dokumen BQ.pdf',
            ];
        });

        return view('newModule.lantikanTerus.cut_off', compact('project', 'suppliers', 'p'));
    }

    public function storeCutoff(Request $request, $id)
    {
        try {
            $payload = $request->all();
            $payload['uploaded_by'] = auth()->id();
            $response = $this->stos->cutoffLantikanTerus((int) $id, $payload);

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
        $projects = $this->fetchProjects(['status_process_id' => 31]);

        return view('newModule.lantikanTerus.pemilihan_syarikat_list', compact('projects'));
    }

    public function pemilihanShow($id)
    {
        $response = $this->stos->getLantikanTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
        $p = $project;

        $docs = collect($json['documents'] ?? []);
        $documents = (object) [
            'jpict' => optional($docs->firstWhere('doc_type', 'jpict'))['original_name'] ?? null,
            'minit_bebas' => optional($docs->firstWhere('doc_type', 'minit_bebas'))['original_name'] ?? null,
        ];

        $offersResponse = $this->stos->getLantikanTerusOffers((int) $id);
        $suppliers = collect($offersResponse->json('data') ?? [])
            ->where('shortlisted', true)
            ->values()
            ->map(function ($offer) {
                $offer = (array) $offer;

                return (object) [
                    'id' => $offer['id'] ?? null,
                    'name' => 'Vendor #' . ($offer['vendor_id'] ?? '-'),
                    'harga_tawaran' => $offer['harga_tawaran'] ?? 0,
                    'bq_filename' => $offer['bq_original_name'] ?? 'Dokumen BQ.pdf',
                ];
            });

        return view('newModule.lantikanTerus.pemilihan_syarikat', compact('project', 'suppliers', 'documents', 'p'));
    }

    public function storePemilihan(Request $request, $id)
    {
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
        $projects = $this->fetchProjects(['status_process_id' => 32]);

        return view('newModule.lantikanTerus.keputusan_syarikat_list', compact('projects'));
    }

    public function keputusanShow($id)
    {
        $response = $this->stos->getLantikanTerus((int) $id);
        abort_unless($response->successful(), 404);

        $json = $response->json();
        $project = $this->mapProject($json['data'] ?? [], $json['documents'] ?? []);
        $p = $project;

        $offersResponse = $this->stos->getLantikanTerusOffers((int) $id);
        $selected = collect($offersResponse->json('data') ?? [])->firstWhere('selected', true);
        $selected = $selected ? (array) $selected : null;

        $decision = $selected ? (object) [
            'company' => 'Vendor #' . ($selected['vendor_id'] ?? '-'),
            'harga_sst' => $selected['harga_tawaran'] ?? 0,
            'status' => $selected['decision'] ?? 'pending',
        ] : null;

        return view('newModule.lantikanTerus.keputusan_syarikat', compact('project', 'decision', 'p'));
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

        $payload = $this->buildPayload($request);
        $action = $request->input('action', 'draft');
        $payload['action'] = $action;

        try {
            if ($id) {
                $response = $this->stos->updateLantikanTerus($id, $payload);
            } else {
                $response = $this->stos->createLantikanTerus($payload);
            }

            if ($response->successful()) {
                $message = $action === 'publish'
                    ? 'Projek berjaya diterbitkan.'
                    : 'Projek berjaya disimpan sebagai draf.';

                return redirect()->route('lantikan.index')->with('success', $message);
            }

            Log::error('Lantikan Terus persist API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect()->back()->withInput()->with('error', $response->json('message') ?? 'Gagal menyimpan projek');
        } catch (\Throwable $e) {
            Log::error('Lantikan Terus persist failed', ['error' => $e->getMessage()]);

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
        $bqFilename = is_array($bqDoc)
            ? ($bqDoc['display_name'] ?? $bqDoc['original_name'] ?? 'Dokumen BQ.pdf')
            : 'Dokumen BQ.pdf';

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
            'documents' => $documents,
            'mof' => [],
            'cidb' => [],
        ];
    }
}
