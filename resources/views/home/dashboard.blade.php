@extends('layouts.modernLanding')

@section('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
        /* ── Dashboard card ── */
        .db-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        /* ── Tab header ── */
        .db-card-header {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 20px;
        }
        .db-card-header .nav-tabs {
            border-bottom: none;
            gap: 2px;
        }
        .db-card-header .nav-link {
            color: #6b7280;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 12px 14px;
            border: none;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            transition: color 0.15s, border-color 0.15s;
            white-space: nowrap;
        }
        .db-card-header .nav-link:hover { color: #c41e3a; }
        .db-card-header .nav-link.active {
            color: #c41e3a;
            border-bottom-color: #c41e3a;
            background: transparent;
        }
        .db-card-header .nav-link .badge {
            background: #f3f4f6 !important;
            color: #374151;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }
        .db-card-header .nav-link.active .badge {
            background: rgba(196,30,58,0.1) !important;
            color: #c41e3a;
        }

        /* ── Action buttons ── */
        .db-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .db-action-btn-primary { background: #c41e3a; color: #fff; border: 1px solid #c41e3a; }
        .db-action-btn-primary:hover { background: #a01830; border-color: #a01830; color: #fff; }
        .db-action-btn-secondary { background: #fff; color: #374151; border: 1px solid #d1d5db; }
        .db-action-btn-secondary:hover { background: #f9fafb; color: #111827; }

        /* ── News sidebar ── */
        .db-news-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .db-news-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .db-news-icon {
            width: 30px; height: 30px;
            background: rgba(196,30,58,0.08);
            color: #c41e3a;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .db-news-title { font-size: 0.8rem; font-weight: 700; color: #111827; margin: 0; }
        .db-news-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 16px;
            border-bottom: 1px solid #f9fafb;
            text-decoration: none;
            transition: background 0.15s;
        }
        .db-news-item:hover { background: #fafafa; }
        .db-news-date {
            display: flex; flex-direction: column; align-items: center;
            min-width: 32px;
            background: #f3f4f6;
            border-radius: 6px;
            padding: 4px 6px;
            flex-shrink: 0;
        }
        .db-news-date .day { font-size: 0.85rem; font-weight: 800; color: #c41e3a; line-height: 1; }
        .db-news-date .month { font-size: 0.6rem; font-weight: 600; color: #6b7280; text-transform: uppercase; }
        .db-news-text { font-size: 0.75rem; font-weight: 600; color: #1f2937; line-height: 1.35;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .db-news-year { font-size: 0.62rem; color: #9ca3af; margin-top: 3px; }
    </style>
@endsection

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Dashboard Vendor</h3>
            <p class="text-muted small m-0">
                Ringkasan tender / sebut harga, dokumen dibeli, jemputan terhad dan pemulangan semula.
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ asset('dashboard') }}" class="db-action-btn db-action-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Maklumat Tender / Sebut Harga
            </a>
            <a href="{{ asset('vendor') }}" class="db-action-btn db-action-btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Maklumat Syarikat
            </a>
        </div>
    </div>

    <div class="row g-4" style="align-items:flex-start;">

        {{-- LEFT: Tab card --}}
        <div class="col-lg-9">
            <div class="db-card">

                <div class="db-card-header">
                    <ul class="nav nav-tabs flex-wrap" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="#db-recom" data-bs-toggle="tab" role="tab"
                                aria-controls="db-recom" aria-selected="true">
                                Anggaran Layak
                                <span class="badge ms-1">{{ count($eligibles) }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#db-docs" data-bs-toggle="tab" role="tab"
                                aria-controls="db-docs" aria-selected="false">
                                Dokumen Dibeli
                                <span class="badge ms-1">{{ count($purchases) }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#db-invites" data-bs-toggle="tab" role="tab"
                                aria-controls="db-invites" aria-selected="false">
                                Tender Terhad
                                <span class="badge ms-1">{{ count($invites) }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#db-refund" data-bs-toggle="tab" role="tab"
                                aria-controls="db-refund" aria-selected="false">
                                Pemulangan Semula
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#db-penilaian-prestasi" data-bs-toggle="tab"
                                role="tab" aria-controls="db-penilaian-prestasi" aria-selected="false">
                                Penilaian Prestasi
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="p-4">
                    <div class="tab-content">

                        <div class="tab-pane active" id="db-recom">
                            @if (count($eligibles) > 0)
                                <div class="table-responsive">
                                    <table class="DT2 table table-hover table-bordered align-middle mb-0">
                                        <thead style="background: #f8fafc;">
                                            <tr>
                                                <th class="text-uppercase fw-bold py-3 ps-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">Tender / Sebut Harga</th>
                                                <th class="text-uppercase fw-bold py-3 pe-3 text-end" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb; width:160px;">Tarikh Tutup</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($eligibles as $tender)
                                                <tr style="border-color:#e5e7eb;">
                                                    <td class="py-3 ps-3" style="border-color:#e5e7eb;">
                                                        <a href="{{ asset('tenders/' . $tender->id) }}" class="fw-semibold mb-1 d-block" style="font-size:0.85rem;">{{ $tender->name }}</a>
                                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                                            <span class="text-muted" style="font-size:0.72rem;">{{ optional($tender->tenderer)->name ?? '-' }}</span>
                                                            @if($tender->ref_number)
                                                                <span class="text-muted" style="font-size:0.72rem;">·</span>
                                                                <span class="fw-semibold" style="font-size:0.72rem; color:#374151;">{{ $tender->ref_number }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-3 pe-3 text-end text-nowrap" style="border-color:#e5e7eb;">
                                                        <div class="fw-semibold" style="font-size:0.82rem; color:#1f2937;">{{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}</div>
                                                        <div class="text-muted" style="font-size:0.7rem;">12:00 PM</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">Tiada tender yang layak buat masa ini.</div>
                            @endif
                        </div>

                        <div class="tab-pane" id="db-docs">
                            @if (count($purchases) > 0)
                                <div class="table-responsive">
                                    <table class="DT3 table table-hover table-bordered align-middle mb-0">
                                        <thead style="background: #f8fafc;">
                                            <tr>
                                                <th class="text-uppercase fw-bold py-3 ps-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">Tender / Sebut Harga</th>
                                                <th class="text-uppercase fw-bold py-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb; width:150px;">Tarikh Tutup</th>
                                                <th class="text-uppercase fw-bold py-3 pe-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb; width:180px;">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purchases as $purchase)
                                                <tr style="border-color:#e5e7eb;">
                                                    <td class="py-3 ps-3" style="border-color:#e5e7eb;">
                                                        <a href="{{ asset('tenders/' . $purchase->tender->id) }}" class="fw-semibold mb-1 d-block" style="font-size:0.85rem;">{{ $purchase->tender->name }}</a>
                                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                                            <span class="text-muted" style="font-size:0.72rem;">{{ optional($purchase->tender->tenderer)->name ?? '-' }}</span>
                                                            @if($purchase->tender->ref_number)
                                                                <span class="text-muted" style="font-size:0.72rem;">·</span>
                                                                <span class="fw-semibold" style="font-size:0.72rem; color:#374151;">{{ $purchase->tender->ref_number }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-3 text-nowrap" style="border-color:#e5e7eb;">
                                                        <div class="fw-semibold" style="font-size:0.82rem; color:#1f2937;">{{ \Carbon\Carbon::parse($purchase->tender->submission_datetime)->format('j M Y') }}</div>
                                                        <div class="text-muted" style="font-size:0.7rem;">12:00 PM</div>
                                                    </td>
                                                    <td class="py-3 pe-3" style="border-color:#e5e7eb;">
                                                        <div class="d-flex flex-column gap-1">
                                                            <a href="{{ asset('tenders/' . $purchase->tender_id . '/receipt/' . $purchase->id) }}" target="_blank"
                                                                style="display:inline-flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:600; color:#0369a1; text-decoration:none; padding:4px 8px; background:#f0f9ff; border-radius:5px; border:1px solid #bae6fd;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                                Resit
                                                            </a>
                                                            <a href="{{ asset('tenders/' . $purchase->tender_id . '/document/' . $purchase->id) }}" target="_blank"
                                                                style="display:inline-flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:600; color:#92400e; text-decoration:none; padding:4px 8px; background:#fffbeb; border-radius:5px; border:1px solid #fde68a;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                                No. Siri Dokumen
                                                            </a>
                                                            <a href="{{ asset('tenders/' . $purchase->tender_id) }}#tf-doc2" target="_blank"
                                                                style="display:inline-flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:600; color:#166534; text-decoration:none; padding:4px 8px; background:#f0fdf4; border-radius:5px; border:1px solid #bbf7d0;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                                                Muat Turun
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">Tiada dokumen yang dibeli.</div>
                            @endif
                        </div>

                        <div class="tab-pane" id="db-invites">
                            @if (count($invites) > 0)
                                <div class="table-responsive">
                                    <table class="DT2 table table-hover table-bordered align-middle mb-0">
                                        <thead style="background: #f8fafc;">
                                            <tr>
                                                <th class="text-uppercase fw-bold py-3 ps-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">Tender / Sebut Harga</th>
                                                <th class="text-uppercase fw-bold py-3 pe-3 text-end" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb; width:160px;">Tarikh Tutup</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invites as $invite)
                                                <tr style="border-color:#e5e7eb;">
                                                    <td class="py-3 ps-3" style="border-color:#e5e7eb;">
                                                        <a href="{{ asset('tenders/' . $invite->tender->id) }}" class="fw-semibold mb-1 d-block" style="font-size:0.85rem;">{{ $invite->tender->name }}</a>
                                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                                            <span class="text-muted" style="font-size:0.72rem;">{{ optional($invite->tender->tenderer)->name ?? '-' }}</span>
                                                            @if($invite->tender->ref_number)
                                                                <span class="text-muted" style="font-size:0.72rem;">·</span>
                                                                <span class="fw-semibold" style="font-size:0.72rem; color:#374151;">{{ $invite->tender->ref_number }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-3 pe-3 text-end text-nowrap" style="border-color:#e5e7eb;">
                                                        <div class="fw-semibold" style="font-size:0.82rem; color:#1f2937;">{{ \Carbon\Carbon::parse($invite->tender->submission_datetime)->format('j M Y') }}</div>
                                                        <div class="text-muted" style="font-size:0.7rem;">12:00 PM</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">Tiada jemputan tender.</div>
                            @endif
                        </div>

                        <div class="tab-pane" id="db-refund">

                            {{-- Info box --}}
                            <div class="rounded-2 mb-4 overflow-hidden" style="border: 1px solid #bae6fd;">
                                <div class="d-flex align-items-center gap-2 px-4 py-3" style="background:#0ea5e9;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <span style="font-size:0.78rem; font-weight:700; color:#fff; letter-spacing:0.3px;">Arahan / Makluman Berkaitan</span>
                                </div>
                                <div style="background:#f0f9ff; padding: 16px 20px;">
                                    <ol style="margin:0; padding-left:18px; font-size:0.8rem; color:#0c4a6e; line-height:1.8;">
                                        <li>Muat turun 'Templat Surat Permohonan' yang disediakan.</li>
                                        <li>Sila <b>tukar</b> kandungan dokumen tersebut yang berwarna <span style="color:#dc2626;">merah</span> dengan maklumat pemohon dan <b>hitamkan</b> semula.</li>
                                        <li>
                                            Selepas permohonan diluluskan oleh BPM, <span style="text-decoration:underline;">semua penyata, resit, surat dan borang yang lengkap wajib perlu dicetak dan dihantar secara pos / fizikal</span> ke alamat berikut:
                                            <div style="margin-top:10px; margin-left:4px; border-left:2px dashed #93c5fd; padding-left:14px;">
                                                <div style="display:inline-flex; align-items:stretch; border-radius:8px; overflow:hidden; border:1px solid #bae6fd; box-shadow:0 1px 4px rgba(3,105,161,0.08);">
                                                    <div style="background:#0369a1; padding:10px 12px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; flex-shrink:0;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                                        <span style="font-size:0.55rem; font-weight:800; color:#bae6fd; letter-spacing:0.5px; writing-mode:vertical-rl; text-orientation:mixed; transform:rotate(180deg);">ALAMAT</span>
                                                    </div>
                                                    <div style="background:#f0f9ff; padding:10px 16px;">
                                                        <div style="font-size:0.7rem; font-weight:600; color:#0369a1; letter-spacing:0.3px; margin-bottom:4px; text-transform:uppercase;">Hantar Dokumen Ke:</div>
                                                        <div style="font-size:0.82rem; font-weight:700; color:#0c4a6e; line-height:1.8;">
                                                            Bahagian Khidmat Pengurusan,<br>
                                                            Unit Kewangan, Tingkat 17,<br>
                                                            Bangunan Sultan Salahuddin Abdul Aziz Shah,<br>
                                                            40503 Shah Alam, Selangor Darul Ehsan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <a href="{{ route('refunds.create') }}" class="btn-form btn-form-create">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Permohonan Baru
                                </a>
                                <a download href="{{ asset('file/Template Surat Permohonan Pelanggan 2022.docx') }}" class="btn-form btn-form-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Templat Surat Permohonan
                                </a>
                            </div>

                            {{-- Refund table --}}
                            <div class="table-responsive">
                                <table class="DT4 table table-hover table-bordered align-middle mb-0">
                                    <thead style="background:#f8fafc;">
                                        <tr>
                                            <th class="text-uppercase fw-bold py-3 ps-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">No Rujukan</th>
                                            <th class="text-uppercase fw-bold py-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">Tarikh Dimohon</th>
                                            <th class="text-uppercase fw-bold py-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">No Resit</th>
                                            <th class="text-uppercase fw-bold py-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">Tarikh Dikemaskini</th>
                                            <th class="text-uppercase fw-bold py-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">Status</th>
                                            <th class="text-uppercase fw-bold py-3" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb;">Amaun</th>
                                            <th class="text-uppercase fw-bold py-3 pe-3 text-center" style="font-size:0.68rem; letter-spacing:0.5px; color:#6b7280; border-color:#e5e7eb; width:100px;">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($refunds as $refund)
                                            <tr style="border-color:#e5e7eb;">
                                                <td class="py-3 ps-3" style="border-color:#e5e7eb; font-size:0.82rem; font-weight:600; color:#1f2937;">{{ $refund->ref_num }}</td>
                                                <td class="py-3" style="border-color:#e5e7eb; font-size:0.82rem; color:#374151;">{{ date('d-m-Y', strtotime($refund->created_at)) }}</td>
                                                <td class="py-3" style="border-color:#e5e7eb; font-size:0.82rem; color:#374151;">{{ $refund->receipt }}</td>
                                                <td class="py-3" style="border-color:#e5e7eb; font-size:0.82rem; color:#374151;">{{ date('d-m-Y', strtotime($refund->updated_at)) }}</td>
                                                <td class="py-3" style="border-color:#e5e7eb;">
                                                    <span style="display:inline-block; font-size:0.7rem; font-weight:700; padding:3px 10px; border-radius:20px; background:#f3f4f6; color:#374151;">{{ $refund->status }}</span>
                                                </td>
                                                <td class="py-3" style="border-color:#e5e7eb; font-size:0.82rem; font-weight:600; color:#1f2937;">{{ $refund->amount }}</td>
                                                <td class="py-3 pe-3 text-center" style="border-color:#e5e7eb;">
                                                    <a href="{{ route('refunds.show', $refund->id) }}"
                                                        style="display:inline-flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:600; color:#0369a1; text-decoration:none; padding:4px 10px; background:#f0f9ff; border-radius:5px; border:1px solid #bae6fd;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                        Papar
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="db-penilaian-prestasi">
                            @include('home.tab-contents.penilaian-prestasi')
                        </div>

                    </div>{{-- /.tab-content --}}
                </div>{{-- /.p-4 --}}

            </div>{{-- /.db-card --}}
        </div>{{-- /.col-lg-9 --}}

        {{-- RIGHT: News sidebar --}}
        <div class="col-lg-3">
            @if ($global_news ?? false)
                <div class="db-news-card">
                    <div class="db-news-header">
                        <div class="db-news-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1-4 0v-13a1 1 0 0 0-1-1h-10a1 1 0 0 0-1 1v12a3 3 0 0 0 3 3h11"/><path d="M8 8l4 0"/><path d="M8 12l4 0"/><path d="M8 16l4 0"/></svg>
                        </div>
                        <p class="db-news-title">Berita Terkini</p>
                    </div>
                    <div style="overflow:hidden; max-height:420px;" id="db-news-ticker">
                        <div>
                        @foreach ($global_news as $news)
                            <a href="{{ asset('news/' . $news->id) }}" class="db-news-item">
                                <div class="db-news-date">
                                    <span class="day">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('d') }}</span>
                                    <span class="month">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('M') }}</span>
                                </div>
                                <div>
                                    <div class="db-news-text">{{ $news->title }}</div>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <span class="news-tag">Berita</span>
                                        <span class="db-news-year">{{ \Carbon\Carbon::parse($news->published_at ?: $news->created_at)->format('Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                        </div>
                    </div>
                    <div class="p-3 border-top bg-light">
                        <a href="/news" class="btn btn-outline-danger btn-sm w-100 fw-bold" style="font-size: 0.75rem;">LIHAT SEMUA</a>
                    </div>
                </div>
            @endif
        </div>{{-- /.col-lg-3 --}}

    </div>{{-- /.row --}}

@endsection

@section('scripts')
    {{-- DataTables 2.x is already loaded in layouts/v3/master.blade.php - do not load datatables.js (1.x) --}}
    <script src="{{ asset('js/easy-ticker.js') }}"></script>
    <script src="{{ asset('js/news.js') }}"></script>
    <script>
        $(function() {
            $('.DT2').DataTable({ order: [[1, 'asc']] });
            $('.DT3').DataTable({ order: [[1, 'desc']] });
            $('.DT4').DataTable({ order: [[1, 'desc']] });

            $('#db-news-ticker').easyTicker({
                direction: 'up',
                easing: 'swing',
                speed: 'slow',
                interval: 3000,
                height: 'auto',
                visible: 5,
                mousePause: 1
            });
        });
    </script>
@endsection
