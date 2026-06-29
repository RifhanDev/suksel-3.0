<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\BonSaham;
use App\Models\BonSahamAccount;
use App\Models\Tender;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BonSahamController extends Controller
{
    use HandlesTenderFormAccess;

    public function index(string $tenderUuid)
    {
        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        $this->ensureTenderFormAccess($tender);

        $bonSaham = BonSaham::with('accounts')
            ->where($this->vendorFormRecordKeys($tender))
            ->first();

        return view('newModule.jawatankuasaSpesifikasi.form_bon_saham', array_merge(
            compact('tender', 'bonSaham'),
            $this->formViewVars($tender)
        ));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $validated = $request->validate([
            'bank_institusi'   => ['nullable', 'array'],
            'bank_institusi.*' => ['nullable', 'string', 'max:255'],
            'jumlah_deposit'   => ['nullable', 'array'],
            'jumlah_deposit.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($validated, $tender) {
                $keys = $this->vendorFormRecordKeys($tender);
                $existing = BonSaham::query()->where($keys)->first();

                $bonSaham = BonSaham::updateOrCreate(
                    $keys,
                    [
                        'uuid'       => $existing?->uuid ?? (string) Str::uuid(),
                        'status'     => 'submitted',
                        'created_by' => $existing?->created_by ?? auth()->id(),
                        'updated_by' => auth()->id(),
                    ]
                );

                // Clear existing accounts
                $bonSaham->accounts()->delete();

                $total = 0.00;
                $banks = $validated['bank_institusi'] ?? [];
                $deposits = $validated['jumlah_deposit'] ?? [];
                $count = max(count($banks), count($deposits));

                for ($index = 0; $index < $count; $index++) {
                    $bank = trim($banks[$index] ?? '');
                    $deposit = (float) ($deposits[$index] ?? 0);
                    if ($bank !== '' || $deposit > 0) {
                        BonSahamAccount::create([
                            'uuid'           => (string) Str::uuid(),
                            'bon_saham_id'   => $bonSaham->id,
                            'bank_institusi' => $bank ?: null,
                            'jumlah_deposit' => $deposit,
                        ]);
                        $total += $deposit;
                    }
                }

                $bonSaham->update(['jumlah_keseluruhan' => $total]);

                if ($this->isVendorFormMode()) {
                    $this->persistVendorFormPayload($tender, 'bon_saham', [
                        'accounts' => collect($banks)->map(function ($bank, $index) use ($deposits) {
                            return [
                                'bank_institusi' => trim($bank ?? ''),
                                'jumlah_deposit' => (float) ($deposits[$index] ?? 0),
                            ];
                        })->filter(fn ($row) => $row['bank_institusi'] !== '' || $row['jumlah_deposit'] > 0)->values()->all(),
                        'jumlah_keseluruhan' => $total,
                    ]);
                }
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

            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'bon_saham', [
                    'text' => 'Bon atau saham disimpan',
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berjaya disimpan.',
                ]);
            }

            if ($this->isVendorFormMode()) {
                return $this->vendorFormRedirect($request, $tender, 'Maklumat Bon atau Saham berjaya disimpan.');
            }

            $redirect = $request->input('return');
            if ($redirect) {
                return redirect($redirect)->with('success', 'Maklumat Bon atau Saham berjaya disimpan.');
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

    private function api()
    {
        return StosBackendClient::http();
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }
}
