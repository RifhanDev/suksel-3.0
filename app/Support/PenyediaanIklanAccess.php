<?php

namespace App\Support;

use App\Tender;
use App\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Penyediaan iklan is normally urusetia (Advertisement:list).
 * Pemilik projek may also prepare it, but only for kaedah that skip
 * pelantikan jawatankuasa and spesifikasi teknikal, and only inside
 * their own agency.
 */
final class PenyediaanIklanAccess
{
    public static function seesAll(?User $user): bool
    {
        return $user !== null && $user->canAccessMenu('Advertisement:list');
    }

    public static function isPemilikProjek(?User $user): bool
    {
        if ($user === null || self::seesAll($user)) {
            return false;
        }

        return $user->hasRole('Agency User') || $user->can('Tender:execute');
    }

    public static function canOpenMenu(?User $user): bool
    {
        return self::seesAll($user) || self::isPemilikProjek($user);
    }

    public static function canManage(?User $user, Tender $tender): bool
    {
        if ($user === null) {
            return false;
        }

        if ((int) ($tender->status_process_id ?? 0) !== TenderProcessStatus::penyediaanIklanListStatus()) {
            return false;
        }

        if (self::seesAll($user)) {
            return true;
        }

        if (! self::isPemilikProjek($user)) {
            return false;
        }

        if (! $tender->kaedahDokumen?->skips_to_penyediaan_iklan) {
            return false;
        }

        if ($user->organization_unit_id) {
            return (int) $tender->organization_unit_id === (int) $user->organization_unit_id;
        }

        return (int) $tender->creator_id === (int) $user->id;
    }

    public static function scopeVisible(Builder $query, ?User $user): void
    {
        if ($user === null || self::seesAll($user)) {
            return;
        }

        $skipIds = \App\Models\Ref\RefKaedahDokumen::query()
            ->where('skips_to_penyediaan_iklan', true)
            ->pluck('id');

        $query->whereIn('kaedah_dokumen_id', $skipIds);

        if ($user->organization_unit_id) {
            $query->where('organization_unit_id', $user->organization_unit_id);

            return;
        }

        $query->where('creator_id', $user->id);
    }
}
