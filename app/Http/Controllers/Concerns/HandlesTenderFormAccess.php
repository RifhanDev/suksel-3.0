<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Tender;
use App\Services\OnlineFormStatusService;
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

        if ($user->hasRole('Admin') || $user->can('tender:specification-management')) {
            return;
        }

        if ($user->vendor_id && $tender->hasParticipate($user->vendor_id)) {
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

        return ! $user->hasRole('Admin') && ! $user->can('tender:specification-management');
    }

    protected function isFormViewOnly(): bool
    {
        return request('mode') === 'view' || request()->boolean('view');
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

        return $user?->vendor_id ? (int) $user->vendor_id : null;
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
            'vendor_id' => $this->isVendorFormMode() ? $this->vendorId() : null,
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

        app(VendorFormPayloadService::class)->save(
            $resolved,
            $this->vendorId(),
            $formKey,
            $payload,
            $status
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function loadVendorFormPayload(Tender|LegacyTender $tender, string $formKey): ?array
    {
        if (! $this->isVendorFormMode() || ! $this->vendorId()) {
            return null;
        }

        $resolved = \App\Tender::query()->find($tender->id);
        if (! $resolved) {
            return null;
        }

        return app(VendorFormPayloadService::class)->get(
            $resolved,
            $this->vendorId(),
            $formKey
        );
    }
}
