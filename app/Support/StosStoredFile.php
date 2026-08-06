<?php

namespace App\Support;

use App\Services\StosBackendClient;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StosStoredFile
{
    /**
     * Stream a file that may live on suksel public disk, STOS disk, or STOS HTTP storage.
     *
     * @param  array<string, mixed>  $file
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

        $remoteUrl = trim((string) ($file['url'] ?? ''));
        if ($remoteUrl === '' && $path !== '') {
            $remoteUrl = rtrim((string) config('services.stos_backend.url'), '/') . '/storage/' . $path;
        }

        if ($remoteUrl === '') {
            abort(404, 'Fail tidak dijumpai.');
        }

        $response = StosBackendClient::http()->get($remoteUrl);
        if (! $response->successful()) {
            // Never surface STOS storage 403 as a permission page on suksel.
            abort(404, 'Fail tidak dijumpai.');
        }

        $mimeType = (string) ($file['mime_type'] ?? $response->header('Content-Type') ?? $mime);

        return new StreamedResponse(function () use ($response) {
            echo $response->body();
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
        ]);
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
