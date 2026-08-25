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

    public function createTender(array $payload): Response
    {
        return $this->post('/api/tenders', $payload);
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

    public function submitPembelianTerusOffer(int $tenderId, array $payload): Response
    {
        return $this->post('/api/pembelian-terus/' . $tenderId . '/offers', $payload);
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

    public function createLantikanTerus(array $payload): Response
    {
        return $this->post('/api/lantikan-terus', $payload);
    }

    public function updateLantikanTerus(int $tenderId, array $payload): Response
    {
        return $this->request('put', '/api/lantikan-terus/' . $tenderId, ['json' => $payload]);
    }

    public function publishLantikanTerus(int $tenderId): Response
    {
        return $this->post('/api/lantikan-terus/' . $tenderId . '/publish');
    }

    public function getLantikanTerusOffers(int $tenderId): Response
    {
        return $this->get('/api/lantikan-terus/' . $tenderId . '/offers');
    }

    public function submitLantikanTerusOffer(int $tenderId, array $payload): Response
    {
        return $this->post('/api/lantikan-terus/' . $tenderId . '/offers', $payload);
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

    protected function request(string $method, string $path, array $options = []): Response
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('STOS backend is not configured. Set STOS_BACKEND_URL and STOS_BACKEND_API_KEY in .env');
        }

        $url = $this->baseUrl . '/' . ltrim($path, '/');

        $client = self::http();

        try {
            if ($method === 'get') {
                return $client->get($url, $options['query'] ?? []);
            }

            if ($method === 'put') {
                return $client->put($url, $options['json'] ?? []);
            }

            return $client->post($url, $options['json'] ?? []);
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
