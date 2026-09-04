<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\Tender;
use App\Models\TenderPrestasiKerja;
use App\Models\TenderPrestasiKerjaItem;
use App\Models\TenderPrestasiKerjaDokumen;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenderPrestasiKerjaController extends Controller
{
    use HandlesTenderFormAccess;

    public function index(string $tenderUuid)
    {
        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where(function ($q) use ($tenderUuid) {
                $q->where('tenders.uuid', $tenderUuid)
                  ->orWhere('tenders.id', $tenderUuid);
            })
            ->firstOrFail();

        $this->ensureTenderFormAccess($tender);

        $prestasi = TenderPrestasiKerja::with(['items', 'dokumens'])
            ->where($this->vendorFormRecordKeys($tender))
            ->first();

        return view('newModule.jawatankuasaSpesifikasi.form_prestasi_kerja_semasa_petender', array_merge(
            compact('tender', 'prestasi'),
            $this->formViewVars($tender)
        ));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $arrayFields = [
            'nama', 'no_kontrak', 'harga', 'wang_kos_prima', 'wang_peruntukan_semasa',
            'tarikh_tapak', 'tempoh', 'tarikh_siap', 'tarikh_penilaian', 'luputan',
            'kemajuan_sebenar', 'kemajuan_jadual',
        ];

        foreach ($arrayFields as $field) {
            if (! $request->has($field)) {
                continue;
            }

            $request->merge([
                $field => collect($request->input($field, []))
                    ->map(function ($value) use ($field) {
                        if ($value === '' || $value === null) {
                            return null;
                        }

                        if (in_array($field, ['harga', 'wang_kos_prima', 'wang_peruntukan_semasa'], true)) {
                            return str_replace(',', '', (string) $value);
                        }

                        return $value;
                    })
                    ->all(),
            ]);
        }

        $validated = $request->validate([
            'nama'                   => ['nullable', 'array'],
            'nama.*'                 => ['nullable', 'string', 'max:255'],
            'no_kontrak'             => ['nullable', 'array'],
            'no_kontrak.*'           => ['nullable', 'string', 'max:255'],
            'harga'                  => ['nullable', 'array'],
            'harga.*'                => ['nullable', 'numeric', 'min:0'],
            'wang_kos_prima'         => ['nullable', 'array'],
            'wang_kos_prima.*'       => ['nullable', 'numeric', 'min:0'],
            'wang_peruntukan_semasa' => ['nullable', 'array'],
            'wang_peruntukan_semasa.*' => ['nullable', 'numeric', 'min:0'],
            'tarikh_tapak'           => ['nullable', 'array'],
            'tarikh_tapak.*'         => ['nullable', 'string', 'max:100'],
            'tempoh'                 => ['nullable', 'array'],
            'tempoh.*'               => ['nullable', 'integer', 'min:0'],
            'tarikh_siap'            => ['nullable', 'array'],
            'tarikh_siap.*'          => ['nullable', 'string', 'max:100'],
            'tarikh_penilaian'       => ['nullable', 'array'],
            'tarikh_penilaian.*'     => ['nullable', 'string', 'max:100'],
            'luputan'                => ['nullable', 'array'],
            'luputan.*'              => ['nullable', 'integer', 'min:0'],
            'kemajuan_sebenar'       => ['nullable', 'array'],
            'kemajuan_sebenar.*'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'kemajuan_jadual'        => ['nullable', 'array'],
            'kemajuan_jadual.*'      => ['nullable', 'numeric', 'min:0', 'max:100'],
            'dokumen_prestasi'       => ['nullable', 'array'],
            'dokumen_prestasi.*'     => ['file', 'max:10240'],
        ]);

        $filledRows = collect($validated['nama'] ?? [])
            ->map(fn ($name, $index) => [
                'index' => $index,
                'nama' => trim((string) $name),
            ])
            ->filter(fn (array $row) => $row['nama'] !== '')
            ->values();

        if ($filledRows->isEmpty()) {
            $message = 'Sila isi sekurang-kurangnya satu baris prestasi kerja semasa.';

            if ($request->ajax() || $request->boolean('modal')) {
                return redirect()->back()->withInput()->with('error', $message);
            }

            return redirect()->back()->withInput()->with('error', $message);
        }

        try {
            DB::transaction(function () use ($validated, $tender, $request, $filledRows) {
                $keys = $this->vendorFormRecordKeys($tender);
                $existing = TenderPrestasiKerja::query()->where($keys)->first();

                $prestasi = TenderPrestasiKerja::updateOrCreate(
                    $keys,
                    [
                        'uuid'       => $existing?->uuid ?? (string) Str::uuid(),
                        'status'     => 'submitted',
                        'created_by' => $existing?->created_by ?? auth()->id(),
                        'updated_by' => auth()->id(),
                    ]
                );

                // Clear existing items
                $prestasi->items()->delete();

                // Save new items
                foreach ($filledRows as $sortOrder => $row) {
                    $index = $row['index'];
                    $itemAttrs = $this->prestasiItemAttributes($validated, $index, $row['nama']);

                    TenderPrestasiKerjaItem::create(array_merge($itemAttrs, [
                        'uuid'                     => (string) Str::uuid(),
                        'tender_prestasi_kerja_id' => $prestasi->id,
                        'harga'                    => $itemAttrs['harga'] ?? 0.00,
                        'sort_order'               => $sortOrder,
                    ]));
                }

                // Handle file uploads
                if ($request->hasFile('dokumen_prestasi')) {
                    foreach ($request->file('dokumen_prestasi') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $mimeType = $file->getClientMimeType();
                        $size = $file->getSize();

                        $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
                        $storedName = date('YmdHis') . '_' . $safeOriginalName;
                        $relativeDir = 'uploads/prestasi_kerja_semasa/' . $tender->uuid;
                        $absoluteDir = public_path($relativeDir);

                        if (!is_dir($absoluteDir)) {
                            mkdir($absoluteDir, 0755, true);
                        }

                        $file->move($absoluteDir, $storedName);

                        TenderPrestasiKerjaDokumen::create([
                            'uuid'                     => (string) Str::uuid(),
                            'tender_prestasi_kerja_id' => $prestasi->id,
                            'original_name'            => $originalName,
                            'stored_name'              => $storedName,
                            'path'                     => $relativeDir . '/' . $storedName,
                            'mime_type'                => $mimeType,
                            'size'                     => $size,
                        ]);
                    }
                }

                if ($this->isVendorFormMode()) {
                    $prestasi->load(['items', 'dokumens']);

                    $this->persistVendorFormPayload($tender, 'prestasi_kerja', [
                        'items' => $prestasi->items->map(fn ($item) => $this->prestasiItemPayloadFromModel($item))->values()->all(),
                        'dokumen' => $prestasi->dokumens->map(fn ($doc) => [
                            'uuid' => $doc->uuid,
                            'original_name' => $doc->original_name,
                            'path' => $doc->path,
                            'mime_type' => $doc->mime_type,
                            'size' => $doc->size,
                        ])->values()->all(),
                        'dokumen_count' => $prestasi->dokumens->count(),
                    ]);
                }
            });

            // Sync status to backend API
            $syncResponse = $this->api()->post(
                $this->url('kewangan-kerja/' . $tenderUuid . '/sync-status'),
                ['action_url' => '/prestasi-kerja-semasa-petender']
            );

            if (!$syncResponse->successful()) {
                Log::warning('TenderPrestasiKerjaController@store: failed to sync status to backend', [
                    'status' => $syncResponse->status(),
                    'body'   => $syncResponse->body(),
                ]);
            }

            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'prestasi_kerja', [
                    'text' => 'Prestasi kerja semasa disimpan',
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berjaya disimpan.',
                ]);
            }

            if ($this->isVendorFormMode()) {
                return $this->vendorFormRedirect($request, $tender, 'Prestasi Kerja Semasa Petender berjaya disimpan.');
            }

            $redirect = $request->input('return');
            if ($redirect) {
                return redirect($redirect)->with('success', 'Prestasi Kerja Semasa Petender berjaya disimpan.');
            }

            return redirect()->route('senaraiKewanganKerja', $tenderUuid)->with('success', 'Prestasi Kerja Semasa Petender berjaya disimpan.');
        } catch (\Throwable $e) {
            Log::error('TenderPrestasiKerjaController@store failed', [
                'tender_uuid' => $tenderUuid,
                'error'       => $e->getMessage(),
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ralat semasa menyimpan.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Ralat semasa menyimpan Prestasi Kerja Semasa Petender.');
        }
    }

    public function deleteFile(Request $request, string $fileUuid)
    {
        $this->ensureFormEditable();

        try {
            $dokumen = TenderPrestasiKerjaDokumen::where('uuid', $fileUuid)->firstOrFail();
            
            // Delete file from storage
            $absolutePath = public_path($dokumen->path);
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }

            $dokumen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berjaya dipadam.'
            ]);
        } catch (\Throwable $e) {
            Log::error('TenderPrestasiKerjaController@deleteFile failed', [
                'file_uuid' => $fileUuid,
                'error'     => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memadam dokumen.'
            ], 500);
        }
    }

    private function api()
    {
        return StosBackendClient::http();
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }

    /**
     * Normalised row attributes for DB insert. Empty optional fields are stored as null.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function prestasiItemAttributes(array $validated, int $index, string $nama): array
    {
        return [
            'nama'                   => $nama,
            'no_kontrak'             => $validated['no_kontrak'][$index] ?? null,
            'harga'                  => $validated['harga'][$index] ?? null,
            'wang_kos_prima'         => $validated['wang_kos_prima'][$index] ?? null,
            'wang_peruntukan_semasa' => $validated['wang_peruntukan_semasa'][$index] ?? null,
            'tarikh_tapak'           => $validated['tarikh_tapak'][$index] ?? null,
            'tempoh'                 => $validated['tempoh'][$index] ?? null,
            'tarikh_siap'            => $validated['tarikh_siap'][$index] ?? null,
            'tarikh_penilaian'       => $validated['tarikh_penilaian'][$index] ?? null,
            'luputan'                => $validated['luputan'][$index] ?? null,
            'kemajuan_sebenar'       => $validated['kemajuan_sebenar'][$index] ?? null,
            'kemajuan_jadual'        => $validated['kemajuan_jadual'][$index] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function prestasiItemPayloadFromModel(TenderPrestasiKerjaItem $item): array
    {
        return [
            'nama'                   => $item->nama,
            'no_kontrak'             => $item->no_kontrak,
            'harga'                  => $item->harga !== null ? (float) $item->harga : null,
            'wang_kos_prima'         => $item->wang_kos_prima !== null ? (float) $item->wang_kos_prima : null,
            'wang_peruntukan_semasa' => $item->wang_peruntukan_semasa !== null ? (float) $item->wang_peruntukan_semasa : null,
            'tarikh_tapak'           => $item->tarikh_tapak,
            'tempoh'                 => $item->tempoh,
            'tarikh_siap'            => $item->tarikh_siap,
            'tarikh_penilaian'       => $item->tarikh_penilaian,
            'luputan'                => $item->luputan,
            'kemajuan_sebenar'       => $item->kemajuan_sebenar !== null ? (float) $item->kemajuan_sebenar : null,
            'kemajuan_jadual'        => $item->kemajuan_jadual !== null ? (float) $item->kemajuan_jadual : null,
        ];
    }
}
