<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use App\Models\ApiToken;
use App\Models\OrganizationUnit;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        if (! ApiToken::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {
            $clients = ApiClient::query()->with(['agency', 'tokens']);

            return Datatables::of($clients)
                ->addColumn('agency_name', function (ApiClient $client) {
                    return $client->agency?->name ?: '—';
                })
                ->addColumn('token_status', function (ApiClient $client) {
                    $token = $client->tokens->sortByDesc('created_at')->first();
                    if (! $token || ! $client->plain_token) {
                        return '<span class="text-muted small">Tiada token</span>';
                    }

                    $value = e($client->plain_token);
                    $lastUsed = $token->last_used_at
                        ? Carbon::parse($token->last_used_at)->format('d/m/Y H:i')
                        : 'Belum digunakan';

                    return '<div class="d-flex align-items-start gap-2">'
                        . '<code class="api-token-value small mb-0" style="word-break:break-all;">' . $value . '</code>'
                        . '<button type="button" class="btn btn-sm btn-outline-secondary btn-copy-token flex-shrink-0" data-token="' . $value . '">Salin</button>'
                        . '</div>'
                        . '<div class="text-muted small mt-1">Terakhir digunakan: ' . e($lastUsed) . '</div>';
                })
                ->editColumn('status', function (ApiClient $client) {
                    return $client->status
                        ? '<span class="badge-bool-yes d-inline-flex align-items-center gap-1">Aktif</span>'
                        : '<span class="badge-bool-no d-inline-flex align-items-center gap-1">Tidak Aktif</span>';
                })
                ->editColumn('created_at', function (ApiClient $client) {
                    return Carbon::parse($client->created_at)->format('j M Y');
                })
                ->addColumn('actions', function (ApiClient $client) {
                    if (! ApiToken::canCreate()) {
                        return '';
                    }

                    $regen = '<form action="' . e(route('apitoken.regenerate', $client)) . '" method="POST" class="d-inline js-token-action">'
                        . csrf_field()
                        . '<button type="button" class="btn btn-sm btn-outline-primary js-swal-confirm"'
                        . ' data-title="Jana Semula Token?"'
                        . ' data-text="Token lama untuk ' . e($client->name) . ' akan dibatalkan dan tidak boleh digunakan lagi."'
                        . ' data-confirm="Ya, Jana Semula"'
                        . ' data-icon="question">Jana Semula</button>'
                        . '</form>';

                    $revoke = '<form action="' . e(route('apitoken.revoke', $client)) . '" method="POST" class="d-inline js-token-action">'
                        . csrf_field()
                        . '<button type="button" class="btn btn-sm btn-outline-danger js-swal-confirm"'
                        . ' data-title="Batalkan Token?"'
                        . ' data-text="Semua token untuk ' . e($client->name) . ' akan dibatalkan. Klien ini tidak lagi boleh akses API."'
                        . ' data-confirm="Ya, Batalkan"'
                        . ' data-icon="warning">Batalkan Token</button>'
                        . '</form>';

                    return '<div class="d-flex flex-wrap gap-1">' . $regen . $revoke . '</div>';
                })
                ->rawColumns(['token_status', 'status', 'actions'])
                ->make();
        }

        return view('api.index');
    }

    public function create()
    {
        if (! ApiToken::canCreate()) {
            return $this->_access_denied();
        }

        $agencies = OrganizationUnit::query()->select('id', 'name')->orderBy('name')->get();

        return view('api.create', compact('agencies'));
    }

    public function store(Request $request)
    {
        if (! ApiToken::canCreate()) {
            return $this->_access_denied();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:api_clients,name',
            'organization_unit_id' => 'nullable|exists:organization_units,id',
        ], [
            'name.unique' => 'Nama klien ini sudah wujud. Setiap klien mesti mempunyai token sendiri.',
        ]);

        if ($validator->fails()) {
            return $this->_validation_error($validator->errors());
        }

        $client = ApiClient::query()->create([
            'name' => trim($request->input('name')),
            'organization_unit_id' => $request->input('organization_unit_id') ?: null,
            'status' => true,
        ]);

        $plainTextToken = $this->issueToken($client);

        return redirect()
            ->route('apitoken.index')
            ->with('success', $this->created_message)
            ->with('plain_text_token', $plainTextToken)
            ->with('plain_text_client', $client->name);
    }

    public function regenerate(ApiClient $client)
    {
        if (! ApiToken::canCreate()) {
            return $this->_access_denied();
        }

        $plainTextToken = $this->issueToken($client);

        return redirect()
            ->route('apitoken.index')
            ->with('success', 'Token baharu telah dijana. Token lama tidak lagi sah.')
            ->with('plain_text_token', $plainTextToken)
            ->with('plain_text_client', $client->name);
    }

    public function revoke(ApiClient $client)
    {
        if (! ApiToken::canCreate()) {
            return $this->_access_denied();
        }

        $client->tokens()->delete();
        $client->plain_token = null;
        $client->save();

        return redirect()
            ->route('apitoken.index')
            ->with('success', 'Semua token untuk ' . $client->name . ' telah dibatalkan.');
    }

    public function generateToken(Request $request)
    {
        return response()->json([
            'message' => 'Token Sanctum dijana semasa simpan. Sila isi nama klien dan tekan Tambah Token.',
        ], 422);
    }

    protected function issueToken(ApiClient $client): string
    {
        $client->tokens()->delete();

        $plainTextToken = $client->createToken(
            $client->name . '-blacklist',
            ['blacklist:read']
        )->plainTextToken;

        $client->plain_token = $plainTextToken;
        $client->save();

        return $plainTextToken;
    }
}
