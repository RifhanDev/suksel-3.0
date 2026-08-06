<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesTenderFileAccess;
use App\Services\StosBackendClient;
use App\Tender;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Proxies vendor online-form files stored on STOS so browsers never hit the STOS host directly.
 */
class StosFormFileController extends Controller
{
    use AuthorizesTenderFileAccess;

    private const TYPES = [
        'pengalaman-kerja' => [
            'api' => 'pengalaman-kerja',
            'collection' => 'dokumens',
        ],
        'kerja-dalam-tangan' => [
            'api' => 'kerja-dalam-tangan',
            'collection' => 'dokumens',
        ],
    ];

    public function download(Tender $tender, string $type, string $fileUuid)
    {
        $this->assertCanAccessTenderFile($tender);
        $config = self::TYPES[$type] ?? null;
        if (! $config || empty($tender->uuid)) {
            abort(404, 'Fail tidak dijumpai.');
        }

        $file = $this->findFileMeta($tender->uuid, $config['api'], $config['collection'], $fileUuid);
        if (! $file) {
            abort(404, 'Fail tidak dijumpai.');
        }

        $name = (string) ($file['original_name'] ?? $file['name'] ?? 'Dokumen');
        $path = ltrim((string) ($file['path'] ?? ''), '/');

        if ($path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path, $name, [
                'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
            ]);
        }

        $remoteUrl = trim((string) ($file['url'] ?? ''));
        if ($remoteUrl === '' && $path !== '') {
            $remoteUrl = rtrim((string) config('services.stos_backend.url'), '/') . '/storage/' . $path;
        }

        if ($remoteUrl === '') {
            abort(404, 'Fail tidak dijumpai.');
        }

        $response = StosBackendClient::http()->get($remoteUrl);
        if (! $response->successful()) {
            abort($response->status() === 403 ? 403 : 404, 'Fail tidak dapat dimuat turun.');
        }

        $mimeType = (string) ($file['mime_type'] ?? $response->header('Content-Type') ?? 'application/octet-stream');

        return new StreamedResponse(function () use ($response) {
            echo $response->body();
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
        ]);
    }

    /**
     * Rewrite STOS absolute storage URLs to local authenticated proxy routes.
     *
     * @param  \App\Tender|\App\Models\Tender  $tender
     * @param  array<int, array<string, mixed>>  $dokumens
     * @return array<int, array<string, mixed>>
     */
    public static function rewriteDokumenUrls(object $tender, string $type, array $dokumens): array
    {
        if (! isset(self::TYPES[$type])) {
            return $dokumens;
        }

        return collect($dokumens)->map(function ($doc) use ($tender, $type) {
            if (! is_array($doc) || empty($doc['uuid'])) {
                return $doc;
            }

            $doc['url'] = route('stosFormFile.download', [
                'tender' => $tender->id,
                'type' => $type,
                'fileUuid' => $doc['uuid'],
            ]);

            return $doc;
        })->values()->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function findFileMeta(string $tenderUuid, string $api, string $collection, string $fileUuid): ?array
    {
        $stos = app(StosBackendClient::class);
        if (! $stos->isConfigured()) {
            return null;
        }

        $response = $stos->get('/api/' . $api . '/' . $tenderUuid);
        if (! $response->successful()) {
            return null;
        }

        $data = $response->json('data') ?? [];
        foreach ($data[$collection] ?? [] as $file) {
            if (is_array($file) && ($file['uuid'] ?? '') === $fileUuid) {
                return $file;
            }
        }

        return null;
    }
}
