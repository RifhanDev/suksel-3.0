@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/guideline-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/modal-confirm.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/stepper.css') }}" rel="stylesheet">

    <style>
        /* =========================
        SECTION TITLES
        ========================= */
        .card-title-grey {
            background: #F9FAFB;
            padding: 12px 16px;
            border-left: 5px solid var(--sg-red, #c41e3a);
            font-weight: 700;
            font-size: 15px;
            border-radius: 6px;
        }

        /* =========================
        RUMUSAN RESULT TABLES (Step 1 summary)
        Clean, flat editorial data table.
        ========================= */
        .rumusan-table {
            margin-bottom: 0;
            /* Matches the card's 12px radius. */
            border-radius: 12px;
            overflow: hidden;
            /* separate + 0 spacing so the radius wraps every corner, not just the top. */
            border-collapse: separate;
            border-spacing: 0;
            /* Full outer border so the table body doesn't blend into the background. */
            border: 1px solid #e5e7eb;
        }

        .rumusan-table thead th {
            background: #dbeafe;
            color: #1e293b;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 0;
        }

        .rumusan-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
            color: #334155;
        }

        .rumusan-table tbody tr:last-child td {
            border-bottom: 1px solid #e5e7eb;
        }

        .rumusan-table tfoot td {
            padding: 12px 16px;
            background: #f8fafc;
            text-align: right;
        }

        .rumusan-total-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b;
        }

        .rumusan-total-value {
            font-size: 1.15rem;
            font-weight: 800;
            margin-left: 12px;
            vertical-align: -1px;
        }

        /* One block per failed checklist item; catatan sits under its own reason, not the evaluator's note run into the sentence. */
        .rumusan-reason-item + .rumusan-reason-item {
            margin-top: 8px;
        }

        .rumusan-reason-catatan {
            font-size: 0.78rem;
            font-style: italic;
            color: #64748b;
            margin-top: 2px;
        }

        /* =========================
       RUMUSAN SECTION HEADING
    ========================= */
        .rumusan-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rumusan-heading-title {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: -0.2px;
            color: #1e293b;
            line-height: 1.3;
        }

        .rumusan-heading-sub {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 1px;
        }

        /* ==========================
       ACTION BUTTON LAYOUT
       (buttons themselves use the shared .btn-form component —
        see public/css/components/button-components.css)
    ========================== */
        .d-flex.justify-content-end.gap-2 .btn-form,
        .d-flex.justify-content-between .btn-form {
            min-width: 120px;
            justify-content: center;
        }

        /* ==========================
       FORM
    ========================== */
        .form-check-label {
            font-size: 13px;
        }

        /* ==========================
       MODAL — page-specific functional fixes only
       (modal chrome uses the Bootstrap defaults from modern.css)
    ========================== */
        /* Status Pematuhan dropdown: enough width, no overlap with chevron */
        #modalSemakanKetepatanDokumenTeknikal select.form-select {
            min-width: 100%;
            width: 100%;
            padding-right: 2.25rem;
            box-sizing: border-box;
        }

        #modalSemakanKetepatanDokumenTeknikal td:nth-child(3) {
            min-width: 200px;
        }

        #modalViewDokumenTeknikal .modal-body iframe {
            background: #fff;
        }

        /* Avoids the modal body stretching the dialog when the iframe is small. */
        #modalViewDokumenTeknikal .modal-body {
            flex: 0 0 auto;
        }

        /* Shared "Tajuk / Dokumen" card, used by every Langkah 1/2 modal — not ID-scoped. */
        .spesifikasi-tajuk-card {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-left: 3px solid var(--sg-red, #c41e3a);
            border-radius: 10px;
            padding: 14px 18px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        }

        .spesifikasi-tajuk-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff1f2;
            color: var(--sg-red, #c41e3a);
            border: 1px solid #ffe4e6;
            flex-shrink: 0;
        }

        .spesifikasi-tajuk-icon svg {
            width: 18px;
            height: 18px;
        }

        .spesifikasi-tajuk-label {
            display: block;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 3px;
        }

        .spesifikasi-tajuk-value {
            display: block;
            font-size: 0.98rem;
            font-weight: 700;
            color: #1e293b;
        }

        .spesifikasi-section-label {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #475569;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        /* Modal chrome shared across every Langkah 1/2 modal. */
        .teknikal-modal-footer {
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }

        .teknikal-modal-eyebrow {
            display: block;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 2px;
        }

    </style>
@endsection

@section('content')

<!-- Tender info card -->
<div class="tender-header-card mb-4">
    <div class="tender-page-header">
        <div class="tender-ref-label">
            <span class="tender-type-label">Sebut Harga / Tender</span>
            <span class="tender-ref-sep">&middot;</span>
            <span class="tender-ref-no">{{ $tender_no ?? 'Belum Dijana' }}</span>
        </div>
        <h2 class="tender-title-main mb-3">Tender Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</h2>

        <div class="row g-3 pb-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">PTJ</span>
                    <span class="fw-semibold text-dark" style="font-size:0.88rem;">BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN</span>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Tempoh Sah Laku Tawaran (Hari)</span>
                    <span class="fw-semibold text-dark" style="font-size:0.88rem;">90</span>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Sah Laku Tawaran Tamat</span>
                    <span class="fw-semibold text-dark" style="font-size:0.88rem;">17/01/2022</span>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Status</span>
                    <div>
                        <span class="badge-status badge-status-warning">Menunggu Penilaian Cadangan Teknikal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 px-0">

    <div class="content-card p-0">
        <div class="content-card-body p-4">
            <div class="row">
                <div id="custom-progress-bar" class="progress-nav mb-4 p-2">

                    <ul class="nav progress-wrapper" role="tablist">

                        <li class="nav-item progress-step active" role="Pematuhan Dokumentasi">
                            <button type="button"
                                id="pematuhan-tab"
                                class="nav-link step-number active"
                                data-bs-target="#pematuhan"
                                role="tab">1</button>
                            <div class="step-label">Pematuhan Dokumentasi</div>
                        </li>

                        <li class="nav-item progress-step" role="Pematuhan Spesifikasi Teknikal">
                            <button type="button"
                                id="penilaian-tab"
                                class="nav-link step-number"
                                data-bs-target="#penilaian"
                                role="tab">2</button>
                            <div class="step-label">Pematuhan Spesifikasi Teknikal</div>
                        </li>

                        <li class="nav-item progress-step" role="Penyediaan Laporan">
                            <button type="button"
                                id="laporan-tab"
                                class="nav-link step-number"
                                data-bs-target="#laporan"
                                role="tab">3</button>
                            <div class="step-label">Penyediaan Laporan</div>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="tab-content px-3" id="application-content">

                <!-- Outer Tab 1 Content -->
                <div class="tab-pane fade show active" id="pematuhan" role="tabpanel"
                    aria-labelledby="pematuhan-tab">

                    <!-- Inner tabs for outer tab 1 -->
                    <ul class="nav segmented-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#teknikal-1" role="tab"
                                aria-selected="true">Teknikal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#rumusan-1" role="tab"
                                aria-selected="false">Rumusan</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="teknikal-1" role="tabpanel">
                            <!-- Content for Teknikal of progress 1 -->
                            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                                <div class="content-card-icon" style="width: 42px; height: 42px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 11l3 3L22 4"></path>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.2px;">Pematuhan Cadangan Teknikal</h4>
                                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Semak dan sahkan pematuhan dokumentasi bagi setiap petender.</p>
                                </div>
                            </div>
                            <div class="guideline-card mb-3">
                                <div class="guideline-card-header" style="margin-bottom: 0;">
                                    <span class="guideline-card-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="16" x2="12" y2="12"></line>
                                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                        </svg>
                                    </span>
                                    <span class="guideline-item-text mb-0">Klik butang <span class="highlight">Menilai</span> untuk meneruskan penilaian pematuhan bagi setiap dokumen.</span>
                                </div>
                            </div>
                            <table class="table table-bordered table-slate dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 60px;">No.</th>
                                        <th class="text-center">Tajuk / Dokumen</th>
                                        <th class="text-center">Mekanisma</th>
                                        <th class="text-center">Status Penilaian</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                {{-- Rows come from technical_checklist_items, synced from Senarai Semak Teknikal. --}}
                                <tbody>
                                    @forelse ($step1Items as $item)
                                        <tr data-mekanisma="{{ $item['mechanism'] }}" data-item-uuid="{{ $item['uuid'] }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item['title'] }}</td>
                                            <td class="text-center">{{ $item['mechanism_label'] }}</td>
                                            <td class="text-center">
                                                <span class="badge-status {{ $item['status_badge_class'] }} status-penilaian-badge">{{ $item['status_label'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php $btnLabel = $item['status_label'] === 'Telah Dinilai' ? 'Papar' : 'Menilai'; @endphp
                                                @if ($item['can_menilai'])
                                                    @if ($item['is_spesifikasi'])
                                                        <button type="button"
                                                            class="btn-form btn-form-success btn-semakan-spesifikasi-teknikal"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalSemakanSpesifikasiTeknikalStep1"
                                                            data-item-uuid="{{ $item['uuid'] }}"
                                                            data-tajuk="{{ $item['title'] }}">
                                                            {{ $btnLabel }}
                                                        </button>
                                                    @elseif ($item['is_borang_atas_talian'])
                                                        <button type="button"
                                                            class="btn-form btn-form-success btn-semakan-borang-teknikal"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalSemakanBorangAtasTalianStep1"
                                                            data-item-uuid="{{ $item['uuid'] }}"
                                                            data-tajuk="{{ $item['title'] }}"
                                                            data-form-url="{{ $item['form_url'] }}">
                                                            {{ $btnLabel }}
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn-form btn-form-success btn-semakan-dok-teknikal"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalSemakanKetepatanDokumenTeknikal"
                                                            data-item-uuid="{{ $item['uuid'] }}"
                                                            data-tajuk="{{ $item['title'] }}">
                                                            {{ $btnLabel }}
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">&mdash;</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Tiada item pematuhan dokumentasi untuk tender ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                {{-- Original dummy data — kept for design reference.
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX</td>
                                        <td>Spesifikasi</td>
                                        <td>Menunggu Penyerahan</td>
                                        <td class="text-center">
                                            <button type="button"
                                                id="btnStep1Menilai"
                                                class="btn-form btn-form-success btn-semakan-dok-teknikal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSemakanKetepatanDokumenTeknikal"
                                                data-tajuk="Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX"
                                                data-doc-pembekal-1="Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf"
                                                data-doc-pembekal-2="Salinan Sijil Pendaftaran dengan Kementerian Kewangan.pdf"
                                                data-doc-url="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf">
                                                Menilai
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-mekanisma="Petender Muat Naik">
                                        <td class="text-center">2</td>
                                        <td>Surat Pengesahan Prinsipal yang lengkap ditandatangani</td>
                                        <td>Petender Muat Naik</td>
                                        <td>Selesai</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn-form btn-form-success btn-semakan-dok-teknikal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSemakanKetepatanDokumenTeknikal"
                                                data-tajuk="Surat Pengesahan Prinsipal yang lengkap ditandatangani"
                                                data-doc-pembekal-1="Surat Pengesahan Prinsipal — Pembekal 1.pdf"
                                                data-doc-pembekal-2="Surat Pengesahan Prinsipal — Pembekal 2.pdf"
                                                data-doc-url="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf">
                                                Menilai
                                            </button>
                                        </td>
                                    </tr>
                                    <tr data-mekanisma="Petender Muat Naik">
                                        <td class="text-center">3</td>
                                        <td>Senarai Kakitangan Teknikal dan Carta Organisasi Pasukan Projek</td>
                                        <td>Petender Muat Naik</td>
                                        <td>Selesai</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn-form btn-form-success btn-semakan-dok-teknikal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalSemakanKetepatanDokumenTeknikal"
                                                data-tajuk="Senarai Kakitangan Teknikal dan Carta Organisasi Pasukan Projek"
                                                data-doc-pembekal-1="Senarai Kakitangan dan Carta Organisasi — Pembekal 1.pdf"
                                                data-doc-pembekal-2="Senarai Kakitangan dan Carta Organisasi — Pembekal 2.pdf"
                                                data-doc-url="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf">
                                                Menilai
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                --}}
                            </table>
                            <div class="row mb-3 px-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button class="btn-form btn-form-primary btn-seterusnya">Seterusnya</button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="rumusan-1" role="tabpanel" aria-labelledby="rumusan-tab">
                            <div class="mt-2">
                                <!-- SECTION 1: Pembekal Melepasi -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="rumusan-icon" style="background: #dcfce7;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="rumusan-heading-title">Pembekal Melepasi Pematuhan Dokumentasi</div>
                                            <div class="rumusan-heading-sub">Senarai pembekal yang lulus semakan pematuhan dokumentasi.</div>
                                        </div>
                                    </div>
                                    <table class="table rumusan-table align-middle">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 120px;">Bil</th>
                                                <th>Ulasan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rumusanMelepasiTbody">
                                            <tr>
                                                <td colspan="2" class="text-center text-muted" style="padding: 18px 16px;">Memuatkan...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="rumusan-total-label">Jumlah Pembekal Melepasi</span>
                                                    <span class="rumusan-total-value" id="rumusanMelepasiTotal" style="color: #16a34a;">0</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Declaration gate -->
                                <label for="confirmLayak"
                                    class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4"
                                    style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 3px solid var(--sg-red, #c41e3a); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); cursor: {{ $pematuhanConfirmed ? 'default' : 'pointer' }};">
                                    <input class="form-check-input flex-shrink-0" type="checkbox" id="confirmLayak"
                                        style="width: 1.3rem; height: 1.3rem; cursor: {{ $pematuhanConfirmed ? 'default' : 'pointer' }};" @checked($pematuhanConfirmed) @disabled($pematuhanConfirmed)>
                                    <span class="d-flex flex-column">
                                        <span class="fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.4;">Saya mengesahkan petender di atas layak untuk penilaian peringkat seterusnya.</span>
                                        <span class="text-muted" style="font-size: 0.78rem;">Tandakan pengesahan ini untuk membuka penilaian peringkat seterusnya.</span>
                                    </span>
                                </label>

                                <!-- SECTION 2: Pembekal Tidak Melepasi -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="rumusan-icon" style="background: #fee2e2;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                                <line x1="9" y1="9" x2="15" y2="15"></line>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="rumusan-heading-title">Pembekal Tidak Melepasi Pematuhan Dokumentasi</div>
                                            <div class="rumusan-heading-sub">Senarai pembekal yang gagal semakan pematuhan dokumentasi.</div>
                                        </div>
                                    </div>
                                    <table class="table rumusan-table align-middle">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 120px;">Bil</th>
                                                <th>Ulasan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rumusanTidakMelepasiTbody">
                                            <tr>
                                                <td colspan="2" class="text-center text-muted" style="padding: 18px 16px;">Memuatkan...</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">
                                                    <span class="rumusan-total-label">Jumlah Pembekal Tidak Melepasi</span>
                                                    <span class="rumusan-total-value" id="rumusanTidakMelepasiTotal" style="color: #dc2626;">0</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn-form btn-form-secondary" id="btnKembaliRumusanPematuhan">Kembali</button>
                                    <button class="btn-form btn-form-primary btn-seterusnya">Seterusnya</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outer Tab 2 Content (Step 2: Pematuhan Spesifikasi Teknikal - from teknikal_step2.blade.php) -->
                <div class="tab-pane fade" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">
                    @include('newModule.penilaian_teknikal.teknikal_step2')
                </div>

                <!-- Outer Tab 3 Content -->
                <div class="tab-pane fade" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
                    @include('newModule.penilaian_teknikal.teknikal_step3')
                </div>

            </div>

        </div>
    </div>

@push('modals')
    {{-- Confirms before submitting Pematuhan Dokumentasi — eliminates failing vendors, irreversible. --}}
    <div class="modal fade" id="modalKonfirmasiHantarPematuhan" tabindex="-1" aria-labelledby="modalKonfirmasiHantarPematuhanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-confirm">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
                    <div class="modal-confirm-center">
                        <div class="modal-confirm-icon modal-confirm-icon--warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                        <h5 class="modal-confirm-title" id="modalKonfirmasiHantarPematuhanLabel">Sahkan Penghantaran</h5>
                        <p class="modal-confirm-desc" id="modalKonfirmasiHantarPematuhanDesc">
                            Pembekal yang tidak melepasi pematuhan dokumentasi akan disingkirkan daripada baki proses ini. Tindakan ini tidak boleh dibuat asal.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-center gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btnKonfirmasiHantarPematuhan">Ya, Hantar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Confirms Langkah 2 -> 3 — no elimination yet, but this locks every Langkah 2 score. --}}
    <div class="modal fade" id="modalKonfirmasiSahkanSpesifikasi" tabindex="-1" aria-labelledby="modalKonfirmasiSahkanSpesifikasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-confirm">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
                    <div class="modal-confirm-center">
                        <div class="modal-confirm-icon modal-confirm-icon--warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                        <h5 class="modal-confirm-title" id="modalKonfirmasiSahkanSpesifikasiLabel">Sahkan Penilaian</h5>
                        <p class="modal-confirm-desc">
                            Semua skor penilaian spesifikasi teknikal akan dikunci dan tidak boleh dikemaskini lagi selepas ini. Teruskan ke Langkah 3?
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-center gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btnKonfirmasiSahkanSpesifikasi">Ya, Sahkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Shared modal for Petender/PTJ Muat Naik items — documents sourced via VendorDokumenResponseService. --}}
    <div class="modal fade" id="modalSemakanKetepatanDokumenTeknikal" tabindex="-1" aria-labelledby="modalSemakanKetepatanDokumenTeknikalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 42px; height: 42px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalSemakanKetepatanDokumenTeknikalLabel" style="font-size: 1.05rem; letter-spacing: -0.2px;">Semakan Pematuhan Dokumen Teknikal</h5>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Semak dokumen yang dikemukakan oleh setiap pembekal.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="spesifikasi-tajuk-card mb-4">
                        <div class="spesifikasi-tajuk-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div>
                            <span class="spesifikasi-tajuk-label">Tajuk / Dokumen</span>
                            <span class="spesifikasi-tajuk-value" id="modalSemakanTajukDokumen">-</span>
                        </div>
                    </div>

                    <h6 class="spesifikasi-section-label mb-3">Senarai Pembekal</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-slate align-middle mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 14%;">Kod Pembekal</th>
                                    <th style="width: 30%;">Dokumen</th>
                                    <th style="width: 28%;">Status Pematuhan</th>
                                    <th style="width: 28%;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shortlistedVendors as $vendor)
                                    <tr>
                                        <td class="text-center">{{ $vendor->kod_pembekal ?? '-' }}</td>
                                        <td>
                                            <div class="dokumen-file-list d-flex flex-column align-items-start gap-1" data-vendor-id="{{ $vendor->vendor_id }}">
                                                <span class="text-muted small">-</span>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select pematuhan-status" name="status_pematuhan_{{ $vendor->vendor_id }}" data-vendor-id="{{ $vendor->vendor_id }}" aria-label="Status Pematuhan">
                                                <option value="" selected disabled>Sila Pilih</option>
                                                <option value="1">Mematuhi</option>
                                                <option value="0">Tidak Mematuhi</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control pematuhan-catatan" name="catatan_{{ $vendor->vendor_id }}" data-vendor-id="{{ $vendor->vendor_id }}" placeholder="Catatan">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tiada pembekal disenarai pendek untuk tender ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer teknikal-modal-footer justify-content-end gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" class="btn-form btn-form-success" id="btnStep1SimpanDokTeknikal">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal paparan dokumen (PDF / dipaparkan dalam iframe) --}}
    <div class="modal fade" id="modalViewDokumenTeknikal" tabindex="-1" aria-labelledby="modalViewDokumenTeknikalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header teknikal-modal-header">
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                        <div class="content-card-icon flex-shrink-0" style="width: 42px; height: 42px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div class="overflow-hidden">
                            <span class="teknikal-modal-eyebrow">Paparan Dokumen</span>
                            <h5 class="modal-title fw-bold text-dark mb-0 text-truncate" id="modalViewDokumenTeknikalLabel" style="font-size: 1rem; letter-spacing: -0.2px;">Paparan dokumen</h5>
                        </div>
                    </div>
                    <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-0 bg-light">
                    <iframe id="iframeViewDokumenTeknikal" title="Paparan dokumen" class="w-100 border-0 d-block"
                        style="min-height: 200px; height: 300px; max-height: 78vh;"></iframe>
                </div>
                <div class="modal-footer teknikal-modal-footer justify-content-start gap-2" id="footerViewDokumenTeknikal">
                    <button type="button" class="btn-form btn-form-secondary" id="btnKembaliViewDokumenTeknikal">Kembali</button>
                </div>
            </div>
        </div>
    </div>

    {{-- "Dokumen" opens the vendor's saved specification form, read-only. --}}
    <div class="modal fade" id="modalSemakanSpesifikasiTeknikalStep1" tabindex="-1" aria-labelledby="modalSemakanSpesifikasiTeknikalStep1Label" aria-hidden="true"
        data-spec-form-url-template="{{ route('tenderDokumen.specificationForm', ['tender' => $tender->id, 'itemUuid' => '__ITEM_UUID__', 'summary' => 'dokumentasi']) }}">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 42px; height: 42px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalSemakanSpesifikasiTeknikalStep1Label" style="font-size: 1.05rem; letter-spacing: -0.2px;">Semakan Pematuhan Spesifikasi</h5>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Semak dokumen spesifikasi yang dikemukakan oleh setiap pembekal.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="spesifikasi-tajuk-card mb-4">
                        <div class="spesifikasi-tajuk-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div>
                            <span class="spesifikasi-tajuk-label">Tajuk / Dokumen</span>
                            <span class="spesifikasi-tajuk-value" id="modalSemakanSpesifikasiTajukDokumen">Perkhidmatan Penilaian Forensik Ke atas Sistem XXXX</span>
                        </div>
                    </div>

                    <h6 class="spesifikasi-section-label mb-3">Senarai Pembekal</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-slate align-middle mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 14%;">Kod Pembekal</th>
                                    <th style="width: 16%;">Dokumen</th>
                                    <th style="width: 28%;">Status Pematuhan</th>
                                    <th style="width: 42%;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shortlistedVendors as $vendor)
                                    <tr>
                                        <td class="text-center">{{ $vendor->kod_pembekal ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);"
                                                class="btn-lihat-spesifikasi-vendor text-primary text-decoration-none d-inline-flex align-items-center gap-1"
                                                data-vendor-id="{{ $vendor->vendor_id }}"
                                                data-kod-pembekal="{{ $vendor->kod_pembekal }}">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                                Lihat
                                            </a>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm pematuhan-status" name="status_pematuhan_{{ $vendor->vendor_id }}" data-vendor-id="{{ $vendor->vendor_id }}" aria-label="Status Pematuhan">
                                                <option value="" selected disabled>Sila Pilih</option>
                                                <option value="1">Mematuhi</option>
                                                <option value="0">Tidak Mematuhi</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control pematuhan-catatan" name="catatan_{{ $vendor->vendor_id }}" data-vendor-id="{{ $vendor->vendor_id }}" placeholder="Catatan">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tiada pembekal disenarai pendek untuk tender ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer teknikal-modal-footer justify-content-end gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" class="btn-form btn-form-success" id="btnSimpanSemakanSpesifikasiTeknikal">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Shared modal for both online forms — data-form-url is resolved per item in the controller. --}}
    <div class="modal fade" id="modalSemakanBorangAtasTalianStep1" tabindex="-1" aria-labelledby="modalSemakanBorangAtasTalianStep1Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 42px; height: 42px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalSemakanBorangAtasTalianStep1Label" style="font-size: 1.05rem; letter-spacing: -0.2px;">Semakan Borang Atas Talian</h5>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Semak borang atas talian yang dikemukakan oleh setiap pembekal.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="spesifikasi-tajuk-card mb-4">
                        <div class="spesifikasi-tajuk-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div>
                            <span class="spesifikasi-tajuk-label">Tajuk / Dokumen</span>
                            <span class="spesifikasi-tajuk-value" id="modalSemakanBorangTajukDokumen">Senarai Pengalaman Kerja</span>
                        </div>
                    </div>

                    <h6 class="spesifikasi-section-label mb-3">Senarai Pembekal</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-slate align-middle mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 14%;">Kod Pembekal</th>
                                    <th style="width: 16%;">Dokumen</th>
                                    <th style="width: 28%;">Status Pematuhan</th>
                                    <th style="width: 42%;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shortlistedVendors as $vendor)
                                    <tr>
                                        <td class="text-center">{{ $vendor->kod_pembekal ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);"
                                                class="btn-lihat-borang-vendor text-primary text-decoration-none d-inline-flex align-items-center gap-1"
                                                data-vendor-id="{{ $vendor->vendor_id }}"
                                                data-kod-pembekal="{{ $vendor->kod_pembekal }}">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                                Lihat
                                            </a>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm pematuhan-status" name="status_pematuhan_borang_{{ $vendor->vendor_id }}" data-vendor-id="{{ $vendor->vendor_id }}" aria-label="Status Pematuhan">
                                                <option value="" selected disabled>Sila Pilih</option>
                                                <option value="1">Mematuhi</option>
                                                <option value="0">Tidak Mematuhi</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control pematuhan-catatan" name="catatan_borang_{{ $vendor->vendor_id }}" data-vendor-id="{{ $vendor->vendor_id }}" placeholder="Catatan">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tiada pembekal disenarai pendek untuk tender ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer teknikal-modal-footer justify-content-end gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" class="btn-form btn-form-success" id="btnSimpanSemakanBorangAtasTalian">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Original dummy design for this modal — kept for reference.
    <div class="modal fade" id="modalPenilaianMuatNaikTeknikal" tabindex="-1" aria-labelledby="modalPenilaianMuatNaikTeknikalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenilaianMuatNaikTeknikalLabel">PENILAIAN SPESIFIKASI TEKNIKAL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rounded border bg-light px-3 py-2 mb-3">
                        <div class="fw-bold text-uppercase small">PENILAIAN SPESIFIKASI TEKNIKAL</div>
                        <p class="mb-0 mt-2 small"><strong>Tajuk / Dokumen :</strong> <span id="muatNaikModalTajuk">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</span></p>
                    </div>
                    <div class="rounded border bg-light px-3 py-2 mb-3">
                        <div class="fw-bold text-uppercase small">SKEMA PEMARKAHAN PENGGUNA BAGI TEKNIKAL</div>
                        <p class="mb-0 mt-2 small"><strong>Dokumen Sokongan :</strong> <span id="muatNaikModalSkema">Skema Pemarkahan Senarai Semakan Teknikal Digital Forensik.docx</span></p>
                    </div>
                    <div class="rounded border bg-light px-3 py-2 mb-2">
                        <div class="fw-bold text-uppercase small">SENARAI PEMBEKAL</div>
                    </div>
                    <p class="card-title-desc text-primary fst-italic small mb-3">Pastikan semua senarai semak lengkap dinilai dan butang Menilai bertukar kepada Papar</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-slate align-middle mb-0">
                            <thead class="table-primary text-center text-white">
                                <tr>
                                    <th style="width: 10%;">Kod Pembekal</th>
                                    <th style="width: 26%;">Dokumen</th>
                                    <th style="width: 12%;">Status Penyerahan</th>
                                    <th style="width: 12%;">Skor Pematuhan</th>
                                    <th style="width: 14%;">Skor Manual <span class="text-danger">*</span></th>
                                    <th style="width: 26%;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="muat-naik-doc-link text-primary text-decoration-none d-inline-flex align-items-center flex-shrink-0"
                                                aria-label="Buka dokumen pembekal 1 dalam tab baharu">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break muat-naik-supplier-doc" data-slot="1">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.pdf</span>
                                        </div>
                                    </td>
                                    <td class="text-center">Hantar</td>
                                    <td class="text-center">Mematuhi</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                            <input type="number" min="0" max="10" step="0.01"
                                                class="form-control form-control-sm text-center muat-naik-skor-manual"
                                                style="width: 4.25rem; max-width: 100%;"
                                                name="skor_manual_muat_naik_1"
                                                aria-label="Skor manual pembekal 1">
                                            <span class="small text-nowrap">/ 10</span>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm muat-naik-catatan" rows="2" name="catatan_muat_naik_1" placeholder="Catatan"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <a href="https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="muat-naik-doc-link text-primary text-decoration-none d-inline-flex align-items-center flex-shrink-0"
                                                aria-label="Buka dokumen pembekal 2 dalam tab baharu">
                                                <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                                            </a>
                                            <span class="small text-break muat-naik-supplier-doc" data-slot="2">Perkhidmatan Penilaian Forensik Keatas Sistem XXXX.pdf</span>
                                        </div>
                                    </td>
                                    <td class="text-center">Hantar</td>
                                    <td class="text-center">Mematuhi</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                            <input type="number" min="0" max="10" step="0.01"
                                                class="form-control form-control-sm text-center muat-naik-skor-manual"
                                                style="width: 4.25rem; max-width: 100%;"
                                                name="skor_manual_muat_naik_2"
                                                aria-label="Skor manual pembekal 2">
                                            <span class="small text-nowrap">/ 10</span>
                                        </div>
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm muat-naik-catatan" rows="2" name="catatan_muat_naik_2" placeholder="Catatan"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="button" class="btn-form btn-form-success" id="btnSimpanPenilaianMuatNaikTeknikal" data-bs-dismiss="modal">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    --}}
@endpush
</div>

<script>
    const vendorDokumenResponses = {!! Illuminate\Support\Js::from($vendorDokumenResponses ?? []) !!};
    const TENDER_IDENTIFIER = '{{ $tender->uuid }}';
    const SIMPAN_PEMATUHAN_URL = '{{ route('penilaianTeknikal.simpanPematuhan') }}';
    const RUMUSAN_PEMATUHAN_URL = '{{ route('penilaianTeknikal.rumusanPematuhan', $tender->id) }}';
    const HANTAR_PEMATUHAN_URL = '{{ route('penilaianTeknikal.hantarPematuhan', $tender->id) }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const PEMATUHAN_EVALUATIONS = {!! Illuminate\Support\Js::from($pematuhanEvaluations ?? []) !!};
    const RESUME_TAB = '{{ $resumeTab }}';
    const FULLY_SUBMITTED = {{ $fullySubmitted ? 'true' : 'false' }};
    const SAHKAN_SPESIFIKASI_URL = '{{ route('penilaianTeknikal.confirmSpesifikasi') }}';
    const CETAK_LAPORAN_URL = '{{ route('penilaianTeknikal.cetakLaporan', $tender->id) }}';
    // let, not const — flipped true in memory right after confirmation, so gates stay
    // correct within the same session without a page reload.
    let PEMATUHAN_CONFIRMED = {{ $pematuhanConfirmed ? 'true' : 'false' }};
    let SPESIFIKASI_CONFIRMED = {{ $spesifikasiConfirmed ? 'true' : 'false' }};

    function cetakLaporanTeknikal() {
        // Nothing to flush once submitted — the fields are locked and already saved.
        if (FULLY_SUBMITTED || typeof window.simpanDrafLaporanSebelumCetak !== 'function') {
            window.open(CETAK_LAPORAN_URL, '_blank');
            return;
        }

        // Opened here, not in the .done() below: a window.open() outside the click's own call
        // stack gets treated as a popup and blocked.
        const tab = window.open('', '_blank');

        window.simpanDrafLaporanSebelumCetak()
            .done(function() {
                if (tab) tab.location = CETAK_LAPORAN_URL;
            })
            .fail(function(xhr) {
                if (tab) tab.close();
                showToast('error', xhr.responseJSON?.message || 'Ralat semasa menyimpan draf laporan.');
            });
    }

    // Global (outside DOMContentLoaded) so teknikal_step2/step3's separate closures can call it too.
    function showOuterStep(stepId) {
        const el = document.getElementById(stepId);
        if (el) bootstrap.Tab.getOrCreateInstance(el).show();
    }

    // Disable + swap label while a save/submit request is in flight — API calls are slower
    // than the old direct DB writes, so a bare disabled button reads as unresponsive.
    function setButtonBusy(button, busyLabel) {
        if (!button || button.disabled) return;
        button.dataset.originalText = button.textContent;
        button.disabled = true;
        button.textContent = busyLabel;
    }

    function clearButtonBusy(button) {
        if (!button) return;
        button.disabled = false;
        if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
            delete button.dataset.originalText;
        }
    }

    // Locks the checkbox in-session right after confirmation, mirroring the server-rendered disabled state.
    function lockPengesahanCheckbox(checkboxId) {
        const box = document.getElementById(checkboxId);
        if (!box) return;

        box.checked = true;
        box.disabled = true;
        box.style.cursor = 'default';

        const label = document.querySelector('label[for="' + checkboxId + '"]');
        if (label) label.style.cursor = 'default';
    }

    // Blocks jumping straight to the Rumusan tab until every checklist item is evaluated.
    function wireRumusanGate(linkHref, paneId) {
        const link = document.querySelector('a[href="' + linkHref + '"]');
        if (!link) return;

        link.addEventListener('click', (event) => {
            event.preventDefault();

            if (FULLY_SUBMITTED) {
                bootstrap.Tab.getOrCreateInstance(link).show();
                return;
            }

            const pane = document.getElementById(paneId);
            const belumSelesai = pane && Array.from(pane.querySelectorAll('.status-penilaian-badge'))
                .some(badge => badge.textContent.trim() !== 'Telah Dinilai');

            if (belumSelesai) {
                showToast('warning', 'Sila lengkapkan semua penilaian dokumen sebelum meneruskan ke rumusan.');
                return;
            }

            bootstrap.Tab.getOrCreateInstance(link).show();
        });
    }

    wireRumusanGate('#rumusan-1', 'teknikal-1');
    wireRumusanGate('#rumusan-2', 'teknikal-2');

    document.addEventListener('DOMContentLoaded', () => {

        const steps = document.querySelectorAll('.progress-step');
        const tabs = document.querySelectorAll('.step-number');

        function updateStepper(activeIndex) {
            steps.forEach((step, i) => {
                step.classList.remove('active', 'done');

                if (i < activeIndex) step.classList.add('done');
                if (i === activeIndex) step.classList.add('active');
            });
        }

        tabs.forEach((tab, index) => {
            tab.addEventListener('shown.bs.tab', () => {
                updateStepper(index);
            });
            tab.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
            });
        });

        // Init
        updateStepper(0);

        // Opens directly on RESUME_TAB so a hard refresh doesn't restart from Langkah 1.
        const STEP_OUTER_MAP = {
            'teknikal-1': 'pematuhan-tab',
            'teknikal-2': 'penilaian-tab',
            'laporan': 'laporan-tab',
        };
        if (RESUME_TAB && STEP_OUTER_MAP[RESUME_TAB]) {
            showOuterStep(STEP_OUTER_MAP[RESUME_TAB]);
            if (RESUME_TAB !== 'laporan') {
                document.querySelector('a[data-bs-toggle="tab"][href="#' + RESUME_TAB + '"]')?.click();
            }
        }

        // Shared modal for Petender/PTJ Muat Naik items — files open in a new tab, not an iframe.
        const modalSemakanStep1 = document.getElementById('modalSemakanKetepatanDokumenTeknikal');
        if (modalSemakanStep1) {
            modalSemakanStep1.addEventListener('show.bs.modal', function(event) {
                const trigger = event.relatedTarget;
                if (!trigger || !trigger.classList.contains('btn-semakan-dok-teknikal')) return;

                const tajuk = trigger.getAttribute('data-tajuk') || '';
                const itemUuid = trigger.getAttribute('data-item-uuid') || '';
                const elTajuk = document.getElementById('modalSemakanTajukDokumen');
                if (elTajuk && tajuk) elTajuk.textContent = tajuk;
                prefillPematuhanRows(modalSemakanStep1, itemUuid, 'btnStep1SimpanDokTeknikal');

                modalSemakanStep1.querySelectorAll('.dokumen-file-list').forEach(function(container) {
                    const vendorId = container.getAttribute('data-vendor-id');
                    const response = (vendorDokumenResponses[vendorId] || {})[itemUuid];
                    const files = (response && response.files) || [];

                    container.innerHTML = '';

                    if (!files.length) {
                        const empty = document.createElement('span');
                        empty.className = 'text-muted small';
                        empty.textContent = 'Tiada dokumen dimuat naik';
                        container.appendChild(empty);
                        return;
                    }

                    files.forEach(function(file) {
                        const fileName = file.name || 'Dokumen';
                        const link = document.createElement('a');
                        link.href = file.url || '#';
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        link.className = 'text-primary text-decoration-none d-inline-flex align-items-center gap-1';

                        const icon = document.createElement('i');
                        icon.className = 'bi bi-file-earmark-pdf-fill';
                        icon.setAttribute('aria-hidden', 'true');
                        link.appendChild(icon);
                        link.appendChild(document.createTextNode(' ' + fileName));

                        container.appendChild(link);
                    });
                });
            });
        }

        // Each vendor's "Dokumen" link is built from data-item-uuid + data-spec-form-url-template.
        const modalSemakanSpesifikasi = document.getElementById('modalSemakanSpesifikasiTeknikalStep1');
        if (modalSemakanSpesifikasi) {
            modalSemakanSpesifikasi.addEventListener('show.bs.modal', function(event) {
                const trigger = event.relatedTarget;
                if (!trigger || !trigger.classList.contains('btn-semakan-spesifikasi-teknikal')) return;

                const tajuk = trigger.getAttribute('data-tajuk') || '';
                const itemUuid = trigger.getAttribute('data-item-uuid') || '';
                const elTajuk = document.getElementById('modalSemakanSpesifikasiTajukDokumen');
                if (elTajuk && tajuk) elTajuk.textContent = tajuk;
                prefillPematuhanRows(modalSemakanSpesifikasi, itemUuid, 'btnSimpanSemakanSpesifikasiTeknikal');

                const urlTemplate = modalSemakanSpesifikasi.getAttribute('data-spec-form-url-template') || '';
                modalSemakanSpesifikasi.querySelectorAll('.btn-lihat-spesifikasi-vendor').forEach(function(link) {
                    const vendorId = link.getAttribute('data-vendor-id');
                    const kodPembekal = link.getAttribute('data-kod-pembekal') || '';
                    if (!itemUuid || !vendorId || !urlTemplate) return;
                    const baseUrl = urlTemplate.replace('__ITEM_UUID__', encodeURIComponent(itemUuid));
                    const separator = baseUrl.includes('?') ? '&' : '?';
                    link.setAttribute('data-doc-url', baseUrl + separator + 'vendor_id=' + encodeURIComponent(vendorId) + '&modal=1&mode=view');
                    link.setAttribute('data-doc-title', (tajuk ? tajuk + ' — ' : '') + 'Pembekal ' + kodPembekal);
                });
            });
        }

        // data-form-url already resolves the full URL server-side — only vendor_id gets appended here.
        const modalSemakanBorang = document.getElementById('modalSemakanBorangAtasTalianStep1');
        if (modalSemakanBorang) {
            modalSemakanBorang.addEventListener('show.bs.modal', function(event) {
                const trigger = event.relatedTarget;
                if (!trigger || !trigger.classList.contains('btn-semakan-borang-teknikal')) return;

                const tajuk = trigger.getAttribute('data-tajuk') || '';
                const itemUuid = trigger.getAttribute('data-item-uuid') || '';
                const formUrl = trigger.getAttribute('data-form-url') || '';
                const elTajuk = document.getElementById('modalSemakanBorangTajukDokumen');
                if (elTajuk && tajuk) elTajuk.textContent = tajuk;
                prefillPematuhanRows(modalSemakanBorang, itemUuid, 'btnSimpanSemakanBorangAtasTalian');

                modalSemakanBorang.querySelectorAll('.btn-lihat-borang-vendor').forEach(function(link) {
                    const vendorId = link.getAttribute('data-vendor-id');
                    const kodPembekal = link.getAttribute('data-kod-pembekal') || '';
                    if (!vendorId || !formUrl) return;
                    const separator = formUrl.includes('?') ? '&' : '?';
                    link.setAttribute('data-doc-url', formUrl + separator + 'vendor_id=' + encodeURIComponent(vendorId) + '&modal=1&mode=view');
                    link.setAttribute('data-doc-title', (tajuk ? tajuk + ' — ' : '') + 'Pembekal ' + kodPembekal);
                });
            });
        }

        // Closes the source modal first, then opens the doc viewer — Bootstrap doesn't support stacked modals.
        const modalViewDoc = document.getElementById('modalViewDokumenTeknikal');
        const iframeViewDoc = document.getElementById('iframeViewDokumenTeknikal');
        const titleViewDoc = document.getElementById('modalViewDokumenTeknikalLabel');
        const btnKembaliViewDoc = document.getElementById('btnKembaliViewDokumenTeknikal');

        // Which modal "Kembali" should reopen.
        let docViewerReturnModal = null;

        function showModalEl(el) {
            if (el) bootstrap.Modal.getOrCreateInstance(el).show();
        }

        // Shared by Semakan Spesifikasi & Semakan Borang Atas Talian's "Lihat" links.
        function wireLihatDokumen(sourceModal, linkClass) {
            if (!sourceModal || !modalViewDoc) return;

            sourceModal.addEventListener('click', function(event) {
                const link = event.target.closest('.' + linkClass);
                if (!link) return;

                const url = link.getAttribute('data-doc-url');
                const docTitle = link.getAttribute('data-doc-title') || 'Dokumen';

                const openDocViewer = function() {
                    sourceModal.removeEventListener('hidden.bs.modal', openDocViewer);
                    if (titleViewDoc) titleViewDoc.textContent = docTitle;
                    if (iframeViewDoc) {
                        iframeViewDoc.style.height = '300px';
                        iframeViewDoc.src = url ? url.trim() : 'about:blank';
                    }
                    docViewerReturnModal = sourceModal;
                    showModalEl(modalViewDoc);
                };
                sourceModal.addEventListener('hidden.bs.modal', openDocViewer);
                bootstrap.Modal.getInstance(sourceModal)?.hide();
            });
        }

        wireLihatDokumen(modalSemakanSpesifikasi, 'btn-lihat-spesifikasi-vendor');
        wireLihatDokumen(modalSemakanBorang, 'btn-lihat-borang-vendor');

        // Refills previously-saved Status Pematuhan/Catatan and tags the modal with the active item uuid.
        function prefillPematuhanRows(modalEl, itemUuid, saveBtnId) {
            if (!modalEl) return;
            modalEl.setAttribute('data-active-item-uuid', itemUuid);

            modalEl.querySelectorAll('.pematuhan-status').forEach(function(select) {
                const vendorId = select.getAttribute('data-vendor-id');
                const saved = PEMATUHAN_EVALUATIONS[vendorId + ':' + itemUuid];
                select.value = saved ? String(saved.status_pematuhan) : '';
                select.classList.remove('is-invalid');
                select.disabled = PEMATUHAN_CONFIRMED;
            });

            modalEl.querySelectorAll('.pematuhan-catatan').forEach(function(input) {
                const vendorId = input.getAttribute('data-vendor-id');
                const saved = PEMATUHAN_EVALUATIONS[vendorId + ':' + itemUuid];
                input.value = saved ? (saved.catatan || '') : '';
                input.classList.remove('is-invalid');
                input.disabled = PEMATUHAN_CONFIRMED;
            });

            // Read-only once confirmed — the elimination it triggered already used this data.
            const saveBtn = saveBtnId ? document.getElementById(saveBtnId) : null;
            if (saveBtn) {
                if (PEMATUHAN_CONFIRMED) {
                    saveBtn.style.display = 'none';
                } else {
                    saveBtn.style.display = '';
                    const hasAnyEvaluation = Object.keys(PEMATUHAN_EVALUATIONS).some(function(key) {
                        return key.endsWith(':' + itemUuid);
                    });
                    saveBtn.textContent = hasAnyEvaluation ? 'Kemaskini' : 'Simpan';
                }
            }
        }

        // itemStatus comes straight from the server response, never recomputed client-side.
        function updateStatusPenilaianBadge(itemUuid, itemStatus) {
            const row = document.querySelector('tr[data-item-uuid="' + itemUuid + '"]');
            const badge = row ? row.querySelector('.status-penilaian-badge') : null;
            if (!badge) return;

            const label = itemStatus.label;
            const badgeClass = itemStatus.badge_class;
            const isComplete = label === 'Telah Dinilai';

            badge.className = 'badge-status ' + badgeClass + ' status-penilaian-badge';
            badge.textContent = label;

            const actionBtn = row.querySelector('.btn-semakan-dok-teknikal, .btn-semakan-spesifikasi-teknikal, .btn-semakan-borang-teknikal');
            if (actionBtn) {
                actionBtn.textContent = isComplete ? 'Papar' : 'Menilai';
            }
        }

        // Shared Simpan handler for all three Langkah 1 modals.
        function wireSimpanPematuhan(modalEl, saveBtnId) {
            if (!modalEl) return;
            const saveBtn = document.getElementById(saveBtnId);
            if (!saveBtn) return;

            saveBtn.addEventListener('click', function() {
                const itemUuid = modalEl.getAttribute('data-active-item-uuid') || '';
                if (!itemUuid) return;

                const rows = [];
                let hasError = false;

                modalEl.querySelectorAll('.pematuhan-status').forEach(function(select) {
                    const vendorId = select.getAttribute('data-vendor-id');
                    const statusPematuhan = select.value;
                    const catatanInput = modalEl.querySelector('.pematuhan-catatan[data-vendor-id="' + vendorId + '"]');
                    const catatan = catatanInput ? catatanInput.value.trim() : '';

                    if (statusPematuhan === '') {
                        hasError = true;
                        select.classList.add('is-invalid');
                        return;
                    }
                    select.classList.remove('is-invalid');

                    if (statusPematuhan === '0' && !catatan) {
                        hasError = true;
                        if (catatanInput) catatanInput.classList.add('is-invalid');
                        return;
                    }
                    if (catatanInput) catatanInput.classList.remove('is-invalid');

                    rows.push({ vendorId: vendorId, statusPematuhan: statusPematuhan, catatan: catatan });
                });

                if (hasError) {
                    showToast('warning', 'Sila lengkapkan semua Status Pematuhan. Catatan wajib diisi jika Status Pematuhan ialah Tidak Mematuhi.');
                    return;
                }
                if (!rows.length) return;

                setButtonBusy(saveBtn, 'Menyimpan...');

                $.ajax({
                    url: SIMPAN_PEMATUHAN_URL,
                    method: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        tender: TENDER_IDENTIFIER,
                        checklist_item_uuid: itemUuid,
                        rows: rows.map(function(row) {
                            return {
                                vendor_id: row.vendorId,
                                status_pematuhan: row.statusPematuhan,
                                catatan: row.catatan,
                            };
                        }),
                    },
                }).done(function(res) {
                    rows.forEach(function(row) {
                        PEMATUHAN_EVALUATIONS[row.vendorId + ':' + itemUuid] = {
                            status_pematuhan: parseInt(row.statusPematuhan, 10),
                            catatan: row.catatan,
                        };
                    });

                    bootstrap.Modal.getInstance(modalEl)?.hide();
                    showToast('success', res.message || 'Penilaian pematuhan telah disimpan.');
                    if (res.item_status) {
                        updateStatusPenilaianBadge(itemUuid, res.item_status);
                    }
                }).fail(function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menyimpan penilaian pematuhan.');
                }).always(function() {
                    clearButtonBusy(saveBtn);
                });
            });
        }

        wireSimpanPematuhan(modalSemakanStep1, 'btnStep1SimpanDokTeknikal');
        wireSimpanPematuhan(modalSemakanSpesifikasi, 'btnSimpanSemakanSpesifikasiTeknikal');
        wireSimpanPematuhan(modalSemakanBorang, 'btnSimpanSemakanBorangAtasTalian');

        if (modalViewDoc) {
            modalViewDoc.addEventListener('hidden.bs.modal', function() {
                if (iframeViewDoc) iframeViewDoc.src = 'about:blank';
            });

            // Sizes the iframe to its real content so the modal has no dead space.
            if (iframeViewDoc) {
                iframeViewDoc.addEventListener('load', function() {
                    if (!iframeViewDoc.src || iframeViewDoc.src === 'about:blank') return;
                    try {
                        const doc = iframeViewDoc.contentDocument || iframeViewDoc.contentWindow.document;
                        const contentHeight = doc.documentElement.scrollHeight || doc.body.scrollHeight;
                        if (contentHeight) {
                            const maxHeight = window.innerHeight * 0.78;
                            iframeViewDoc.style.height = Math.min(contentHeight + 16, maxHeight) + 'px';
                        }
                    } catch (e) {
                        // Content unreadable (e.g. cross-origin) — keep the default height.
                    }
                });
            }

            if (btnKembaliViewDoc) {
                btnKembaliViewDoc.addEventListener('click', function() {
                    const returnModal = docViewerReturnModal;
                    docViewerReturnModal = null;

                    if (returnModal) {
                        const reopenReturnModal = function() {
                            modalViewDoc.removeEventListener('hidden.bs.modal', reopenReturnModal);
                            showModalEl(returnModal);
                        };
                        modalViewDoc.addEventListener('hidden.bs.modal', reopenReturnModal);
                    }
                    bootstrap.Modal.getInstance(modalViewDoc)?.hide();
                });
            }
        }

        // Reloaded every time this tab is shown, not just once, to reflect the latest saves.
        function renderRumusanPematuhan(data) {
            const layak = data.layak || [];
            const tidakLayak = data.tidak_layak || [];

            const melepasiTbody = document.getElementById('rumusanMelepasiTbody');
            if (melepasiTbody) {
                melepasiTbody.innerHTML = '';
                if (!layak.length) {
                    melepasiTbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted" style="padding: 18px 16px;">Tiada rekod dijumpai</td></tr>';
                } else {
                    layak.forEach(function(row) {
                        const tr = document.createElement('tr');
                        const tdBil = document.createElement('td');
                        tdBil.className = 'text-center';
                        tdBil.textContent = row.kod_pembekal || '-';
                        const tdUlasan = document.createElement('td');
                        tdUlasan.textContent = row.ulasan || '-';
                        tr.append(tdBil, tdUlasan);
                        melepasiTbody.appendChild(tr);
                    });
                }
            }

            const melepasiTotal = document.getElementById('rumusanMelepasiTotal');
            if (melepasiTotal) melepasiTotal.textContent = layak.length;

            const tidakMelepasiTbody = document.getElementById('rumusanTidakMelepasiTbody');
            if (tidakMelepasiTbody) {
                tidakMelepasiTbody.innerHTML = '';
                if (!tidakLayak.length) {
                    tidakMelepasiTbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted" style="padding: 18px 16px;">Tiada rekod dijumpai</td></tr>';
                } else {
                    tidakLayak.forEach(function(row) {
                        const tr = document.createElement('tr');
                        const tdBil = document.createElement('td');
                        tdBil.className = 'text-center';
                        tdBil.textContent = row.kod_pembekal || '-';
                        const tdUlasan = document.createElement('td');
                        (row.reasons || []).forEach(function(reason) {
                            const item = document.createElement('div');
                            item.className = 'rumusan-reason-item';
                            const text = document.createElement('div');
                            text.textContent = reason.text || '-';
                            item.appendChild(text);
                            if (reason.catatan) {
                                const catatan = document.createElement('div');
                                catatan.className = 'rumusan-reason-catatan';
                                catatan.textContent = 'Catatan: ' + reason.catatan;
                                item.appendChild(catatan);
                            }
                            tdUlasan.appendChild(item);
                        });
                        tr.append(tdBil, tdUlasan);
                        tidakMelepasiTbody.appendChild(tr);
                    });
                }
            }

            const tidakMelepasiTotal = document.getElementById('rumusanTidakMelepasiTotal');
            if (tidakMelepasiTotal) tidakMelepasiTotal.textContent = tidakLayak.length;
        }

        const rumusanTabLink = document.querySelector('a[href="#rumusan-1"]');
        if (rumusanTabLink) {
            rumusanTabLink.addEventListener('shown.bs.tab', function() {
                $.get(RUMUSAN_PEMATUHAN_URL)
                    .done(renderRumusanPematuhan)
                    .fail(function() {
                        showToast('error', 'Ralat memuatkan rumusan pematuhan dokumentasi.');
                    });
            });
        }

        const btnKembaliRumusanPematuhan = document.getElementById('btnKembaliRumusanPematuhan');
        if (btnKembaliRumusanPematuhan) {
            btnKembaliRumusanPematuhan.addEventListener('click', function() {
                const teknikalTab = document.querySelector('a[data-bs-toggle="tab"][href="#teknikal-1"]');
                if (teknikalTab) teknikalTab.click();
            });
        }
    });

    const msgTandakanPengesahan = 'Sila tandakan kotak pengesahan terlebih dahulu sebelum meneruskan.';
    document.querySelectorAll('.btn-seterusnya').forEach(btn => {
        btn.addEventListener('click', () => {
            // Which inner sub-pane is this Seterusnya in? (teknikal-1/rumusan-1/teknikal-2/rumusan-2)
            const pane = btn.closest('.tab-pane');
            const paneId = pane ? pane.id : '';

            // On the "Teknikal" sub-tab: move to this step's "Rumusan" (Rumusan) sub-tab first,
            // don't advance the step or check the confirmation yet.
            if (paneId.startsWith('teknikal-')) {
                const belumSelesai = Array.from(pane.querySelectorAll('.status-penilaian-badge'))
                    .some(badge => badge.textContent.trim() !== 'Telah Dinilai');
                if (belumSelesai) {
                    showToast('warning', 'Sila lengkapkan semua penilaian dokumen sebelum meneruskan ke rumusan.');
                    return;
                }

                const suffix = paneId.split('-')[1]; // "1" or "2"
                const rumusanTab = document.querySelector('a[href="#rumusan-' + suffix + '"]');
                if (rumusanTab) bootstrap.Tab.getOrCreateInstance(rumusanTab).show();
                return;
            }

            // On the "Rumusan" sub-tab: require the confirmation tick, then advance to the next step.
            const current = document.querySelector('.step-number.active');
            if (!current) return;

            const currentId = current.id;
            const checks = [
                { id: 'pematuhan-tab', el: document.getElementById('confirmLayak') },
                { id: 'penilaian-tab', el: document.getElementById('confirmLayakStep2') },
            ];
            const stepCheck = checks.find(c => c.id === currentId);
            if (stepCheck?.el && !stepCheck.el.checked) {
                showToast('warning', msgTandakanPengesahan);
                return;
            }

            // Langkah 1 needs explicit confirmation before advancing; skip re-asking if already confirmed.
            if (currentId === 'pematuhan-tab' && paneId === 'rumusan-1') {
                if (PEMATUHAN_CONFIRMED) {
                    const next = current.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
                    if (next) showOuterStep(next.id);
                    return;
                }

                const modalKonfirmasi = document.getElementById('modalKonfirmasiHantarPematuhan');
                if (modalKonfirmasi) {
                    bootstrap.Modal.getOrCreateInstance(modalKonfirmasi).show();
                    return;
                }
            }

            // Langkah 2 records confirmation before advancing; skip the endpoint call if already confirmed.
            if (currentId === 'penilaian-tab' && paneId === 'rumusan-2') {
                if (SPESIFIKASI_CONFIRMED) {
                    const next = current.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
                    if (next) showOuterStep(next.id);
                    return;
                }

                // Falls back to confirming directly if the modal is missing for any reason.
                const modalSahkanSpesifikasi = document.getElementById('modalKonfirmasiSahkanSpesifikasi');
                if (modalSahkanSpesifikasi) {
                    bootstrap.Modal.getOrCreateInstance(modalSahkanSpesifikasi).show();
                    return;
                }

                $.ajax({
                    url: SAHKAN_SPESIFIKASI_URL,
                    method: 'POST',
                    data: { _token: CSRF_TOKEN, tender: TENDER_IDENTIFIER },
                }).done(function() {
                    SPESIFIKASI_CONFIRMED = true;
                    lockPengesahanCheckbox('confirmLayakStep2');
                    const next = current.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
                    if (next) showOuterStep(next.id);
                }).fail(function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa mengesahkan pematuhan spesifikasi teknikal.');
                });
                return;
            }

            const next = current.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
            if (next) showOuterStep(next.id);
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        // These buttons live in the modal stack the master layout renders after the main content,
        // so wiring must wait for DOMContentLoaded rather than running at parse time.
        const btnKonfirmasiHantarPematuhan = document.getElementById('btnKonfirmasiHantarPematuhan');
        if (btnKonfirmasiHantarPematuhan) {
            btnKonfirmasiHantarPematuhan.addEventListener('click', function() {
                setButtonBusy(btnKonfirmasiHantarPematuhan, 'Menghantar...');

                $.ajax({
                    url: HANTAR_PEMATUHAN_URL,
                    method: 'POST',
                    data: { _token: CSRF_TOKEN },
                }).done(function(res) {
                    PEMATUHAN_CONFIRMED = true;
                    lockPengesahanCheckbox('confirmLayak');
                    bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiHantarPematuhan'))?.hide();
                    showToast('success', res.message || 'Pematuhan dokumentasi telah dihantar.');

                    const current = document.querySelector('.step-number.active');
                    const next = current?.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
                    if (next) showOuterStep(next.id);
                }).fail(function(xhr) {
                    bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiHantarPematuhan'))?.hide();
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menghantar pematuhan dokumentasi.');
                }).always(function() {
                    clearButtonBusy(btnKonfirmasiHantarPematuhan);
                });
            });
        }

        // Langkah 2 -> 3: confirms and locks Langkah 2 scores before advancing.
        const btnKonfirmasiSahkanSpesifikasi = document.getElementById('btnKonfirmasiSahkanSpesifikasi');
        if (btnKonfirmasiSahkanSpesifikasi) {
            btnKonfirmasiSahkanSpesifikasi.addEventListener('click', function() {
                setButtonBusy(btnKonfirmasiSahkanSpesifikasi, 'Mengesahkan...');

                $.ajax({
                    url: SAHKAN_SPESIFIKASI_URL,
                    method: 'POST',
                    data: { _token: CSRF_TOKEN, tender: TENDER_IDENTIFIER },
                }).done(function(res) {
                    SPESIFIKASI_CONFIRMED = true;
                    lockPengesahanCheckbox('confirmLayakStep2');
                    bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiSahkanSpesifikasi'))?.hide();
                    showToast('success', res.message || 'Pematuhan spesifikasi teknikal telah disahkan.');

                    const current = document.querySelector('.step-number.active');
                    const next = current?.closest('.progress-step')?.nextElementSibling?.querySelector('.step-number');
                    if (next) showOuterStep(next.id);
                }).fail(function(xhr) {
                    bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiSahkanSpesifikasi'))?.hide();
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa mengesahkan pematuhan spesifikasi teknikal.');
                }).always(function() {
                    clearButtonBusy(btnKonfirmasiSahkanSpesifikasi);
                });
            });
        }
    });
</script>
@endsection
