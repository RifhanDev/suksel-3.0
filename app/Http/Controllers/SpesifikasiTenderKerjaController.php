<?php

namespace App\Http\Controllers;

use App\Models\SpesifikasiKerjaFile;
use App\Models\SpesifikasiKerjaHeader;
use App\Models\SpesifikasiKerjaItem;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpesifikasiTenderKerjaController extends Controller
{

    /**
     * Display the Penyediaan Spesifikasi Tender page with existing data.
     */
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $header = SpesifikasiKerjaHeader::where('tender_id', $tender->id)
            ->with(['items.files', 'items.specs.files', 'files'])
            ->first();

        $checklistData = $header ? $this->serializeLocalSpesifikasi($header, $tender) : null;

        return view(
            'newModule.jawatankuasaSpesifikasi.form_penyediaan_spesifikasi_tender',
            compact('tender', 'checklistData')
        );
    }

    /** Save the local BQ draft without forwarding documents or state to STOS. */
    public function store(Request $request, string $tenderUuid)
    {
        return $this->saveLocalTemplate($tenderUuid, false);
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $response = $this->saveLocalTemplate($tenderUuid, true);
        $tender = \App\Tender::where('uuid', $tenderUuid)->firstOrFail();
        app(\App\Services\TenderProcessStatusService::class)->syncAfterChecklistSubmit($tender, false);

        return $response;
    }

    private function saveLocalTemplate(string $tenderUuid, bool $submit)
    {
        $this->ensureAccess();
        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();
        abort_unless((int) $tender->kategori_perolehan_id === 3, 404);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($tender, $submit) {
            $header = SpesifikasiKerjaHeader::where('tender_id', $tender->id)->lockForUpdate()->first();
            if (! $header) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'file' => 'Muat naik templat BQ sebelum menyimpan atau menghantar.',
                ]);
            }
            abort_if($header->status === 'submitted', 422, 'Templat yang telah dihantar tidak boleh diubah.');
            $template = $header->files()->whereNull('spesifikasi_kerja_item_id')
                ->whereIn('file_type', ['bq_template', 'bq'])->latest('id')->first();
            if (! $template || ! \App\Support\SukselStoredFile::exists((string) $template->path)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'file' => 'Templat BQ tidak dijumpai pada storan SUKSel. Sila muat naik dokumen sebelum meneruskan.',
                ]);
            }
            $header->status = $submit ? 'submitted' : 'draft';
            $header->updated_by = auth()->id();
            if ($submit) {
                $header->submitted_at = now();
                $header->submitted_by = auth()->id();
            }
            $header->save();

            return response()->json([
                'success' => true,
                'message' => $submit ? 'Spesifikasi tender berjaya dihantar.' : 'Draf berjaya disimpan.',
                'data' => $this->serializeLocalSpesifikasi($header->load(['files', 'items.files', 'items.specs.files']), $tender),
            ]);
        });
    }
    /**
     * Upload a per-item supporting document — stored directly on SUKSel 3.0 server storage.
     */
    public function uploadFile(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $upload = $request->file('file');
        if ($upload && ! $upload->isValid()) {
            $message = match ($upload->getError()) {
                UPLOAD_ERR_INI_SIZE => 'Saiz fail melebihi had muat naik pelayan (' . ini_get('upload_max_filesize') . '). Sila kecilkan fail atau hubungi pentadbir.',
                UPLOAD_ERR_FORM_SIZE => 'Saiz fail melebihi had muat naik borang.',
                UPLOAD_ERR_PARTIAL => 'Fail hanya dimuat naik sebahagian. Sila semak sambungan dan cuba lagi.',
                UPLOAD_ERR_NO_FILE => 'Tiada fail diterima. Sila pilih dokumen dan cuba lagi.',
                UPLOAD_ERR_NO_TMP_DIR => 'Pelayan tidak mempunyai folder sementara untuk muat naik. Sila hubungi pentadbir.',
                UPLOAD_ERR_CANT_WRITE => 'Pelayan gagal menyimpan fail muat naik. Sila hubungi pentadbir.',
                UPLOAD_ERR_EXTENSION => 'Muat naik dihentikan oleh konfigurasi pelayan. Sila hubungi pentadbir.',
                default => 'Muat naik tidak lengkap. Sila pilih semula dokumen dan cuba lagi.',
            };

            return response()->json(['success' => false, 'message' => $message, 'errors' => ['file' => [$message]]], 422);
        }

        $request->validate([
            'file' => \App\Support\AllowedAttachment::rules(maxKb: 25600),
            'file_type' => ['nullable', 'string', 'in:support,bq,bq_template'],
            'checklist_item_uuid' => ['nullable', 'string'],
            'spesifikasi_kerja_item_uuid' => ['nullable', 'string'],
        ], [
            'file.required' => 'Tiada fail diterima. Sila pilih dokumen untuk dimuat naik.',
            'file.file' => 'Dokumen tidak sah. Sila pilih fail yang boleh dibaca dan cuba lagi.',
            'file.max' => 'Saiz fail melebihi had 25 MB.',
            'file.mimes' => \App\Support\AllowedAttachment::MESSAGE,
            'file.uploaded' => 'Fail tidak dapat diterima oleh pelayan. Sila pilih semula dokumen atau hubungi pentadbir.',
        ]);

        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();
        abort_unless((int) $tender->kategori_perolehan_id === 3, 404);

        $header = SpesifikasiKerjaHeader::firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'uuid' => (string) Str::uuid(),
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]
        );

        $fileType = $request->input('file_type') ?: 'support';
        if ($fileType === 'bq_template' && $header->status === 'submitted') {
            return response()->json(['success' => false, 'message' => 'Templat yang telah dihantar tidak boleh diganti.'], 422);
        }

        $itemUuid = $request->input('checklist_item_uuid') ?: $request->input('spesifikasi_kerja_item_uuid');
        $item = null;
        if (!empty($itemUuid)) {
            $item = SpesifikasiKerjaItem::where('spesifikasi_kerja_header_id', $header->id)
                ->where('uuid', $itemUuid)
                ->first();

            if (!$item) {
                abort(422, 'Item spesifikasi tidak dijumpai untuk tender ini.');
            }
        }

        $uploadedFile = $request->file('file');
        $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $uploadedFile->getClientOriginalName());
        $storedName = now()->format('YmdHis') . '_' . Str::uuid() . '_' . $safeOriginalName;
        $path = $uploadedFile->storeAs('spesifikasi-kerja/' . $tender->uuid, $storedName, 'public');
        abort_unless($path, 500, 'Pelayan SUKSel gagal menyimpan fail. Sila cuba lagi atau hubungi pentadbir.');

        try {
            $file = SpesifikasiKerjaFile::create([
            'uuid' => (string) Str::uuid(),
            'spesifikasi_kerja_header_id' => $header->id,
            'spesifikasi_kerja_item_id' => $item?->id,
            'file_type' => $fileType,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize() ?: 0,
            'uploaded_by' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        if ($fileType === 'bq_template') {
            $previousTemplates = $header->files()
                ->whereNull('spesifikasi_kerja_item_id')
                ->where('file_type', 'bq_template')
                ->where('id', '!=', $file->id)
                ->get();
            foreach ($previousTemplates as $previous) {
                if ($previous->path && Storage::disk('public')->exists($previous->path)) {
                    Storage::disk('public')->delete($previous->path);
                }
                $previous->delete();
            }
        }

        $downloadUrl = route('penyediaanSpekTender.downloadFile', [
            'tenderUuid' => $tender->uuid,
            'fileUuid' => $file->uuid,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fail berjaya dimuat naik ke storan SUKSel 3.0.',
            'data' => [
                'uuid' => $file->uuid,
                'checklist_item_uuid' => $item?->uuid,
                'spesifikasi_kerja_item_uuid' => $item?->uuid,
                'file_type' => $file->file_type,
                'original_name' => $file->original_name,
                'stored_name' => $file->stored_name,
                'path' => $file->path,
                'mime_type' => $file->mime_type,
                'size' => (int) $file->size,
                'url' => $downloadUrl,
                'uploaded_by' => $file->uploaded_by,
                'created_at' => $file->created_at?->toISOString(),
            ],
        ], 200);
    }

    /**
     * Open an uploaded specification file through this app.
     */
    public function downloadFile(string $tenderUuid, string $fileUuid)
    {
        $this->ensureAccess();

        $tender = Tender::query()->where('uuid', $tenderUuid)->firstOrFail();

        $file = SpesifikasiKerjaFile::query()
            ->where('uuid', $fileUuid)
            ->whereHas('header', fn ($query) => $query->where('tender_id', $tender->id))
            ->first();

        abort_unless($file, 404, 'Fail tidak dijumpai pada storan SUKSel.');

        return \App\Support\SukselStoredFile::response((string) $file->path, $file->original_name);
    }
    /**
     * Delete an uploaded file — deleted directly on SUKSel 3.0 server storage.
     */
    public function deleteFile(string $fileUuid)
    {
        $this->ensureAccess();

        $file = SpesifikasiKerjaFile::where('uuid', $fileUuid)
            ->whereHas('header', fn ($query) => $query->whereHas('tender', fn ($tenderQuery) => $tenderQuery->where('kategori_perolehan_id', 3)))
            ->first();
        if ($file) {
            if ($file->file_type === 'bq_template' && $file->header?->status === 'submitted') {
                return response()->json(['success' => false, 'message' => 'Templat yang telah dihantar tidak boleh dipadam.'], 422);
            }
            if ($file->path && Storage::disk('public')->exists($file->path)) {
                abort_unless(Storage::disk('public')->delete($file->path), 500, 'Fail tidak dapat dipadam dari storan SUKSel. Sila cuba lagi.');
            }
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fail berjaya dipadam dari storan SUKSel 3.0.',
            ]);
        }

        abort(404, 'Fail tidak dijumpai pada storan SUKSel.');
    }
    private function serializeLocalSpesifikasi(SpesifikasiKerjaHeader $header, Tender $tender): array
    {
        return [
            'uuid' => $header->uuid,
            'tender_uuid' => $tender->uuid,
            'status' => $header->status,
            'submitted_at' => optional($header->submitted_at)->toIso8601String(),
            'items' => $header->items->map(function ($item) use ($tender) {
                return [
                    'uuid' => $item->uuid,
                    'nama_item' => $item->nama_item,
                    'spesifikasi' => $item->spesifikasi,
                    'unit' => $item->unit,
                    'kuantiti' => (float) $item->kuantiti,
                    'kadar' => (float) $item->kadar,
                    'jumlah' => $item->jumlah(),
                    'catatan' => $item->catatan,
                    'sort_order' => (int) $item->sort_order,
                    'files' => $item->files->map(fn ($f) => [
                        'uuid' => $f->uuid,
                        'file_type' => $f->file_type,
                        'original_name' => $f->original_name,
                        'stored_name' => $f->stored_name,
                        'path' => $f->path,
                        'mime_type' => $f->mime_type,
                        'size' => (int) $f->size,
                        'url' => route('penyediaanSpekTender.downloadFile', ['tenderUuid' => $tender->uuid, 'fileUuid' => $f->uuid]),
                    ])->values()->all(),
                    'specs' => $item->specs->map(function ($spec) use ($tender) {
                        return [
                            'uuid' => $spec->uuid,
                            'nama_item' => $spec->nama_item,
                            'spesifikasi' => $spec->spesifikasi,
                            'unit' => $spec->unit,
                            'kuantiti' => (float) $spec->kuantiti,
                            'kadar' => (float) $spec->kadar,
                            'jumlah' => $spec->jumlah(),
                            'catatan' => $spec->catatan,
                            'sort_order' => (int) $spec->sort_order,
                            'files' => $spec->files->map(fn ($f) => [
                                'uuid' => $f->uuid,
                                'file_type' => $f->file_type,
                                'original_name' => $f->original_name,
                                'stored_name' => $f->stored_name,
                                'path' => $f->path,
                                'mime_type' => $f->mime_type,
                                'size' => (int) $f->size,
                                'url' => route('penyediaanSpekTender.downloadFile', ['tenderUuid' => $tender->uuid, 'fileUuid' => $f->uuid]),
                            ])->values()->all(),
                        ];
                    })->values()->all(),
                ];
            })->values()->all(),
            'files' => $header->files
                ->filter(fn ($file) => $file->spesifikasi_kerja_item_id === null)
                ->filter(fn ($file) => in_array($file->file_type, ['bq_template', 'bq', 'support'], true))
                ->map(fn ($f) => [
                    'uuid' => $f->uuid,
                    'file_type' => $f->file_type,
                    'original_name' => $f->original_name,
                    'stored_name' => $f->stored_name,
                    'path' => $f->path,
                    'mime_type' => $f->mime_type,
                    'size' => (int) $f->size,
                    'created_at' => $f->created_at?->toIso8601String(),
                    'url' => route('penyediaanSpekTender.downloadFile', ['tenderUuid' => $tender->uuid, 'fileUuid' => $f->uuid]),
                ])
                ->values()
                ->all(),
        ];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function ensureAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

}
