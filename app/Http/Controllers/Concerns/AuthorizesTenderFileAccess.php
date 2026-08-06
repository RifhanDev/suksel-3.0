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

        // Staff reviewers (no vendor account)
        if (! $user->vendor_id) {
            return;
        }

        $vendorId = (int) $user->vendor_id;
        if (! $tender->hasParticipate($vendorId)) {
            abort(403, 'Sila beli dokumen tender terlebih dahulu.');
        }

        if ($ownerVendorId !== null && $ownerVendorId !== $vendorId) {
            abort(403, 'Akses fail tidak dibenarkan.');
        }
    }
}
