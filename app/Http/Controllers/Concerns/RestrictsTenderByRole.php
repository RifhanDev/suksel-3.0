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

        return $user->hasRole('Admin')
            || $user->hasRole('Agency Admin')
            || $user->hasRole('Admin UPEN')
            || $user->hasRole('Admin PWN');
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
        if (!$this->shouldScopeCommitteeTenders()) {
            return $query;
        }

        return $query->appointedTo(auth()->user(), $jenis);
    }

    protected function assertCommitteeAppointment(?Tender $tender, string|array $jenis): void
    {
        if (!$this->shouldScopeCommitteeTenders()) {
            return;
        }

        if (!$tender || !$tender->isAppointedTo(auth()->user(), $jenis)) {
            abort(403, 'Anda tidak dilantik pada tender ini.');
        }
    }

    protected function filterTendersByAppointment(iterable $tenders, string|array $jenis): Collection
    {
        $items = collect($tenders);

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
}
