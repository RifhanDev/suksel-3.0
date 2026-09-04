<?php

namespace App\Http\Controllers\Concerns;

use App\Tender;
use Illuminate\Support\Facades\Auth;

trait AuthorizesTenderFileAccess
{
    /**
     * Allow authenticated staff (Admin / PTJ / Jawatankuasa) and participating vendors.
     *
     * @param  int|null  $ownerVendorId  When set, vendors may only access their own file.
     */
    protected function assertCanAccessTenderFile(Tender $tender, ?int $ownerVendorId = null): void
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Sila log masuk untuk memuat turun fail.');
        }

        if ($this->userCanReviewTenderFiles($user, $tender)) {
            return;
        }

        $vendorId = (int) ($user->vendor_id ?? 0);
        if ($vendorId <= 0) {
            abort(403, 'Akses fail tidak dibenarkan.');
        }

        if (! $tender->hasParticipate($vendorId)) {
            abort(403, 'Sila beli dokumen tender terlebih dahulu.');
        }

        if ($ownerVendorId !== null && $ownerVendorId !== $vendorId) {
            abort(403, 'Akses fail tidak dibenarkan.');
        }
    }

    protected function userCanReviewTenderFiles($user, ?Tender $tender = null): bool
    {
        if ($user->hasRole('Admin') || $user->can('tender:specification-management')) {
            return true;
        }

        if (
            $user->hasRole('Agency Admin')
            || $user->hasRole('Agency User')
        ) {
            return true;
        }

        // Committee members (including Agency Jawatankuasa / dual-role accounts).
        if (
            $user->hasRole('Jawatankuasa')
            || $user->hasRole('Agency Jawatankuasa')
            || $user->hasRole('Urus Setia')
            || $user->hasRole('Agency Urus Setia')
            || $user->hasRole('Agency Urusetia')
            || $user->hasRole('Penilai')
            || $user->hasRole('Agency Penilai')
        ) {
            if ($tender === null) {
                return true;
            }

            return $tender->isAppointedTo($user, ['spec', 'open', 'tech', 'fin', 'eval', 'harga']);
        }

        // Non-vendor staff (e.g. PTJ, Agency staff, government evaluators) may review tender files.
        if (empty($user->vendor_id)) {
            return true;
        }

        return false;
    }
}
