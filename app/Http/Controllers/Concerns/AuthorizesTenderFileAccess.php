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

        if ($this->userCanReviewTenderFiles($user)) {
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

    protected function userCanReviewTenderFiles($user): bool
    {
        if (! $user->vendor_id) {
            return true;
        }

        if ($user->hasRole('Admin') || $user->can('tender:specification-management')) {
            return true;
        }

        return $user->hasRole('Jawatankuasa')
            || $user->hasRole('Agency Admin')
            || $user->hasRole('Agency User')
            || $user->hasRole('Front Desk');
    }
}
