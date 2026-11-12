<?php

namespace App\Support;

/**
 * Document access for SUKSel-stored files.
 * Uses AttachmentStorage so production hits NAS (baru + lama fallback).
 */
class SukselStoredFile
{
    public static function exists(string $path): bool
    {
        $path = self::sanitize($path);
        if ($path === null) {
            return false;
        }

        return AttachmentStorage::exists($path);
    }

    public static function response(string $path, string $name)
    {
        $path = self::sanitize($path);
        abort_unless($path !== null, 404, 'Fail tidak dijumpai pada storan SUKSel.');

        $response = AttachmentStorage::responseOrNull($path, $name);
        abort_unless($response !== null, 404, 'Fail tidak dijumpai pada storan SUKSel.');

        return $response;
    }

    protected static function sanitize(string $path): ?string
    {
        $path = str_replace('\\', '/', trim($path));
        $path = ltrim($path, '/');

        if ($path === '' || str_starts_with($path, '/') || str_contains($path, ':')
            || in_array('..', explode('/', $path), true)) {
            return null;
        }

        return $path;
    }
}
