<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesTenderFileAccess;
use App\Services\StosBackendClient;
use App\Support\StosStoredFile;
use App\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenderChecklistFileController extends Controller
{
    use AuthorizesTenderFileAccess;

    public function download(Request $request, Tender $tender, string $section, string $fileUuid)
    {
        $this->assertCanAccessTenderFile($tender);

        $localFile = $this->findLocalFile($section, $fileUuid, $tender->id);
        if ($localFile) {
            $streamed = $this->streamLocalFileOrNull($localFile);
            if ($streamed) {
                return $streamed;
            }

            // DB row exists (often shared with STOS) but file is only on STOS disk/API.
            return StosStoredFile::response([
                'uuid' => $fileUuid,
                'original_name' => $localFile->original_name ?? 'Dokumen',
                'path' => $localFile->path ?? null,
                'mime_type' => $localFile->mime_type ?? null,
                'download_api' => $this->downloadApiForSection($section, $fileUuid),
            ]);
        }

        $remoteFile = $this->findRemoteFile($tender, $section, $fileUuid);
        if ($remoteFile) {
            return StosStoredFile::response($remoteFile);
        }

        abort(404, 'Fail tidak dijumpai.');
    }

    /**
     * @return object|null
     */
    protected function findLocalFile(string $section, string $fileUuid, int $tenderId)
    {
        return match ($section) {
            'technical' => DB::table('technical_checklist_files as f')
                ->join('technical_checklist_items as i', 'i.id', '=', 'f.technical_checklist_item_id')
                ->join('technical_checklist_headers as h', 'h.id', '=', 'i.technical_checklist_header_id')
                ->where('f.uuid', $fileUuid)
                ->where('h.tender_id', $tenderId)
                ->select('f.original_name', 'f.path', 'f.mime_type')
                ->first(),
            'financial' => DB::table('financial_checklist_files as f')
                ->join('financial_checklist_items as i', 'i.id', '=', 'f.financial_checklist_item_id')
                ->join('financial_checklist_headers as h', 'h.id', '=', 'i.financial_checklist_header_id')
                ->where('f.uuid', $fileUuid)
                ->where('h.tender_id', $tenderId)
                ->select('f.original_name', 'f.path', 'f.mime_type')
                ->first(),
            'kewangan_kerja' => DB::table('kewangan_kerja_files as f')
                ->join('kewangan_kerja_items as i', 'i.id', '=', 'f.kewangan_kerja_item_id')
                ->join('kewangan_kerja_headers as h', 'h.id', '=', 'i.kewangan_kerja_header_id')
                ->where('f.uuid', $fileUuid)
                ->where('h.tender_id', $tenderId)
                ->select('f.original_name', 'f.path', 'f.mime_type')
                ->first(),
            default => null,
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function findRemoteFile(Tender $tender, string $section, string $fileUuid): ?array
    {
        $downloadApi = $this->downloadApiForSection($section, $fileUuid);
        if ($downloadApi === null) {
            return null;
        }

        if (empty($tender->uuid)) {
            return [
                'uuid' => $fileUuid,
                'original_name' => 'Dokumen',
                'download_api' => $downloadApi,
            ];
        }

        $stos = app(StosBackendClient::class);
        if (! $stos->isConfigured()) {
            return [
                'uuid' => $fileUuid,
                'original_name' => 'Dokumen',
                'download_api' => $downloadApi,
            ];
        }

        $path = match ($section) {
            'technical' => '/api/technical-checklists/' . $tender->uuid,
            'financial' => '/api/financial-checklists/' . $tender->uuid,
            'kewangan_kerja' => '/api/kewangan-kerja/' . $tender->uuid,
            default => null,
        };

        if ($path !== null) {
            $response = $stos->get($path);
            if ($response->successful()) {
                $data = $response->json('data') ?? [];

                foreach ($data['files'] ?? [] as $file) {
                    if (is_array($file) && ($file['uuid'] ?? '') === $fileUuid) {
                        $file['download_api'] = $downloadApi;

                        return $file;
                    }
                }

                foreach ($data['items'] ?? [] as $item) {
                    if (! is_array($item)) {
                        continue;
                    }

                    foreach ($item['files'] ?? [] as $file) {
                        if (is_array($file) && ($file['uuid'] ?? '') === $fileUuid) {
                            $file['download_api'] = $downloadApi;

                            return $file;
                        }
                    }
                }
            }
        }

        return [
            'uuid' => $fileUuid,
            'original_name' => 'Dokumen',
            'download_api' => $downloadApi,
        ];
    }

    protected function downloadApiForSection(string $section, string $fileUuid): ?string
    {
        return match ($section) {
            'technical' => 'technical-checklist-files/' . $fileUuid . '/download',
            'financial' => 'financial-checklist-files/' . $fileUuid . '/download',
            default => null,
        };
    }

    /**
     * @param  object{original_name?: string, path?: string, mime_type?: string|null}  $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse|null
     */
    protected function streamLocalFileOrNull(object $file)
    {
        $path = ltrim((string) ($file->path ?? ''), '/');
        $name = (string) ($file->original_name ?? 'Dokumen');

        if ($path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path, $name, [
                'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
            ]);
        }

        if ($path !== '') {
            $publicPath = public_path($path);
            if (is_file($publicPath)) {
                return response()->file($publicPath, [
                    'Content-Type' => $file->mime_type ?? mime_content_type($publicPath) ?: 'application/octet-stream',
                    'Content-Disposition' => 'inline; filename="' . addslashes($name) . '"',
                ]);
            }
        }

        return null;
    }
}
