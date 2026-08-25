<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\Tender;
use App\Services\StosBackendClient;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class KerjaDalamTanganController extends Controller
{
    use HandlesTenderFormAccess;

    public function create(?string $tenderUuid = null)
    {
        if (!$tenderUuid)
        {
            abort(404);
        }

        $tender = $this->findTender($tenderUuid);

        if (!$tender)
        {
            abort(404);
        }

        $this->ensureTenderFormAccess($tender);

        $existingData = null;

        $vendorId = $this->vendorId();
        $apiPath  = 'kerja-dalam-tangan/' . $tenderUuid;
        $response = $vendorId
            ? $this->api()->get($this->stosUrlWithVendor($apiPath, $vendorId))
            : $this->api()->get($this->url($apiPath));

        if ($response->successful())
        {
            $existingData = $response->json('data');
        }
        else
        {
            Log::warning('KerjaDalamTanganController@create: failed to load existing data from API', [
                'tender_uuid' => $tenderUuid,
                'status'      => $response->status(),
                'body'        => Str::limit($response->body(), 500),
            ]);
        }

        // Staff preview (?vendor_id=) and vendor mode both need per-vendor isolation.
        if ($this->vendorId()) {
            $existingData = $this->resolveVendorFormDisplayData(
                $tender,
                'kerja_dalam_tangan',
                is_array($existingData) ? $existingData : null
            );
        }

        if (is_array($existingData) && ! empty($existingData['dokumens'])) {
            $existingData['dokumens'] = StosFormFileController::rewriteDokumenUrls(
                $tender,
                'kerja-dalam-tangan',
                $existingData['dokumens']
            );
        }

        return view('tenderKerjaDalamTangan.form_kerja_dalam_tangan', array_merge(
            compact('tender', 'existingData'),
            $this->formViewVars($tender)
        ));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        if (!$tender) {
            abort(404);
        }
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $tajukList   = $request->input('kdt_tajuk', []);
        $picList     = $request->input('kdt_pic', []);
        $telefonList = $request->input('kdt_telefon', []);
        $nilaiList   = $request->input('kdt_nilai', []);

        $items = [];
        foreach ($tajukList as $i => $tajuk) {
            if (empty(trim($tajuk))) {
                continue;
            }
            $nilaiRaw = str_replace(',', '', $nilaiList[$i] ?? '0');
            $items[]  = [
                'tajuk'       => trim($tajuk),
                'pic'         => trim($picList[$i] ?? ''),
                'telefon_pic' => trim($telefonList[$i] ?? ''),
                'nilai_kerja' => is_numeric($nilaiRaw) ? (float) $nilaiRaw : 0,
                'sort_order'  => $i,
            ];
        }

        $payload = [
            'items'   => $items,
            'user_id' => auth()->id(),
        ];
        if ($this->isVendorFormMode()) {
            $payload['vendor_id'] = $this->vendorId();
            $this->persistVendorFormPayload($tender, 'kerja_dalam_tangan', $payload);
        }

        $apiUrl = $this->url('kerja-dalam-tangan/' . $tenderUuid);

        try {
            $response = $this->api()->post($apiUrl, $payload);
        } catch (Throwable $e) {
            Log::error('KerjaDalamTanganController@store: API request threw an exception', [
                'tender_uuid'     => $tenderUuid,
                'item_count'      => count($items),
                'user_id'         => auth()->id(),
                'exception_class' => get_class($e),
                'exception_msg'   => $e->getMessage(),
                'exception_file'  => $e->getFile(),
                'exception_line'  => $e->getLine(),
            ]);

            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'kerja_dalam_tangan', [
                    'text' => count($items) . ' rekod kerja dalam tangan (disimpan tempatan)',
                ]);

                return $this->vendorFormRedirect($request, $tender, 'Data disimpan secara tempatan. Penyegerakan STOS gagal.', 'warning');
            }

            return redirect()->back()
                ->with('error', 'Terdapat ralat sambungan semasa menyimpan. Sila cuba lagi.');
        }

        if (!$response->successful()) {
            $this->logStoreFailure($tenderUuid, $payload, $apiUrl, $response);

            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'kerja_dalam_tangan', [
                    'text' => count($items) . ' rekod kerja dalam tangan (disimpan tempatan)',
                ]);

                return $this->vendorFormRedirect($request, $tender, 'Data disimpan secara tempatan. Penyegerakan STOS gagal.', 'warning');
            }

            $message = $response->json('message') ?: 'Terdapat ralat semasa menyimpan kerja dalam tangan.';

            return redirect()->back()->with('error', $message);
        }

        $fileErrors = [];

        if ($request->hasFile('dokumen_kdt')) {
            foreach ($request->file('dokumen_kdt') as $file) {
                if (!$file->isValid()) {
                    $fileErrors[] = $file->getClientOriginalName() . ': fail tidak sah.';
                    continue;
                }

                $uploadUrl = $this->url('kerja-dalam-tangan/' . $tenderUuid . '/files');

                try {
                    $uploadExtra = ['user_id' => auth()->id()];
                    if ($this->isVendorFormMode()) {
                        $uploadExtra['vendor_id'] = $this->vendorId();
                    }
                    $uploadResponse = $this->api()
                        ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                        ->post($uploadUrl, $uploadExtra);

                    if (!$uploadResponse->successful()) {
                        $fileErrors[] = $file->getClientOriginalName() . ': gagal dimuat naik.';

                        Log::warning('KerjaDalamTanganController@store: file upload failed', [
                            'tender_uuid'   => $tenderUuid,
                            'original_name' => $file->getClientOriginalName(),
                            'user_id'       => auth()->id(),
                            'status'        => $uploadResponse->status(),
                            'body'          => Str::limit($uploadResponse->body(), 300),
                        ]);
                    } elseif ($this->isVendorFormMode()) {
                        $uploaded = $uploadResponse->json('data');
                        if (is_array($uploaded)) {
                            $this->appendVendorFormDokumen($tender, 'kerja_dalam_tangan', $uploaded);
                        }
                    }
                } catch (Throwable $e) {
                    $fileErrors[] = $file->getClientOriginalName() . ': ralat semasa muat naik.';

                    Log::error('KerjaDalamTanganController@store: file upload threw an exception', [
                        'tender_uuid'     => $tenderUuid,
                        'original_name'   => $file->getClientOriginalName(),
                        'user_id'         => auth()->id(),
                        'exception_class' => get_class($e),
                        'exception_msg'   => $e->getMessage(),
                        'exception_file'  => $e->getFile(),
                        'exception_line'  => $e->getLine(),
                    ]);
                }
            }
        }

        if (!empty($fileErrors)) {
            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'kerja_dalam_tangan', [
                    'text' => count($items) . ' rekod kerja dalam tangan (sebahagian fail gagal)',
                ]);

                return $this->vendorFormRedirect(
                    $request,
                    $tender,
                    'Kerja dalam tangan berjaya disimpan. Beberapa fail gagal dimuat naik: ' . implode(', ', $fileErrors)
                );
            }

            return redirect()->back()
                ->with('success', 'Kerja dalam tangan berjaya disimpan.')
                ->with('warning', 'Beberapa fail gagal dimuat naik: ' . implode(', ', $fileErrors));
        }

        if ($this->isVendorFormMode()) {
            $this->trackVendorFormSubmitted($tender, 'kerja_dalam_tangan', [
                'text' => count($items) . ' rekod kerja dalam tangan',
            ]);

            return $this->vendorFormRedirect($request, $tender, 'Kerja dalam tangan berjaya disimpan.');
        }

        return redirect()
            ->route('senaraiTeknikal', $tenderUuid)
            ->with('success', 'Kerja dalam tangan berjaya disimpan.');
    }

    public function deleteFile(string $fileUuid)
    {
        $user = auth()->user();
        if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management') && ! $user->vendor_id) {
            abort(403);
        }
        $this->ensureFormEditable();

        $apiUrl = $this->url('kerja-dalam-tangan-files/' . $fileUuid);

        try {
            $response = $this->api()->delete($apiUrl);
        } catch (Throwable $e) {
            Log::error('KerjaDalamTanganController@deleteFile: API request threw an exception', [
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
            Log::warning('KerjaDalamTanganController@deleteFile: API returned error', [
                'file_uuid' => $fileUuid,
                'user_id'   => auth()->id(),
                'status'    => $response->status(),
                'body'      => Str::limit($response->body(), 300),
            ]);
        }

        return response()->json($response->json(), $response->status());
    }

    /** Read-only admin review of one vendor's Kerja Dalam Tangan submission (shared by Penilaian Teknikal + Jawatankuasa Pembuka). */
    public function review(\App\Tender $tender, Request $request)
    {
        $this->ensureTenderFormAccess($tender);

        $local = $this->loadVendorFormPayload($tender, 'kerja_dalam_tangan');
        $remote = $this->fetchOnlineFormData('kerja-dalam-tangan', $tender->uuid, (int) $request->query('vendor_id'));
        $resolved = $this->resolveVendorFormDisplayData($tender, 'kerja_dalam_tangan', $remote ?: null);

        return view('tenders.dokumen.review.kerja_dalam_tangan_review', array_merge([
            'items'    => $resolved['items'] ?? ($local['items'] ?? []),
            'dokumens' => $resolved['dokumens'] ?? [],
        ], $this->formViewVars($tender)));
    }

    private function findTender(string $uuid): ?Tender
    {
        return Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $uuid)
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
            ? 'KerjaDalamTanganController@store: Backend API returned a server error'
            : 'KerjaDalamTanganController@store: Backend API returned an unsuccessful response';

        $itemTitles = collect($payload['items'] ?? [])->take(5)->pluck('tajuk')->all();

        Log::log($level, $message, [
            'api_url'               => $apiUrl,
            'http_method'           => 'POST',
            'status'                => $response->status(),
            'tender_uuid'           => $tenderUuid,
            'user_id'               => auth()->id(),
            'item_count'            => count($payload['items'] ?? []),
            'item_titles_preview'   => $itemTitles,
            'response_message'      => $response->json('message'),
            'response_body_preview' => Str::limit($response->body(), 500),
        ]);
    }
}