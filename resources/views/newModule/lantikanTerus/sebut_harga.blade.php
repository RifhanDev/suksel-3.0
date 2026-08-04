@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/file-upload.css') }}" rel="stylesheet">
    <style>
        /* --- TIMELINE (2 steps) --- */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            padding: 0 40px;
        }

        .stepper-track {
            position: absolute;
            top: 15px;
            left: 40px;
            right: 40px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            z-index: 0;
            overflow: hidden;
        }

        .stepper-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: var(--sg-red);
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        .stepper-wrapper[data-step="1"] .stepper-progress { width: 0%; }
        .stepper-wrapper[data-step="2"] .stepper-progress { width: 100%; }

        .stepper-item {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .step-counter {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid #cbd5e1;
            color: #94a3b8;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .step-name {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            transition: color 0.3s ease;
            text-align: center;
        }

        .stepper-item.active .step-counter {
            border-color: var(--sg-red);
            background: var(--sg-red);
            color: #fff;
            box-shadow: 0 0 0 5px var(--sg-red-soft), 0 2px 8px rgba(196, 30, 58, 0.3);
            transform: scale(1.05);
        }

        .stepper-item.active .step-name { color: var(--sg-red); }

        .stepper-item.completed .step-counter {
            border-color: #10b981;
            background: #10b981;
            color: #fff;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        }

        .stepper-item.completed .step-counter::after { content: '✓'; font-size: 0.9rem; }
        .stepper-item.completed .step-counter span { display: none; }
        .stepper-item.completed .step-name { color: #10b981; }

        /* --- PROJECT SUMMARY --- */
        .project-summary {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid var(--sg-red);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .project-summary-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 22px;
            flex: 1;
            min-width: 240px;
        }

        .project-summary-item .ps-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(196, 30, 58, 0.08);
            color: var(--sg-red);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-summary-item .ps-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .project-summary-item .ps-label {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .project-summary-item .ps-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.35;
        }

        .project-summary .ps-divider {
            width: 1px;
            background: #f1f5f9;
            align-self: stretch;
            margin: 12px 0;
        }

        @media (max-width: 767px) {
            .project-summary .ps-divider { display: none; }
            .project-summary-item {
                flex: 1 1 100%;
                border-bottom: 1px solid #f1f5f9;
            }
            .project-summary-item:last-child { border-bottom: none; }
        }

        /* --- BQ TABLE --- */
        .bq-table thead th {
            background: #f8fafc;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6b7280;
            border-color: #e5e7eb;
        }

        @media (max-width: 991px) {
            .stepper-wrapper { padding: 0 20px; }
            .stepper-track { left: 40px; right: 40px; }
            .step-name { font-size: 0.65rem; }
            .step-counter { width: 30px; height: 30px; font-size: 0.75rem; }
        }
    </style>
@endsection

@section('content')
    @php
        $p = $project;

        // Resolve display values for the read-only Step 1
        $ptjName      = optional(\App\OrganizationUnit::find($p->ptj_id))->name;
        $lokalitiName = optional(\App\Models\Ref\RefLokaliti::find($p->lokaliti_id))->name;
        $kategoriMap  = ['ict' => 'ICT', 'bekalan' => 'Bekalan', 'perkhidmatan' => 'Perkhidmatan', 'kerja' => 'Kerja'];
        $kategoriName = $kategoriMap[$p->kategori_perolehan] ?? $p->kategori_perolehan;
    @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Sebut Harga Pembelian Terus</h3>
            <p class="text-muted small m-0">Paparan maklumat projek dan penghantaran sebut harga.</p>
        </div>
    </div>

    <!-- PROJECT SUMMARY -->
    <div class="project-summary mb-4">
        <div class="project-summary-item">
            <div class="ps-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div class="ps-text">
                <span class="ps-label">No. Sebut Harga / Tender</span>
                <span class="ps-value">{{ $p->no_tender }}</span>
            </div>
        </div>
        <div class="ps-divider"></div>
        <div class="project-summary-item" style="flex: 2;">
            <div class="ps-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
            </div>
            <div class="ps-text">
                <span class="ps-label">Tajuk Perolehan</span>
                <span class="ps-value">{{ $p->name }}</span>
            </div>
        </div>
    </div>

    <!-- TIMELINE -->
    <div class="stepper-wrapper" data-step="1" id="stepper-wrapper">
        <div class="stepper-track">
            <div class="stepper-progress" id="stepper-progress"></div>
        </div>
        <div class="stepper-item active" id="step1-indicator">
            <div class="step-counter"><span>1</span></div>
            <div class="step-name">Cipta Projek</div>
        </div>
        <div class="stepper-item" id="step2-indicator">
            <div class="step-counter"><span>2</span></div>
            <div class="step-name">Maklumat Spesifikasi</div>
        </div>
    </div>

    {{-- TODO: point action to the "selesai" submit route once the controller is ready --}}
    <form id="sebutHargaForm" action="{{ route('sebutHargaTerus.submitOffer', $p->id ?? $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modern-card">

            <!-- ======================= STEP 1: CIPTA PROJEK (VIEW ONLY) ======================= -->
            <div id="step1-content">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Maklumat Projek</span>
                </div>

                <div class="p-4">

                    <!-- Tajuk Perolehan -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Tajuk Perolehan</label>
                            <textarea class="form-control" rows="2" disabled>{{ $p->name }}</textarea>
                        </div>
                    </div>

                    <!-- Disediakan Untuk PTJ -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Disediakan Untuk PTJ</label>
                            <input type="text" class="form-control" value="{{ strtoupper($ptjName) }}" disabled>
                        </div>
                    </div>

                    <!-- No. Rujukan Fail / Harga Indikatif -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Rujukan Fail</label>
                            <input type="text" class="form-control" value="{{ $p->ref_number }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Indikatif Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="text" class="form-control text-end"
                                    value="{{ $p->harga_indikatif ? number_format($p->harga_indikatif, 2) : '' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Tarikh Buka / Tarikh Tutup -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Buka</label>
                            <input type="text" class="form-control" value="{{ $p->tarikh_buka }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Tutup</label>
                            <input type="text" class="form-control" value="{{ $p->tarikh_tutup }}" disabled>
                        </div>
                    </div>

                    <hr class="border-light my-2">

                    <div class="row g-4">
                        <!-- Left column -->
                        <div class="col-lg-6">
                            <!-- Zon / Lokasi -->
                            <div class="mb-3">
                                <label class="form-label">Zon / Lokasi</label>
                                <div class="segmented-control">
                                    <input type="radio" id="z_y" value="1" {{ $p->zon_lokasi == '1' ? 'checked' : '' }} disabled>
                                    <label for="z_y">Ya</label>
                                    <input type="radio" id="z_t" value="0" {{ $p->zon_lokasi == '0' ? 'checked' : '' }} disabled>
                                    <label for="z_t">Tidak</label>
                                </div>
                            </div>
                            <!-- Lokaliti Liputan -->
                            @if ($p->zon_lokasi == '1')
                                <div class="mb-3">
                                    <label class="form-label">Lokaliti Liputan</label>
                                    <input type="text" class="form-control" value="{{ $lokalitiName }}" disabled>
                                </div>
                            @endif
                            <!-- Kategori Perolehan -->
                            <div class="mb-0">
                                <label class="form-label">Kategori Perolehan</label>
                                <input type="text" class="form-control" value="{{ $kategoriName }}" disabled>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="col-lg-6">
                            <!-- Sumber Peruntukan -->
                            <div class="mb-3">
                                <label class="form-label">Sumber Peruntukan</label>
                                <div class="segmented-control">
                                    <input type="radio" id="sp_pem" value="pembangunan"
                                        {{ $p->sumber_peruntukan == 'pembangunan' ? 'checked' : '' }} disabled>
                                    <label for="sp_pem">Pembangunan</label>
                                    <input type="radio" id="sp_meng" value="mengurus"
                                        {{ $p->sumber_peruntukan == 'mengurus' ? 'checked' : '' }} disabled>
                                    <label for="sp_meng">Mengurus</label>
                                </div>
                            </div>
                            <!-- Terbuka Kepada -->
                            <div class="mb-0">
                                <label class="form-label">Terbuka Kepada</label>
                                <div class="segmented-control">
                                    <input type="radio" id="tk_b" value="bumiputera"
                                        {{ $p->terbuka_kepada == 'bumiputera' ? 'checked' : '' }} disabled>
                                    <label for="tk_b">Bumiputera</label>
                                    <input type="radio" id="tk_s" value="semua"
                                        {{ $p->terbuka_kepada == 'semua' ? 'checked' : '' }} disabled>
                                    <label for="tk_s">Semua</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END STEP 1 -->

            <!-- ======================= STEP 2: MAKLUMAT SPESIFIKASI ======================= -->
            <div id="step2-content" class="d-none">
                <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span class="fw-bold text-dark text-uppercase small">Maklumat Spesifikasi Kajian</span>
                </div>

                <div class="p-4">

                    <div class="alert-selangor mb-4">
                        <div class="alert-selangor-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <div class="small mt-1">
                            Muat turun Dokumen BQ, lengkapkan maklumat yang diperlukan, kemudian muat naik semula dokumen yang telah dilengkapkan.
                        </div>
                    </div>

                    <!-- BQ Table -->
                    <div class="table-responsive mb-4">
                        <table class="table bq-table align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="py-3 ps-4 fw-bold" width="60px">Bil.</th>
                                    <th class="py-3 fw-bold">Muat Turun BQ</th>
                                    <th class="py-3 pe-4 fw-bold text-center" width="280px">Muat Naik BQ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 fw-semibold">1</td>
                                    <td>
                                        <a href="#" download
                                            class="fw-semibold text-decoration-none d-inline-flex align-items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                            {{ $p->bq_filename ?? 'Dokumen BQ.pdf' }}
                                        </a>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <label for="input-muatnaik-bq" id="muatnaik-bq-btn"
                                            class="btn-form btn-form-success d-inline-flex mx-auto" style="cursor:pointer;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                            Muat Naik
                                        </label>
                                        <input type="file" id="input-muatnaik-bq" name="muat_naik_bq" hidden
                                            accept=".pdf,.doc,.docx,.xls,.xlsx">

                                        <!-- Uploaded file chip -->
                                        <div id="muatnaik-bq-preview" class="d-none justify-content-center mt-2">
                                            <div class="file-chip">
                                                <span class="file-chip-ext" id="muatnaik-bq-ext">file</span>
                                                <div class="file-chip-body">
                                                    <span class="file-chip-name" id="muatnaik-bq-name">-</span>
                                                    <span class="file-chip-size" id="muatnaik-bq-size">-</span>
                                                </div>
                                                <button type="button" class="file-chip-remove" id="muatnaik-bq-remove"
                                                    title="Buang fail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Harga Tawaran -->
                    <h6 class="fw-bold text-dark mb-3">Harga Tawaran</h6>
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label">Jumlah Harga Tawaran (RM) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="text" class="form-control text-end amount-input" name="harga_tawaran"
                                    placeholder="0.00" inputmode="decimal" autocomplete="off">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END STEP 2 -->

            <!-- FOOTER -->
            <div class="bg-light p-4 border-top d-flex justify-content-between align-items-center">
                <button type="button" class="btn-form btn-form-secondary d-none" id="btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Sebelumnya
                </button>

                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn-form btn-form-primary" id="btn-next">
                        Seterusnya
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>

                    <button type="submit" class="btn-form btn-form-success d-none" id="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Selesai
                    </button>
                </div>
            </div>

        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // --- WIZARD NAVIGATION (2 steps) ---
            let currentStep = 1;
            const TOTAL_STEPS = 2;

            function updateWizardUI() {
                $('#stepper-wrapper').attr('data-step', currentStep);

                for (let i = 1; i <= TOTAL_STEPS; i++) {
                    $('#step' + i + '-content').toggleClass('d-none', i !== currentStep);
                }

                $('#step1-indicator, #step2-indicator').removeClass('active completed');
                for (let i = 1; i < currentStep; i++) {
                    $('#step' + i + '-indicator').addClass('completed');
                }
                $('#step' + currentStep + '-indicator').addClass('active');

                $('#btn-back').toggleClass('d-none', currentStep === 1);
                $('#btn-next').toggleClass('d-none', currentStep === TOTAL_STEPS);
                $('#btn-submit').toggleClass('d-none', currentStep !== TOTAL_STEPS);
            }

            function scrollToStepper() {
                $('html, body').animate({ scrollTop: $('#stepper-wrapper').offset().top - 20 }, 400);
            }

            $('#btn-next').click(function() {
                if (currentStep < TOTAL_STEPS) {
                    currentStep++;
                    updateWizardUI();
                    scrollToStepper();
                }
            });

            $('#btn-back').click(function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateWizardUI();
                    scrollToStepper();
                }
            });

            // --- MUAT NAIK BQ: single-file upload ---
            (function() {
                var bqInput  = document.getElementById('input-muatnaik-bq');
                var $preview = $('#muatnaik-bq-preview');

                function formatBytes(b) {
                    if (b < 1024) return b + ' B';
                    if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
                    return (b / 1048576).toFixed(1) + ' MB';
                }

                function showFile(file) {
                    var ext = file.name.split('.').pop().toLowerCase();
                    $('#muatnaik-bq-ext').attr('class', 'file-chip-ext ext-' + ext).text(ext);
                    $('#muatnaik-bq-name').attr('title', file.name).text(file.name);
                    $('#muatnaik-bq-size').text(formatBytes(file.size));
                    $preview.removeClass('d-none').addClass('d-flex');
                    $('#muatnaik-bq-btn').addClass('d-none'); // hide upload button while a file exists
                }

                $(bqInput).on('change', function() {
                    if (this.files && this.files[0]) showFile(this.files[0]);
                });

                $('#muatnaik-bq-remove').on('click', function() {
                    bqInput.value = '';
                    $preview.addClass('d-none').removeClass('d-flex');
                    $('#muatnaik-bq-btn').removeClass('d-none'); // restore upload button
                });
            })();

        });

        // --- AMOUNT INPUT: comma formatting ---
        function parseAmount(val) {
            return parseFloat(String(val).replace(/,/g, '')) || 0;
        }

        function formatAmount(n) {
            return n.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        $(document).on('focus', '.amount-input', function () {
            var raw = $(this).val().replace(/,/g, '');
            $(this).val(raw === '0.00' || raw === '0' ? '' : raw);
        });

        $(document).on('blur', '.amount-input', function () {
            var val = $(this).val().trim();
            if (val === '') return;
            $(this).val(formatAmount(parseAmount(val)));
        });

        $(document).on('input', '.amount-input', function () {
            var val = $(this).val().replace(/[^\d.]/g, '');
            var parts = val.split('.');
            if (parts.length > 2) val = parts[0] + '.' + parts.slice(1).join('');
            $(this).val(val);
        });

        $('#sebutHargaForm').on('submit', function () {
            $(this).find('.amount-input').each(function () {
                $(this).val($(this).val().replace(/,/g, ''));
            });
        });
    </script>
@endsection
