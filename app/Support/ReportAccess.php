<?php

namespace App\Support;

use App\User;

/**
 * LAPORAN access for Admin and Agency Admin.
 * Agency Admin may open the listed reports (menu + routes).
 */
final class ReportAccess
{
    /**
     * Permissions granted to Agency Admin (and used as role fallback before migrate).
     */
    public const AGENCY_ADMIN_PERMISSIONS = [
        'Report:view',
        'Report:view:revenue_yearly',
        'Report:view:agency_transaction',
        'Report:view:agency_tender',
        'Report:view:agency_tender:organization_unit_id',
        'Report:view:agency_daily',
        'Report:view:agency_daily:organization_unit_id',
        'Report:view:gateway_daily',
        'Report:view:gateway_daily:organization_unit_id',
        'Report:view:vendor_status',
        'Report:view:vendor_code',
        'Report:view:vendor_district',
        'Report:view:user_agency',
        'Report:view:user_agency:organization_unit_id',
        'Report:view:user_activity',
        'Report:view:staff_activity',
    ];

    public static function canOpenMenu(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return $user->can('Report:view')
            || $user->hasRole('Admin')
            || $user->hasRole('Agency Admin');
    }

    public static function allows(?User $user, string ...$permissions): bool
    {
        if ($user === null || $permissions === []) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        if (! $user->hasRole('Agency Admin')) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (in_array($permission, self::AGENCY_ADMIN_PERMISSIONS, true)) {
                return true;
            }
        }

        return false;
    }
}
