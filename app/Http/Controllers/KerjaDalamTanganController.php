<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class KerjaDalamTanganController extends Controller
{
    public function create(?string $tenderUuid = null)
    {
        $this->ensureSpecificationAccess();

        if (!$tenderUuid)
        {
            abort(404);
        }

        $tender = $this->findTender($tenderUuid);

        if (!$tender)
        {
            abort(404);
        }

        $existingData = null;

        $response = $this->api()->get($this->url('kerja-dalam-tangan/' . $tenderUuid));

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

        return view('tenderKerjaDalamTangan.form_kerja_dalam_tangan', compact('tender', 'existingData'));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureSpecificationAccess();

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

            return redirect()->back()
                ->with('error', 'Terdapat ralat sambungan semasa menyimpan. Sila cuba lagi.');
        }

        if (!$response->successful()) {
            $this->logStoreFailure($tenderUuid, $payload, $apiUrl, $response);

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
                    $uploadResponse = $this->api()
                        ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                        ->post($uploadUrl, ['user_id' => auth()->id()]);

                    if (!$uploadResponse->successful()) {
                        $fileErrors[] = $file->getClientOriginalName() . ': gagal dimuat naik.';

                        Log::warning('KerjaDalamTanganController@store: file upload failed', [
                            'tender_uuid'   => $tenderUuid,
                            'original_name' => $file->getClientOriginalName(),
                            'user_id'       => auth()->id(),
                            'status'        => $uploadResponse->status(),
                            'body'          => Str::limit($uploadResponse->body(), 300),
                        ]);
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
            return redirect()->back()
                ->with('success', 'Kerja dalam tangan berjaya disimpan.')
                ->with('warning', 'Beberapa fail gagal dimuat naik: ' . implode(', ', $fileErrors));
        }

        return redirect()
            ->route('senaraiTeknikal', $tenderUuid)
            ->with('success', 'Kerja dalam tangan berjaya disimpan.');
    }

    public function deleteFile(string $fileUuid)
    {
        $this->ensureSpecificationAccess();

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
        return Http::withHeaders([
            'X-Api-Key' => config('services.stos_backend.api_key'),
            'Accept'    => 'application/json',
        ]);
    }

    private function url(string $path): string
    {
        return config('services.stos_backend.url') . '/api/' . $path;
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

    private function ensureSpecificationAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }
}
