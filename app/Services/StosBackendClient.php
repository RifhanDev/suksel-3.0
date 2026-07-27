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

    public function getKehadiranMesyuarat(int $tenderId, ?int $meetingId = null): Response
    {
        $query = $meetingId ? ['meeting_id' => $meetingId] : [];

        return $this->get('/api/tenders/' . $tenderId . '/kehadiran-mesyuarat', $query);
    }

    public function saveKehadiranMesyuarat(int $tenderId, array $payload): Response
    {
        return $this->post('/api/tenders/' . $tenderId . '/kehadiran-mesyuarat', $payload);
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
