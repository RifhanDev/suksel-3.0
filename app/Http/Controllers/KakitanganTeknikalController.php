<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\Tender;
use App\Models\TenderKakitanganTeknikal;
use App\Models\TenderKakitanganTeknikalDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KakitanganTeknikalController extends Controller
{
    use HandlesTenderFormAccess;

    public function create(?string $tenderUuid = null)
    {
        if (! $tenderUuid) {
            abort(404);
        }

        $tender = $this->findTender($tenderUuid);
        if (! $tender) {
            abort(404);
        }
        $this->ensureTenderFormAccess($tender);

        $vendorId = $this->resolveVendorId();

        $query = TenderKakitanganTeknikal::with('dokumens')
            ->where('tender_uuid', $tender->uuid)
            ->orderBy('sort_order', 'asc');

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        $kakitanganList = $query->get();

        $generalDokumensQuery = TenderKakitanganTeknikalDokumen::query()
            ->where('tender_uuid', $tender->uuid)
            ->where(function ($q) {
                $q->whereNull('kakitangan_uuid')->orWhere('kakitangan_uuid', '');
            });

        if ($vendorId) {
            $generalDokumensQuery->where('vendor_id', $vendorId);
        }

        $generalDokumens = $generalDokumensQuery->get();

        return view('tenderKakitanganTeknikal.form_kakitangan_teknikal', array_merge([
            'tender'           => $tender,
            'kakitanganList'   => $kakitanganList,
            'generalDokumens' => $generalDokumens,
        ], $this->formViewVars($tender)));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        if (! $tender) {
            return response()->json(['success' => false, 'message' => 'Tender tidak ditemui.'], 404);
        }
        $this->ensureTenderFormAccess($tender);

        $validated = $request->validate([
            'nama_pegawai'         => ['required', 'string', 'max:255'],
            'tahap_pendidikan'     => ['required', 'string', 'in:Pascasiswazah,Diploma dan Ijazah,SPM dan Sijil'],
            'jumlah_pengalaman'    => ['required', 'integer', 'min:0'],
            'sijil_professional'   => ['nullable', 'string', 'max:1000'],
            'kakitangan_dokumen'   => ['required', 'array', 'min:1'],
            'kakitangan_dokumen.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar'],
        ], [
            'nama_pegawai.required'       => 'Sila isi nama pegawai.',
            'tahap_pendidikan.required'   => 'Sila pilih tahap pendidikan tertinggi.',
            'jumlah_pengalaman.required'  => 'Sila isi jumlah pengalaman.',
            'kakitangan_dokumen.required' => 'Sila muat naik sekurang-kurangnya satu dokumen sokongan.',
            'kakitangan_dokumen.min'      => 'Sila muat naik sekurang-kurangnya satu dokumen sokongan.',
        ]);

        $vendorId = $this->resolveVendorId();
        $kategori = TenderKakitanganTeknikal::calculateKategori(
            $validated['tahap_pendidikan'],
            $validated['sijil_professional'] ?? null
        );

        $staffUuid = (string) Str::uuid();

        DB::beginTransaction();
        try {
            $staff = TenderKakitanganTeknikal::create([
                'uuid'               => $staffUuid,
                'tender_uuid'        => $tender->uuid,
                'vendor_id'          => $vendorId,
                'nama_pegawai'       => $validated['nama_pegawai'],
                'tahap_pendidikan'   => $validated['tahap_pendidikan'],
                'jumlah_pengalaman'  => (int) $validated['jumlah_pengalaman'],
                'sijil_professional' => $validated['sijil_professional'] ?? null,
                'kategori'           => $kategori,
                'sort_order'         => TenderKakitanganTeknikal::where('tender_uuid', $tender->uuid)->count() + 1,
            ]);

            // Save uploaded files
            if ($request->hasFile('kakitangan_dokumen')) {
                foreach ($request->file('kakitangan_dokumen') as $file) {
                    if (! $file->isValid()) {
                        continue;
                    }
                    $originalName = $file->getClientOriginalName();
                    $storedName   = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path         = $file->storeAs("kakitangan-teknikal/{$tender->uuid}", $storedName, 'public');

                    TenderKakitanganTeknikalDokumen::create([
                        'uuid'            => (string) Str::uuid(),
                        'tender_uuid'     => $tender->uuid,
                        'vendor_id'       => $vendorId,
                        'kakitangan_uuid' => $staffUuid,
                        'original_name'   => $originalName,
                        'stored_name'     => $storedName,
                        'path'            => $path,
                        'mime_type'       => $file->getClientMimeType(),
                        'size'            => $file->getSize(),
                        'uploaded_by'     => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            $staff->load('dokumens');

            $count = TenderKakitanganTeknikal::where('tender_uuid', $tender->uuid)
                ->where('vendor_id', $vendorId)
                ->count();
            $this->trackVendorFormSubmitted($tender, 'kakitangan_teknikal', [
                'text' => "{$count} kakitangan teknikal disimpan",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kakitangan teknikal berjaya disimpan.',
                'data'    => $staff,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan rekod: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $uuid)
    {
        $staff = TenderKakitanganTeknikal::with('dokumens')->where('uuid', $uuid)->first();
        if (! $staff) {
            return response()->json(['success' => false, 'message' => 'Rekod kakitangan tidak ditemui.'], 404);
        }

        $tender = $this->findTender($staff->tender_uuid);
        if (! $tender) {
            return response()->json(['success' => false, 'message' => 'Tender tidak ditemui.'], 404);
        }
        $this->ensureTenderFormAccess($tender);

        $validated = $request->validate([
            'nama_pegawai'         => ['required', 'string', 'max:255'],
            'tahap_pendidikan'     => ['required', 'string', 'in:Pascasiswazah,Diploma dan Ijazah,SPM dan Sijil'],
            'jumlah_pengalaman'    => ['required', 'integer', 'min:0'],
            'sijil_professional'   => ['nullable', 'string', 'max:1000'],
            'kakitangan_dokumen'   => ['nullable', 'array'],
            'kakitangan_dokumen.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar'],
        ]);

        $newFilesCount = $request->hasFile('kakitangan_dokumen') ? count($request->file('kakitangan_dokumen')) : 0;
        $existingCount = $staff->dokumens()->count();

        if (($newFilesCount + $existingCount) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Sila muat naik sekurang-kurangnya satu dokumen sokongan.',
            ], 422);
        }

        $kategori = TenderKakitanganTeknikal::calculateKategori(
            $validated['tahap_pendidikan'],
            $validated['sijil_professional'] ?? null
        );

        DB::beginTransaction();
        try {
            $staff->update([
                'nama_pegawai'       => $validated['nama_pegawai'],
                'tahap_pendidikan'   => $validated['tahap_pendidikan'],
                'jumlah_pengalaman'  => (int) $validated['jumlah_pengalaman'],
                'sijil_professional' => $validated['sijil_professional'] ?? null,
                'kategori'           => $kategori,
            ]);

            // Save new uploaded files
            if ($request->hasFile('kakitangan_dokumen')) {
                foreach ($request->file('kakitangan_dokumen') as $file) {
                    if (! $file->isValid()) {
                        continue;
                    }
                    $originalName = $file->getClientOriginalName();
                    $storedName   = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path         = $file->storeAs("kakitangan-teknikal/{$staff->tender_uuid}", $storedName, 'public');

                    TenderKakitanganTeknikalDokumen::create([
                        'uuid'            => (string) Str::uuid(),
                        'tender_uuid'     => $staff->tender_uuid,
                        'vendor_id'       => $staff->vendor_id,
                        'kakitangan_uuid' => $staff->uuid,
                        'original_name'   => $originalName,
                        'stored_name'     => $storedName,
                        'path'            => $path,
                        'mime_type'       => $file->getClientMimeType(),
                        'size'            => $file->getSize(),
                        'uploaded_by'     => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            $staff->load('dokumens');

            $vendorId = $staff->vendor_id ?: $this->resolveVendorId();
            $count = TenderKakitanganTeknikal::where('tender_uuid', $staff->tender_uuid)
                ->where('vendor_id', $vendorId)
                ->count();
            $this->trackVendorFormSubmitted($tender, 'kakitangan_teknikal', [
                'text' => "{$count} kakitangan teknikal disimpan",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kakitangan teknikal berjaya dikemaskini.',
                'data'    => $staff,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengemaskini rekod: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $uuid)
    {
        $staff = TenderKakitanganTeknikal::with('dokumens')->where('uuid', $uuid)->first();
        if (! $staff) {
            return response()->json(['success' => false, 'message' => 'Rekod kakitangan tidak ditemui.'], 404);
        }

        $tender = $this->findTender($staff->tender_uuid);
        if (! $tender) {
            return response()->json(['success' => false, 'message' => 'Tender tidak ditemui.'], 404);
        }
        $this->ensureTenderFormAccess($tender);

        DB::beginTransaction();
        try {
            foreach ($staff->dokumens as $dokumen) {
                if (Storage::disk('public')->exists($dokumen->path)) {
                    Storage::disk('public')->delete($dokumen->path);
                }
                $dokumen->delete();
            }

            $staff->delete();

            DB::commit();

            $vendorId = $staff->vendor_id ?: $this->resolveVendorId();
            $remainingCount = TenderKakitanganTeknikal::where('tender_uuid', $staff->tender_uuid)
                ->where('vendor_id', $vendorId)
                ->count();

            if ($remainingCount > 0) {
                $this->trackVendorFormSubmitted($tender, 'kakitangan_teknikal', [
                    'text' => "{$remainingCount} kakitangan teknikal disimpan",
                ]);
            } else {
                $this->trackVendorFormDraft($tender, 'kakitangan_teknikal', [
                    'text' => 'Tiada kakitangan teknikal',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kakitangan teknikal berjaya dipadam.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memadam rekod: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyDokumen(string $uuid)
    {
        $dokumen = TenderKakitanganTeknikalDokumen::where('uuid', $uuid)->first();
        if (! $dokumen) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemui.'], 404);
        }

        if (! empty($dokumen->kakitangan_uuid)) {
            $staff = TenderKakitanganTeknikal::where('uuid', $dokumen->kakitangan_uuid)->first();

            if ($staff && $staff->dokumens()->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sekurang-kurangnya satu dokumen sokongan mesti dikekalkan untuk kakitangan ini.',
                ], 422);
            }
        }

        try {
            if (Storage::disk('public')->exists($dokumen->path)) {
                Storage::disk('public')->delete($dokumen->path);
            }
            $dokumen->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berjaya dipadam.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memadam dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function uploadGeneralDokumen(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        if (! $tender) {
            return response()->json(['success' => false, 'message' => 'Tender tidak ditemui.'], 404);
        }
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $request->validate([
            'dokumen_umum'   => ['required', 'array', 'min:1'],
            'dokumen_umum.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar'],
        ], [
            'dokumen_umum.required' => 'Sila muat naik sekurang-kurangnya satu dokumen sokongan.',
            'dokumen_umum.min'      => 'Sila muat naik sekurang-kurangnya satu dokumen sokongan.',
        ]);

        $vendorId = $this->resolveVendorId();
        $savedFiles = [];

        DB::beginTransaction();
        try {
            if ($request->hasFile('dokumen_umum')) {
                foreach ($request->file('dokumen_umum') as $file) {
                    if (! $file->isValid()) {
                        continue;
                    }
                    $originalName = $file->getClientOriginalName();
                    $storedName   = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path         = $file->storeAs("kakitangan-teknikal/{$tender->uuid}", $storedName, 'public');

                    $doc = TenderKakitanganTeknikalDokumen::create([
                        'uuid'            => (string) Str::uuid(),
                        'tender_uuid'     => $tender->uuid,
                        'vendor_id'       => $vendorId,
                        'kakitangan_uuid' => null,
                        'original_name'   => $originalName,
                        'stored_name'     => $storedName,
                        'path'            => $path,
                        'mime_type'       => $file->getClientMimeType(),
                        'size'            => $file->getSize(),
                        'uploaded_by'     => auth()->id(),
                    ]);

                    $savedFiles[] = [
                        'uuid'          => $doc->uuid,
                        'original_name' => $doc->original_name,
                        'path'          => $doc->path,
                        'url'           => Storage::disk('public')->url($doc->path),
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen sokongan keseluruhan berjaya dimuat naik.',
                'files'   => $savedFiles,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat naik dokumen: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function resolveVendorId(): ?int
    {
        return auth()->check() ? (auth()->user()->vendor_id ?? auth()->id()) : null;
    }

    private function findTender(string $uuid): ?Tender
    {
        return Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $uuid)
            ->first();
    }
}
