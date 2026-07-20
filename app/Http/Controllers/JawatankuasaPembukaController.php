<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JawatankuasaPembukaController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    public function index()
    {
        $tenders = Tender::query()
            ->with('tenderer')
            ->where('status_process_id', TenderProcessStatus::penilaianPembukaListStatus())
            ->orderByDesc('id')
            ->get()
            ->map(function (Tender $tender) {
                return [
                    'uuid' => $tender->uuid,
                    'name' => $tender->name ?: '-',
                    'no_tender' => $tender->no_tender ?: $tender->ref_number ?: '-',
                    'tarikh_jual' => $tender->advertise_start_date
                        ? Carbon::parse($tender->advertise_start_date)->format('d/m/Y')
                        : '-',
                    'tarikh_tutup' => $tender->advertise_stop_date
                        ? Carbon::parse($tender->advertise_stop_date)->format('d/m/Y')
                        : '-',
                    'harga' => number_format((float) ($tender->price ?? 0), 2),
                ];
            })
            ->values()
            ->all();

        return view('newModule.jawatankuasaPembuka.index', compact('tenders'));
    }

    public function show(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->query('tender'));

        if (! $tender) {
            return redirect()
                ->route('indexJawatankuasaPembuka')
                ->with('error', 'Tender tidak ditemui.');
        }

        $tender->loadMissing('tenderer');

        $tenderDokumen = TenderDokumenPresenter::for($tender);
        $participants = $tender->participants()
            ->where('participate', 1)
            ->with('vendor')
            ->orderBy('id')
            ->get();

        $dokumenByVendor = [];
        foreach ($participants as $participant) {
            $vendorId = (int) $participant->vendor_id;
            $dokumenByVendor[$vendorId] = $tenderDokumen->items('vendor', $vendorId);
        }

        $checklistItems = $tenderDokumen->items('admin');
        $teknikalItems = collect($checklistItems)
            ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['technical', 'spesifikasi_kerja'], true))
            ->values()
            ->all();
        $kewanganItems = collect($checklistItems)
            ->filter(fn (array $item) => in_array($item['source'] ?? $item['section'] ?? '', ['financial', 'kewangan_kerja'], true))
            ->values()
            ->all();

        $vendors = $participants->map(function ($participant) {
            return [
                'vendor_id' => (int) $participant->vendor_id,
                'name' => $participant->vendor?->name ?: ('Vendor #' . $participant->vendor_id),
                'kod' => $participant->vendor?->registration ?: (string) $participant->vendor_id,
            ];
        })->values()->all();

        $semakPayload = $this->buildSemakPayload($teknikalItems, $kewanganItems, $dokumenByVendor, $vendors);

        return view('newModule.jawatankuasaPembuka.jawatankuasa_pembuka', [
            'tender' => $tender,
            'tenderDokumen' => $tenderDokumen,
            'teknikalItems' => $teknikalItems,
            'kewanganItems' => $kewanganItems,
            'vendors' => $vendors,
            'dokumenByVendor' => $dokumenByVendor,
            'semakPayload' => $semakPayload,
        ]);
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        if (! $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::PENILAIAN_PEMBUKA,
            TenderProcessStatus::penilaianPembukaListStatus()
        )) {
            return response()->json([
                'message' => 'Tender belum sedia untuk penilaian pembuka (status ' . TenderProcessStatus::penilaianPembukaListStatus() . ').',
            ], 422);
        }

        return response()->json(['message' => 'Penilaian pembuka berjaya dihantar.']);
    }

    /**
     * @param  array<int, array<string, mixed>>  $teknikalItems
     * @param  array<int, array<string, mixed>>  $kewanganItems
     * @param  array<int, array<int, array<string, mixed>>>  $dokumenByVendor
     * @param  array<int, array{vendor_id: int, name: string, kod: string}>  $vendors
     * @return array<string, array<string, mixed>>
     */
    protected function buildSemakPayload(array $teknikalItems, array $kewanganItems, array $dokumenByVendor, array $vendors): array
    {
        $payload = [];

        foreach (array_merge($teknikalItems, $kewanganItems) as $item) {
            $uuid = (string) ($item['uuid'] ?? '');
            if ($uuid === '') {
                continue;
            }

            $vendorRows = [];
            foreach ($vendors as $vendor) {
                $vendorId = (int) $vendor['vendor_id'];
                $vendorItem = collect($dokumenByVendor[$vendorId] ?? [])
                    ->firstWhere('uuid', $uuid);

                $content = $vendorItem['vendor_content'] ?? [
                    'key_in' => null,
                    'specification' => [],
                    'files' => [],
                    'status' => 'draft',
                ];
                $status = $vendorItem['vendor_status'] ?? ($content['status'] ?? 'draft');
                $files = $content['files'] ?? [];

                $summary = match ($item['action'] ?? '') {
                    'vendor_upload', 'download_upload' => count($files) > 0
                        ? count($files) . ' fail dimuat naik'
                        : 'Tiada fail',
                    'key_in' => filled($content['key_in'] ?? null)
                        ? 'Telah diisi'
                        : 'Belum diisi',
                    'view_specification' => ($status === 'submitted')
                        ? 'Spesifikasi dihantar'
                        : 'Belum dihantar',
                    'online_form' => ($status === 'submitted')
                        ? 'Borang dihantar'
                        : 'Belum dihantar',
                    default => ($status === 'submitted') ? 'Dihantar' : 'Belum dihantar',
                };

                $vendorRows[] = [
                    'vendor_id' => $vendorId,
                    'name' => $vendor['name'],
                    'kod' => $vendor['kod'],
                    'status' => $status,
                    'status_label' => $status === 'submitted' ? 'Hantar' : 'Menunggu',
                    'summary' => $summary,
                    'files' => $files,
                    'form_url' => $vendorItem['admin_content']['form']['url'] ?? ($item['admin_content']['form']['url'] ?? null),
                    'form_key' => $item['admin_content']['form']['form_key'] ?? null,
                ];
            }

            $submittedCount = collect($vendorRows)
                ->where('status', 'submitted')
                ->count();

            $payload[$uuid] = [
                'uuid' => $uuid,
                'title' => $item['title'] ?? $item['nama'] ?? '-',
                'action' => $item['action'] ?? '',
                'tindakan' => $item['tindakan'] ?? '-',
                'submitted_count' => $submittedCount,
                'vendor_count' => count($vendors),
                'vendors' => $vendorRows,
            ];
        }

        return $payload;
    }
}
