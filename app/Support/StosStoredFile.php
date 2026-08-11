<?php

namespace App\Support;

use App\Services\StosBackendClient;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StosStoredFile
{
    /**
     * Stream a file that may live on suksel public disk, STOS disk, STOS API download, or HTTP storage.
     *
     * @param  array<string, mixed>  $file  Optional keys: path, url, original_name, mime_type, download_api
     */
    public static function response(array $file)
    {
        $name = (string) ($file['original_name'] ?? $file['name'] ?? 'Dokumen');
        $path = ltrim((string) ($file['path'] ?? ''), '/');
        $mime = (string) ($file['mime_type'] ?? 'application/octet-stream');

        if ($path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path, $name, [
                'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
            ]);
        }

        $absolute = self::absolutePathOnStosDisk($path);
        if ($absolute) {
            return response()->file($absolute, [
                'Content-Type' => is_file($absolute) ? (mime_content_type($absolute) ?: $mime) : $mime,
                'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
            ]);
        }

        $apiPath = trim((string) ($file['download_api'] ?? ''));
        if ($apiPath !== '') {
            $apiResponse = StosBackendClient::http()
                ->withHeaders(['Accept' => '*/*'])
                ->get(StosBackendClient::apiUrl($apiPath));
            if ($apiResponse->successful()) {
                $mimeType = (string) ($file['mime_type'] ?? $apiResponse->header('Content-Type') ?? $mime);

                return new StreamedResponse(function () use ($apiResponse) {
                    echo $apiResponse->body();
                }, 200, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
                ]);
            }
        }

        $remoteUrl = trim((string) ($file['url'] ?? ''));
        if ($remoteUrl === '' && $path !== '') {
            $remoteUrl = rtrim((string) config('services.stos_backend.url'), '/') . '/storage/' . $path;
        }

        if ($remoteUrl !== '') {
            $response = StosBackendClient::http()
                ->withHeaders(['Accept' => '*/*'])
                ->get($remoteUrl);
            if ($response->successful()) {
                $mimeType = (string) ($file['mime_type'] ?? $response->header('Content-Type') ?? $mime);

                return new StreamedResponse(function () use ($response) {
                    echo $response->body();
                }, 200, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
                ]);
            }
        }

        abort(404, 'Fail tidak dijumpai.');
    }

    protected static function absolutePathOnStosDisk(string $path): ?string
    {
        if ($path === '') {
            return null;
        }

        $roots = array_filter([
            config('services.stos_backend.storage_path'),
            base_path('../STOS-EPENILAIAN-WEB/storage/app/public'),
            base_path('../stos-epenilaian-web/storage/app/public'),
        ]);

        foreach ($roots as $root) {
            $root = rtrim(str_replace('\\', '/', (string) $root), '/');
            if ($root === '' || ! is_dir($root)) {
                continue;
            }

            $full = $root . '/' . $path;
            if (is_file($full)) {
                return $full;
            }
        }

        return null;
    }
}
