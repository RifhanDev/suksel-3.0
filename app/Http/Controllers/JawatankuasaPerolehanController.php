<?php

namespace App\Http\Controllers;

use App\Models\JawatankuasaPerolehanKertasKeputusan;
use App\Models\JawatankuasaPerolehanMeeting;
use App\Models\PerakuanJabatanKertasTaklimatItem;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class JawatankuasaPerolehanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenders = Tender::query()
            ->where('status_process_id', 3)
            ->orderByDesc('id')
            ->get([
                'id',
                'uuid',
                'no_tender',
                'ref_number',
                'name',
                'submission_datetime',
                'status_process_id',
            ])
            ->map(function ($tender) {
                $submissionDate = null;
                $tempohSahLaku = '-';

                if (!empty($tender->submission_datetime)) {
                    $submissionDate = Carbon::parse($tender->submission_datetime);
                    $tempohSahLaku = '90 Hari (Sehingga ' . $submissionDate->copy()->addDays(90)->format('d/m/Y') . ')';
                }

                return [
                    'id' => $tender->id,
                    'uuid' => $tender->uuid,
                    'no_tender' => $tender->no_tender ?: $tender->ref_number ?: '-',
                    'tajuk' => $tender->name ?: '-',
                    'tarikh_serahan' => $submissionDate ? $submissionDate->format('d/m/Y') : '-',
                    'tempoh_sah_laku' => $tempohSahLaku,
                    'status_label' => ((int) $tender->status_process_id === 3) ? 'Dalam Proses' : 'Selesai',
                ];
            })
            ->values();

        return view('newModule.jawatankuasaPerolehan.index', compact('tenders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form()
    {
        $tender = $this->resolveTender(request('tender'));
        $meetings = collect();
        $taklimatAttachments = collect();
        $kertasKeputusan = null;

        if ($tender) {
            $meetings = JawatankuasaPerolehanMeeting::query()
                ->where('tender_id', $tender->id)
                ->orderBy('id')
                ->get()
                ->map(function ($meeting) {
                    return [
                        'id' => $meeting->id,
                        'bil_mesyuarat' => $meeting->bil_mesyuarat ?? '',
                        'tarikh_mesyuarat' => optional($meeting->tarikh_mesyuarat)->format('Y-m-d'),
                        'tajuk_agenda' => $meeting->tajuk_agenda ?? '',
                        'tempat' => $meeting->tempat ?? '',
                        'no_kod_kertas' => $meeting->no_kod_kertas ?? '',
                        'status' => $meeting->status ?? 'Belum Selesai',
                        'catatan' => $meeting->catatan ?? '',
                    ];
                })
                ->values();

            $taklimatAttachments = PerakuanJabatanKertasTaklimatItem::query()
                ->whereHas('header', function ($query) use ($tender) {
                    $query->where('tender_id', $tender->id);
                })
                ->with('files')
                ->orderBy('sort_order')
                ->get()
                ->flatMap(function ($item) {
                    return $item->files->map(function ($file) use ($item) {
                        return [
                            'kandungan' => $item->kandungan,
                            'file_name' => $file->file_original_name,
                            'download_url' => route('perakuanjabatan.kertasTaklimat.download', $file),
                        ];
                    });
                })
                ->values();

            $kertasKeputusan = JawatankuasaPerolehanKertasKeputusan::query()
                ->where('tender_id', $tender->id)
                ->first();
        }

        return view('newModule.jawatankuasaPerolehan.form', compact('tender', 'meetings', 'taklimatAttachments', 'kertasKeputusan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('newModule.jawatankuasaPerolehan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));

        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.bil_mesyuarat' => ['required', 'string', 'max:100'],
            'rows.*.tarikh_mesyuarat' => ['required', 'date'],
            'rows.*.tajuk_agenda' => ['required', 'string', 'max:255'],
            'rows.*.tempat' => ['required', 'string', 'max:255'],
            'rows.*.no_kod_kertas' => ['required', 'string', 'max:100'],
            'rows.*.status' => ['required', 'in:Belum Selesai,Selesai'],
            'rows.*.catatan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($tender, $payload) {
            JawatankuasaPerolehanMeeting::query()
                ->where('tender_id', $tender->id)
                ->delete();

            foreach ($payload['rows'] as $row) {
                JawatankuasaPerolehanMeeting::query()->create([
                    'tender_id' => $tender->id,
                    'bil_mesyuarat' => trim($row['bil_mesyuarat']),
                    'tarikh_mesyuarat' => $row['tarikh_mesyuarat'],
                    'tajuk_agenda' => trim($row['tajuk_agenda']),
                    'tempat' => trim($row['tempat']),
                    'no_kod_kertas' => trim($row['no_kod_kertas']),
                    'status' => $row['status'],
                    'catatan' => isset($row['catatan']) ? trim((string) $row['catatan']) : null,
                    'submitted_at' => null,
                ]);
            }
        });

        return response()->json([
            'message' => 'Perincian mesyuarat berjaya disimpan.',
        ]);
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));

        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.bil_mesyuarat' => ['required', 'string', 'max:100'],
            'rows.*.tarikh_mesyuarat' => ['required', 'date'],
            'rows.*.tajuk_agenda' => ['required', 'string', 'max:255'],
            'rows.*.tempat' => ['required', 'string', 'max:255'],
            'rows.*.no_kod_kertas' => ['required', 'string', 'max:100'],
            'rows.*.status' => ['required', 'in:Belum Selesai,Selesai'],
            'rows.*.catatan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($tender, $payload) {
            JawatankuasaPerolehanMeeting::query()
                ->where('tender_id', $tender->id)
                ->delete();

            $now = now();
            foreach ($payload['rows'] as $row) {
                JawatankuasaPerolehanMeeting::query()->create([
                    'tender_id' => $tender->id,
                    'bil_mesyuarat' => trim($row['bil_mesyuarat']),
                    'tarikh_mesyuarat' => $row['tarikh_mesyuarat'],
                    'tajuk_agenda' => trim($row['tajuk_agenda']),
                    'tempat' => trim($row['tempat']),
                    'no_kod_kertas' => trim($row['no_kod_kertas']),
                    'status' => $row['status'],
                    'catatan' => isset($row['catatan']) ? trim((string) $row['catatan']) : null,
                    'submitted_at' => $now,
                ]);
            }
        });

        return response()->json([
            'message' => 'Perincian mesyuarat berjaya dihantar.',
        ]);
    }

    public function simpanKertasKeputusan(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'dengan_syarat' => ['nullable', 'in:0,1'],
            'syarat_nyatakan' => ['nullable', 'string', 'max:65535'],
            'pengesyoran_catatan' => ['nullable', 'string', 'max:65535'],
            'justifikasi_pemilihan_pembekal' => ['nullable', 'string', 'max:255'],
            'keputusan' => ['nullable', 'in:Lulus,Tawaran Semula,Batal,Tangguh'],
            'catatan' => ['nullable', 'string', 'max:65535'],
            'lampiran' => ['nullable', 'file', 'max:10240'],
            'buang_lampiran' => ['nullable', 'in:0,1'],
        ]);

        $record = JawatankuasaPerolehanKertasKeputusan::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['submitted_at' => null]
        );

        DB::transaction(function () use ($request, $payload, $record, $tender) {
            $this->applyKertasKeputusanData($request, $payload, $record, $tender, false);
            $record->save();
        });

        return response()->json(['message' => 'Kertas keputusan berjaya disimpan.']);
    }

    public function hantarKertasKeputusan(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'dengan_syarat' => ['required', 'in:0,1'],
            'syarat_nyatakan' => ['nullable', 'string', 'max:65535'],
            'pengesyoran_catatan' => ['required', 'string', 'max:65535'],
            'justifikasi_pemilihan_pembekal' => ['required', 'string', 'max:255'],
            'keputusan' => ['required', 'in:Lulus,Tawaran Semula,Batal,Tangguh'],
            'catatan' => ['nullable', 'string', 'max:65535'],
            'lampiran' => ['nullable', 'file', 'max:10240'],
            'buang_lampiran' => ['nullable', 'in:0,1'],
        ], [
            'pengesyoran_catatan.required' => 'Ruangan Pengesyoran adalah wajib.',
            'justifikasi_pemilihan_pembekal.required' => 'Justifikasi Pemilihan Pembekal adalah wajib.',
            'keputusan.required' => 'Sila pilih Keputusan.',
        ]);

        if (($payload['dengan_syarat'] ?? null) === '1' && empty(trim((string) ($payload['syarat_nyatakan'] ?? '')))) {
            return response()->json([
                'message' => 'Sila nyatakan syarat apabila memilih "Dengan Syarat: Ya".',
            ], 422);
        }

        $record = JawatankuasaPerolehanKertasKeputusan::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['submitted_at' => null]
        );

        DB::transaction(function () use ($request, $payload, $record, $tender) {
            $this->applyKertasKeputusanData($request, $payload, $record, $tender, true);
            $record->submitted_at = now();
            $record->save();

            // Use query update to avoid Tender model event side-effects.
            Tender::query()
                ->where('id', $tender->id)
                ->update(['status_process_id' => 4]);
        });

        return response()->json(['message' => 'Kertas keputusan berjaya dihantar.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function resolveTender($identifier): ?Tender
    {
        if (empty($identifier)) {
            return null;
        }

        if (is_numeric($identifier)) {
            return Tender::query()->where('id', (int) $identifier)->first();
        }

        return Tender::query()->where('uuid', $identifier)->first();
    }

    private function applyKertasKeputusanData(
        Request $request,
        array $payload,
        JawatankuasaPerolehanKertasKeputusan $record,
        Tender $tender,
        bool $forSubmit
    ): void {
        $record->dengan_syarat = array_key_exists('dengan_syarat', $payload)
            ? ((string) $payload['dengan_syarat'] === '1')
            : null;
        $record->syarat_nyatakan = $this->nullableTrim($payload['syarat_nyatakan'] ?? null);
        $record->pengesyoran_catatan = $this->nullableTrim($payload['pengesyoran_catatan'] ?? null);
        $record->justifikasi_pemilihan_pembekal = $this->nullableTrim($payload['justifikasi_pemilihan_pembekal'] ?? null);
        $record->keputusan = $payload['keputusan'] ?? null;
        $record->catatan = $this->nullableTrim($payload['catatan'] ?? null);

        $shouldRemoveAttachment = ($payload['buang_lampiran'] ?? '0') === '1';
        if ($shouldRemoveAttachment) {
            $this->deleteStoredFile($record->lampiran_file_path);
            $record->lampiran_file_nama = null;
            $record->lampiran_file_path = null;
        }

        $upload = $request->file('lampiran');
        if ($upload instanceof UploadedFile && $upload->isValid()) {
            $this->deleteStoredFile($record->lampiran_file_path);
            $stored = $this->storeKertasKeputusanLampiran($upload, $tender->id);
            $record->lampiran_file_nama = $stored['original_name'];
            $record->lampiran_file_path = $stored['relative_path'];
        }
    }

    private function storeKertasKeputusanLampiran(UploadedFile $file, int $tenderId): array
    {
        $relativeDir = 'uploads/jawatankuasa_perolehan/' . $tenderId . '/kertas_keputusan';
        $absoluteDir = public_path($relativeDir);
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755, true);
        }

        $safeOriginal = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = date('YmdHis') . '_' . $safeOriginal;
        $file->move($absoluteDir, $fileName);

        return [
            'original_name' => $file->getClientOriginalName(),
            'relative_path' => $relativeDir . '/' . $fileName,
        ];
    }

    private function deleteStoredFile(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $fullPath = public_path($relativePath);
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function nullableTrim($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }
}
