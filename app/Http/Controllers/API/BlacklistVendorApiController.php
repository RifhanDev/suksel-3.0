<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Models\ApiRequest;
use App\Vendor;
use App\VendorBlacklist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class BlacklistVendorApiController extends Controller
{
    /**
     * List currently blacklisted vendors, or check one vendor by registration.
     *
     * GET/POST /api/v1/blacklist_vendor
     * Auth: Sanctum Bearer token (one token per client).
     */
    public function index(Request $request): JsonResponse
    {
        /** @var ApiClient $client */
        $client = $request->user();

        if (! $client instanceof ApiClient || ! $client->isActive()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'token is not valid',
                'data' => [],
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'registration' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,cancelled,all',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation error.',
                'errors' => $validator->messages(),
                'data' => [],
            ], 422);
        }

        $registration = trim((string) $request->input('registration', ''));
        $recordStatus = $request->input('status', 'active');

        ApiRequest::query()->create([
            'organization_unit_id' => $client->organization_unit_id ?: 0,
            'token' => 'sanctum:' . ($client->currentAccessToken()?->id ?? $client->id),
            'api_type' => 'blacklist_vendor',
            'parameter' => json_encode([
                'client' => $client->name,
                'registration' => $registration !== '' ? $registration : null,
                'status' => $recordStatus,
            ]),
        ]);

        if ($registration !== '') {
            return $this->checkVendor($registration, $recordStatus);
        }

        return $this->listBlacklistedVendors($recordStatus);
    }

    protected function checkVendor(string $registration, string $recordStatus): JsonResponse
    {
        $vendor = Vendor::query()
            ->with(['user', 'blacklists.agency'])
            ->where('registration', $registration)
            ->first();

        if (! $vendor) {
            return response()->json([
                'status' => 'failed',
                'message' => 'data not found',
                'data' => [],
            ], 404);
        }

        $payload = $this->formatVendor($vendor, $recordStatus);

        return response()->json([
            'status' => 'success',
            'message' => $payload['is_blacklisted']
                ? 'Vendor is currently blacklisted.'
                : 'Vendor is not currently blacklisted.',
            'count' => 1,
            'data' => [$payload],
        ]);
    }

    protected function listBlacklistedVendors(string $recordStatus): JsonResponse
    {
        $today = Carbon::today()->toDateString();

        $blacklistQuery = VendorBlacklist::query()
            ->with(['vendor.user', 'agency'])
            ->whereHas('vendor');

        if ($recordStatus !== 'all') {
            $blacklistQuery->where('status', $recordStatus);
        }

        if ($recordStatus === 'active') {
            $blacklistQuery
                ->whereDate('start', '<=', $today)
                ->whereDate('end', '>=', $today);
        }

        $records = $blacklistQuery->orderByDesc('end')->get();

        $vendorsById = [];
        foreach ($records as $record) {
            $vendor = $record->vendor;
            if (! $vendor) {
                continue;
            }
            $id = (int) $vendor->id;
            if (! isset($vendorsById[$id])) {
                $vendor->setRelation('blacklists', collect());
                $vendorsById[$id] = $vendor;
            }
            $vendorsById[$id]->blacklists->push($record);
        }

        if ($recordStatus === 'active') {
            $legacyVendors = Vendor::query()
                ->with(['user', 'blacklists.agency'])
                ->whereNotNull('blacklisted_until')
                ->whereDate('blacklisted_until', '>=', $today)
                ->whereNotIn('id', array_keys($vendorsById))
                ->get();

            foreach ($legacyVendors as $vendor) {
                $vendorsById[(int) $vendor->id] = $vendor;
            }
        }

        $data = collect($vendorsById)
            ->map(fn (Vendor $vendor) => $this->formatVendor($vendor, $recordStatus))
            ->filter(fn (array $row) => $recordStatus !== 'active' || $row['is_blacklisted'] === true)
            ->values()
            ->all();

        return response()->json([
            'status' => 'success',
            'message' => count($data) > 0
                ? 'Blacklisted vendors retrieved.'
                : 'No blacklisted vendors found.',
            'count' => count($data),
            'data' => $data,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function formatVendor(Vendor $vendor, string $recordStatus): array
    {
        $today = Carbon::today();
        $records = $vendor->blacklists ?? collect();

        if ($recordStatus !== 'all') {
            $records = $records->where('status', $recordStatus);
        }

        $formattedRecords = $records->map(function (VendorBlacklist $row) use ($today) {
            $start = $row->start ? Carbon::parse($row->start)->startOfDay() : null;
            $end = $row->end ? Carbon::parse($row->end)->endOfDay() : null;
            $inPeriod = $start && $end && $today->between($start, $end);

            return [
                'id' => (int) $row->id,
                'reason' => $row->reason,
                'start' => $row->start ? Carbon::parse($row->start)->format('Y-m-d') : null,
                'end' => $row->end ? Carbon::parse($row->end)->format('Y-m-d') : null,
                'status' => $row->status,
                'agency' => $row->agency?->name,
                'currently_blacklisted' => $row->status === 'active' && $inPeriod,
            ];
        })->values()->all();

        $legacyUntil = $vendor->blacklisted_until
            ? Carbon::parse($vendor->blacklisted_until)
            : null;
        $legacyActive = $legacyUntil && $today->lte($legacyUntil);

        $activeRecord = collect($formattedRecords)->firstWhere('currently_blacklisted', true);
        $isBlacklisted = (bool) $activeRecord || $legacyActive;

        $until = $activeRecord['end']
            ?? ($legacyUntil ? $legacyUntil->format('Y-m-d') : null);
        $reason = $activeRecord['reason']
            ?? $vendor->blacklist_reason
            ?? null;

        return [
            'vendor_id' => (int) $vendor->id,
            'registration_no' => $vendor->registration,
            'name' => $vendor->name,
            'email' => $vendor->user?->email,
            'tel' => $vendor->tel,
            'address' => $vendor->address,
            'is_blacklisted' => $isBlacklisted,
            'blacklisted_until' => $until,
            'blacklist_reason' => $reason,
            'records' => $formattedRecords,
        ];
    }
}
