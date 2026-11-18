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
    /**
     * A tender can be edited while being prepared (status 4) or while awaiting
     * Ketua Jabatan publication (status 5 with no approver).
     */
    public static function isEditable(Tender $tender): bool
    {
        if (! in_array($tender->type, ['tender', 'quotation'], true)) {
            return false;
        }

        if (! empty($tender->approver_id)) {
            return false;
        }

        return in_array((int) ($tender->status_process_id ?? 0), [
            TenderProcessStatus::penyediaanIklanListStatus(),
            TenderProcessStatus::PENYEDIAAN_IKLAN,
        ], true);
    }

    public static function scopeEditable(Builder $query): void
    {
        $query->whereIn('type', ['tender', 'quotation'])
            ->where(function (Builder $statusQuery) {
                $statusQuery->where('status_process_id', TenderProcessStatus::penyediaanIklanListStatus())
                    ->orWhere('status_process_id', TenderProcessStatus::PENYEDIAAN_IKLAN);
            })->where(function (Builder $approverQuery) {
                $approverQuery->whereNull('approver_id')->orWhere('approver_id', 0);
            });
    }

    public static function seesAll(?User $user): bool
    {
        return $user !== null && $user->hasRole('Admin');
    }

    public static function canList(?User $user): bool
    {
        return $user !== null && $user->canAccessMenu('Advertisement:list');
    }

    public static function isPemilikProjek(?User $user): bool
    {
        if ($user === null || self::seesAll($user)) {
            return false;
        }

        return $user->hasRole('Agency User')
            || $user->hasRole('Agency Admin')
            || $user->can('Tender:execute');
    }

    public static function canOpenMenu(?User $user): bool
    {
        return self::seesAll($user) || self::canList($user) || self::isPemilikProjek($user);
    }

    public static function canManage(?User $user, Tender $tender): bool
    {
        if ($user === null) {
            return false;
        }

        if (! self::isEditable($tender)) {
            return false;
        }

        if (self::seesAll($user)) {
            return true;
        }

        if (self::canList($user)) {
            return $user->organization_unit_id
                && (int) $tender->organization_unit_id === (int) $user->organization_unit_id;
        }

        if (! self::isPemilikProjek($user)) {
            return false;
        }

        if (! $tender->kaedahDokumen?->skips_to_penyediaan_iklan) {
            return false;
        }

        return $user->organization_unit_id
            && (int) $tender->organization_unit_id === (int) $user->organization_unit_id;
    }

    public static function scopeVisible(Builder $query, ?User $user): void
    {
        if ($user === null || self::seesAll($user)) {
            return;
        }

        if (self::canList($user)) {
            if ($user->organization_unit_id) {
                $query->where('organization_unit_id', $user->organization_unit_id);
            } else {
                $query->whereRaw('1 = 0');
            }

            return;
        }

        $skipIds = \App\Models\Ref\RefKaedahDokumen::query()
            ->where('skips_to_penyediaan_iklan', true)
            ->pluck('id');

        $query->whereIn('kaedah_dokumen_id', $skipIds);

        if ($user->organization_unit_id) {
            $query->where('organization_unit_id', $user->organization_unit_id);
        } else {
            $query->whereRaw('1 = 0');
        }
    }
}
