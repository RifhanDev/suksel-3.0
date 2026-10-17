<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesTenderFileAccess;
use App\Services\StosBackendClient;
use App\Support\StosStoredFile;
use App\Tender;

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
            'download_api' => 'pengalaman-kerja-files/{uuid}/download',
        ],
        'kerja-dalam-tangan' => [
            'api' => 'kerja-dalam-tangan',
            'collection' => 'dokumens',
            'download_api' => 'kerja-dalam-tangan-files/{uuid}/download',
        ],
    ];

    public function download(Tender $tender, string $type, string $fileUuid)
    {
        $this->assertCanAccessTenderFile($tender);
        $config = self::TYPES[$type] ?? null;
        if (! $config || empty($tender->uuid)) {
            abort(404, 'Fail tidak dijumpai.');
        }

        if ($type === 'pengalaman-kerja') {
            $localDoc = \App\Models\TenderPengalamanKerjaDokumen::where('uuid', $fileUuid)->first();
            if ($localDoc) {
                return StosStoredFile::response([
                    'uuid'          => $localDoc->uuid,
                    'original_name' => $localDoc->original_name,
                    'path'          => $localDoc->path,
                    'mime_type'     => $localDoc->mime_type ?: 'application/pdf',
                ]);
            }
        } elseif ($type === 'kerja-dalam-tangan') {
            $localDoc = \App\Models\TenderKerjaDalamTanganDokumen::where('uuid', $fileUuid)->first();
            if ($localDoc) {
                return StosStoredFile::response([
                    'uuid'          => $localDoc->uuid,
                    'original_name' => $localDoc->original_name,
                    'path'          => $localDoc->path,
                    'mime_type'     => $localDoc->mime_type ?: 'application/pdf',
                ]);
            }
        }

        // Also check TenderVendorFormPayload in case metadata only exists in form payload
        $formKey = match ($type) {
            'pengalaman-kerja' => 'pengalaman_kerja',
            'kerja-dalam-tangan' => 'kerja_dalam_tangan',
            default => null,
        };
        if ($formKey) {
            $payloads = \App\Models\TenderVendorFormPayload::where('tender_id', $tender->id)
                ->where('form_key', $formKey)
                ->get();
            foreach ($payloads as $p) {
                $dokumens = $p->payload['dokumens'] ?? [];
                foreach ($dokumens as $d) {
                    if (($d['uuid'] ?? '') === $fileUuid) {
                        return StosStoredFile::response([
                            'uuid'          => $d['uuid'],
                            'original_name' => $d['original_name'] ?? $d['name'] ?? 'Dokumen',
                            'path'          => $d['path'] ?? '',
                            'mime_type'     => $d['mime_type'] ?? 'application/pdf',
                        ]);
                    }
                }
            }
        }

        $file = $this->findFileMeta($tender->uuid, $config['api'], $config['collection'], $fileUuid);
        if ($file) {
            $file['download_api'] = str_replace('{uuid}', $fileUuid, $config['download_api']);
            return StosStoredFile::response($file);
        }

        abort(404, 'Fail dokumen tidak dijumpai pada storan sistem.');
    }

    /**
     * Rewrite STOS absolute storage URLs to local authenticated proxy routes.
     *
     * @param  Tender  $tender
     * @param  array<int, array<string, mixed>>  $dokumens
     * @return array<int, array<string, mixed>>
     */
    public static function rewriteDokumenUrls(Tender $tender, string $type, array $dokumens): array
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
