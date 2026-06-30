<?php

namespace App\Http\Controllers;

use App\Models\Jawatankuasa;
use App\Tender;
use App\User;
use Carbon\Carbon;
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
        $tenderUuid = $request->input('tender');
        if (!empty($tenderUuid)) {
            $tender = Tender::where('uuid', $tenderUuid)->first();
            if ($tender) {
                $tender->update(['tender_peringkat' => 2]);
            }
        }
        return $this->renderPelantikanView($request);
    }

    /**
     * Show the form for creating a new 1 peringkat resource.
     */
    public function create1Peringkat(Request $request)
    {
        $tenderUuid = $request->input('tender');
        if (!empty($tenderUuid)) {
            $tender = Tender::where('uuid', $tenderUuid)->first();
            if ($tender) {
                $tender->update(['tender_peringkat' => 1]);
            }
        }
        return $this->renderPelantikanView1Peringkat($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenderUuid = $request->input('tender_uuid');
        $tender = null;
        if (!empty($tenderUuid)) {
            $tender = Tender::where('uuid', $tenderUuid)->first();
        }

        $supportedJenis = $this->getSupportedJenis($tender);

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

    public function laporan(Request $request)
    {
        $tenderUuid = $request->input('tender');

        if (empty($tenderUuid)) {
            abort(404, 'Tender/Sebut Harga tidak ditemui.');
        }

        $tender = Tender::with('tenderer')->where('uuid', $tenderUuid)->first();
        if (!$tender) {
            abort(404, 'Tender/Sebut Harga tidak ditemui.');
        }

        $committeeDrafts = Jawatankuasa::with('user')
            ->where('tender_id', $tender->id)
            ->orderBy('id')
            ->get()
            ->groupBy('jenis_jawatankuasa')
            ->map(function ($rows) {
                $firstRow = $rows->first();

                return [
                    'catatan' => optional($firstRow)->catatan,
                    'rows' => $rows
                        ->filter(fn($row) => !empty($row->user_id) && !empty($row->user))
                        ->map(fn($row) => [
                            'ic_number' => $row->user->ic_number ?? '',
                            'name' => $row->user->name,
                            'email' => $row->user->email,
                            'jawatan' => $row->user->jawatan ?? '-',
                            'gred' => $row->user->gred ?? '-',
                            'p_p' => (string) $row->p_p,
                            'peranan' => (string) $row->peranan,
                        ])
                        ->values()
                        ->toArray(),
                ];
            })
            ->toArray();

        return view('tenders.laporan_jawatankuasa', compact('tender', 'committeeDrafts'));
    }

    public function hantarPemakluman(Request $request)
    {
        set_time_limit(300);

        $validated = $request->validate([
            'tender_uuid' => ['required', 'string', 'exists:tenders,uuid'],
        ]);

        $tender = Tender::with('tenderer')->where('uuid', $validated['tender_uuid'])->firstOrFail();

        $jenisLabels = [
            'spec' => 'Jawatankuasa Spesifikasi',
            'open' => 'Jawatankuasa Pembuka',
            'tech' => 'Jawatankuasa Penilaian Teknikal',
            'fin'  => 'Jawatankuasa Penilaian Kewangan',
            'eval' => 'Jawatankuasa Penilaian Sebut Harga/Tender',
        ];

        $perananLabels = [
            '1' => 'Pengerusi',
            '2' => 'Setiausaha',
            '3' => 'Ahli',
        ];

        $members = Jawatankuasa::with('user')
            ->where('tender_id', $tender->id)
            ->whereNotNull('user_id')
            ->get();

        if ($members->isEmpty()) {
            return response()->json([
                'message' => 'Tiada ahli jawatankuasa untuk dihantar pemakluman.',
            ], 422);
        }

        $requiredJenis = ['spec', 'open', 'tech', 'fin'];
        if ($tender->tender_peringkat == 1) {
            $requiredJenis = ['spec', 'open', 'eval'];
        }
        $requiredPeranan = ['1', '2', '3']; // Pengerusi, Setiausaha, Ahli

        $grouped = $members->groupBy('jenis_jawatankuasa');

        foreach ($requiredJenis as $jenis) {
            $tabMembers = $grouped->get($jenis, collect());
            $existingPeranan = $tabMembers->pluck('peranan')->map(fn($p) => (string) $p)->unique()->values()->toArray();

            foreach ($requiredPeranan as $peranan) {
                if (!in_array($peranan, $existingPeranan)) {
                    return response()->json([
                        'message' => 'Jawatan Kuasa Tidak Mencukupi',
                    ], 422);
                }
            }
        }

        $emailCount = 0;
        $useQueue = config('mail.use_queue', false);

        foreach ($members as $member) {
            if (empty($member->user) || empty($member->user->email)) {
                continue;
            }

            $jenisLabel = $jenisLabels[$member->jenis_jawatankuasa] ?? $member->jenis_jawatankuasa;
            $perananLabel = $perananLabels[(string) $member->peranan] ?? 'Ahli';

            $to = trim($member->user->email);
            $subject = 'Pemakluman Pelantikan ' . $jenisLabel . ' - ' . ($tender->ref_number ?? '');
            $viewParams = [
                'tender' => $tender,
                'emailUser' => $member->user,
                'jenisLabel' => $jenisLabel,
                'perananLabel' => $perananLabel,
                'tenderRefNumber' => $tender->ref_number ?? '-',
                'tenderPtj' => optional($tender->tenderer)->name ?? '-',
            ];

            if ($useQueue) {
                $content = view('emails.pemakluman_jawatankuasa', $viewParams)->render();
                $available_config = $this->getAvailableEmailConfig();

                if (count($available_config) > 0) {
                    $config = $this->setEmailConfig($available_config);
                    $payload = [
                        'from' => $config['mail_username'],
                        'alias' => $config['mail_alias'],
                        'to' => $to,
                        'subject' => $subject,
                    ];

                    $mailQueueRepo = new \App\Repositories\MailQueueRepository();
                    $new_queue = $mailQueueRepo->createMailQueue([
                        'smtp_mail_id' => $available_config['id'],
                        'content' => $content,
                        'config' => json_encode($config),
                        'payload' => json_encode($payload),
                        'status' => 'N',
                    ]);

                    $unique_id = $this->encryptString($new_queue->id);
                    dispatch(new \App\Jobs\SendEmailJob($unique_id))->afterResponse();
                }
            } else {
                ob_start();
                $this->sendMail('html', $to, $subject, '', 'emails.pemakluman_jawatankuasa', $viewParams);
                ob_end_clean();
            }

            $emailCount++;
        }

        Jawatankuasa::where('tender_id', $tender->id)
            ->whereNotNull('user_id')
            ->update(['dihantar_pemakluman_pada' => Carbon::now()]);

        Log::debug('hantarPemakluman completed', [
            'email_count' => $emailCount,
            'use_queue' => $useQueue,
        ]);

        return response()->json([
            'message' => "Pemakluman berjaya dihantar kepada {$emailCount} ahli jawatankuasa.",
            'email_count' => $emailCount,
        ]);
    }

    public function storeProfilPetender(Request $request)
    {
        $data = $request->all();

        dd($data);
    }

    public function simpanSenaraiKewanganKerja(Request $request)
    {
        $data = $request->all();

        dd($data);
    }

    public function simpanSenaraiKewanganBekalan(Request $request)
    {
        $data = $request->all();

        dd($data);
    }

    public function simpanSenaraiTeknikal(Request $request)
    {
        $data = $request->all();

        dd($data);
    }

    public function storePenyataKewangan(Request $request)
    {
        $data = $request->all();

        dd($data);
    }

    public function storeBonSaham(Request $request)
    {
        $data = $request->all();

        dd($data);
    }

    public function storePrestasiKerjaSemasa(Request $request)
    {
        $data = $request->all();

        dd($data);
    }

    public function spesifikasiKewanganBekalan(Request $request, ?string $spesifikasiUuid = null)
    {
        $tender = null;

        // Dummy data — will be replaced with real DB queries later
        $anggaranJabatan = 150000.00;

        $items = [
            [
                'nama'      => 'Air Mineral 500ml',
                'kuantiti'  => 1000,
                'uom'       => 'Lot',
                'specs'     => [
                    'Jenama tempatan yang diiktiraf KKM',
                    'Pembungkusan shrink wrap 24 botol per karton',
                ],
            ],
            [
                'nama'      => 'Air Mineral 1.5L',
                'kuantiti'  => 500,
                'uom'       => 'Unit',
                'specs'     => [
                    'Jenama tempatan yang diiktiraf KKM',
                    'Tarikh luput minima 6 bulan dari tarikh penghantaran',
                ],
            ],
            [
                'nama'      => 'Air Mineral 5 Gallon',
                'kuantiti'  => 100,
                'uom'       => 'Hari',
                'specs'     => [
                    'Dispenser compatible standard fitting',
                ],
            ],
        ];

        return view('newModule.jawatankuasaSpesifikasi.form_spesifikasi_kewangan_bekalan', compact('items', 'anggaranJabatan', 'tender'));
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
        if (!empty($tenderUuid)) {
            $tender = Tender::with('tenderer')->where('uuid', $tenderUuid)->first();
        }

        $committeeDrafts = [];
        $supportedDraftJenis = $this->getSupportedJenis($tender);
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

    private function renderPelantikanView1Peringkat(Request $request)
    {
        $tenderUuid = $request->input('tender');
        $tender = null;
        if (!empty($tenderUuid)) {
            $tender = Tender::with('tenderer')->where('uuid', $tenderUuid)->first();
        }

        $committeeDrafts = [];
        $supportedDraftJenis = $this->getSupportedJenis($tender);
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

        return view('tenders.pelantikan_jawatankuasa_1_peringkat', compact('tender', 'committeeDrafts', 'supportedDraftJenis', 'icUsers'));
    }

    private function getSupportedJenis(?Tender $tender = null): array
    {
        if ($tender) {
            if ((int) $tender->tender_peringkat === 1) {
                return ['spec', 'open', 'eval'];
            }
            return ['spec', 'open', 'tech', 'fin'];
        }
        return ['spec', 'open', 'tech', 'fin', 'eval'];
    }
}
