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
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $isPdf = ($ext === 'pdf' || str_contains(strtolower($mime), 'pdf'));
        $dispositionType = $isPdf ? 'inline' : 'attachment';
        $disposition = self::contentDisposition($dispositionType, $name);

        $resolved = self::resolveLocalFilePath($path);
        if ($resolved && is_file($resolved)) {
            $resolvedMime = (function_exists('mime_content_type') ? @mime_content_type($resolved) : null) ?: $mime;
            if ($isPdf && ! str_contains(strtolower($resolvedMime), 'pdf')) {
                $resolvedMime = 'application/pdf';
            }

            return response()->file($resolved, [
                'Content-Type' => $resolvedMime,
                'Content-Disposition' => $disposition,
            ]);
        }

        $apiPath = trim((string) ($file['download_api'] ?? ''));
        if ($apiPath !== '' && StosBackendClient::isConfigured()) {
            try {
                $apiResponse = StosBackendClient::http()
                    ->timeout(5)
                    ->withHeaders(['Accept' => '*/*'])
                    ->get(StosBackendClient::apiUrl($apiPath));
                if ($apiResponse->successful()) {
                    $mimeType = (string) ($file['mime_type'] ?? $apiResponse->header('Content-Type') ?? $mime);

                    return new StreamedResponse(function () use ($apiResponse) {
                        echo $apiResponse->body();
                    }, 200, [
                        'Content-Type' => $mimeType,
                        'Content-Disposition' => self::contentDisposition('inline', $name),
                    ]);
                }
            } catch (\Throwable) {
                // Ignore API timeout/connection failure and proceed to 404
            }
        }

        $remoteUrl = trim((string) ($file['url'] ?? ''));
        if ($remoteUrl !== '' && filter_var($remoteUrl, FILTER_VALIDATE_URL)) {
            try {
                $response = StosBackendClient::http()
                    ->timeout(5)
                    ->withHeaders(['Accept' => '*/*'])
                    ->get($remoteUrl);
                if ($response->successful()) {
                    $mimeType = (string) ($file['mime_type'] ?? $response->header('Content-Type') ?? $mime);

                    return new StreamedResponse(function () use ($response) {
                        echo $response->body();
                    }, 200, [
                        'Content-Type' => $mimeType,
                        'Content-Disposition' => self::contentDisposition('inline', $name),
                    ]);
                }
            } catch (\Throwable) {
                // Ignore remote URL failure and proceed to 404
            }
        }

        abort(404, 'Fail dokumen tidak dijumpai pada storan sistem.');
    }

    /**
     * Build a Content-Disposition header that won't throw on non-ASCII / quote-heavy names.
     */
    protected static function contentDisposition(string $type, string $name): string
    {
        $safeAscii = preg_replace('/[^\x20-\x7E]+/', '_', $name) ?: 'Dokumen';
        $safeAscii = str_replace(['"', '\\', "\r", "\n"], ['_', '_', '', ''], $safeAscii);
        $utf8 = rawurlencode(str_replace(["\r", "\n"], '', $name));

        return $type . '; filename="' . $safeAscii . '"; filename*=UTF-8\'\'' . $utf8;
    }

    /**
     * Resolve file path across SUKSel 3.0 server storage disks and candidate folders.
     */
    public static function resolveLocalFilePath(string $path): ?string
    {
        $path = ltrim(trim($path), '/');
        if ($path === '') {
            return null;
        }

        $cleanPath = ltrim(str_replace('public/', '', $path), '/');
        $strippedUploads = preg_replace('#^uploads/#', '', $cleanPath);

        $candidates = [
            storage_path('app/public/' . $cleanPath),
            storage_path('app/public/uploads/' . $cleanPath),
            storage_path('app/public/' . $strippedUploads),
            storage_path('app/' . $cleanPath),
            storage_path('app/uploads/' . $cleanPath),
            storage_path('app/' . $strippedUploads),
            storage_path('app/private/' . $cleanPath),
            storage_path('app/private/uploads/' . $cleanPath),
            storage_path('app/private/' . $strippedUploads),
            public_path('storage/' . $cleanPath),
            public_path('storage/uploads/' . $cleanPath),
            public_path('storage/' . $strippedUploads),
            public_path($cleanPath),
        ];

        foreach ($candidates as $cand) {
            $cand = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $cand);
            if (is_file($cand)) {
                return $cand;
            }
        }

        $stosCand = self::absolutePathOnStosDisk($cleanPath)
            ?: self::absolutePathOnStosDisk('uploads/' . $cleanPath)
            ?: self::absolutePathOnStosDisk($strippedUploads);

        if ($stosCand && is_file($stosCand)) {
            return $stosCand;
        }

        return null;
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
            base_path('../STOS-EPENILAIAN-WEB/storage/app/private'),
            base_path('../stos-epenilaian-web/storage/app/private'),
            base_path('../STOS-EPENILAIAN-WEB/storage/app'),
            base_path('../stos-epenilaian-web/storage/app'),
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
