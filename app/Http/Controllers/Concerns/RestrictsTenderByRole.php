<?php

namespace App\Http\Controllers\Concerns;

use App\Tender;
use Illuminate\Support\Collection;

trait RestrictsTenderByRole
{
    protected function userBypassesTenderScoping($user = null): bool
    {
        $user ??= auth()->user();

        if (!$user) {
            return false;
        }

        // Admin is the application's system-wide administrator. Other roles may
        // have elevated workflow permissions, but do not inherit cross-PTJ access.
        return $user->hasRole('Admin');
    }

    protected function userIsAgencyCommittee($user = null): bool
    {
        $user ??= auth()->user();

        if (!$user) {
            return false;
        }

        return $user->hasRole('Agency Jawatankuasa')
            || $user->hasRole('Jawatankuasa');
    }

    protected function shouldScopeCommitteeTenders($user = null): bool
    {
        $user ??= auth()->user();

        return $user
            && $this->userIsAgencyCommittee($user)
            && !$this->userBypassesTenderScoping($user);
    }

    protected function applyCommitteeAppointment($query, string|array $jenis)
    {
        $query = $this->applyOwnAgencyScope($query);

        if (!$this->shouldScopeCommitteeTenders()) {
            return $query;
        }

        return $query->appointedTo(auth()->user(), $jenis);
    }

    protected function assertCommitteeAppointment(?Tender $tender, string|array $jenis): void
    {
        $this->assertOwnAgency($tender);

        if (!$this->shouldScopeCommitteeTenders()) {
            return;
        }

        if (!$tender || !$tender->isAppointedTo(auth()->user(), $jenis)) {
            abort(403, 'Anda tidak dilantik pada tender ini.');
        }
    }

    protected function filterTendersByAppointment(iterable $tenders, string|array $jenis): Collection
    {
        $user = auth()->user();
        $items = collect($tenders)->filter(function ($tender) use ($user) {
            if (!$user || $user->hasRole('Admin')) {
                return true;
            }

            $organizationUnitId = is_array($tender)
                ? ($tender['organization_unit_id'] ?? null)
                : ($tender->organization_unit_id ?? null);

            if ($organizationUnitId === null) {
                $tenderId = is_array($tender)
                    ? ($tender['id'] ?? null)
                    : ($tender->id ?? null);
                if ($tenderId) {
                    $organizationUnitId = Tender::query()
                        ->whereKey($tenderId)
                        ->value('organization_unit_id');
                }
            }

            return $user->organization_unit_id
                && (int) $organizationUnitId === (int) $user->organization_unit_id;
        });

        if (!$this->shouldScopeCommitteeTenders()) {
            return $items->values();
        }

        $user = auth()->user();
        $jenis = (array) $jenis;

        return $items->filter(function ($tender) use ($user, $jenis) {
            $model = $tender instanceof Tender
                ? $tender
                : Tender::query()->find($tender->id ?? $tender['id'] ?? null);

            return $model && $model->isAppointedTo($user, $jenis);
        })->values();
    }

    protected function applyLembagaDecisionScope($query)
    {
        $user = auth()->user();

        if (!$user || $this->userBypassesTenderScoping($user)) {
            return $query;
        }

        return $query->forLembagaDecision($user);
    }

    protected function assertLembagaDecisionAccess(?Tender $tender): void
    {
        $user = auth()->user();

        if (!$user || $this->userBypassesTenderScoping($user)) {
            return;
        }

        if (!$user->hasRole('Agency Lembaga Perolehan') && !$user->hasRole('Lembaga Perolehan Negeri Selangor')) {
            return;
        }

        if (!$tender || !$tender->isVisibleToLembaga($user)) {
            abort(403, 'Tender ini di luar bidang kuasa lembaga anda.');
        }
    }

    /**
     * Agency logins only see tenders that belong to their own organization unit.
     * Central admins, the state lembaga, and vendors are left unscoped.
     */
    protected function userIsAgencyLogin($user = null): bool
    {
        $user ??= auth()->user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole('Admin')
            || $user->hasRole('Admin UPEN')
            || $user->hasRole('Admin PWN')
            || $user->hasRole('Admin Kewangan')
            || $user->hasRole('Front Desk')
            || $user->hasRole('Lembaga Perolehan Negeri Selangor')) {
            return false;
        }

        foreach ([
            'Agency Admin',
            'Agency User',
            'Agency Urusetia',
            'Agency Jawatankuasa',
            'Agency Lembaga Perolehan',
            'Agency Ketua Jabatan',
        ] as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    protected function applyOwnAgencyScope($query)
    {
        $user = auth()->user();

        if (!$user || $user->hasRole('Admin')) {
            return $query;
        }

        $column = $query->getModel()->getTable() . '.organization_unit_id';

        if (!$user->organization_unit_id) {
            return $query->where($column, 0);
        }

        return $query->where($column, $user->organization_unit_id);
    }

    protected function assertOwnAgency(?Tender $tender): void
    {
        $user = auth()->user();

        if (!$user || $user->hasRole('Admin')) {
            return;
        }

        if (!$tender || !$user->organization_unit_id
            || (int) $tender->organization_unit_id !== (int) $user->organization_unit_id) {
            abort(403, 'Tender ini bukan milik agensi anda.');
        }
    }
}
