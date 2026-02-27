<?php

namespace App\Http\Controllers;

use App\Models\Jawatankuasa;
use App\Tender;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class JawatankuasaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->renderPelantikanView($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return $this->renderPelantikanView($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $supportedJenis = $this->getSupportedJenis();

        if ($request->has('tabs')) {
            return $this->storeAllTabsDraft($request, $supportedJenis);
        }

        $validated = $request->validate([
            'tender_uuid' => ['required', 'string', 'exists:tenders,uuid'],
            'jenis' => ['required', Rule::in($supportedJenis)],
            'catatan' => ['nullable', 'string'],
            'rows' => ['nullable', 'array'],
            'rows.*.user_id' => ['nullable', 'integer', 'exists:users,id'],
            'rows.*.p_p' => ['nullable', Rule::in(['0', '1'])],
            'rows.*.peranan' => ['nullable', Rule::in(['1', '2', '3'])],
            'dokumen_sokongan' => ['nullable', 'file', 'max:10240'],
        ], [
            'jenis.in' => 'Jenis jawatankuasa ini belum disokong untuk simpan draf.',
        ]);

        $tender = Tender::where('uuid', $validated['tender_uuid'])->firstOrFail();
        $jenis = $validated['jenis'];
        $catatan = $this->normalizeCatatan($validated['catatan'] ?? null);
        $rows = $this->normalizeRows($validated['rows'] ?? []);

        try {
            DB::transaction(function () use ($request, $tender, $jenis, $catatan, $rows) {
                $this->persistJenisDraft(
                    $request,
                    $tender,
                    $jenis,
                    $catatan,
                    $rows,
                    'dokumen_sokongan'
                );
            });
        } catch (\Throwable $e) {
            Log::error('Gagal simpan draf jawatankuasa', [
                'tender_uuid' => $validated['tender_uuid'],
                'jenis' => $jenis,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Simpan draf gagal. Sila cuba semula.',
            ], 500);
        }

        return response()->json([
            'message' => 'Draf jawatankuasa berjaya disimpan.',
            'saved_rows' => $rows->count(),
        ]);
    }

    private function storeAllTabsDraft(Request $request, array $supportedJenis)
    {
        $rules = [
            'tender_uuid' => ['required', 'string', 'exists:tenders,uuid'],
            'tabs' => ['required', 'array'],
        ];

        foreach ($supportedJenis as $jenis) {
            $rules["tabs.$jenis"] = ['nullable', 'array'];
            $rules["tabs.$jenis.catatan"] = ['nullable', 'string'];
            $rules["tabs.$jenis.rows"] = ['nullable', 'array'];
            $rules["tabs.$jenis.rows.*.user_id"] = ['nullable', 'integer', 'exists:users,id'];
            $rules["tabs.$jenis.rows.*.p_p"] = ['nullable', Rule::in(['0', '1'])];
            $rules["tabs.$jenis.rows.*.peranan"] = ['nullable', Rule::in(['1', '2', '3'])];
            $rules["tabs.$jenis.dokumen_sokongan"] = ['nullable', 'file', 'max:10240'];
        }

        $validated = $request->validate($rules);
        $tender = Tender::where('uuid', $validated['tender_uuid'])->firstOrFail();
        $tabsInput = $validated['tabs'] ?? [];

        try {
            DB::transaction(function () use ($request, $tender, $supportedJenis, $tabsInput) {
                foreach ($supportedJenis as $jenis) {
                    $hasTabInput = array_key_exists($jenis, $tabsInput);
                    $hasTabFile = $request->hasFile("tabs.$jenis.dokumen_sokongan");

                    if (!$hasTabInput && !$hasTabFile) {
                        continue;
                    }

                    $tabData = $hasTabInput ? ($tabsInput[$jenis] ?? []) : [];
                    $catatan = $this->normalizeCatatan($tabData['catatan'] ?? null);
                    $rows = $this->normalizeRows($tabData['rows'] ?? []);

                    $this->persistJenisDraft(
                        $request,
                        $tender,
                        $jenis,
                        $catatan,
                        $rows,
                        "tabs.$jenis.dokumen_sokongan"
                    );
                }
            });
        } catch (\Throwable $e) {
            Log::error('Gagal simpan draf jawatankuasa (semua tab)', [
                'tender_uuid' => $validated['tender_uuid'],
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Simpan draf semua tab gagal. Sila cuba semula.',
            ], 500);
        }

        return response()->json([
            'message' => 'Draf jawatankuasa berjaya disimpan untuk semua tab.',
        ]);
    }

    private function normalizeCatatan($catatan): ?string
    {
        if (!is_string($catatan)) {
            return null;
        }

        $catatan = trim($catatan);
        return $catatan === '' ? null : $catatan;
    }

    private function normalizeRows($rows)
    {
        return collect($rows ?? [])
            ->map(function ($row) {
                return [
                    'user_id' => isset($row['user_id']) && $row['user_id'] !== '' ? (int) $row['user_id'] : null,
                    'p_p' => isset($row['p_p']) ? (string) $row['p_p'] : '1',
                    'peranan' => isset($row['peranan']) ? (string) $row['peranan'] : '3',
                ];
            })
            ->filter(function ($row) {
                return !empty($row['user_id']);
            })
            ->values();
    }

    private function persistJenisDraft(
        Request $request,
        Tender $tender,
        string $jenis,
        ?string $catatan,
        $rows,
        string $fileKey
    ): void {
        $existing = Jawatankuasa::where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', $jenis)
            ->get();

        $docName = optional($existing->first())->dokumen_sokongan_nama;
        $docPath = optional($existing->first())->dokumen_sokongan_path;

        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
            $fileName = date('YmdHis') . '_' . $safeOriginalName;
            $relativeDir = 'uploads/jawatankuasa/' . $tender->uuid . '/' . $jenis;
            $absoluteDir = public_path($relativeDir);

            if (!is_dir($absoluteDir)) {
                mkdir($absoluteDir, 0755, true);
            }

            $file->move($absoluteDir, $fileName);
            $docName = $file->getClientOriginalName();
            $docPath = $relativeDir . '/' . $fileName;
        }

        Jawatankuasa::where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', $jenis)
            ->delete();

        if ($rows->isEmpty()) {
            $shouldKeepMeta = !empty($catatan) || !empty($docPath);

            if ($shouldKeepMeta) {
                Jawatankuasa::create([
                    'tender_id' => $tender->id,
                    'jenis_jawatankuasa' => $jenis,
                    'p_p' => '1',
                    'peranan' => '3',
                    'user_id' => null,
                    'catatan' => $catatan,
                    'dokumen_sokongan_nama' => $docName,
                    'dokumen_sokongan_path' => $docPath,
                ]);
            }

            return;
        }

        foreach ($rows as $row) {
            Jawatankuasa::create([
                'tender_id' => $tender->id,
                'jenis_jawatankuasa' => $jenis,
                'p_p' => $row['p_p'],
                'peranan' => $row['peranan'],
                'user_id' => $row['user_id'],
                'catatan' => $catatan,
                'dokumen_sokongan_nama' => $docName,
                'dokumen_sokongan_path' => $docPath,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Jawatankuasa $jawatankuasa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jawatankuasa $jawatankuasa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jawatankuasa $jawatankuasa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jawatankuasa $jawatankuasa)
    {
        //
    }

    private function renderPelantikanView(Request $request)
    {
        $tenderUuid = $request->input('tender');
        $tender = null;
        $committeeDrafts = [];
        $supportedDraftJenis = $this->getSupportedJenis();
        $icUsers = User::query()
            ->whereNotNull('ic_number')
            ->where('ic_number', '!=', '')
            ->orderBy('ic_number')
            ->get(['id', 'ic_number', 'name', 'email', 'jawatan', 'gred'])
            ->map(function ($user) {
                return [
                    'id' => (int) $user->id,
                    'ic_number' => (string) $user->ic_number,
                    'name' => $user->name,
                    'email' => $user->email,
                    'jawatan' => $user->jawatan ?? '-',
                    'gred' => $user->gred ?? '-',
                ];
            })
            ->values();

        if (!empty($tenderUuid)) {
            $tender = Tender::with('tenderer')->where('uuid', $tenderUuid)->first();
        }

        if ($tender) {
            $committeeDrafts = Jawatankuasa::with('user')
                ->where('tender_id', $tender->id)
                ->orderBy('id')
                ->get()
                ->groupBy('jenis_jawatankuasa')
                ->map(function ($rows) {
                    $firstRow = $rows->first();

                    return [
                        'catatan' => optional($firstRow)->catatan,
                        'dokumen_sokongan_nama' => optional($firstRow)->dokumen_sokongan_nama,
                        'dokumen_sokongan_path' => optional($firstRow)->dokumen_sokongan_path,
                        'rows' => $rows
                            ->filter(function ($row) {
                                return !empty($row->user_id) && !empty($row->user);
                            })
                            ->map(function ($row) {
                                return [
                                    'user_id' => (int) $row->user_id,
                                    'ic_number' => $row->user->ic_number ?? '',
                                    'name' => $row->user->name,
                                    'email' => $row->user->email,
                                    'jawatan' => $row->user->jawatan ?? '-',
                                    'gred' => $row->user->gred ?? '-',
                                    'p_p' => (string) $row->p_p,
                                    'peranan' => (string) $row->peranan,
                                ];
                            })
                            ->values(),
                    ];
                })
                ->toArray();
        }

        return view('tenders.pelantikan_jawatankuasa', compact('tender', 'committeeDrafts', 'supportedDraftJenis', 'icUsers'));
    }

    private function getSupportedJenis(): array
    {
        $fallback = ['spec', 'open', 'tech', 'fin'];

        try {
            $table = (new Jawatankuasa())->getTable();
            $column = DB::selectOne("SHOW COLUMNS FROM `{$table}` WHERE Field = 'jenis_jawatankuasa'");

            if (empty($column) || empty($column->Type)) {
                return $fallback;
            }

            if (!preg_match('/^enum\((.*)\)$/i', $column->Type, $matches)) {
                return $fallback;
            }

            $enumValues = str_getcsv($matches[1], ',', "'");
            $supported = array_values(array_intersect($enumValues, ['spec', 'open', 'tech', 'fin', 'harga']));

            return !empty($supported) ? $supported : $fallback;
        } catch (\Throwable $e) {
            Log::warning('Tidak dapat baca enum jenis_jawatankuasa', [
                'message' => $e->getMessage(),
            ]);

            return $fallback;
        }
    }
}
