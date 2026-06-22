<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\BonSaham;
use App\Models\BonSahamAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BonSahamController extends Controller
{
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $bonSaham = BonSaham::with('accounts')->where('tender_id', $tender->id)->first();

        return view('newModule.jawatankuasaSpesifikasi.form_bon_saham', compact('tender', 'bonSaham'));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();

        $validated = $request->validate([
            'bank_institusi'   => ['nullable', 'array'],
            'bank_institusi.*' => ['nullable', 'string', 'max:255'],
            'jumlah_deposit'   => ['nullable', 'array'],
            'jumlah_deposit.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($validated, $tender) {
                $bonSaham = BonSaham::updateOrCreate(
                    ['tender_id' => $tender->id],
                    [
                        'uuid'               => (string) Str::uuid(),
                        'jumlah_keseluruhan' => 0.00, // will sum up below
                        'status'             => 'submitted',
                        'created_by'         => auth()->id(),
                        'updated_by'         => auth()->id(),
                    ]
                );

                // Clear existing accounts
                $bonSaham->accounts()->delete();

                $total = 0.00;
                $banks = $validated['bank_institusi'] ?? [];
                $deposits = $validated['jumlah_deposit'] ?? [];

                foreach ($banks as $index => $bank) {
                    $deposit = $deposits[$index] ?? 0.00;
                    if (!empty($bank) || $deposit > 0) {
                        BonSahamAccount::create([
                            'uuid'           => (string) Str::uuid(),
                            'bon_saham_id'   => $bonSaham->id,
                            'bank_institusi' => $bank,
                            'jumlah_deposit' => $deposit,
                        ]);
                        $total += $deposit;
                    }
                }

                $bonSaham->update(['jumlah_keseluruhan' => $total]);
            });

            // Sync status to backend API
            $syncResponse = $this->api()->post(
                $this->url('kewangan-kerja/' . $tenderUuid . '/sync-status'),
                ['action_url' => '/bon-atau-saham']
            );

            if (!$syncResponse->successful()) {
                Log::warning('BonSahamController@store: failed to sync status to backend', [
                    'status' => $syncResponse->status(),
                    'body'   => $syncResponse->body(),
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berjaya disimpan.',
                ]);
            }

            return redirect()->route('senaraiKewanganKerja', $tenderUuid)->with('success', 'Maklumat Bon atau Saham berjaya disimpan.');
        } catch (\Throwable $e) {
            Log::error('BonSahamController@store failed', [
                'tender_uuid' => $tenderUuid,
                'error'       => $e->getMessage(),
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ralat semasa menyimpan.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Ralat semasa menyimpan Maklumat Bon atau Saham.');
        }
    }

    private function ensureAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function api()
    {
        return Http::withoutVerifying()->timeout(30)->withHeaders([
            'X-Api-Key' => config('services.stos_backend.api_key'),
            'Accept'    => 'application/json',
        ]);
    }

    private function url(string $path): string
    {
        return config('services.stos_backend.url') . '/api/' . $path;
    }
}
