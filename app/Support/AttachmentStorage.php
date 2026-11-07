<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Document storage.
 * Local and staging stay on the app disk. Production sends files to the file server.
 */
class AttachmentStorage
{
    public static function isRemote(): bool
    {
        return config('filesystems.disks.attachments.driver') !== 'local';
    }

    public static function diskName(string $localDisk = 'public'): string
    {
        return self::isRemote() ? 'attachments' : $localDisk;
    }

    public static function normalize(string $path): string
    {
        $path = str_replace('\\', '/', trim($path));
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            return '';
        }

        return $path;
    }

    public static function storeAs(UploadedFile $file, string $directory, string $name, string $localDisk = 'public'): string|false
    {
        return $file->storeAs($directory, $name, self::diskName($localDisk));
    }

    /**
     * Same as UploadedFile::move() on local/staging.
     * Production writes the file to the attachment server instead.
     */
    public static function moveUploadedFile(UploadedFile $file, string $directory, string $name): void
    {
        if (! self::isRemote()) {
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $file->move($directory, $name);

            return;
        }

        $relativeDir = trim(self::relativeFromAbsolute($directory), '/');
        $stored = $file->storeAs($relativeDir, $name, 'attachments');
        if ($stored === false) {
            throw new \RuntimeException('Gagal menyimpan lampiran ke pelayan fail.');
        }
    }

    /**
     * After a local write (for example a PDF watermark), copy the file to the
     * production file server and remove the app-server copy.
     */
    public static function pushPublicFile(string $absolutePath): void
    {
        if (! self::isRemote() || ! is_file($absolutePath)) {
            return;
        }

        $relative = self::normalize(self::relativeFromAbsolute($absolutePath));
        if ($relative === '') {
            return;
        }

        $ok = Storage::disk('attachments')->put($relative, file_get_contents($absolutePath));
        if ($ok === false) {
            throw new \RuntimeException('Gagal menyimpan lampiran ke pelayan fail.');
        }

        @unlink($absolutePath);
    }

    public static function exists(string $path): bool
    {
        return self::locateDisk($path) !== null || self::publicFile($path) !== null;
    }

    public static function delete(?string $path): void
    {
        $path = self::normalize((string) $path);
        if ($path === '') {
            return;
        }

        foreach (['attachments', 'public', 'local'] as $disk) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            } catch (\Throwable) {
            }
        }

        $publicFile = self::publicFile($path);
        if ($publicFile) {
            @unlink($publicFile);
        }
    }

    public static function copy(string $from, string $to): void
    {
        $from = self::normalize($from);
        $to = self::normalize($to);
        $source = self::locateDisk($from);
        if ($from === '' || $to === '' || $source === null) {
            return;
        }

        if (self::isRemote()) {
            if ($source === 'attachments') {
                Storage::disk('attachments')->copy($from, $to);

                return;
            }

            Storage::disk('attachments')->put($to, Storage::disk($source)->get($from));

            return;
        }

        Storage::disk($source)->copy($from, $to);
    }

    public static function locateDisk(string $path): ?string
    {
        $path = self::normalize($path);
        if ($path === '') {
            return null;
        }

        foreach (array_unique([self::diskName(), 'attachments', 'public', 'local']) as $disk) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    return $disk;
                }
            } catch (\Throwable) {
            }
        }

        return null;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|StreamedResponse|null
     */
    public static function responseOrNull(string $path, string $name, ?string $mime = null, bool $forceDownload = false)
    {
        $path = self::normalize($path);
        if ($path === '') {
            return null;
        }

        $name = $name !== '' ? $name : basename($path);
        $disposition = (! $forceDownload && self::shouldInline($name, $mime)) ? 'inline' : 'attachment';
        $headers = [];
        if ($mime) {
            $headers['Content-Type'] = $mime;
        }

        $disk = self::locateDisk($path);
        if ($disk !== null) {
            return Storage::disk($disk)->response($path, $name, $headers, $disposition);
        }

        $publicFile = self::publicFile($path);
        if ($publicFile) {
            $fileMime = $mime ?: (function_exists('mime_content_type') ? @mime_content_type($publicFile) : null) ?: 'application/octet-stream';

            return response()->file($publicFile, [
                'Content-Type' => $fileMime,
                'Content-Disposition' => self::contentDisposition($disposition, $name),
            ]);
        }

        return null;
    }

    public static function browserUrl(string $relative): string
    {
        $relative = self::normalize($relative);
        if ($relative === '') {
            return '#';
        }

        if (! self::isRemote()) {
            return asset($relative);
        }

        return route('attachments.stream', ['path' => $relative]);
    }

    public static function urlForUploadModel(object $upload): ?string
    {
        if (! self::isRemote()) {
            return null;
        }

        $type = (string) ($upload->uploadable_type ?? '');
        $ownerId = $upload->uploadable_id ?? null;
        $id = $upload->id ?? null;
        if (in_array($type, [\App\Tender::class, \App\Models\Tender::class, 'App\Tender', 'App\\Models\\Tender'], true) && $ownerId && $id) {
            return route('tenders.files', ['tender_id' => $ownerId, 'id' => $id]);
        }

        $relative = self::relativeForUpload((string) ($upload->path ?? ''), (string) ($upload->name ?? ''));
        if ($relative === '') {
            return null;
        }

        return route('attachments.stream', ['path' => $relative]);
    }

    public static function relativeForUpload(string $path, string $name): string
    {
        $name = ltrim(str_replace('\\', '/', $name), '/');
        $relativeDir = self::relativeFromAbsolute($path);
        if ($relativeDir === '' || $name === '') {
            return '';
        }

        return self::normalize(rtrim($relativeDir, '/') . '/' . $name);
    }

    public static function relativeFromAbsolute(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $public = rtrim(str_replace('\\', '/', public_path()), '/');
        if ($public !== '' && str_starts_with($path, $public)) {
            return ltrim(substr($path, strlen($public)), '/');
        }

        if (preg_match('#(uploads/.+)$#', $path, $matches)) {
            return ltrim($matches[1], '/');
        }

        return ltrim($path, '/');
    }

    public static function isPublicPrefix(string $path): bool
    {
        $path = self::normalize($path);

        foreach (['uploads/banners/', 'uploads/circulars/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }

    protected static function publicFile(string $path): ?string
    {
        $path = self::normalize($path);
        if ($path === '') {
            return null;
        }

        $candidate = public_path($path);

        return is_file($candidate) ? $candidate : null;
    }

    protected static function shouldInline(string $name, ?string $mime): bool
    {
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $mime = strtolower((string) $mime);

        if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'txt'], true)) {
            return true;
        }

        return str_contains($mime, 'pdf') || str_starts_with($mime, 'image/');
    }

    protected static function contentDisposition(string $type, string $name): string
    {
        $safeAscii = preg_replace('/[^\x20-\x7E]+/', '_', $name) ?: 'Dokumen';
        $safeAscii = str_replace(['"', '\\', "\r", "\n"], ['_', '_', '', ''], $safeAscii);
        $utf8 = rawurlencode(str_replace(["\r", "\n"], '', $name));

        return $type . '; filename="' . $safeAscii . '"; filename*=UTF-8\'\'' . $utf8;
    }
}
