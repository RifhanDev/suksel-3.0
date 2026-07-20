@extends('layouts.modernLanding')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <style>
        .select2-search input { display: none; }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.875rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            color: #374151;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #c41e3a;
            box-shadow: 0 0 0 3px rgba(196,30,58,0.08);
        }

        .content-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .content-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            background: #fff;
        }
        .content-card-icon {
            width: 34px; height: 34px;
            background: rgba(196,30,58,0.08);
            color: #c41e3a;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .content-card-icon svg { width: 16px; height: 16px; }
        .content-card-title { font-size: 0.875rem; font-weight: 700; color: #111827; margin: 0; }
        .content-card-body { padding: 20px; }

        .field-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 16px;
        }
        .field-row:last-child { margin-bottom: 0; }
        .field-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
        }
        .field-label sup { color: #c41e3a; }
        .field-hint {
            font-size: 0.72rem;
            color: #6b7280;
            margin-top: 3px;
        }
        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #374151;
            padding: 8px 10px;
            width: 100%;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus {
            border-color: #c41e3a;
            box-shadow: 0 0 0 3px rgba(196,30,58,0.08);
            outline: none;
        }
        .form-control[readonly] {
            background: #f9fafb;
            color: #6b7280;
        }
        .form-control-file {
            font-size: 0.82rem;
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0 16px;
        }
        .section-divider-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            white-space: nowrap;
        }
        .section-divider-line {
            flex: 1;
            height: 1px;
            background: #f3f4f6;
        }

        .upload-group {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 10px;
        }
        .upload-instruction {
            font-size: 0.78rem;
            color: #374151;
            margin-bottom: 8px;
        }

        .loader2 {
            font-size: 10px;
            margin: 5em auto;
            width: 1em;
            height: 1em;
            border-radius: 50%;
            position: relative;
            text-indent: -9999em;
            -webkit-animation: load2 1.3s infinite linear;
            animation: load2 1.3s infinite linear;
        }
        @-webkit-keyframes load2 {
            0%, 100% { box-shadow: 0em -3em 0em 0.2em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 0em #c32508; }
            12.5% { box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 0.2em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            25% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 0.2em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            37.5% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 0.2em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            50% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 0.2em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            62.5% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 0.2em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 -0.5em #c32508; }
            75% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0.2em #c32508, -2em -2em 0 0em #c32508; }
            87.5% { box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 0.2em #c32508; }
        }
        @keyframes load2 {
            0%, 100% { box-shadow: 0em -3em 0em 0.2em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 0em #c32508; }
            12.5% { box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 0.2em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            25% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 0em #c32508, 3em 0em 0 0.2em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            37.5% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 0em #c32508, 2em 2em 0 0.2em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 -0.5em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            50% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 0em #c32508, 0em 3em 0 0.2em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 -0.5em #c32508, -2em -2em 0 -0.5em #c32508; }
            62.5% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 0em #c32508, -2em 2em 0 0.2em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 -0.5em #c32508; }
            75% { box-shadow: 0em -3em 0em -0.5em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0.2em #c32508, -2em -2em 0 0em #c32508; }
            87.5% { box-shadow: 0em -3em 0em 0em #c32508, 2em -2em 0 -0.5em #c32508, 3em 0em 0 -0.5em #c32508, 2em 2em 0 -0.5em #c32508, 0em 3em 0 -0.5em #c32508, -2em 2em 0 0em #c32508, -3em 0em 0 0em #c32508, -2em -2em 0 0.2em #c32508; }
        }
    </style>
@endsection

@section('content')

    {{-- Page header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Permohonan Pemulangan Semula</h3>
            <p class="text-muted small m-0">Lengkapkan borang di bawah untuk membuat permohonan pemulangan semula.</p>
        </div>
    </div>

    <form action="{{ route('refunds.store') }}" enctype="multipart/form-data" method="POST">
        @csrf

        {{-- Senarai Transaksi --}}
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
                <h3 class="content-card-title">Pilih Transaksi</h3>
            </div>
            <div class="content-card-body">
                <div class="field-row">
                    <label class="field-label" for="transaction_id">Senarai Transaksi <sup>*</sup></label>
                    <select class="form-control" id="transaction_id" name="transaction_id" onchange="transactionDetails(this)" required></select>
                </div>
            </div>
        </div>

        {{-- Loader --}}
        <div id="loader"></div>

        {{-- Transaction detail + form fields (hidden until transaction selected) --}}
        <div id="table-transaction" style="display: none;">

            {{-- Maklumat Transaksi --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <h3 class="content-card-title">Maklumat Transaksi</h3>
                </div>
                <div class="content-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label">Nama Perniagaan / Syarikat</label>
                                <input class="form-control" type="text" id="company_name" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label">Pembayaran Melalui</label>
                                <input class="form-control" type="text" id="payment_type" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label">Tarikh Transaksi</label>
                                <input class="form-control" type="text" id="transaction_date" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label">Jumlah Bayaran (RM)</label>
                                <input class="form-control" type="text" id="amount" name="amount" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-row">
                                <label class="field-label">Langganan / Tajuk Sebut Harga / Tender yang dibeli</label>
                                <textarea class="form-control" type="text" id="title" style="resize:vertical" readonly></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-row">
                                <label class="field-label">Agensi</label>
                                <input class="form-control" type="text" id="agency" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maklumat Pemohon --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h3 class="content-card-title">Maklumat Pemohon</h3>
                </div>
                <div class="content-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label" for="name">Nama Pemohon <sup>*</sup></label>
                                <input class="form-control" type="text" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label" for="ic">No. Kad Pengenalan Pemohon <sup>*</sup></label>
                                <input class="form-control" type="text" id="ic" name="ic" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label" for="tel">No. Telefon <sup>*</sup></label>
                                <input class="form-control" type="tel" id="tel" name="tel" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-row">
                                <label class="field-label" for="address">Alamat Pemohon <sup>*</sup></label>
                                <textarea class="form-control" type="text" id="address" name="address" style="resize:vertical" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maklumat Bank --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <h3 class="content-card-title">Maklumat Bank Pemohon</h3>
                </div>
                <div class="content-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label" for="bank_acc">No. Akaun Bank Pemohon <sup>*</sup></label>
                                <input class="form-control" type="text" id="bank_acc" name="bank_acc" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-row">
                                <label class="field-label" for="bank_id">Jenis Bank <sup>*</sup></label>
                                <select class="form-control" id="bank_id" name="bank_id" required>
                                    <option value="">-- Sila Pilih Bank --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="field-row">
                                <label class="field-label" for="bank_address">Alamat Bank Pemohon <sup>*</sup></label>
                                <textarea class="form-control" type="text" id="bank_address" name="bank_address" style="resize:vertical" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lampiran --}}
            <div class="content-card">
                <div class="content-card-header">
                    <div class="content-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    </div>
                    <h3 class="content-card-title">Lampiran Dokumen</h3>
                </div>
                <div class="content-card-body">

                    <div class="section-divider">
                        <span class="section-divider-label">Surat Permohonan <sup style="color:#c41e3a;">*</sup></span>
                        <div class="section-divider-line"></div>
                    </div>
                    <div class="upload-group">
                        <p class="upload-instruction">1) Sila muat naik surat permohonan yang telah <b>lengkap diisi</b>.</p>
                        <input class="form-control form-control-file" type="file" accept="application/pdf" name="application_letter" id="application_letter" required>
                        <div class="field-hint">Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.</div>
                    </div>

                    <div class="section-divider">
                        <span class="section-divider-label">Salinan Penyata Bank <sup style="color:#c41e3a;">*</sup></span>
                        <div class="section-divider-line"></div>
                    </div>
                    <div class="upload-group">
                        <p class="upload-instruction">1) Resit bank yang mengandungi bukti penolakan bayaran langganan/pembelian <b>disahkan oleh pegawai bank</b>.</p>
                        <input class="form-control form-control-file" type="file" accept="application/pdf" name="bank_statement1" id="bank_statement1" required>
                        <div class="field-hint">Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.</div>
                    </div>
                    <div class="upload-group">
                        <p class="upload-instruction">2) Penyata akaun bank pemohon (untuk dikreditkan pembayaran semula) yang <b>disahkan oleh pegawai bank</b>.</p>
                        <input class="form-control form-control-file" type="file" accept="application/pdf" name="bank_statement2" id="bank_statement2" required>
                        <div class="field-hint">Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.</div>
                    </div>

                    <div class="section-divider">
                        <span class="section-divider-label">Lampiran Tambahan <sup style="color:#c41e3a;">*</sup></span>
                        <div class="section-divider-line"></div>
                    </div>
                    <div class="upload-group">
                        <p class="upload-instruction">1) Nyatakan sebab-sebab pemulangan semula.</p>
                        <textarea class="form-control" type="text" id="remark" name="remark" style="resize:vertical" rows="3" required></textarea>
                    </div>
                    <div class="upload-group">
                        <p class="upload-instruction">2) Sila lampirkan tangkapan skrin masalah yang dihadapi. (Tidak Wajib)</p>
                        <input class="form-control form-control-file" type="file" accept="application/pdf" name="screenshot_problem" id="screenshot_problem">
                        <div class="field-hint">Muat naik fail berkaitan untuk tujuan pengesahan. Hanya fail beformat PDF sahaja.</div>
                    </div>

                </div>
            </div>

        </div>{{-- /#table-transaction --}}

        {{-- Footer actions --}}
        <div class="d-flex justify-content-between align-items-center py-3">
            <a href="{{ route('dashboard') }}" class="btn-form btn-form-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>
            <button type="submit" class="btn-form btn-form-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Hantar
            </button>
        </div>

    </form>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#transaction_id").select2({
                minimumResultsForSearch: -1,
                ajax: {
                    url: "{{ route('get_transaction') }}",
                    type: "post",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            page: params.page || 1
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response,
                            pagination: {
                                more: response.length >= 10
                            }
                        };
                    },
                    cache: true,
                    error: function(jqXHR, status, error) {
                        console.log(error + ": " + jqXHR.responseText);
                        return { results: [] };
                    }
                }
            });
        });

        function transactionDetails(data) {
            $.ajax({
                url: "{{ route('get_refund_details') }}",
                type: 'POST',
                data: { id: data.value },
                beforeSend: function() {
                    $('#loader').addClass('loader2');
                    $('#table-transaction').hide();
                },
                success: function(response) {
                    $('#company_name').val(response.vendor);
                    $('#payment_type').val(response.method);
                    $('#transaction_date').val(response.transaction_date);
                    $('#amount').val(response.amount);
                    $('#title').val(response.title);
                    $('#agency').val(response.agency);
                },
                complete: function() {
                    $('#loader').removeClass('loader2');
                    $('#table-transaction').show();
                }
            });
        }
    </script>
@endsection
