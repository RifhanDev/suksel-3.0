<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class TechnicalSpecificationController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureSpecificationAccess();

        $params   = $request->only(['item_type', 'tender_uuid', 'per_page', 'page']);
        $response = $this->api()->get($this->url('technical-specifications'), $params);

        return response()->json($response->json(), $response->status());
    }

    public function create(Request $request, string $tenderUuid, ?string $documentUuid = null)
    {
        $this->ensureSpecificationAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $specificationData = null;
        $cloneFrom         = $request->query('clone_from');

        if ($cloneFrom)
        {
            $cloneResponse = $this->api()->get($this->url('technical-specifications/' . $cloneFrom));

            if ($cloneResponse->successful())
            {
                $specificationData = $cloneResponse->json('data');
                $specificationData['uuid']        = null;
                $specificationData['tender_uuid'] = $tender->uuid;
                $specificationData['status']      = null;
            }
            else
            {
                Log::warning('TechnicalSpecificationController@create: clone_from API request failed',
                [
                    'tender_uuid' => $tenderUuid,
                    'clone_from'  => $cloneFrom,
                    'status'      => $cloneResponse->status(),
                    'body'        => $cloneResponse->body(),
                ]);
            }
        }
        elseif ($documentUuid)
        {
            $response = $this->api()->get($this->url('technical-specifications/' . $documentUuid));

            if ($response->successful())
            {
                $specificationData = $response->json('data');
            }
            else
            {
                Log::warning('TechnicalSpecificationController@create: API request failed',
                [
                    'tender_uuid' => $tenderUuid,
                    'spec_uuid'   => $documentUuid,
                    'status'      => $response->status(),
                    'body'        => $response->body(),
                ]);
            }
        }

        return view('TechnicalChecklist.form_cipta_spesifikasi', compact('tender', 'specificationData'));
    }

    public function store(Request $request)
    {
        $this->ensureSpecificationAccess();

        $payload = $request->except('_token');
        $apiUrl = $this->url('technical-specifications');

        try
        {
            $response = $this->api()->post($apiUrl, $payload);
        }
        catch (Throwable $exception)
        {
            $this->logStoreApiException($payload, $apiUrl, $exception);

            return response()->json(
            [
                'message' => 'Terdapat ralat semasa menyimpan spesifikasi teknikal.',
            ], 502);
        }

        if (!$response->successful())
        {
            $this->logStoreApiFailure($payload, $apiUrl, $response);
        }

        return response()->json($response->json(), $response->status());
    }

    public function show(string $uuid)
    {
        $response = $this->api()->get($this->url('technical-specifications/' . $uuid));

        return response()->json($response->json(), $response->status());
    }

    public function update(Request $request, string $uuid)
    {
        $response = $this->api()->put($this->url('technical-specifications/' . $uuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function complete(string $uuid)
    {
        $response = $this->api()->post($this->url('technical-specifications/' . $uuid . '/complete'));

        return response()->json($response->json(), $response->status());
    }

    public function destroy(string $uuid)
    {
        $response = $this->api()->delete($this->url('technical-specifications/' . $uuid));

        return response()->json($response->json(), $response->status());
    }

    private function api()
    {
        return Http::withHeaders([
            'X-Api-Key' => config('services.stos_backend.api_key'),
            'Accept'    => 'application/json',
        ]);
    }

    private function ensureSpecificationAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function logStoreApiFailure(array $payload, string $apiUrl, Response $response): void
    {
        $payloadSummary = $this->buildStorePayloadSummary($payload);
        $responseSummary = $this->buildApiResponseSummary($response);
        $level = $response->status() >= 500 ? 'error' : 'warning';
        $message = $response->status() >= 500
            ? 'TechnicalSpecificationController@store: Backend API returned a server error'
            : 'TechnicalSpecificationController@store: Backend API returned an unsuccessful response';

        Log::log($level, $message, [
            'api_url' => $apiUrl,
            'http_method' => 'POST',
            'status' => $response->status(),
            'user_id' => optional(auth()->user())->id,
            'tender_uuid' => $payload['tender_uuid'] ?? null,
            'title' => $payload['title'] ?? null,
            'item_type' => $payload['item_type'] ?? null,
            'specification_type' => $payload['specification_type'] ?? null,
            'goods_type' => $payload['goods_type'] ?? null,
            'weighting_type' => $payload['weighting_type'] ?? null,
            'physical_submission' => $payload['physical_submission'] ?? null,
            'item_count' => $payloadSummary['item_count'],
            'detail_count' => $payloadSummary['detail_count'],
            'item_titles_preview' => $payloadSummary['item_titles_preview'],
            'response_message' => $responseSummary['message'],
            'response_errors' => $responseSummary['errors'],
            'response_body_preview' => $responseSummary['body_preview'],
        ]);
    }

    private function logStoreApiException(array $payload, string $apiUrl, Throwable $exception): void
    {
        $payloadSummary = $this->buildStorePayloadSummary($payload);

        Log::error('TechnicalSpecificationController@store: Backend API request threw an exception', [
            'api_url' => $apiUrl,
            'http_method' => 'POST',
            'user_id' => optional(auth()->user())->id,
            'tender_uuid' => $payload['tender_uuid'] ?? null,
            'title' => $payload['title'] ?? null,
            'item_type' => $payload['item_type'] ?? null,
            'specification_type' => $payload['specification_type'] ?? null,
            'goods_type' => $payload['goods_type'] ?? null,
            'weighting_type' => $payload['weighting_type'] ?? null,
            'physical_submission' => $payload['physical_submission'] ?? null,
            'item_count' => $payloadSummary['item_count'],
            'detail_count' => $payloadSummary['detail_count'],
            'item_titles_preview' => $payloadSummary['item_titles_preview'],
            'exception_class' => get_class($exception),
            'exception_message' => $exception->getMessage(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
        ]);
    }

    private function buildStorePayloadSummary(array $payload): array
    {
        $items = is_array($payload['items'] ?? null) ? $payload['items'] : [];
        $detailCount = 0;
        $itemTitlesPreview = [];

        foreach ($items as $item) {
            $details = is_array($item['details'] ?? null) ? $item['details'] : [];
            $detailCount += count($details);

            if (count($itemTitlesPreview) < 5 && !empty($item['title'])) {
                $itemTitlesPreview[] = $item['title'];
            }
        }

        return [
            'item_count' => count($items),
            'detail_count' => $detailCount,
            'item_titles_preview' => $itemTitlesPreview,
        ];
    }

    private function buildApiResponseSummary(Response $response): array
    {
        $json = $response->json();
        $bodyPreview = $this->makeBodyPreview($response->body());

        if (!is_array($json)) {
            return [
                'message' => null,
                'errors' => null,
                'body_preview' => $bodyPreview,
            ];
        }

        $errors = isset($json['errors']) && is_array($json['errors'])
            ? $this->summarizeValidationErrors($json['errors'])
            : null;

        return [
            'message' => $json['message'] ?? null,
            'errors' => $errors,
            'body_preview' => empty($json['message']) && empty($errors) ? $bodyPreview : null,
        ];
    }

    private function summarizeValidationErrors(array $errors): array
    {
        $summary = [];

        foreach ($errors as $field => $messages) {
            $summary[$field] = array_slice((array) $messages, 0, 3);
        }

        return $summary;
    }

    private function makeBodyPreview(?string $body): ?string
    {
        if (empty($body)) {
            return null;
        }

        return Str::limit(preg_replace('/\s+/', ' ', trim($body)), 1000);
    }

    private function url(string $path): string
    {
        return config('services.stos_backend.url') . '/api/' . $path;
    }
}
