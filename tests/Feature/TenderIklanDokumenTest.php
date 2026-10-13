<?php

namespace Tests\Feature;

use App\Services\TenderIklanDokumenService;
use App\Tender;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TenderIklanDokumenTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sync_stores_keeps_and_removes_iklan_documents(): void
    {
        if (! Schema::hasTable('tender_iklan_dokumens')) {
            $this->markTestSkipped('tender_iklan_dokumens is not migrated.');
        }

        Storage::fake('local');

        $orgId = DB::table('organization_units')->insertGetId([
            'name' => 'Unit Iklan Dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tenderId = DB::table('tenders')->insertGetId([
            'name' => 'Tender Iklan Dokumen',
            'organization_unit_id' => $orgId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tender = Tender::query()->findOrFail($tenderId);
        $service = app(TenderIklanDokumenService::class);

        $created = $service->sync($tender, $this->request([
            0 => ['name' => 'Borang Sebut Harga'],
        ], [
            0 => UploadedFile::fake()->create('borang.pdf', 20, 'application/pdf'),
        ]));

        $this->assertCount(1, $created);
        $this->assertSame('Borang Sebut Harga', $created[0]['name']);
        $this->assertSame(1, $tender->iklanDokumens()->count());

        $kept = $service->sync($tender, $this->request([
            0 => ['id' => $created[0]['id'], 'name' => 'Borang Dikemaskini'],
        ]));

        $this->assertCount(1, $kept);
        $this->assertSame('Borang Dikemaskini', $kept[0]['name']);
        $this->assertSame('borang.pdf', $kept[0]['original_name']);

        $cleared = $service->sync($tender, $this->request([]));

        $this->assertSame([], $cleared);
        $this->assertSame(0, $tender->iklanDokumens()->count());
    }

    private function request(array $rows, array $files = []): Request
    {
        $uploaded = [];
        foreach ($files as $index => $file) {
            $uploaded[$index] = ['file' => $file];
        }

        return Request::create('/', 'POST', [
            'iklan_dokumen_present' => '1',
            'iklan_dokumen' => $rows,
        ], [], [
            'iklan_dokumen' => $uploaded,
        ]);
    }
}
