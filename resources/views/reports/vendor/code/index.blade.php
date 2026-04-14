@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
    <style>
        .code-section {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: visible;
        }

        .code-section-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 8px 8px 0 0;
            border-left: 3px solid var(--sg-red);
        }

        .code-section-header .section-badge {
            background: var(--sg-red);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .code-section-header .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }

        .code-section-header .section-sub {
            font-size: 0.7rem;
            color: #94a3b8;
            margin: 0;
        }

        .code-row {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
        }

        .code-row:last-child {
            border-bottom: none;
        }

        .code-row:hover {
            background: #fafbfc;
        }

        .code-row .row-logic {
            width: 145px;
            flex-shrink: 0;
        }

        .code-row .row-logic .form-label,
        .code-row .row-select .form-label {
            font-size: 0.72rem;
            color: #64748b;
            margin-bottom: 4px;
        }

        .code-row .row-select {
            flex: 1;
            min-width: 0;
        }

        .code-row .row-remove {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: none;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .code-row .row-remove:hover {
            background: var(--sg-red);
            color: #fff;
        }

        /* Compact connector */
        .code-connector {
            display: flex;
            align-items: center;
            padding: 0 12px;
            background: #f8fafc;
        }

        .code-connector .connector-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .code-connector .connector-pill {
            background: var(--sg-yellow);
            color: #1a1a1a;
            font-weight: 700;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 80px;
            padding: 4px 10px;
            padding-right: 22px;
            border-radius: 12px;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            border: none;
            margin: 5px 10px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .code-connector .connector-pill:focus {
            outline: none;
        }

        .connector-pill-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .connector-pill-wrapper::after {
            content: '';
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            border-top: 4px solid #1a1a1a;
            pointer-events: none;
        }

        .btn-add-row {
            width: 100%;
            padding: 8px 12px;
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 0 0 8px 8px;
            color: #64748b;
            font-weight: 600;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-add-row:hover {
            background: #fef2f2;
            border-color: var(--sg-red);
            color: var(--sg-red);
        }

        @media (max-width: 768px) {
            .code-row {
                flex-wrap: wrap;
            }

            .code-row .row-logic,
            .code-row .row-select {
                width: 100%;
                flex: none;
            }

            .code-row .row-remove {
                position: absolute;
                top: 8px;
                right: 8px;
            }
        }
    </style>
@endsection

@section('content')

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Syarikat Mengikut Kod Bidang</h3>
            <p class="text-muted small m-0">Jana laporan senarai syarikat mengikut kod bidang MOF dan CIDB.</p>
        </div>
    </div>

    <div class="content-card p-4" style="overflow: visible;">
        <form action="{{ action('ReportVendorCodeController@view') }}" method="POST" target="_blank">
            @csrf

            {{-- Daerah --}}
            <div class="mb-3">
                <label for="district_id" class="form-label fw-semibold">Daerah</label>
                <select name="district_id" id="district_id" class="form-select">
                    <option value="-1"></option>
                    @foreach (App\Vendor::$districts as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- MOF Kod Bidang --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Kod Bidang MOF</label>
                <div class="code-section">
                    <div class="code-section-header">
                        <span class="section-badge">MOF</span>
                        <span class="section-title">Kod Bidang MOF</span>
                        <span class="section-sub">· Kementerian Kewangan Malaysia</span>
                    </div>
                    <div id="mof_form">
                        <div id="mof_form_template">
                            <div class="code-row">
                                <div class="row-logic">
                                    <div class="form-label">Hubungan</div>
                                    <div class="segmented-control">
                                        <input type="radio" name="mof_codes[#index#][inner_rule]" id="mof_inner_or_#index#" value="or" checked>
                                        <label for="mof_inner_or_#index#">Salah Satu</label>
                                        <input type="radio" name="mof_codes[#index#][inner_rule]" id="mof_inner_and_#index#" value="and">
                                        <label for="mof_inner_and_#index#">Kesemua</label>
                                    </div>
                                </div>
                                <div class="row-select">
                                    <div class="form-label">Kod Bidang</div>
                                    <select id="mof_form_#index#_codes" class="mof-code-codes selectize" name="mof_codes[#index#][codes][]" multiple="multiple">
                                        @foreach (App\Code::where('type', 'mof')->get() as $code)
                                            <option value="{{ $code->id }}">{{ $code->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <a class="row-remove" id="mof_form_remove_current">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </a>
                            </div>
                            <div class="code-connector join-rule" style="display: none;">
                                <span class="connector-line"></span>
                                <div class="connector-pill-wrapper">
                                    <select id="mof_form_#index#_join_rule" class="mof-code-join-rule connector-pill" name="mof_codes[#index#][join_rule]">
                                        <option value="or">ATAU</option>
                                        <option value="and">DAN</option>
                                    </select>
                                </div>
                                <span class="connector-line"></span>
                            </div>
                        </div>
                        <div id="mof_form_noforms_template"></div>
                        <div id="mof_form_controls">
                            <div id="mof_form_add">
                                <a class="btn-add-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    Tambah Kod Bidang MOF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CIDB Gred --}}
            <div class="mb-3">
                <label for="cidb_grades" class="form-label fw-semibold">Gred CIDB</label>
                <select name="cidb_grades[]" id="cidb_grades" class="selectize" multiple>
                    @foreach (App\Code::where('type', 'cidb-g')->get()->pluck('label', 'id') as $id => $label)
                        <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- CIDB Kod Bidang --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Kod Bidang CIDB</label>
                <div class="code-section">
                    <div class="code-section-header">
                        <span class="section-badge">CIDB</span>
                        <span class="section-title">Kod Bidang CIDB</span>
                        <span class="section-sub">· Lembaga Pembangunan Industri Pembinaan</span>
                    </div>
                    <div id="cidb_form">
                        <div id="cidb_form_template">
                            <div class="code-row">
                                <div class="row-logic">
                                    <div class="form-label">Hubungan</div>
                                    <div class="segmented-control">
                                        <input type="radio" name="cidb_codes[#index#][inner_rule]" id="cidb_inner_and_#index#" value="and" checked>
                                        <label for="cidb_inner_and_#index#">Kesemua</label>
                                        <input type="radio" name="cidb_codes[#index#][inner_rule]" id="cidb_inner_or_#index#" value="or">
                                        <label for="cidb_inner_or_#index#">Salah Satu</label>
                                    </div>
                                </div>
                                <div class="row-select">
                                    <div class="form-label">Kod Bidang</div>
                                    <select id="cidb_form_#index#_codes" class="mof-codes-code selectize" name="cidb_codes[#index#][codes][]" multiple="multiple">
                                        @foreach (App\Code::where('type', 'cidb-c')->get() as $code)
                                            <option value="{{ $code->id }}">{{ $code->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <a class="row-remove" id="cidb_form_remove_current">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </a>
                            </div>
                            <div class="code-connector join-rule" style="display: none;">
                                <span class="connector-line"></span>
                                <div class="connector-pill-wrapper">
                                    <select id="cidb_form_#index#_join_rule" class="cidb-codes-join-rule connector-pill" name="cidb_codes[#index#][join_rule]">
                                        <option value="or">ATAU</option>
                                        <option value="and">DAN</option>
                                    </select>
                                </div>
                                <span class="connector-line"></span>
                            </div>
                        </div>
                        <div id="cidb_form_noforms_template"></div>
                        <div id="cidb_form_controls">
                            <div id="cidb_form_add">
                                <a class="btn-add-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    Tambah Kod Bidang CIDB
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-form btn-form-primary">Jana Laporan</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        function selectize_select(id) {
            $(id).find('select.selectize').each(function() {
                if (!this.selectize) $(this).selectize();
            });
        }

        $("#mof_form").sheepIt({
            separator: '',
            iniFormsCount: 1,
            allowAdd: true,
            beforeAdd: function(source) {
                source.find('[id^="mof_form_template"]').last().find('.join-rule').css('display', 'flex');
            },
            afterAdd: function(source, newForm) {
                newForm.find('.join-rule').hide();
                selectize_select('#mof_form');
            },
            afterRemoveCurrent: function(source) {
                source.find('[id^="mof_form_template"]').last().find('.join-rule').hide();
            }
        });

        $("#cidb_form").sheepIt({
            separator: '',
            iniFormsCount: 1,
            allowAdd: true,
            beforeAdd: function(source) {
                source.find('[id^="cidb_form_template"]').last().find('.join-rule').css('display', 'flex');
            },
            afterAdd: function(source, newForm) {
                newForm.find('.join-rule').hide();
                selectize_select('#cidb_form');
            },
            afterRemoveCurrent: function(source) {
                source.find('[id^="cidb_form_template"]').last().find('.join-rule').hide();
            }
        });

        selectize_select('#mof_form');
        selectize_select('#cidb_form');
        $("#cidb_grades").selectize();

    </script>
@endsection
