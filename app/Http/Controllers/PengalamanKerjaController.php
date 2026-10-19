<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\Tender;
use App\Models\TenderPengalamanKerjaDokumen;
use App\Services\StosBackendClient;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PengalamanKerjaController extends Controller
{
    use HandlesTenderFormAccess;

    public function create(?string $tenderUuid = null)
    {
        if (!$tenderUuid)
        {
            abort(404);
        }

        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);

        $existingData = null;

        $vendorId = $this->vendorId();
        $apiPath  = 'pengalaman-kerja/' . $tenderUuid;
        $response = $vendorId
            ? $this->api()->get($this->stosUrlWithVendor($apiPath, $vendorId))
            : $this->api()->get($this->url($apiPath));

        if ($response->successful())
        {
            $existingData = $response->json('data');
        }
        else
        {
            Log::warning('PengalamanKerjaController@create: failed to load existing data from API', [
                'tender_uuid' => $tenderUuid,
                'status'      => $response->status(),
                'body'        => Str::limit($response->body(), 500),
            ]);
        }

        // Staff preview (?vendor_id=) and vendor mode both need per-vendor isolation.
        if ($this->vendorId()) {
            $existingData = $this->resolveVendorFormDisplayData(
                $tender,
                'pengalaman_kerja',
                is_array($existingData) ? $existingData : null
            );
        }

        if (! is_array($existingData)) {
            $existingData = ['items' => [], 'dokumens' => []];
        }

        $localDokumenQuery = TenderPengalamanKerjaDokumen::where('tender_uuid', $tenderUuid);
        if ($vendorId) {
            $localDokumenQuery->where('vendor_id', $vendorId);
        }
        $localDocs = $localDokumenQuery->get()->map(fn ($d) => [
            'uuid'          => $d->uuid,
            'original_name' => $d->original_name,
            'size'          => $d->size,
            'path'          => $d->path,
            'created_at'    => $d->created_at?->toISOString(),
        ])->all();

        $dokumens = is_array($existingData['dokumens'] ?? null) ? $existingData['dokumens'] : [];
        $seenUuids = array_filter(array_column($dokumens, 'uuid'));
        foreach ($localDocs as $ld) {
            if (! in_array($ld['uuid'], $seenUuids, true)) {
                $dokumens[] = $ld;
                $seenUuids[] = $ld['uuid'];
            }
        }
        $existingData['dokumens'] = $dokumens;

        if (! empty($existingData['dokumens'])) {
            $existingData['dokumens'] = StosFormFileController::rewriteDokumenUrls(
                $tender,
                'pengalaman-kerja',
                $existingData['dokumens']
            );
        }

        $isKerja = (int) ($tender->kategori_perolehan_id ?? 0) === 3 || strtolower($tender->kategori_perolehan_name ?? '') === 'kerja';

        return view('tenderPengalamanKerja.form_pengalaman_kerja', array_merge([
            'tender'       => $tender,
            'existingData' => $existingData,
            'isKerja'      => $isKerja,
        ], $this->formViewVars($tender)));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        if (!$tender) {
            abort(404);
        }
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $isKerja = (int) ($tender->kategori_perolehan_id ?? 0) === 3 || strtolower($tender->kategori_perolehan_name ?? '') === 'kerja';

        $tajukList      = $request->input('pengalaman_tajuk', []);
        $picList        = $request->input('pengalaman_pic', []);
        $telefonList    = $request->input('pengalaman_telefon', []);
        $kosPrimaList   = $request->input('pengalaman_wang_kos_prima', []);
        $peruntukanList = $request->input('pengalaman_wang_peruntukan_semasa', []);
        $nilaiList      = $request->input('pengalaman_nilai', []);

        $items = [];
        foreach ($tajukList as $i => $tajuk) {
            if (empty(trim($tajuk))) {
                continue;
            }
            $nilaiRaw      = str_replace(',', '', $nilaiList[$i] ?? '0');
            $kosPrimaRaw   = str_replace(',', '', $kosPrimaList[$i] ?? '');
            $peruntukanRaw = str_replace(',', '', $peruntukanList[$i] ?? '');

            $wangKosPrima = ($isKerja && $kosPrimaRaw !== '' && is_numeric($kosPrimaRaw)) ? (float) $kosPrimaRaw : null;
            $wangPeruntukanSemasa = ($isKerja && $peruntukanRaw !== '' && is_numeric($peruntukanRaw)) ? (float) $peruntukanRaw : null;

            $items[]  = [
                'tajuk'                  => trim($tajuk),
                'pic'                    => trim($picList[$i] ?? ''),
                'telefon_pic'            => trim($telefonList[$i] ?? ''),
                'wang_kos_prima'        => $wangKosPrima,
                'wang_peruntukan_semasa' => $wangPeruntukanSemasa,
                'nilai_kerja'            => is_numeric($nilaiRaw) ? (float) $nilaiRaw : 0,
                'sort_order'             => $i,
            ];
        }

        $payload = [
            'items'   => $items,
            'user_id' => auth()->id(),
        ];
        if ($this->isVendorFormMode()) {
            $payload['vendor_id'] = $this->vendorId();
            $this->persistVendorFormPayload($tender, 'pengalaman_kerja', $payload);
        }

        $apiUrl = $this->url('pengalaman-kerja/' . $tenderUuid);

        try {
            $response = $this->api()->post($apiUrl, $payload);
        } catch (Throwable $e) {
            Log::error('PengalamanKerjaController@store: API request threw an exception', [
                'tender_uuid'     => $tenderUuid,
                'item_count'      => count($items),
                'user_id'         => auth()->id(),
                'exception_class' => get_class($e),
                'exception_msg'   => $e->getMessage(),
                'exception_file'  => $e->getFile(),
                'exception_line'  => $e->getLine(),
            ]);

            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'pengalaman_kerja', [
                    'text' => count($items) . ' rekod pengalaman (disimpan tempatan)',
                ]);

                return $this->vendorFormRedirect($request, $tender, 'Data disimpan secara tempatan. Penyegerakan STOS gagal.', 'warning');
            }

            return redirect()->back()
                ->with('error', 'Terdapat ralat sambungan semasa menyimpan. Sila cuba lagi.');
        }

        if (!$response->successful()) {
            $this->logStoreFailure($tenderUuid, $payload, $apiUrl, $response);

            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'pengalaman_kerja', [
                    'text' => count($items) . ' rekod pengalaman (disimpan tempatan)',
                ]);

                return $this->vendorFormRedirect($request, $tender, 'Data disimpan secara tempatan. Penyegerakan STOS gagal.', 'warning');
            }

            $message = $response->json('message') ?: 'Terdapat ralat semasa menyimpan pengalaman kerja.';

            return redirect()->back()->with('error', $message);
        }

        // ── Handle file uploads (direct to SUKSel 3.0 server storage) ─────────────────
        $fileErrors = [];

        if ($request->hasFile('dokumen_pengalaman')) {
            $vendorId = $this->isVendorFormMode() ? $this->vendorId() : null;

            foreach ($request->file('dokumen_pengalaman') as $file) {
                if (!$file->isValid()) {
                    $fileErrors[] = $file->getClientOriginalName() . ': fail tidak sah.';
                    continue;
                }

                $maxSize = 10240 * 1024; // 10MB
                if ($file->getSize() > $maxSize) {
                    $fileErrors[] = $file->getClientOriginalName() . ': saiz fail melebihi had 10MB.';
                    continue;
                }

                try {
                    $safeOriginalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
                    $storedName       = now()->format('YmdHis') . '_' . Str::uuid() . '_' . $safeOriginalName;
                    $folder           = 'uploads/pengalaman-kerja/' . $tenderUuid . ($vendorId ? '/' . $vendorId : '');
                    $path             = $file->storeAs($folder, $storedName, 'public');

                    if (! $path) {
                        $fileErrors[] = $file->getClientOriginalName() . ': gagal disimpan ke storan SUKSel 3.0.';
                        continue;
                    }

                    $dokumen = TenderPengalamanKerjaDokumen::create([
                        'uuid'          => (string) Str::uuid(),
                        'tender_uuid'   => $tenderUuid,
                        'vendor_id'     => $vendorId,
                        'original_name' => $file->getClientOriginalName(),
                        'stored_name'   => $storedName,
                        'path'          => $path,
                        'mime_type'     => $file->getClientMimeType(),
                        'size'          => $file->getSize() ?: 0,
                        'uploaded_by'   => auth()->id(),
                    ]);

                    if ($this->isVendorFormMode()) {
                        $this->appendVendorFormDokumen($tender, 'pengalaman_kerja', [
                            'uuid'          => $dokumen->uuid,
                            'original_name' => $dokumen->original_name,
                            'size'          => $dokumen->size,
                            'path'          => $dokumen->path,
                            'created_at'    => $dokumen->created_at?->toISOString(),
                        ]);
                    }
                } catch (Throwable $e) {
                    $fileErrors[] = $file->getClientOriginalName() . ': ralat semasa simpan fail.';

                    Log::error('PengalamanKerjaController@store: direct upload threw an exception', [
                        'tender_uuid'     => $tenderUuid,
                        'original_name'   => $file->getClientOriginalName(),
                        'user_id'         => auth()->id(),
                        'exception_class' => get_class($e),
                        'exception_msg'   => $e->getMessage(),
                    ]);
                }
            }
        }

        if (!empty($fileErrors)) {
            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'pengalaman_kerja', [
                    'text' => count($items) . ' rekod pengalaman (sebahagian fail gagal)',
                ]);

                return $this->vendorFormRedirect(
                    $request,
                    $tender,
                    'Pengalaman kerja berjaya disimpan. Beberapa fail gagal dimuat naik: ' . implode(', ', $fileErrors)
                );
            }

            return redirect()->back()
                ->with('success', 'Pengalaman kerja berjaya disimpan.')
                ->with('warning', 'Beberapa fail gagal dimuat naik: ' . implode(', ', $fileErrors));
        }

        if ($this->isVendorFormMode()) {
            $this->trackVendorFormSubmitted($tender, 'pengalaman_kerja', [
                'text' => count($items) . ' rekod pengalaman kerja',
            ]);

            return $this->vendorFormRedirect($request, $tender, 'Pengalaman kerja berjaya disimpan.');
        }

        return redirect()
            ->route('senaraiTeknikal', $tenderUuid)
            ->with('success', 'Pengalaman kerja berjaya disimpan.');
    }

    public function deleteFile(string $fileUuid)
    {
        $user = auth()->user();
        if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management') && ! $user->vendor_id) {
            abort(403);
        }
        $this->ensureFormEditable();

        $dokumen = TenderPengalamanKerjaDokumen::where('uuid', $fileUuid)->first();
        if ($dokumen) {
            if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management')) {
                $vendorId = (int) ($user->vendor_id ?? 0);
                if ($vendorId <= 0 || ($dokumen->vendor_id && (int) $dokumen->vendor_id !== $vendorId)) {
                    abort(403, 'Akses tidak dibenarkan.');
                }
            }

            if ($dokumen->path && Storage::disk('public')->exists($dokumen->path)) {
                Storage::disk('public')->delete($dokumen->path);
            }
            $tenderUuid = $dokumen->tender_uuid;
            $dokumen->delete();

            $tender = $this->findTender($tenderUuid);
            if ($tender) {
                $this->removeVendorFormDokumen($tender, 'pengalaman_kerja', $fileUuid);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fail berjaya dipadam dari storan SUKSel 3.0.',
            ]);
        }

        $apiUrl = $this->url('pengalaman-kerja-files/' . $fileUuid);

        try {
            $response = $this->api()->delete($apiUrl);
        } catch (Throwable $e) {
            Log::error('PengalamanKerjaController@deleteFile: API request threw an exception', [
                'file_uuid'       => $fileUuid,
                'user_id'         => auth()->id(),
                'exception_class' => get_class($e),
                'exception_msg'   => $e->getMessage(),
                'exception_file'  => $e->getFile(),
                'exception_line'  => $e->getLine(),
            ]);

            return response()->json(['message' => 'Ralat sambungan semasa memadam fail.'], 502);
        }

        if (!$response->successful()) {
            Log::warning('PengalamanKerjaController@deleteFile: API returned error', [
                'file_uuid' => $fileUuid,
                'user_id'   => auth()->id(),
                'status'    => $response->status(),
                'body'      => Str::limit($response->body(), 300),
            ]);
        }

        return response()->json($response->json(), $response->status());
    }

    /** Read-only admin review of one vendor's Pengalaman Kerja submission (shared by Penilaian Teknikal + Jawatankuasa Pembuka). */
    public function review(\App\Tender $tender, Request $request)
    {
        $this->ensureTenderFormAccess($tender);

        $vendorId = (int) $request->query('vendor_id') ?: null;
        $local = $this->loadVendorFormPayload($tender, 'pengalaman_kerja');
        $remote = $this->fetchOnlineFormData('pengalaman-kerja', $tender->uuid, $vendorId);
        $resolved = $this->resolveVendorFormDisplayData($tender, 'pengalaman_kerja', $remote ?: null);

        $localDokumenQuery = TenderPengalamanKerjaDokumen::where('tender_uuid', $tender->uuid);
        if ($vendorId) {
            $localDokumenQuery->where('vendor_id', $vendorId);
        }
        $localDocs = $localDokumenQuery->get()->map(fn ($d) => [
            'uuid'          => $d->uuid,
            'original_name' => $d->original_name,
            'size'          => $d->size,
            'path'          => $d->path,
            'created_at'    => $d->created_at?->toISOString(),
        ])->all();

        $dokumens = is_array($resolved['dokumens'] ?? null) ? $resolved['dokumens'] : [];
        $seenUuids = array_filter(array_column($dokumens, 'uuid'));
        foreach ($localDocs as $ld) {
            if (! in_array($ld['uuid'], $seenUuids, true)) {
                $dokumens[] = $ld;
                $seenUuids[] = $ld['uuid'];
            }
        }

        $dokumens = StosFormFileController::rewriteDokumenUrls($tender, 'pengalaman-kerja', $dokumens);

        return view('tenders.dokumen.review.pengalaman_kerja_review', array_merge([
            'items'    => $resolved['items'] ?? ($local['items'] ?? []),
            'dokumens' => $dokumens,
        ], $this->formViewVars($tender)));
    }

    private function findTender(string $uuid): ?Tender
    {
        return Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where(function ($q) use ($uuid) {
                $q->where('tenders.uuid', $uuid);

                // Hanya jatuh balik kepada id apabila nilainya benar-benar
                // angka. tenders.id ialah integer, jadi MySQL memaksa string
                // UUID kepada nombor sebelum membandingkan — UUID seperti
                // '26f653c1-...' menjadi 26 dan memadankan tender id 26 yang
                // tiada kaitan, memuatkan tender yang salah sepenuhnya.
                if (ctype_digit((string) $uuid)) {
                    $q->orWhere('tenders.id', (int) $uuid);
                }
            })
            ->first();
    }

    private function api()
    {
        return StosBackendClient::http();
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }

    private function logStoreFailure(string $tenderUuid, array $payload, string $apiUrl, Response $response): void
    {
        $level   = $response->status() >= 500 ? 'error' : 'warning';
        $message = $response->status() >= 500
            ? 'PengalamanKerjaController@store: Backend API returned a server error'
            : 'PengalamanKerjaController@store: Backend API returned an unsuccessful response';

        $itemTitles = collect($payload['items'] ?? [])->take(5)->pluck('tajuk')->all();

        Log::log($level, $message, [
            'api_url'              => $apiUrl,
            'http_method'          => 'POST',
            'status'               => $response->status(),
            'tender_uuid'          => $tenderUuid,
            'user_id'              => auth()->id(),
            'item_count'           => count($payload['items'] ?? []),
            'item_titles_preview'  => $itemTitles,
            'response_message'     => $response->json('message'),
            'response_body_preview'=> Str::limit($response->body(), 500),
        ]);
    }
}
