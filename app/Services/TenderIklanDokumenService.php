<?php

namespace App\Services;

use App\Models\TenderIklanDokumen;
use App\Tender;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TenderIklanDokumenService
{
    /** @var list<string> */
    private const EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg'];

    private const MAX_KILOBYTES = 20480;

    /**
     * Replace the tender's iklan documents with the submitted rows.
     * A row that is left out of the request is deleted.
     *
     * @return list<array{id: int, name: string, original_name: ?string, download_url: string}>
     */
    public function sync(Tender $tender, Request $request): array
    {
        $rows = (array) $request->input('iklan_dokumen', []);
        $keptIds = [];
        $sort = 0;

        foreach ($rows as $index => $row) {
            if (! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
            $file = $request->file("iklan_dokumen.{$index}.file");
            $file = $file instanceof UploadedFile ? $file : null;

            if ($name === '' && $file === null && $id === null) {
                continue;
            }

            $existing = $id
                ? TenderIklanDokumen::query()->where('tender_id', $tender->id)->whereKey($id)->first()
                : null;

            if ($name === '') {
                throw ValidationException::withMessages([
                    "iklan_dokumen.{$index}.name" => 'Sila isi nama dokumen.',
                ]);
            }

            if ($existing === null && $file === null) {
                throw ValidationException::withMessages([
                    "iklan_dokumen.{$index}.file" => 'Sila muat naik dokumen.',
                ]);
            }

            $stored = $file ? $this->storeFile($file, $tender->id) : null;

            if ($existing) {
                if ($stored !== null) {
                    $this->deleteStoredFile($existing->path);
                    $existing->fill($stored);
                }
                $existing->name = $name;
                $existing->sort_order = $sort;
                $existing->save();
                $keptIds[] = $existing->id;
            } else {
                $created = TenderIklanDokumen::query()->create([
                    'tender_id' => $tender->id,
                    'name' => $name,
                    'sort_order' => $sort,
                    'uploaded_by' => auth()->id(),
                ] + ($stored ?? []));
                $keptIds[] = $created->id;
            }

            $sort++;
        }

        $removed = TenderIklanDokumen::query()
            ->where('tender_id', $tender->id)
            ->when($keptIds !== [], fn ($query) => $query->whereNotIn('id', $keptIds))
            ->get();

        foreach ($removed as $document) {
            $this->deleteStoredFile($document->path);
            $document->delete();
        }

        return $this->rowsFor($tender);
    }

    /**
     * @return list<array{id: int, name: string, original_name: ?string, download_url: string}>
     */
    public function rowsFor(Tender $tender): array
    {
        return TenderIklanDokumen::query()
            ->where('tender_id', $tender->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (TenderIklanDokumen $document) => [
                'id' => $document->id,
                'name' => $document->name,
                'original_name' => $document->original_name,
                'download_url' => route('tenderIklanDokumen.download', [$tender->id, $document->id]),
            ])
            ->all();
    }

    /**
     * @return array{original_name: string, path: string, mime: ?string, size: int, uploaded_by: ?int}
     */
    private function storeFile(UploadedFile $file, int $tenderId): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, self::EXTENSIONS, true)) {
            throw ValidationException::withMessages([
                'iklan_dokumen' => 'Jenis fail tidak dibenarkan. Gunakan PDF, Word, Excel atau imej.',
            ]);
        }

        if ($file->getSize() > self::MAX_KILOBYTES * 1024) {
            throw ValidationException::withMessages([
                'iklan_dokumen' => 'Saiz fail melebihi 20MB.',
            ]);
        }

        $filename = Str::uuid() . '.' . $extension;
        $relative = $file->storeAs('tender-iklan-dokumen/' . $tenderId, $filename, 'local');

        return [
            'original_name' => $file->getClientOriginalName(),
            'path' => $relative,
            'mime' => $file->getClientMimeType(),
            'size' => (int) $file->getSize(),
            'uploaded_by' => auth()->id(),
        ];
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path && Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }
}
