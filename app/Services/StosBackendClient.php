<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StosBackendClient
{
    public static function http(): PendingRequest
    {
        $client = Http::timeout(30)->withHeaders([
            'X-API-Key' => config('services.stos_backend.api_key'),
            'Accept' => 'application/json',
        ]);

        if (! config('services.stos_backend.verify_ssl', true)) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    public static function apiUrl(string $path): string
    {
        return rtrim((string) config('services.stos_backend.url'), '/') . '/api/' . ltrim($path, '/');
    }

    protected string $baseUrl;

    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.stos_backend.url'), '/');
        $this->apiKey = config('services.stos_backend.api_key');
    }

    public function isConfigured(): bool
    {
        return $this->baseUrl !== '' && ! empty($this->apiKey);
    }

    public function get(string $path, array $query = []): Response
    {
        return $this->request('get', $path, ['query' => $query]);
    }

    public function post(string $path, array $payload = []): Response
    {
        return $this->request('post', $path, ['json' => $payload]);
    }

    public function put(string $path, array $payload = []): Response
    {
        return $this->request('put', $path, ['json' => $payload]);
    }

    public function createTender(array $payload): Response
    {
        return $this->post('/api/tenders', $payload);
    }

    public function updateTender(int $tenderId, array $payload): Response
    {
        return $this->put('/api/tenders/' . $tenderId, $payload);
    }

    public function getTender(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId);
    }

    public function dispatchProcess(string $process, array $payload): Response
    {
        $processSlug = str_replace('_', '-', $process);

        return $this->post('/api/processes/' . $processSlug, $payload);
    }

    public function getPenyediaanIklan(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penyediaan-iklan');
    }

    public function savePenyediaanIklan(int $tenderId, array $payload): Response
    {
        return $this->post('/api/tenders/' . $tenderId . '/penyediaan-iklan', $payload);
    }

    public function submitPenyediaanIklan(int $tenderId, array $payload): Response
    {
        return $this->post('/api/tenders/' . $tenderId . '/penyediaan-iklan/submit', $payload);
    }

    public function getPenyediaanMesyuarat(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penyediaan-mesyuarat');
    }

    public function savePenyediaanMesyuarat(int $tenderId, array $payload): Response
    {
        return $this->post('/api/tenders/' . $tenderId . '/penyediaan-mesyuarat', $payload);
    }

    public function submitPenyediaanMesyuarat(int $tenderId, array $payload): Response
    {
        return $this->post('/api/tenders/' . $tenderId . '/penyediaan-mesyuarat/submit', $payload);
    }

    public function submitJawatankuasaSpesifikasiPemakluman(int $tenderId, array $payload): Response
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('STOS backend is not configured. Set STOS_BACKEND_URL and STOS_BACKEND_API_KEY in .env');
        }

        $url = $this->baseUrl . '/api/tenders/' . $tenderId . '/jawatankuasa-spesifikasi-mesyuarat/pemakluman';

        return self::http()->timeout(120)->post($url, $payload);
    }

    public function getKehadiranMesyuarat(int $tenderId, ?int $meetingId = null): Response
    {
        $query = $meetingId ? ['meeting_id' => $meetingId] : [];

        return $this->get('/api/tenders/' . $tenderId . '/kehadiran-mesyuarat', $query);
    }

    public function getCutOffTenders(array $query = []): Response
    {
        return $this->get('/api/cut-off/tenders', $query);
    }

    public function getCutOffTender(string $uuid): Response
    {
        return $this->get('/api/cut-off/tenders/' . $uuid);
    }

    public function simpanCutOff(string $uuid, array $payload): Response
    {
        return $this->post('/api/cut-off/tenders/' . $uuid . '/simpan', $payload);
    }

    public function finalizeCutOff(string $uuid, array $payload = []): Response
    {
        return $this->post('/api/cut-off/tenders/' . $uuid . '/finalize', $payload);
    }

    public function saveKehadiranMesyuarat(int $tenderId, array $payload): Response
    {
        return $this->post('/api/tenders/' . $tenderId . '/kehadiran-mesyuarat', $payload);
    }

    public function savePematuhanTeknikal(array $payload): Response
    {
        return $this->post('/api/penilaian-teknikal/pematuhan', $payload);
    }

    public function getRumusanPematuhanTeknikal(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penilaian-teknikal/pematuhan/rumusan');
    }

    public function hantarPematuhanTeknikal(int $tenderId, array $payload = []): Response
    {
        return $this->post('/api/tenders/' . $tenderId . '/penilaian-teknikal/pematuhan/hantar', $payload);
    }

    public function getSpesifikasiRollup(int $tenderId, string $checklistItemUuid): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penilaian-teknikal/spesifikasi/' . $checklistItemUuid . '/rollup');
    }

    public function getSpesifikasiDetail(int $tenderId, string $checklistItemUuid, int $vendorId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penilaian-teknikal/spesifikasi/' . $checklistItemUuid . '/vendor/' . $vendorId);
    }

    public function saveSpesifikasiTeknikal(array $payload): Response
    {
        return $this->post('/api/penilaian-teknikal/spesifikasi', $payload);
    }

    public function confirmSpesifikasiTeknikal(array $payload): Response
    {
        return $this->post('/api/penilaian-teknikal/spesifikasi/sahkan', $payload);
    }

    public function getBorangEvaluations(int $tenderId, string $checklistItemUuid): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penilaian-teknikal/borang/' . $checklistItemUuid);
    }

    public function saveBorangTeknikal(array $payload): Response
    {
        return $this->post('/api/penilaian-teknikal/borang', $payload);
    }

    public function getRumusanPenilaianTeknikal(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penilaian-teknikal/rumusan');
    }

    public function getLaporanTeknikal(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/penilaian-teknikal/laporan');
    }

    public function saveDrafLaporanTeknikal(array $payload): Response
    {
        return $this->post('/api/penilaian-teknikal/laporan/draf', $payload);
    }

    public function hantarPenilaianTeknikal(array $payload): Response
    {
        return $this->post('/api/penilaian-teknikal/hantar', $payload);
    }

    public function hantarPenilaianTeknikalKerja(array $payload): Response
    {
        return $this->post('/api/penilaian-teknikal-kerja/hantar', $payload);
    }

    // ─────────────────────────────────────────────────────────────────
    // Sesi penilaian langsung (akuan, tempahan baris, log aktiviti).
    // $jenis: open = Jawatankuasa Pembuka, tech = Teknikal, fin = Kewangan.
    // ─────────────────────────────────────────────────────────────────

    public function getEvaluationSession(int $tenderId, string $jenis, int $actingUserId): Response
    {
        return $this->get($this->evaluationPath($tenderId, $jenis, 'session'), [
            'acting_user_id' => $actingUserId,
        ]);
    }

    public function storeEvaluationDeclaration(int $tenderId, string $jenis, array $payload): Response
    {
        return $this->post($this->evaluationPath($tenderId, $jenis, 'declaration'), $payload);
    }

    public function acquireEvaluationLock(int $tenderId, string $jenis, array $payload): Response
    {
        return $this->post($this->evaluationPath($tenderId, $jenis, 'lock'), $payload);
    }

    public function releaseEvaluationLock(int $tenderId, string $jenis, array $payload): Response
    {
        return $this->post($this->evaluationPath($tenderId, $jenis, 'lock/release'), $payload);
    }

    public function completeEvaluationRows(int $tenderId, string $jenis, array $payload): Response
    {
        return $this->post($this->evaluationPath($tenderId, $jenis, 'rows/complete'), $payload);
    }

    public function getEvaluationLocks(int $tenderId, string $jenis, ?string $checklistItemUuid = null): Response
    {
        return $this->get(
            $this->evaluationPath($tenderId, $jenis, 'locks'),
            $checklistItemUuid ? ['checklist_item_uuid' => $checklistItemUuid] : []
        );
    }

    public function storeEvaluationLog(int $tenderId, string $jenis, array $payload): Response
    {
        return $this->post($this->evaluationPath($tenderId, $jenis, 'log'), $payload);
    }

    protected function evaluationPath(int $tenderId, string $jenis, string $suffix): string
    {
        return '/api/tenders/' . $tenderId . '/evaluation/' . $jenis . '/' . $suffix;
    }

    public function getSstPembekal(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/sst/pembekal');
    }

    public function getSstSenaraiSurat(int $tenderId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/sst/surat');
    }

    public function getSst(int $tenderId, int $vendorId): Response
    {
        return $this->get('/api/tenders/' . $tenderId . '/sst/vendor/' . $vendorId);
    }

    public function simpanSst(array $payload): Response
    {
        return $this->post('/api/sst/simpan', $payload);
    }

    public function hantarSst(array $payload): Response
    {
        return $this->post('/api/sst/hantar', $payload);
    }

    public function listPembelianTerus(array $query = []): Response
    {
        return $this->get('/api/pembelian-terus', $query);
    }

    public function getPembelianTerus(int $tenderId): Response
    {
        return $this->get('/api/pembelian-terus/' . $tenderId);
    }

    public function createPembelianTerus(array $payload): Response
    {
        return $this->post('/api/pembelian-terus', $payload);
    }

    public function updatePembelianTerus(int $tenderId, array $payload): Response
    {
        return $this->request('put', '/api/pembelian-terus/' . $tenderId, ['json' => $payload]);
    }

    public function publishPembelianTerus(int $tenderId): Response
    {
        return $this->post('/api/pembelian-terus/' . $tenderId . '/publish');
    }

    public function getPembelianTerusOffers(int $tenderId): Response
    {
        return $this->get('/api/pembelian-terus/' . $tenderId . '/offers');
    }

    public function submitPembelianTerusOffer(int $tenderId, array $payload, array $files = []): Response
    {
        return $this->postMultipart('/api/pembelian-terus/' . $tenderId . '/offers', $payload, $files);
    }

    public function cutoffPembelianTerus(int $tenderId, array $payload): Response
    {
        return $this->post('/api/pembelian-terus/' . $tenderId . '/cutoff', $payload);
    }

    public function selectPembelianTerusWinner(int $tenderId, array $payload): Response
    {
        return $this->post('/api/pembelian-terus/' . $tenderId . '/select-winner', $payload);
    }

    public function keputusanPembelianTerus(int $tenderId, array $payload): Response
    {
        return $this->post('/api/pembelian-terus/' . $tenderId . '/company-decision', $payload);
    }

    public function listLantikanTerus(array $query = []): Response
    {
        return $this->get('/api/lantikan-terus', $query);
    }

    public function getLantikanTerus(int $tenderId): Response
    {
        return $this->get('/api/lantikan-terus/' . $tenderId);
    }

    public function createLantikanTerus(array $payload, array $files = []): Response
    {
        $files = array_filter($files);
        $payload = $this->embedLantikanBqFile($payload, $files);

        return $this->request('post', '/api/lantikan-terus', ['json' => $payload]);
    }

    public function updateLantikanTerus(int $tenderId, array $payload, array $files = []): Response
    {
        $files = array_filter($files);
        $payload = $this->embedLantikanBqFile($payload, $files);

        // Always PUT JSON — deployed STOS may not expose POST /lantikan-terus/{id}.
        // BQ file (if any) is embedded as base64 in the JSON body.
        $url = $this->baseUrl . '/api/lantikan-terus/' . $tenderId;

        Log::info('STOS Lantikan Terus update', [
            'method' => 'PUT',
            'url' => $url,
            'has_bq' => isset($payload['dokumen_bq_base64']),
            'action' => $payload['action'] ?? null,
        ]);

        return self::http()
            ->timeout(120)
            ->asJson()
            ->acceptJson()
            ->put($url, $payload);
    }

    /**
     * Embed uploaded BQ into JSON payload (works with PUT; avoids multipart POST).
     *
     * @param  array<string, mixed>  $payload
     * @param  array<string, \Illuminate\Http\UploadedFile|null>  $files
     * @return array<string, mixed>
     */
    protected function embedLantikanBqFile(array $payload, array $files): array
    {
        $file = $files['dokumen_bq'] ?? null;
        if (! $file) {
            return $payload;
        }

        $payload['dokumen_bq_base64'] = base64_encode((string) file_get_contents($file->getRealPath()));
        $payload['dokumen_bq_filename'] = $file->getClientOriginalName();
        $payload['dokumen_bq_mime'] = $file->getMimeType() ?: 'application/octet-stream';

        return $payload;
    }

    public function publishLantikanTerus(int $tenderId): Response
    {
        return $this->post('/api/lantikan-terus/' . $tenderId . '/publish');
    }

    public function getLantikanTerusOffers(int $tenderId): Response
    {
        return $this->get('/api/lantikan-terus/' . $tenderId . '/offers');
    }

    public function submitLantikanTerusOffer(int $tenderId, array $payload, array $files = []): Response
    {
        return $this->postMultipart('/api/lantikan-terus/' . $tenderId . '/offers', $payload, $files);
    }

    public function cutoffLantikanTerus(int $tenderId, array $payload): Response
    {
        return $this->post('/api/lantikan-terus/' . $tenderId . '/cutoff', $payload);
    }

    public function selectLantikanTerusWinner(int $tenderId, array $payload): Response
    {
        return $this->post('/api/lantikan-terus/' . $tenderId . '/select-winner', $payload);
    }

    public function keputusanLantikanTerus(int $tenderId, array $payload): Response
    {
        return $this->post('/api/lantikan-terus/' . $tenderId . '/company-decision', $payload);
    }

    public function getSuratNiatPembekals(int $tenderId): Response
    {
        return $this->get('/api/surat-niat/' . $tenderId . '/pembekals');
    }

    public function saveSuratNiatPembekals(int $tenderId, array $payload): Response
    {
        return $this->post('/api/surat-niat/' . $tenderId . '/pembekals', $payload);
    }

    public function listSuratNiat(int $tenderId): Response
    {
        return $this->get('/api/surat-niat/' . $tenderId . '/surat');
    }

    public function generateSuratNiat(int $tenderId, array $payload): Response
    {
        return $this->post('/api/surat-niat/' . $tenderId . '/surat', $payload);
    }

    public function updateSuratNiat(int $suratId, array $payload): Response
    {
        return $this->request('put', '/api/surat-niat/surat/' . $suratId, ['json' => $payload]);
    }

    public function deleteSuratNiat(int $suratId): Response
    {
        return self::http()->delete($this->baseUrl . '/api/surat-niat/surat/' . $suratId);
    }

    public function downloadSuratNiat(int $suratId): Response
    {
        return self::http()->get($this->baseUrl . '/api/surat-niat/surat/' . $suratId . '/download');
    }

    public function hantarSuratNiat(int $tenderId): Response
    {
        return $this->post('/api/surat-niat/' . $tenderId . '/hantar');
    }

    /**
     * POST with multipart/form-data (fields + uploaded files).
     *
     * Pass nested field arrays as-is (do not pre-flatten bracket keys) so Laravel/Guzzle
     * encodes them once. Pre-flattening broke scalar fields like `name` on the STOS API.
     *
     * @param  array<string, mixed>  $fields
     * @param  array<string, \Illuminate\Http\UploadedFile|null>  $files
     */
    public function postMultipart(string $path, array $fields = [], array $files = []): Response
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('STOS backend is not configured. Set STOS_BACKEND_URL and STOS_BACKEND_API_KEY in .env');
        }

        $url = $this->baseUrl . '/' . ltrim($path, '/');
        $client = self::http();
        $hasFiles = false;

        foreach ($files as $name => $file) {
            if (! $file) {
                continue;
            }

            $pathOnDisk = $file->getRealPath();
            if (! $pathOnDisk || ! is_readable($pathOnDisk)) {
                continue;
            }

            $contents = file_get_contents($pathOnDisk);
            if ($contents === false) {
                continue;
            }

            $hasFiles = true;
            $client = $client->attach(
                $name,
                $contents,
                $file->getClientOriginalName() ?: 'upload.bin'
            );
        }

        $payload = $this->stringifyMultipartScalars($fields);

        // attach() forces multipart. Nested arrays (e.g. offer_items[0][item_id]) are not
        // valid multipart parts and trigger Guzzle "A content key is required".
        // Flatten only when files are present; without files, form-urlencoded nested arrays work.
        if ($hasFiles) {
            $payload = $this->flattenMultipartFields($payload);
        }

        try {
            return $client->post($url, $payload);
        } catch (\Throwable $e) {
            Log::error('STOS API multipart request failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Keep nested arrays for Laravel multipart encoding, but cast scalars to string
     * (and bools to 1/0) so PHP form parsing on the API side is consistent.
     *
     * @param  array<string, mixed>  $fields
     * @return array<string, mixed>
     */
    protected function stringifyMultipartScalars(array $fields): array
    {
        $out = [];

        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $out[$key] = $this->stringifyMultipartScalars($value);
                continue;
            }

            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                $out[$key] = $value ? '1' : '0';
                continue;
            }

            $out[$key] = is_scalar($value) ? (string) $value : $value;
        }

        return $out;
    }

    /**
     * @deprecated Prefer stringifyMultipartScalars — kept for any callers that still flatten.
     *
     * @param  array<string, mixed>  $fields
     * @return array<string, string>
     */
    protected function flattenMultipartFields(array $fields, string $prefix = ''): array
    {
        $flat = [];

        foreach ($fields as $key => $value) {
            $name = $prefix === '' ? (string) $key : $prefix . '[' . $key . ']';

            if (is_array($value)) {
                $flat = array_merge($flat, $this->flattenMultipartFields($value, $name));
                continue;
            }

            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                $flat[$name] = $value ? '1' : '0';
                continue;
            }

            $flat[$name] = (string) $value;
        }

        return $flat;
    }

    protected function request(string $method, string $path, array $options = []): Response
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('STOS backend is not configured. Set STOS_BACKEND_URL and STOS_BACKEND_API_KEY in .env');
        }

        $url = $this->baseUrl . '/' . ltrim($path, '/');

        $client = self::http();

        try {
            if ($method === 'get') {
                return $client->acceptJson()->get($url, $options['query'] ?? []);
            }

            if ($method === 'put') {
                return $client->asJson()->acceptJson()->put($url, $options['json'] ?? []);
            }

            return $client->asJson()->acceptJson()->post($url, $options['json'] ?? []);
        } catch (\Throwable $e) {
            Log::error('STOS API request failed', [
                'method' => strtoupper($method),
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
