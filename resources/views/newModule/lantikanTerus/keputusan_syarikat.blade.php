@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
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

        .project-summary-item { display: flex; align-items: center; gap: 14px; padding: 16px 22px; flex: 1; min-width: 240px; }

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

        .project-summary-item .ps-text { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
        .project-summary-item .ps-label { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; }
        .project-summary-item .ps-value { font-size: 0.9rem; font-weight: 700; color: #1f2937; line-height: 1.35; }
        .project-summary .ps-divider { width: 1px; background: #f1f5f9; align-self: stretch; margin: 12px 0; }

        @media (max-width: 767px) {
            .project-summary .ps-divider { display: none; }
            .project-summary-item { flex: 1 1 100%; border-bottom: 1px solid #f1f5f9; }
            .project-summary-item:last-child { border-bottom: none; }
        }

        /* --- DECISION RECORD --- */
        .decision-record {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
        }

        .dr-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            border-bottom: 1px solid #eef2f6;
        }

        .dr-company { display: flex; align-items: center; gap: 14px; min-width: 0; }

        .dr-company-label { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #94a3b8; margin-bottom: 3px; }
        .dr-company-name { font-size: 1rem; font-weight: 700; color: #0f172a; line-height: 1.3; }

        /* Flat, bordered status tag */
        .dr-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            border: 1px solid;
            white-space: nowrap;
        }

        .dr-status svg { width: 13px; height: 13px; }
        .dr-status--pending  { color: #854d0e; background: #fffbeb; border-color: #fcd34d; }
        .dr-status--accepted { color: #15803d; background: #f0fdf4; border-color: #86efac; }
        .dr-status--rejected { color: #b91c1c; background: #fef2f2; border-color: #fca5a5; }

        .dr-body { display: flex; flex-wrap: wrap; }

        .dr-field { flex: 1; min-width: 220px; padding: 16px 22px; border-right: 1px solid #eef2f6; }
        .dr-field:last-child { border-right: none; }
        .dr-field-label { display: block; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #94a3b8; margin-bottom: 5px; }
        .dr-field-value { font-size: 0.95rem; font-weight: 600; color: #1e293b; }
        .dr-field-value .rm { font-size: 0.72rem; font-weight: 700; color: #94a3b8; margin-right: 3px; }
        .dr-field-value.dr-amount { font-size: 1.25rem; font-weight: 800; color: #0f172a; letter-spacing: -0.3px; }

        .dr-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 14px 22px;
            border-top: 1px solid #eef2f6;
            background: #fafbfc;
        }

        .dr-hint { display: flex; align-items: center; gap: 8px; font-size: 0.78rem; color: #64748b; }
        .dr-actions { display: flex; gap: 10px; }

        .btn-form:disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none; }

        @media (max-width: 575px) {
            .dr-head { flex-direction: column; align-items: flex-start; }
            .dr-field { border-right: none; border-bottom: 1px solid #eef2f6; }
            .dr-foot { flex-direction: column; align-items: stretch; }
            .dr-actions .btn-form { flex: 1; justify-content: center; }
        }
    </style>
@endsection

@section('content')
    @php
        $p = $project;
        // Monogram initials from the first two alphanumeric words
        $words    = array_values(array_filter(preg_split('/\s+/', trim($decision->company)), fn ($w) => ctype_alnum($w[0] ?? '')));
        $initials = strtoupper(substr($words[0] ?? '', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    @endphp

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Keputusan Syarikat</h3>
            <p class="text-muted small m-0">Terima atau tolak syarikat yang telah dipilih.</p>
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

    <div class="modern-card">

        <!-- ======================= KEPUTUSAN SYARIKAT ======================= -->
        <div class="bg-light px-4 py-3 border-bottom d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 11l3 3L22 4"></path>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            <span class="fw-bold text-dark text-uppercase small">Keputusan Syarikat</span>
        </div>

        <div class="p-4">

            <div class="decision-record">
                <!-- Head: company + status -->
                <div class="dr-head">
                    <div class="dr-company">
                        <div>
                            <div class="dr-company-label">Syarikat Dipilih</div>
                            <div class="dr-company-name">{{ $decision->company }}</div>
                        </div>
                    </div>
                    <span class="dr-status dr-status--pending" id="decision-status">
                        <svg id="decision-status-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="status-text">Menunggu Keputusan</span>
                    </span>
                </div>

                <!-- Body: key figures -->
                <div class="dr-body">
                    <div class="dr-field">
                        <span class="dr-field-label">Harga Termasuk SST</span>
                        <span class="dr-field-value dr-amount"><span class="rm">RM</span>{{ number_format($decision->harga_sst, 2) }}</span>
                    </div>
                    <div class="dr-field">
                        <span class="dr-field-label">Tarikh Tawaran</span>
                        <span class="dr-field-value">{{ $p->tarikh }}</span>
                    </div>
                </div>

                <!-- Foot: hint + actions -->
                <div class="dr-foot">
                    <span class="dr-hint">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Surat Setuju Terima hanya boleh dijana selepas syarikat diterima.
                    </span>
                    <div class="dr-actions" id="decision-actions">
                        <button type="button" class="btn-form btn-form-success" id="btn-terima">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Terima
                        </button>
                        <button type="button" class="btn-form btn-form-danger" id="btn-tolak">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Tolak
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="bg-light p-4 border-top d-flex justify-content-end gap-2">
            <button type="button" class="btn-form btn-form-primary" id="btn-surat" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                Surat Setuju Terima
            </button>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            var $status  = $('#decision-status');
            var $actions = $('#decision-actions');
            var $surat   = $('#btn-surat');

            var STATUS_ICONS = {
                accepted: '<polyline points="20 6 9 17 4 12"></polyline>',
                rejected: '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>'
            };

            function setStatus(state, label) {
                $status.removeClass('dr-status--pending dr-status--accepted dr-status--rejected')
                       .addClass('dr-status--' + state);
                $status.find('.status-text').text(label);
                if (STATUS_ICONS[state]) {
                    $('#decision-status-icon').html(STATUS_ICONS[state]);
                }
            }

            // --- TERIMA: unlock Surat Setuju Terima ---
            $('#btn-terima').on('click', function() {
                setStatus('accepted', 'Diterima');
                $actions.addClass('d-none');
                $surat.prop('disabled', false);
            });

            // --- TOLAK: confirm via modal ---
            $('#btn-tolak').on('click', function() {
                Swal.fire({
                    title: 'Tolak Syarikat?',
                    text: 'Adakah anda pasti untuk menolak syarikat ini? Tindakan ini tidak boleh diubah.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        setStatus('rejected', 'Ditolak');
                        $actions.addClass('d-none');
                        $surat.prop('disabled', true);
                        Swal.fire({
                            title: 'Syarikat Ditolak',
                            text: 'Syarikat ini telah ditandakan sebagai ditolak.',
                            icon: 'success',
                            confirmButtonColor: '#1e293b'
                        });
                    }
                });
            });

            // --- SURAT SETUJU TERIMA ---
            $surat.on('click', function() {
                // TODO: hook to the real "Surat Setuju Terima" generation/route when ready
                Swal.fire({
                    title: 'Surat Setuju Terima',
                    text: 'Surat Setuju Terima akan dijana untuk syarikat ini.',
                    icon: 'info',
                    confirmButtonColor: '#1e293b'
                });
            });

        });
    </script>
@endsection
