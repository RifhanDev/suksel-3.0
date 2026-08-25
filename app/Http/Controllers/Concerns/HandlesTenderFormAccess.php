<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Tender;
use App\Services\OnlineFormStatusService;
use App\Services\StosBackendClient;
use App\Services\VendorFormPayloadService;
use App\Tender as LegacyTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait HandlesTenderFormAccess
{
    protected function ensureTenderFormAccess(Tender|LegacyTender $tender): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        if ($user->hasRole('Admin') || $user->can('tender:specification-management')) {
            return;
        }

        // PTJ / Jawatankuasa / agency staff reviewing tender documents
        if (! $user->vendor_id) {
            return;
        }

        if ($user->hasRole('Jawatankuasa') || $user->hasRole('Agency Admin') || $user->hasRole('Agency User') || $user->hasRole('Front Desk')) {
            return;
        }

        if ($tender->hasParticipate($user->vendor_id)) {
            return;
        }

        abort(403);
    }

    protected function isVendorFormMode(): bool
    {
        $user = auth()->user();

        if (! $user || ! $user->vendor_id) {
            return false;
        }

        $isStaff = $user->hasRole('Admin')
            || $user->can('tender:specification-management')
            || $user->hasRole('Jawatankuasa')
            || $user->hasRole('Agency Admin')
            || $user->hasRole('Agency User');

        // Staff/committee review (mode=view) must not run as vendor save/edit mode,
        // even for dual-role Vendor + Jawatankuasa accounts.
        if ($isStaff && (request('mode') === 'view' || request()->boolean('view'))) {
            return false;
        }

        // Dual-role accounts still act as vendor when filling their own tender documents.
        if ($user->hasRole('Vendor')) {
            return true;
        }

        if ($isStaff) {
            return false;
        }

        return true;
    }

    protected function isFormViewOnly(): bool
    {
        if (request('mode') === 'view' || request()->boolean('view')) {
            return true;
        }

        if (! $this->isVendorFormMode() && (int) request('vendor_id') > 0) {
            return true;
        }

        return false;
    }

    protected function isFormModalEmbed(): bool
    {
        return request()->boolean('modal');
    }

    protected function ensureFormEditable(): void
    {
        if ($this->isFormViewOnly()) {
            abort(403, 'Borang ini dalam mod paparan sahaja dan tidak boleh diedit.');
        }

        if ($this->isVendorFormMode() && $this->vendorId()) {
            $routeTender = request()->route('tender');
            $resolved = null;
            if ($routeTender instanceof \App\Tender || $routeTender instanceof Tender) {
                $resolved = \App\Tender::query()->find($routeTender->id);
            } elseif ($routeTender) {
                $resolved = \App\Tender::query()->find($routeTender);
            }

            if ($resolved) {
                $submissions = app(\App\Services\VendorTenderSubmissionService::class);
                if ($submissions->isSubmitted($resolved, $this->vendorId())) {
                    abort(403, 'Tawaran telah dihantar. Borang tidak boleh dikemaskini.');
                }

                $windowReason = $resolved->vendorDokumenWindowBlockedReason();
                if ($windowReason !== null) {
                    abort(403, $windowReason);
                }
            }
        }
    }

    /**
     * @return array{viewOnly: bool, vendorFormMode: bool, modalEmbed: bool, layout: string, returnUrl: string|null}
     */
    protected function formViewVars(Tender|LegacyTender $tender): array
    {
        $modalEmbed = $this->isFormModalEmbed();

        return [
            'viewOnly' => $this->isFormViewOnly(),
            'vendorFormMode' => $this->isVendorFormMode(),
            'modalEmbed' => $modalEmbed,
            'layout' => $modalEmbed ? 'layouts.form_modal_embed' : 'layouts.v3.master',
            'returnUrl' => request('return', $this->isVendorFormMode() ? $this->vendorFormReturnUrl($tender) : null),
        ];
    }

    protected function appendModalEmbedToUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (str_contains($url, 'modal=1')) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'modal=1';
    }

    protected function vendorFormRedirect(
        Request $request,
        Tender|LegacyTender $tender,
        string $message,
        string $flashKey = 'success',
        bool $useBack = false
    ): RedirectResponse|Response {
        if ($request->boolean('modal')) {
            return response()->view('tenders.forms.modal_complete', [
                'message' => $message,
                'flashKey' => $flashKey,
            ]);
        }

        if ($useBack) {
            return redirect()->back()->withInput()->with($flashKey, $message);
        }

        return redirect($request->input('return', $this->vendorFormReturnUrl($tender)))
            ->with($flashKey, $message);
    }

    protected function appendViewModeToUrl(?string $url, bool $viewOnly = true): ?string
    {
        if (! $url || ! $viewOnly) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'mode=view';
    }

    protected function vendorId(): ?int
    {
        $user = auth()->user();
        $queryVendorId = (int) request('vendor_id');

        // Staff review with ?vendor_id=... must use the queried vendor, not the
        // reviewer's own vendor_id (dual-role Vendor + Jawatankuasa).
        if (! $this->isVendorFormMode() && $queryVendorId > 0) {
            return $queryVendorId;
        }

        if ($user?->vendor_id) {
            return (int) $user->vendor_id;
        }

        return $queryVendorId > 0 ? $queryVendorId : null;
    }

    /**
     * @param  array<string, mixed>  $summary
     */
    protected function trackVendorFormSubmitted(Tender|LegacyTender $tender, string $formKey, array $summary = []): void
    {
        if (! $this->isVendorFormMode() || ! $this->vendorId()) {
            return;
        }

        $resolved = \App\Tender::query()->find($tender->id);
        if (! $resolved) {
            return;
        }

        app(OnlineFormStatusService::class)->markSubmitted(
            $resolved,
            $this->vendorId(),
            $formKey,
            $summary
        );
    }

    protected function vendorFormReturnUrl(Tender|LegacyTender $tender): string
    {
        return route('tenders.show', $tender->id) . '#vt-dokumen-tawaran';
    }

    protected function stosUrlWithVendor(string $path, ?int $vendorId = null): string
    {
        $url = \App\Services\StosBackendClient::apiUrl($path);
        $vendorId ??= $this->vendorId();

        if ($vendorId) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'vendor_id=' . $vendorId;
        }

        return $url;
    }

    /**
     * Lookup keys for vendor-scoped local form records.
     *
     * @return array{tender_id: int, vendor_id: int|null}
     */
    protected function vendorFormRecordKeys(Tender|LegacyTender $tender): array
    {
        return [
            'tender_id' => $tender->id,
            'vendor_id' => $this->vendorId(),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function persistVendorFormPayload(
        Tender|LegacyTender $tender,
        string $formKey,
        array $payload,
        string $status = 'submitted'
    ): void {
        if (! $this->isVendorFormMode() || ! $this->vendorId()) {
            return;
        }

        $resolved = \App\Tender::query()->find($tender->id);
        if (! $resolved) {
            return;
        }

        // Preserve previously uploaded dokumen metadata unless caller replaces it.
        if (! array_key_exists('dokumens', $payload)) {
            $existing = app(VendorFormPayloadService::class)->get(
                $resolved,
                $this->vendorId(),
                $formKey
            );
            if (! empty($existing['dokumens']) && is_array($existing['dokumens'])) {
                $payload['dokumens'] = $existing['dokumens'];
            }
        }

        $payload['vendor_id'] = $this->vendorId();

        app(VendorFormPayloadService::class)->save(
            $resolved,
            $this->vendorId(),
            $formKey,
            $payload,
            $status
        );
    }

    /**
     * @param  array<string, mixed>  $dokumen
     */
    protected function appendVendorFormDokumen(
        Tender|LegacyTender $tender,
        string $formKey,
        array $dokumen
    ): void {
        if (! $this->isVendorFormMode() || ! $this->vendorId()) {
            return;
        }

        $resolved = \App\Tender::query()->find($tender->id);
        if (! $resolved) {
            return;
        }

        $payload = app(VendorFormPayloadService::class)->get(
            $resolved,
            $this->vendorId(),
            $formKey
        ) ?? [
            'items' => [],
            'vendor_id' => $this->vendorId(),
        ];

        $dokumens = is_array($payload['dokumens'] ?? null) ? $payload['dokumens'] : [];
        $uuid = (string) ($dokumen['uuid'] ?? '');
        if ($uuid !== '') {
            $dokumens = array_values(array_filter(
                $dokumens,
                fn ($row) => (string) ($row['uuid'] ?? '') !== $uuid
            ));
        }
        $dokumens[] = $dokumen;
        $payload['dokumens'] = $dokumens;

        app(VendorFormPayloadService::class)->save(
            $resolved,
            $this->vendorId(),
            $formKey,
            $payload,
            $payload['status'] ?? 'submitted'
        );
    }

    protected function removeVendorFormDokumen(
        Tender|LegacyTender $tender,
        string $formKey,
        string $fileUuid
    ): void {
        if (! $this->vendorId()) {
            return;
        }

        $resolved = \App\Tender::query()->find($tender->id);
        if (! $resolved) {
            return;
        }

        $payload = app(VendorFormPayloadService::class)->get(
            $resolved,
            $this->vendorId(),
            $formKey
        );
        if (! is_array($payload) || empty($payload['dokumens']) || ! is_array($payload['dokumens'])) {
            return;
        }

        $payload['dokumens'] = array_values(array_filter(
            $payload['dokumens'],
            fn ($row) => (string) ($row['uuid'] ?? '') !== $fileUuid
        ));

        app(VendorFormPayloadService::class)->save(
            $resolved,
            $this->vendorId(),
            $formKey,
            $payload,
            $payload['status'] ?? 'submitted'
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function loadVendorFormPayload(Tender|LegacyTender $tender, string $formKey): ?array
    {
        $vId = $this->vendorId();
        if (! $vId) {
            return null;
        }

        $resolved = \App\Tender::query()->find($tender->id);
        if (! $resolved) {
            return null;
        }

        return app(VendorFormPayloadService::class)->get(
            $resolved,
            $vId,
            $formKey
        );
    }

    /**
     * Vendor forms (and staff previews with ?vendor_id=) must never show another
     * company's tender-level / shared STOS data. Prefer the local per-vendor payload;
     * only use API data when it is explicitly scoped to that vendor.
     *
     * @param  array<string, mixed>|null  $apiData
     * @return array<string, mixed>|null
     */
    protected function resolveVendorFormDisplayData(
        Tender|LegacyTender $tender,
        string $formKey,
        ?array $apiData
    ): ?array {
        $vendorId = $this->vendorId();
        if (! $vendorId) {
            return $apiData;
        }

        $local = $this->loadVendorFormPayload($tender, $formKey);
        if ($this->vendorFormPayloadHasContent($local)) {
            $merged = $local;
            $merged['vendor_id'] = $vendorId;

            // Only attach STOS files when the API payload is vendor-scoped.
            if (
                empty($merged['dokumens'])
                && $this->stosDataBelongsToCurrentVendor($apiData)
                && ! empty($apiData['dokumens'])
                && is_array($apiData['dokumens'])
            ) {
                $merged['dokumens'] = $apiData['dokumens'];
            }

            return $merged;
        }

        if ($this->stosDataBelongsToCurrentVendor($apiData)) {
            return $apiData;
        }

        // Never leak shared tender-level STOS rows into another vendor's view.
        return [
            'tender_uuid' => $tender->uuid ?? null,
            'vendor_id' => $vendorId,
            'items' => is_array($local) ? ($local['items'] ?? []) : [],
            'dokumens' => is_array($local) ? ($local['dokumens'] ?? []) : [],
        ];
    }

    /**
     * @return array{items?: array, dokumens?: array}
     */
    protected function fetchOnlineFormData(string $apiSlug, string $tenderUuid, ?int $vendorId): array
    {
        $url = StosBackendClient::apiUrl($apiSlug . '/' . $tenderUuid);
        if ($vendorId) {
            $url .= '?vendor_id=' . $vendorId;
        }

        $response = StosBackendClient::http()->get($url);

        return $response->successful()
            ? $this->rewriteOnlineFormDokumens($apiSlug, $tenderUuid, (array) $response->json('data'))
            : [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function rewriteOnlineFormDokumens(string $apiSlug, string $tenderUuid, array $data): array
    {
        if (empty($data['dokumens']) || ! is_array($data['dokumens'])) {
            return $data;
        }

        $tender = LegacyTender::query()->where('uuid', $tenderUuid)->first();
        if (! $tender) {
            return $data;
        }

        $type = match ($apiSlug) {
            'pengalaman-kerja' => 'pengalaman-kerja',
            'kerja-dalam-tangan' => 'kerja-dalam-tangan',
            default => null,
        };

        if ($type) {
            $data['dokumens'] = \App\Http\Controllers\StosFormFileController::rewriteDokumenUrls($tender, $type, $data['dokumens']);
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>|null  $payload
     */
    protected function vendorFormPayloadHasContent(?array $payload): bool
    {
        if ($payload === null || $payload === []) {
            return false;
        }

        if (array_key_exists('items', $payload) && is_array($payload['items'])) {
            return count($payload['items']) > 0
                || ! empty($payload['dokumens'] ?? null)
                || ! empty($payload['files'] ?? null);
        }

        $ignore = ['vendor_id', 'user_id', '_token', 'status'];
        foreach ($payload as $key => $value) {
            if (in_array($key, $ignore, true)) {
                continue;
            }
            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * @param  array<string, mixed>|null  $data
     */
    protected function stosDataBelongsToCurrentVendor(?array $data): bool
    {
        if ($data === null || $data === []) {
            return false;
        }

        $vendorId = $this->vendorId();
        if (! $vendorId) {
            return false;
        }

        $payloadVendorId = $data['vendor_id'] ?? $data['vendorId'] ?? null;

        if ($payloadVendorId === null && isset($data['meta']) && is_array($data['meta'])) {
            $payloadVendorId = $data['meta']['vendor_id'] ?? $data['meta']['vendorId'] ?? null;
        }

        return $payloadVendorId !== null && (int) $payloadVendorId === $vendorId;
    }
}
