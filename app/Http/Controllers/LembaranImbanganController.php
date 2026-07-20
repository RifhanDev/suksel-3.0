<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\Tender;
use App\Models\LembaranImbangan;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LembaranImbanganController extends Controller
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

        // Load lembaran data scoped to vendor or PTJ
        $keys = $this->vendorFormRecordKeys($tender);
        $lembaranData = LembaranImbangan::where($keys)->first();

        if (! $lembaranData && $this->isVendorFormMode()) {
            $fallback = $this->loadVendorFormPayload($tender, 'lembaran_imbangan');
            if ($fallback) {
                $lembaranData = new LembaranImbangan($fallback);
            }
        }

        if (! $lembaranData && ! $this->isVendorFormMode()) {
            $lembaranData = LembaranImbangan::create(array_merge($keys, [
                'uuid'   => (string) Str::uuid(),
                'status' => 'draft',
            ]));
        }

        if (! $lembaranData) {
            $lembaranData = new LembaranImbangan();
        }

        return view('newModule.jawatankuasaSpesifikasi.form_lembaran_imbangan', array_merge(
            compact('tender', 'lembaranData'),
            $this->formViewVars($tender)
        ));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $validated = $request->validate([
            'aset_tetap'             => ['nullable', 'numeric', 'min:0'],
            'aset_semasa'            => ['nullable', 'numeric', 'min:0'],
            'liabiliti_semasa'       => ['nullable', 'numeric', 'min:0'],
            'liabiliti_tetap'        => ['nullable', 'numeric', 'min:0'],
            'wang_tunai'             => ['nullable', 'numeric', 'min:0'],
            'baki_kemudahan_kredit'  => ['nullable', 'numeric', 'min:0'],
            'status'                 => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $keys = $this->vendorFormRecordKeys($tender);
            $existing = LembaranImbangan::query()->where($keys)->first();

            $lembaran = LembaranImbangan::updateOrCreate(
                $keys,
                [
                    'uuid'                  => $existing?->uuid ?? (string) Str::uuid(),
                    'aset_tetap'            => $validated['aset_tetap'] ?? 0.00,
                    'aset_semasa'           => $validated['aset_semasa'] ?? 0.00,
                    'liabiliti_semasa'      => $validated['liabiliti_semasa'] ?? 0.00,
                    'liabiliti_tetap'       => $validated['liabiliti_tetap'] ?? 0.00,
                    'wang_tunai'            => $validated['wang_tunai'] ?? 0.00,
                    'baki_kemudahan_kredit' => $validated['baki_kemudahan_kredit'] ?? 0.00,
                    'status'                => $validated['status'] ?? 'submitted',
                    'created_by'            => $existing?->created_by ?? auth()->id(),
                    'updated_by'            => auth()->id(),
                ]
            );

            if ($this->isVendorFormMode()) {
                $this->persistVendorFormPayload($tender, 'lembaran_imbangan', $validated);
            }

            // Sync checklist item status to backend API
            $syncResponse = $this->api()->post(
                $this->url('kewangan-kerja/' . $tenderUuid . '/sync-status'),
                ['action_url' => '/lembaran-imbangan']
            );

            if (!$syncResponse->successful()) {
                Log::warning('LembaranImbanganController@store: failed to sync status to backend', [
                    'status' => $syncResponse->status(),
                    'body'   => $syncResponse->body(),
                ]);
            }

            if ($this->isVendorFormMode()) {
                $this->trackVendorFormSubmitted($tender, 'lembaran_imbangan', [
                    'text' => 'Lembaran imbangan disimpan',
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berjaya disimpan.',
                    'data'    => $lembaran
                ]);
            }

            if ($this->isVendorFormMode()) {
                return $this->vendorFormRedirect($request, $tender, 'Lembaran Imbangan berjaya disimpan.');
            }

            $redirect = $request->input('return');
            if ($redirect) {
                return redirect($redirect)->with('success', 'Lembaran Imbangan berjaya disimpan.');
            }

            return redirect()->route('senaraiKewanganKerja', $tenderUuid)->with('success', 'Lembaran Imbangan berjaya disimpan.');
        } catch (\Throwable $e) {
            Log::error('LembaranImbanganController@store failed', [
                'tender_uuid' => $tenderUuid,
                'error'       => $e->getMessage(),
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ralat semasa menyimpan.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Ralat semasa menyimpan Lembaran Imbangan.');
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
