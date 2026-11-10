<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/** Document access restricted to SUKSel's configured public disk. */
class SukselStoredFile
{
    public static function exists(string $path): bool
    {
        $path = str_replace('\\', '/', $path);
        if ($path === '' || str_starts_with($path, '/') || str_contains($path, ':')
            || in_array('..', explode('/', $path), true)) {
            return false;
        }

        return Storage::disk('public')->exists($path);
    }

    public static function response(string $path, string $name)
    {
        abort_unless(self::exists($path), 404, 'Fail tidak dijumpai pada storan SUKSel.');

        $disposition = strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'pdf' ? 'inline' : 'attachment';

        return Storage::disk('public')->response($path, $name, [], $disposition);
    }
}
