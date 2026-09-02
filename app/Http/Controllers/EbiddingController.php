<?php

namespace App\Http\Controllers;

use App\Models\EbiddingKertasTaklimat;
use App\Models\EbiddingKertasTaklimatItem;
use App\Models\EbiddingKertasTaklimatItemFile;
use App\Models\EbiddingJadualBidaan;
use App\Models\EbiddingPengesyoranPembekal;
use App\Models\EbiddingVendorBidItem;
use App\Models\JawatankuasaPerolehanPemilihanItem;
use App\Models\PerakuanJabatanKertasTaklimat;
use App\TenderEligible;
use App\Tender;
use App\TenderVendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EbiddingController extends Controller
{
    private const STAGE_AGENCY_ADMIN = 1;
    private const STAGE_VENDOR = 2;
    private const STAGE_AGENCY_ADMIN_REVIEW = 3;
    private const STAGE_ADMIN_SULP = 4;

    private const EBIDDING_ALLOWED_STAGES = [
        self::STAGE_AGENCY_ADMIN,
        self::STAGE_VENDOR,
        self::STAGE_AGENCY_ADMIN_REVIEW,
        self::STAGE_ADMIN_SULP,
    ];

    private const EBIDDING_TRANSITIONS = [
        self::STAGE_AGENCY_ADMIN => self::STAGE_VENDOR,
        self::STAGE_VENDOR => self::STAGE_AGENCY_ADMIN_REVIEW,
        self::STAGE_AGENCY_ADMIN_REVIEW => self::STAGE_ADMIN_SULP,
    ];

    private const LEGACY_STATUS_TO_STAGE = [
        4 => self::STAGE_AGENCY_ADMIN,
        5 => self::STAGE_VENDOR,
        6 => self::STAGE_AGENCY_ADMIN_REVIEW,
        7 => self::STAGE_ADMIN_SULP,
    ];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $method = $request->route()?->getActionMethod();
            $vendorMethods = ['show', 'hantarVendorBidaan'];

            if ($user && $user->hasRole('Vendor') && in_array($method, $vendorMethods, true)) {
                return $next($request);
            }

            if ($denied = $this->denyUnlessMenu('Bidding:list')) {
                return $denied;
            }

            return $next($request);
        });
    }

    /**
     * Senarai tender yang telah melengkapkan Jawatankuasa Perolehan (status 4)
     dan ditandakan untuk proses e-bidding.
     */
    public function index()
    {
        $rows = Tender::query()
            ->where('is_ebidding', true)
            ->where(function ($query) {
                $query->whereIn('ebidding_process_stage_id', self::EBIDDING_ALLOWED_STAGES)
                    ->orWhereIn('status_process_id', array_keys(self::LEGACY_STATUS_TO_STAGE));
            })
            ->orderByDesc('id')
            ->get([
                'id',
                'uuid',
                'no_tender',
                'ref_number',
                'name',
                'submission_datetime',
            ])
            ->map(function (Tender $tender) {
                $submissionDate = null;
                if (!empty($tender->submission_datetime)) {
                    $submissionDate = Carbon::parse($tender->submission_datetime);
                }

                $noTender = $tender->no_tender ?: $tender->ref_number ?: '-';
                $tajukPlain = $tender->name ?: '-';
                // $link = route('keputusanMesyuarat', ['tender' => $tender->uuid]);
                $link = route('eBidding.show', ['id' => $tender->id]);

                $stage = $this->resolveEbiddingStage($tender);
                $statusLabel = $this->statusLabel($stage, (int) $tender->id);

                return [
                    'no_tender' => $noTender,
                    'tajuk_plain' => $tajukPlain,
                    'tajuk_html' => '<a href="' . e($link) . '" class="fw-semibold text-primary text-decoration-none">' . e($tajukPlain) . '</a>',
                    'tarikh' => $submissionDate ? $submissionDate->format('d/m/Y') : '-',
                    'status_key' => $statusLabel,
                    'status_html' => '<span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill small fw-semibold" style="background:#fef3c7;color:#b45309;">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>'
                        . ' ' . e($statusLabel) . '</span>',
                ];
            })
            ->values();

        return view('newModule.eBidding.index', compact('rows'));
    }

    public function show($id)
    {
        $tender = Tender::findOrFail($id);
        $currentStage = $this->resolveEbiddingStage($tender);
        $window = $this->biddingWindowState((int) $tender->id);
        $currentUser = Auth::user();
        $isVendorUser = Auth::check() && $currentUser && $this->isVendorUserById((int) $currentUser->id);
        $vendorId = $isVendorUser ? (int) ($currentUser->vendor_id ?? 0) : 0;
        if (!$isVendorUser && $currentStage === self::STAGE_VENDOR && $window['has_ended']) {
            Tender::query()->where('id', $tender->id)->update([
                'ebidding_process_stage_id' => self::STAGE_AGENCY_ADMIN_REVIEW,
                'status_process_id' => 2,
            ]);
            $tender->ebidding_process_stage_id = self::STAGE_AGENCY_ADMIN_REVIEW;
            $tender->status_process_id = 2;
            $currentStage = self::STAGE_AGENCY_ADMIN_REVIEW;
        }
        $pengesyoran = EbiddingPengesyoranPembekal::query()
            ->where('tender_id', $tender->id)
            ->first();
        $ebTaklimat = EbiddingKertasTaklimat::query()
            ->firstOrCreate(['tender_id' => $tender->id], ['catatan' => null, 'submitted_at' => null]);
        $this->seedDefaultTaklimatItems($ebTaklimat);

        $perakuanTaklimat = PerakuanJabatanKertasTaklimat::query()
            ->where('tender_id', $tender->id)
            ->with(['items.files'])
            ->first();
        $perakuanItems = $perakuanTaklimat
            ? $perakuanTaklimat->items->keyBy(fn($i) => (string) ($i->slot_key ?? ''))
            : collect();

        $ebTaklimatRows = $ebTaklimat->items()
            ->with('files')
            ->orderBy('sort_order')
            ->get()
            ->map(function (EbiddingKertasTaklimatItem $item) use ($perakuanItems) {
                $perakuanItem = null;
                if (in_array($item->slot_key, ['pembuka', 'teknikal', 'kewangan', 'kertas_perakuan'], true)) {
                    $perakuanItem = $perakuanItems->get((string) $item->slot_key);
                }

                $files = collect();
                if ($perakuanItem) {
                    $files = $perakuanItem->files->map(function ($f) {
                        return [
                            'id' => 'pj-' . $f->id,
                            'name' => $f->file_original_name,
                            'url' => $f->file_path ? asset($f->file_path) : '#',
                            'source' => 'perakuan',
                            'can_delete' => false,
                        ];
                    });
                } else {
                    $files = $item->files->map(function (EbiddingKertasTaklimatItemFile $f) {
                        return [
                            'id' => (int) $f->id,
                            'name' => $f->file_original_name,
                            'url' => $f->file_path ? asset($f->file_path) : '#',
                            'source' => 'ebidding',
                            'can_delete' => true,
                        ];
                    });
                }

                $canUpload = !in_array($item->slot_key, ['pembuka', 'teknikal', 'kewangan', 'kertas_perakuan'], true);
                $canDelete = $item->slot_key === null;

                return [
                    'id' => (int) $item->id,
                    'slot_key' => $item->slot_key,
                    'kandungan' => $item->kandungan,
                    'can_upload' => $canUpload,
                    'can_delete' => $canDelete,
                    'files' => $files->values(),
                ];
            })
            ->values();
        $jadualBidaan = EbiddingJadualBidaan::query()
            ->firstOrCreate(['tender_id' => $tender->id]);
        $agencyPemilihanItems = JawatankuasaPerolehanPemilihanItem::query()
            ->where('tender_id', $tender->id)
            ->with(['petenders' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $bidByItem = EbiddingVendorBidItem::query()
            ->where('tender_id', $tender->id)
            ->whereNotNull('submitted_at')
            ->orderBy('vendor_id')
            ->get()
            ->groupBy('pemilihan_item_id');

        $agencyPemilihanItems = $agencyPemilihanItems->map(function ($item) use ($bidByItem) {
            $prices = ($bidByItem->get($item->id, collect()))
                ->pluck('bid_price')
                ->values();

            $petenders = $item->petenders->values()->map(function ($petender, $idx) use ($prices) {
                $bidaan = $prices->get($idx);
                return [
                    'bil_label' => (string) ($petender->bil_label ?? ''),
                    'status_bumiputra' => (string) ($petender->status_bumiputra ?? ''),
                    'harga_tawaran' => (float) ($petender->harga_tawaran ?? 0),
                    'jumlah_skor' => (string) ($petender->jumlah_skor ?? ''),
                    'kedudukan_penilaian' => (string) ($petender->kedudukan_penilaian ?? ''),
                    'status_mof' => (string) ($petender->status_mof ?? ''),
                    'tindakan_disiplin' => (string) ($petender->tindakan_disiplin ?? ''),
                    'lembaga_pengarah_url' => $petender->lembaga_pengarah_file_path ? asset($petender->lembaga_pengarah_file_path) : null,
                    'kaedah_sulp' => 'Bidaan',
                    'harga_bidaan' => $bidaan !== null ? (float) $bidaan : (float) ($petender->harga_tawaran ?? 0),
                ];
            });

            return [
                'item' => (string) ($item->perihal_item ?? ''),
                'jenis_item' => (string) ($item->jenis_item ?? ''),
                'unit_ukuran' => (string) ($item->unit_ukuran ?? ''),
                'jenis_harga' => (string) ($item->jenis_harga ?? ''),
                'petenders' => $petenders,
            ];
        })->values();

        if ($isVendorUser) {
            $vendorItems = JawatankuasaPerolehanPemilihanItem::query()
                ->where('tender_id', $tender->id)
                ->orderBy('sort_order')
                ->get()
                ->map(function (JawatankuasaPerolehanPemilihanItem $item) use ($tender, $vendorId) {
                    $bid = EbiddingVendorBidItem::query()
                        ->where('tender_id', $tender->id)
                        ->where('vendor_id', $vendorId)
                        ->where('pemilihan_item_id', $item->id)
                        ->first();
                    $tenderVendor = TenderVendor::query()
                        ->where('tender_id', $tender->id)
                        ->where('vendor_id', $vendorId)
                        ->orderByDesc('id')
                        ->first();
                    $previousPrice = $tenderVendor ? (float) $tenderVendor->amount : null;
                    $effectiveBid = $bid ? (float) $bid->bid_price : $previousPrice;

                    return [
                        'pemilihan_item_id' => (int) $item->id,
                        'spesifikasi' => (string) $item->perihal_item,
                        'kuantiti' => (string) $item->kuantiti,
                        'unit_ukuran' => (string) ($item->unit_ukuran ?? '-'),
                        'pematuhan' => '-',
                        'cadangan_petender' => '-',
                        'previous_price' => $previousPrice !== null ? number_format($previousPrice, 2, '.', '') : '',
                        'bid_price' => $effectiveBid !== null ? number_format($effectiveBid, 2, '.', '') : '',
                    ];
                })
                ->values();

            $canVendorEditBid = $currentStage === self::STAGE_VENDOR && $window['is_open'];
            $hasVendorSubmitted = EbiddingVendorBidItem::query()
                ->where('tender_id', $tender->id)
                ->where('vendor_id', $vendorId)
                ->whereNotNull('submitted_at')
                ->exists();

            return view('newModule.eBidding.vendor_bidding', [
                'tender' => $tender,
                'jadualBidaan' => $jadualBidaan,
                'vendorItems' => $vendorItems,
                'canVendorEditBid' => $canVendorEditBid,
                'hasVendorSubmitted' => $hasVendorSubmitted,
                'window' => $window,
            ]);
        }

        $isRestrictedEbidding = (bool) $tender->is_ebidding
            && $currentStage === self::STAGE_AGENCY_ADMIN;

        $visibleTabs = $isRestrictedEbidding
            ? ['pengesyoran', 'taklimat', 'jadual-bidaan']
            : ['penyediaan', 'taklimat', 'pemilihan', 'pengesyoran', 'jadual-bidaan', 'keputusan'];

        return view('newModule.eBidding.keptusan_mesyuarat', compact(
            'tender',
            'visibleTabs',
            'isRestrictedEbidding',
            'pengesyoran',
            'ebTaklimat',
            'ebTaklimatRows',
            'jadualBidaan',
            'currentStage',
            'agencyPemilihanItems',
        ));
    }

    public function hantarVendorBidaan(Request $request, $id)
    {
        $tender = Tender::query()->findOrFail($id);
        $currentStage = $this->resolveEbiddingStage($tender);
        if ($currentStage !== self::STAGE_VENDOR) {
            return response()->json(['message' => 'Bidaan vendor hanya dibenarkan pada peringkat Vendor.'], 422);
        }

        $currentUser = Auth::user();
        $isVendorUser = Auth::check() && $currentUser && $this->isVendorUserById((int) $currentUser->id);
        if (!$isVendorUser) {
            return response()->json(['message' => 'Hanya vendor dibenarkan menghantar harga bidaan.'], 403);
        }
        $vendorId = (int) ($currentUser->vendor_id ?? 0);
        if ($vendorId <= 0) {
            return response()->json(['message' => 'Profil vendor tidak sah.'], 422);
        }

        $window = $this->biddingWindowState((int) $tender->id);
        if (!$window['is_open']) {
            return response()->json(['message' => 'Bidaan tidak berada dalam tempoh aktif.'], 422);
        }

        $payload = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.pemilihan_item_id' => ['required', 'integer'],
            'items.*.bid_price' => ['nullable', 'numeric', 'min:0.01'],
        ]);

        $nextStage = self::EBIDDING_TRANSITIONS[$currentStage] ?? self::STAGE_AGENCY_ADMIN_REVIEW;
        DB::transaction(function () use ($payload, $tender, $vendorId, $nextStage) {
            $tenderVendor = TenderVendor::query()
                ->where('tender_id', $tender->id)
                ->where('vendor_id', $vendorId)
                ->orderByDesc('id')
                ->first();
            $basePrice = $tenderVendor ? (float) $tenderVendor->amount : null;

            foreach ($payload['items'] as $row) {
                $item = JawatankuasaPerolehanPemilihanItem::query()
                    ->where('id', (int) $row['pemilihan_item_id'])
                    ->where('tender_id', $tender->id)
                    ->first();
                if (!$item) {
                    continue;
                }

                $existing = EbiddingVendorBidItem::query()
                    ->where('tender_id', $tender->id)
                    ->where('vendor_id', $vendorId)
                    ->where('pemilihan_item_id', $item->id)
                    ->first();

                $inputPrice = isset($row['bid_price']) && $row['bid_price'] !== '' ? (float) $row['bid_price'] : null;
                $finalPrice = $inputPrice ?? ($existing ? (float) $existing->bid_price : $basePrice);
                if ($finalPrice === null || $finalPrice <= 0) {
                    continue;
                }

                EbiddingVendorBidItem::query()->updateOrCreate(
                    [
                        'tender_id' => $tender->id,
                        'vendor_id' => $vendorId,
                        'pemilihan_item_id' => $item->id,
                    ],
                    [
                        'bid_price' => $finalPrice,
                        'submitted_at' => now(),
                    ]
                );
            }

            Tender::query()
                ->where('id', $tender->id)
                ->update([
                    'ebidding_process_stage_id' => $nextStage,
                    'status_process_id' => 2,
                ]);
        });

        return response()->json([
            'message' => 'Harga bidaan berjaya dihantar. Status eBidding dikemas kini ke Agency Admin (Semakan) dan proses kembali ke Perakuan Jabatan.',
            'next_status_label' => $this->statusLabel($nextStage, (int) $tender->id),
            'redirect_url' => route('eBidding.index'),
        ]);
    }

    public function simpanKertasTaklimat(Request $request, $id)
    {
        $tender = Tender::query()->findOrFail($id);
        if (!(bool) $tender->is_ebidding) {
            return response()->json(['message' => 'Tender ini bukan dalam aliran e-bidding.'], 422);
        }
        $this->persistKertasTaklimat($request, $tender, false);

        return response()->json(['message' => 'Kertas taklimat berjaya disimpan.']);
    }

    public function hantarKertasTaklimat(Request $request, $id)
    {
        $tender = Tender::query()->findOrFail($id);
        if (!(bool) $tender->is_ebidding) {
            return response()->json(['message' => 'Tender ini bukan dalam aliran e-bidding.'], 422);
        }
        $this->persistKertasTaklimat($request, $tender, true);

        return response()->json(['message' => 'Kertas taklimat berjaya dihantar.']);
    }

    public function simpanPengesyoranPembekal(Request $request, $id)
    {
        $tender = Tender::query()->findOrFail($id);
        if (!(bool) $tender->is_ebidding) {
            return response()->json(['message' => 'Tender ini bukan dalam aliran e-bidding.'], 422);
        }

        $payload = $request->validate([
            'catatan' => ['nullable', 'string', 'max:65535'],
        ]);

        EbiddingPengesyoranPembekal::query()->updateOrCreate(
            ['tender_id' => $tender->id],
            [
                'catatan' => $this->nullableTrim($payload['catatan'] ?? null),
                'submitted_at' => null,
            ]
        );

        return response()->json(['message' => 'Pemilihan Pembekal berjaya disimpan.']);
    }

    public function simpanJadualBidaan(Request $request, $id)
    {
        $tender = Tender::query()->findOrFail($id);
        if (!(bool) $tender->is_ebidding) {
            return response()->json(['message' => 'Tender ini bukan dalam aliran e-bidding.'], 422);
        }

        $payload = $this->validateJadualBidaan($request, false);
        EbiddingJadualBidaan::query()->updateOrCreate(
            ['tender_id' => $tender->id],
            [
                'tarikh_bidaan_mula' => $payload['tarikh_bidaan_mula'] ?? null,
                'masa_bidaan_mula' => $payload['masa_bidaan_mula'] ?? null,
                'tarikh_bidaan_tamat' => $payload['tarikh_bidaan_tamat'] ?? null,
                'masa_bidaan_tamat' => $payload['masa_bidaan_tamat'] ?? null,
            ]
        );

        return response()->json(['message' => 'Jadual bidaan berjaya disimpan.']);
    }

    public function mulaBidaan(Request $request, $id)
    {
        $tender = Tender::query()->findOrFail($id);
        if (!(bool) $tender->is_ebidding) {
            return response()->json(['message' => 'Tender ini bukan dalam aliran e-bidding.'], 422);
        }

        $payload = $this->validateJadualBidaan($request, true);
        $currentStage = $this->resolveEbiddingStage($tender);
        $nextStage = self::EBIDDING_TRANSITIONS[$currentStage] ?? null;
        if ($nextStage === null) {
            return response()->json(['message' => 'Tiada peringkat seterusnya untuk status semasa.'], 422);
        }

        DB::transaction(function () use ($tender, $payload, $nextStage) {
            EbiddingJadualBidaan::query()->updateOrCreate(
                ['tender_id' => $tender->id],
                [
                    'tarikh_bidaan_mula' => $payload['tarikh_bidaan_mula'],
                    'masa_bidaan_mula' => $payload['masa_bidaan_mula'],
                    'tarikh_bidaan_tamat' => $payload['tarikh_bidaan_tamat'],
                    'masa_bidaan_tamat' => $payload['masa_bidaan_tamat'],
                    'started_at' => now(),
                    'submitted_at' => now(),
                ]
            );

            Tender::query()
                ->where('id', $tender->id)
                ->update(['ebidding_process_stage_id' => $nextStage]);
        });

        $emailsSent = $this->notifyEligibleVendorsBidaanStarted($tender->id);

        return response()->json([
            'message' => 'Bidaan berjaya dimulakan. Emel jemputan dihantar kepada ' . $emailsSent . ' vendor.',
            'next_status_label' => $this->statusLabel($nextStage),
            'emails_sent' => $emailsSent,
        ]);
    }

    public function hantarPengesyoranPembekal(Request $request, $id)
    {
        $tender = Tender::query()->findOrFail($id);
        if (!(bool) $tender->is_ebidding) {
            return response()->json(['message' => 'Tender ini bukan dalam aliran e-bidding.'], 422);
        }

        $payload = $request->validate([
            'catatan' => ['required', 'string', 'max:65535'],
        ], [
            'catatan.required' => 'Catatan adalah wajib sebelum hantar.',
        ]);

        $currentStage = $this->resolveEbiddingStage($tender);
        $nextStage = self::EBIDDING_TRANSITIONS[$currentStage] ?? null;
        if ($nextStage === null) {
            return response()->json(['message' => 'Tiada peringkat seterusnya untuk status semasa.'], 422);
        }

        DB::transaction(function () use ($tender, $payload, $nextStage) {
            EbiddingPengesyoranPembekal::query()->updateOrCreate(
                ['tender_id' => $tender->id],
                [
                    'catatan' => $this->nullableTrim($payload['catatan'] ?? null),
                    'submitted_at' => now(),
                ]
            );

            Tender::query()
                ->where('id', $tender->id)
                ->update(['ebidding_process_stage_id' => $nextStage]);
        });

        return response()->json([
            'message' => 'Pemilihan Pembekal berjaya dihantar.',
            'next_status_label' => $this->statusLabel($nextStage),
        ]);
    }

    public function advanceStage($id)
    {
        $tender = Tender::query()->findOrFail($id);

        if (!(bool) $tender->is_ebidding) {
            return response()->json(['message' => 'Tender ini bukan dalam aliran e-bidding.'], 422);
        }

        $currentStage = $this->resolveEbiddingStage($tender);
        $nextStage = self::EBIDDING_TRANSITIONS[$currentStage] ?? null;

        if ($currentStage === self::STAGE_VENDOR) {
            $window = $this->biddingWindowState((int) $tender->id);
            if (!$window['has_schedule']) {
                return response()->json(['message' => 'Jadual bidaan belum ditetapkan.'], 422);
            }
            if (!$window['has_ended']) {
                return response()->json(['message' => 'Bidaan masih berlangsung atau belum bermula. Sila tunggu sehingga masa tamat bidaan.'], 422);
            }
        }

        if ($nextStage === null) {
            return response()->json(['message' => 'Tiada peringkat seterusnya untuk status semasa.'], 422);
        }

        $updateData = ['ebidding_process_stage_id' => $nextStage];
        if ($currentStage === self::STAGE_VENDOR && $nextStage === self::STAGE_AGENCY_ADMIN_REVIEW) {
            $updateData['status_process_id'] = 2;
        }

        Tender::query()
            ->where('id', $tender->id)
            ->update($updateData);

        return response()->json([
            'message' => 'Status e-bidding berjaya dikemas kini.',
            'current_stage' => $currentStage,
            'next_stage' => $nextStage,
            'next_status_label' => $this->statusLabel($nextStage),
        ]);
    }

    private function statusLabel(?int $stage, ?int $tenderId = null): string
    {
        if ((int) $stage === self::STAGE_VENDOR && $tenderId) {
            $window = $this->biddingWindowState($tenderId);
            if (!$window['has_schedule']) {
                return 'Vendor (Jadual Belum Diset)';
            }
            if ($window['is_open']) {
                return 'Vendor (Bidaan Dibuka)';
            }
            if ($window['has_ended']) {
                return 'Vendor (Bidaan Ditutup)';
            }
            return 'Vendor (Menunggu Masa Mula)';
        }

        $labels = [
            self::STAGE_AGENCY_ADMIN => 'Agency Admin',
            self::STAGE_VENDOR => 'Vendor',
            self::STAGE_AGENCY_ADMIN_REVIEW => 'Agency Admin (Semakan)',
            self::STAGE_ADMIN_SULP => 'Admin SULP',
        ];

        return $labels[$stage] ?? 'Dalam Proses';
    }

    private function resolveEbiddingStage(Tender $tender): int
    {
        $stage = (int) ($tender->ebidding_process_stage_id ?? 0);
        if ($stage > 0) {
            return $stage;
        }

        $legacyStatus = (int) ($tender->status_process_id ?? 0);
        return self::LEGACY_STATUS_TO_STAGE[$legacyStatus] ?? self::STAGE_AGENCY_ADMIN;
    }

    private function seedDefaultTaklimatItems(EbiddingKertasTaklimat $header): void
    {
        if ($header->items()->exists()) {
            return;
        }

        $defaults = [
            ['slot_key' => 'pembuka', 'kandungan' => 'Laporan Jawatankuasa Pembuka', 'sort_order' => 1],
            ['slot_key' => 'teknikal', 'kandungan' => 'Laporan Jawatankuasa Teknikal', 'sort_order' => 2],
            ['slot_key' => 'kewangan', 'kandungan' => 'Laporan Jawatankuasa Kewangan', 'sort_order' => 3],
            ['slot_key' => 'kertas_perakuan', 'kandungan' => 'Kertas Taklimat (Perakuan Jabatan)', 'sort_order' => 4],
            ['slot_key' => 'ringkasan', 'kandungan' => 'Ringkasan Kertas Taklimat (wajib untuk tender)', 'sort_order' => 5],
            ['slot_key' => 'laporan_bidaan', 'kandungan' => 'Laporan Bidaan', 'sort_order' => 6],
        ];

        foreach ($defaults as $row) {
            $header->items()->create($row);
        }
    }

    private function persistKertasTaklimat(Request $request, Tender $tender, bool $submit): void
    {
        $header = EbiddingKertasTaklimat::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['catatan' => null, 'submitted_at' => null]
        );
        $this->seedDefaultTaklimatItems($header);

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:65535'],
            'rows' => ['nullable', 'array'],
            'rows.*.id' => ['nullable', 'integer'],
            'rows.*.kandungan' => ['required_with:rows', 'string', 'max:500'],
            'rows.*.files' => ['nullable', 'array'],
            'rows.*.files.*' => ['file', 'max:10240'],
            'deleted_item_ids' => ['nullable', 'array'],
            'deleted_item_ids.*' => ['integer'],
            'deleted_file_ids' => ['nullable', 'array'],
            'deleted_file_ids.*' => ['integer'],
        ]);

        $rows = $validated['rows'] ?? [];
        DB::transaction(function () use ($header, $validated, $rows, $request, $tender, $submit) {
            foreach ($validated['deleted_file_ids'] ?? [] as $fileId) {
                $file = EbiddingKertasTaklimatItemFile::query()->find($fileId);
                if (!$file) {
                    continue;
                }
                $item = $file->item;
                if (!$item || (int) $item->kertas_taklimat_id !== (int) $header->id) {
                    continue;
                }
                $this->deleteStoredFile($file->file_path);
                $file->delete();
            }

            foreach ($validated['deleted_item_ids'] ?? [] as $itemId) {
                $item = EbiddingKertasTaklimatItem::query()
                    ->where('id', $itemId)
                    ->where('kertas_taklimat_id', $header->id)
                    ->first();
                if (!$item || $item->slot_key !== null) {
                    continue;
                }
                foreach ($item->files as $f) {
                    $this->deleteStoredFile($f->file_path);
                    $f->delete();
                }
                $item->delete();
            }

            $header->catatan = $this->nullableTrim($validated['catatan'] ?? null);
            if ($submit) {
                $header->submitted_at = now();
            }
            $header->save();

            $maxSort = (int) $header->items()->max('sort_order');
            $itemByIndex = [];
            foreach ($rows as $idx => $row) {
                $id = $row['id'] ?? null;
                if ($id) {
                    $item = EbiddingKertasTaklimatItem::query()
                        ->where('id', $id)
                        ->where('kertas_taklimat_id', $header->id)
                        ->first();
                    if (!$item) {
                        continue;
                    }
                    if ($item->slot_key === null) {
                        $item->kandungan = $row['kandungan'];
                        $item->save();
                    }
                    $itemByIndex[$idx] = $item;
                } else {
                    $maxSort++;
                    $itemByIndex[$idx] = $header->items()->create([
                        'slot_key' => null,
                        'kandungan' => $row['kandungan'],
                        'sort_order' => $maxSort,
                    ]);
                }
            }

            foreach ($rows as $idx => $row) {
                if (!isset($itemByIndex[$idx])) {
                    continue;
                }
                $item = $itemByIndex[$idx];
                if (in_array($item->slot_key, ['pembuka', 'teknikal', 'kewangan', 'kertas_perakuan'], true)) {
                    continue;
                }

                $uploads = $request->file("rows.$idx.files") ?? [];
                foreach ((array) $uploads as $upload) {
                    if ($upload instanceof UploadedFile && $upload->isValid()) {
                        $this->storeTaklimatFile($item, $upload, (int) $tender->id);
                    }
                }
            }
        });
    }

    private function storeTaklimatFile(EbiddingKertasTaklimatItem $item, UploadedFile $file, int $tenderId): void
    {
        $relativeDir = 'uploads/ebidding/' . $tenderId . '/kertas_taklimat';
        $absoluteDir = public_path($relativeDir);
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755, true);
        }
        $safeOriginal = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = date('YmdHis') . '_' . $safeOriginal;
        $file->move($absoluteDir, $fileName);
        $relative = $relativeDir . '/' . $fileName;

        $item->files()->create([
            'file_path' => $relative,
            'file_original_name' => $file->getClientOriginalName(),
        ]);
    }

    private function deleteStoredFile(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }
        $full = public_path($relativePath);
        if (is_file($full)) {
            @unlink($full);
        }
    }

    private function validateJadualBidaan(Request $request, bool $forStart): array
    {
        $rules = [
            'tarikh_bidaan_mula' => [$forStart ? 'required' : 'nullable', 'date'],
            'masa_bidaan_mula' => [$forStart ? 'required' : 'nullable', 'date_format:H:i'],
            'tarikh_bidaan_tamat' => [$forStart ? 'required' : 'nullable', 'date'],
            'masa_bidaan_tamat' => [$forStart ? 'required' : 'nullable', 'date_format:H:i'],
        ];

        $payload = $request->validate($rules, [
            'tarikh_bidaan_mula.required' => 'Tarikh Bidaan Mula wajib diisi.',
            'masa_bidaan_mula.required' => 'Masa Bidaan Mula wajib diisi.',
            'tarikh_bidaan_tamat.required' => 'Tarikh Bidaan Tamat wajib diisi.',
            'masa_bidaan_tamat.required' => 'Masa Bidaan Tamat wajib diisi.',
        ]);

        if (
            !empty($payload['tarikh_bidaan_mula']) && !empty($payload['masa_bidaan_mula']) &&
            !empty($payload['tarikh_bidaan_tamat']) && !empty($payload['masa_bidaan_tamat'])
        ) {
            $mula = Carbon::parse($payload['tarikh_bidaan_mula'] . ' ' . $payload['masa_bidaan_mula']);
            $tamat = Carbon::parse($payload['tarikh_bidaan_tamat'] . ' ' . $payload['masa_bidaan_tamat']);
            if ($tamat->lessThanOrEqualTo($mula)) {
                throw ValidationException::withMessages([
                    'masa_bidaan_tamat' => 'Masa/Tarikh tamat bidaan mesti selepas masa/tarikh mula.',
                ]);
            }
        }

        return $payload;
    }

    private function biddingWindowState(int $tenderId): array
    {
        $schedule = EbiddingJadualBidaan::query()->where('tender_id', $tenderId)->first();
        if (!$schedule || !$schedule->tarikh_bidaan_mula || !$schedule->masa_bidaan_mula || !$schedule->tarikh_bidaan_tamat || !$schedule->masa_bidaan_tamat) {
            return [
                'has_schedule' => false,
                'is_open' => false,
                'has_started' => false,
                'has_ended' => false,
            ];
        }

        $startAt = Carbon::parse($schedule->tarikh_bidaan_mula->format('Y-m-d') . ' ' . $schedule->masa_bidaan_mula);
        $endAt = Carbon::parse($schedule->tarikh_bidaan_tamat->format('Y-m-d') . ' ' . $schedule->masa_bidaan_tamat);
        $now = Carbon::now();

        return [
            'has_schedule' => true,
            'is_open' => $now->betweenIncluded($startAt, $endAt),
            'has_started' => $now->greaterThanOrEqualTo($startAt),
            'has_ended' => $now->greaterThan($endAt),
        ];
    }

    private function notifyEligibleVendorsBidaanStarted(int $tenderId): int
    {
        $tender = Tender::query()->find($tenderId);
        if (!$tender) {
            return 0;
        }

        $eligibles = TenderEligible::query()
            ->with(['vendor.user'])
            ->where('tender_id', $tenderId)
            ->where(function ($q) {
                $q->whereNull('email')->orWhere('email', 1)->orWhere('email', '1');
            })
            ->get();

        $count = 0;
        foreach ($eligibles as $eligible) {
            $vendor = $eligible->vendor;
            $user = $vendor ? $vendor->user : null;
            $to = trim((string) ($user->email ?? ''));
            if (!$vendor || !$user || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $subject = 'Sistem Tender Online Selangor: Jemputan Menyertai Bidaan - ' . $tender->name;
            $status = $this->sendMail('html', $to, $subject, '', 'tenders.emails.eligible', [
                'tender_id' => $tender->id,
                'vendor_id' => $vendor->id,
            ]);

            if (is_string($status) && stripos($status, 'Email') !== false) {
                $count++;
                $eligible->update(['sent_at' => now()]);
            }
        }

        return $count;
    }

    private function isVendorUserById(int $userId): bool
    {
        if ($userId <= 0) {
            return false;
        }

        return DB::table('role_user')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $userId)
            ->where('roles.name', 'Vendor')
            ->exists();
    }

    private function nullableTrim($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }
        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }
}
