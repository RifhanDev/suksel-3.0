<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\LembaranImbangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LembaranImbanganController extends Controller
{
    public function index(string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();

        // Load lembaran data locally
        $lembaranData = LembaranImbangan::where('tender_id', $tender->id)->first();
        if (!$lembaranData) {
            $lembaranData = LembaranImbangan::create([
                'uuid'      => (string) Str::uuid(),
                'tender_id' => $tender->id,
                'status'    => 'draft',
            ]);
        }

        return view('newModule.jawatankuasaSpesifikasi.form_lembaran_imbangan', compact('tender', 'lembaranData'));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $this->ensureAccess();

        $tender = Tender::where('uuid', $tenderUuid)->firstOrFail();

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
            $lembaran = LembaranImbangan::updateOrCreate(
                ['tender_id' => $tender->id],
                [
                    'uuid'                  => (string) Str::uuid(),
                    'aset_tetap'            => $validated['aset_tetap'] ?? 0.00,
                    'aset_semasa'           => $validated['aset_semasa'] ?? 0.00,
                    'liabiliti_semasa'      => $validated['liabiliti_semasa'] ?? 0.00,
                    'liabiliti_tetap'       => $validated['liabiliti_tetap'] ?? 0.00,
                    'wang_tunai'            => $validated['wang_tunai'] ?? 0.00,
                    'baki_kemudahan_kredit' => $validated['baki_kemudahan_kredit'] ?? 0.00,
                    'status'                => $validated['status'] ?? 'draft',
                    'created_by'            => auth()->id(),
                    'updated_by'            => auth()->id(),
                ]
            );

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

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berjaya disimpan.',
                    'data'    => $lembaran
                ]);
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
