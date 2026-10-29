<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * System-wide attachment whitelist: PDF, images, Excel, Word only.
 */
class AllowedAttachment
{
    public const EXTENSIONS = [
        'pdf',
        'png',
        'jpg',
        'jpeg',
        'xls',
        'xlsx',
        'doc',
        'docx',
    ];

    public const MESSAGE = 'Format fail tidak dibenarkan. Hanya PDF, PNG, JPG, JPEG, Excel (xls, xlsx) dan Word (doc, docx).';

    public static function mimesRule(): string
    {
        return 'mimes:' . implode(',', self::EXTENSIONS);
    }

    /**
     * @return list<string>
     */
    public static function rules(bool $required = true, int $maxKb = 10240): array
    {
        return array_values(array_filter([
            $required ? 'required' : 'nullable',
            'file',
            'max:' . $maxKb,
            self::mimesRule(),
        ]));
    }

    public static function acceptAttribute(): string
    {
        return '.' . implode(',.', self::EXTENSIONS);
    }

    public static function isAllowed(UploadedFile $file): bool
    {
        if (! $file->isValid()) {
            return false;
        }

        $validator = Validator::make(
            ['file' => $file],
            ['file' => ['file', self::mimesRule()]]
        );

        return ! $validator->fails();
    }

    /**
     * @throws ValidationException
     */
    public static function assert(UploadedFile $file, string $attribute = 'file'): void
    {
        if (self::isAllowed($file)) {
            return;
        }

        throw ValidationException::withMessages([
            $attribute => self::MESSAGE,
        ]);
    }
}
