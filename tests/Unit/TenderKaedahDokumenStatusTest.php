<?php

namespace Tests\Unit;

use App\Models\Ref\RefKaedahDokumen;
use App\Support\PenyediaanIklanAccess;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\User;
use Mockery;
use Tests\TestCase;

class TenderKaedahDokumenStatusTest extends TestCase
{
    public function test_online_and_manual_skip_to_penyediaan_iklan(): void
    {
        $this->assertSame(
            TenderProcessStatus::penyediaanIklanListStatus(),
            TenderProcessStatus::statusAfterCiptaTender($this->kaedah(true))
        );
        $this->assertSame(4, TenderProcessStatus::statusAfterCiptaTender($this->kaedah(true)));
    }

    public function test_online_penilaian_stays_on_cipta_tender(): void
    {
        $this->assertSame(
            TenderProcessStatus::CIPTA_TENDER,
            TenderProcessStatus::statusAfterCiptaTender($this->kaedah(false))
        );
        $this->assertSame(1, TenderProcessStatus::statusAfterCiptaTender($this->kaedah(false)));
    }

    public function test_unknown_kaedah_does_not_skip(): void
    {
        $this->assertSame(TenderProcessStatus::CIPTA_TENDER, TenderProcessStatus::statusAfterCiptaTender(null));
    }

    public function test_bayaran_dokumen_online_bypasses_payment(): void
    {
        $online = new RefKaedahDokumen();
        $online->code = 'online';
        $online->skips_to_penyediaan_iklan = true;

        $manual = new RefKaedahDokumen();
        $manual->code = 'manual';
        $manual->skips_to_penyediaan_iklan = true;

        $tenderOnline = new Tender();
        $tenderOnline->setRelation('kaedahDokumen', $online);
        $this->assertTrue($tenderOnline->isBayaranDokumenOnline());
        $this->assertTrue($tenderOnline->shouldBypassDokumenPayment());
        $this->assertSame('Beli Dokumen', $tenderOnline->vendorDokumenActionLabel());

        $tenderManual = new Tender();
        $tenderManual->setRelation('kaedahDokumen', $manual);
        $this->assertTrue($tenderManual->isIklanSahajaManual());
        $this->assertTrue($tenderManual->shouldBypassDokumenPayment());
        $this->assertSame('Tambah ke Senarai', $tenderManual->vendorDokumenActionLabel());
    }

    public function test_attend_visits_passes_when_no_required_lawatan(): void
    {
        $tender = new Tender();
        $tender->setRelation('siteVisits', collect([
            (object) ['id' => 1, 'required' => false],
        ]));

        $this->assertFalse($tender->hasRequiredSiteVisits());
        $this->assertTrue($tender->attendVisits(7));
    }

    public function test_pemilik_projek_can_prepare_iklan_only_for_bypass_kaedah_in_their_agency(): void
    {
        $pemilik = $this->userDouble(seesAll: false, pemilik: true, organizationUnitId: 9, id: 3);
        $urusetia = $this->userDouble(seesAll: true, pemilik: false, organizationUnitId: 1, id: 8);

        $manual = $this->tenderDouble(4, true, 9, 3);
        $penilaian = $this->tenderDouble(4, false, 9, 3);
        $otherAgency = $this->tenderDouble(4, true, 4, 3);

        $this->assertTrue(PenyediaanIklanAccess::canOpenMenu($pemilik));
        $this->assertTrue(PenyediaanIklanAccess::canManage($pemilik, $manual));
        $this->assertFalse(PenyediaanIklanAccess::canManage($pemilik, $penilaian));
        $this->assertFalse(PenyediaanIklanAccess::canManage($pemilik, $otherAgency));

        $this->assertTrue(PenyediaanIklanAccess::canManage($urusetia, $penilaian));
        $this->assertTrue(PenyediaanIklanAccess::canManage($urusetia, $otherAgency));
    }

    private function kaedah(bool $skips): RefKaedahDokumen
    {
        $row = new RefKaedahDokumen();
        $row->skips_to_penyediaan_iklan = $skips;

        return $row;
    }

    private function userDouble(bool $seesAll, bool $pemilik, int $organizationUnitId, int $id): User
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('canAccessMenu')->with('Advertisement:list')->andReturn($seesAll);
        $user->shouldReceive('hasRole')->with('Agency User')->andReturn($pemilik);
        $user->shouldReceive('can')->with('Tender:execute')->andReturn(false);
        $user->organization_unit_id = $organizationUnitId;
        $user->id = $id;

        return $user;
    }

    private function tenderDouble(int $status, bool $skips, int $organizationUnitId, int $creatorId): Tender
    {
        $tender = new Tender();
        $tender->status_process_id = $status;
        $tender->organization_unit_id = $organizationUnitId;
        $tender->creator_id = $creatorId;
        $tender->setRelation('kaedahDokumen', $this->kaedah($skips));

        return $tender;
    }
}
