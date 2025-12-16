@extends('layouts.v3.master')

@section('content')

<style>
    /* Breadcrumb Styles */
    .breadcrumb-nav {
        background: transparent;
        padding: 10px 0;
        margin-bottom: 20px;
    }

    .breadcrumb-nav .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
    }

    .breadcrumb-nav .breadcrumb-item {
        color: #6c757d;
        font-size: 14px;
    }

    .breadcrumb-nav .breadcrumb-item.active {
        color: #495057;
        font-weight: 600;
    }

    .breadcrumb-nav .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
        padding: 0 8px;
        color: #6c757d;
    }

    /* Progress Bar Styles */
    .progress-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0 30px 0;
        padding: 0 20px;
        position: relative;
    }

    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 18px;
        left: 50%;
        width: 100%;
        height: 3px;
        background: #e0e0e0;
        z-index: 0;
    }

    .progress-step.active:not(:last-child)::after,
    .progress-step.done:not(:last-child)::after {
        background: #1F3A8A;
    }

    .step-number {
        width: 36px;
        height: 36px;
        line-height: 36px;
        border-radius: 50%;
        background: #e0e0e0;
        color: #6c757d;
        margin: 0 auto 8px;
        font-weight: bold;
        position: relative;
        z-index: 2;
    }

    .progress-step.active .step-number,
    .progress-step.done .step-number {
        background: #1F3A8A;
        color: white;
    }

    .step-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
    }

    .progress-step.active .step-label,
    .progress-step.done .step-label {
        color: #1F3A8A;
    }

    /* Form Styles */
    .form-title {
        font-size: 18px;
        font-weight: bold;
        color: #1F3A8A;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e0e0;
    }

    .form-row-horizontal {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-row-horizontal .form-label {
        width: 200px;
        min-width: 200px;
        margin-bottom: 0;
        margin-right: 15px;
        text-align: right;
        font-weight: 600;
        color: #495057;
    }

    .form-row-horizontal .form-label.required::after {
        content: " *";
        color: #dc3545;
    }

    .form-row-horizontal .form-control-wrapper {
        flex: 1;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-label.required::after {
        content: " *";
        color: #dc3545;
    }

    .form-control,
    .form-select {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 8px 12px;
        width: 100%;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1F3A8A;
        box-shadow: 0 0 0 0.2rem rgba(31, 58, 138, 0.25);
        outline: none;
    }

    .input-group-text {
        background: #f8f9fa;
        border: 1px solid #ced4da;
        cursor: pointer;
    }

    .form-check-input {
        margin-top: 0.4rem;
    }

    .form-check-label {
        margin-left: 8px;
        font-weight: 500;
    }

    .radio-group {
        display: flex;
        gap: 15px;
    }

    /* Button Styles */
    .btn-simpan {
        background: #00a988;
        color: white;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: bold;
        border: none;
    }

    .btn-simpan:hover {
        background: #008f73;
        color: white;
    }

    .btn-seterusnya {
        background: #1F3A8A;
        color: white;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: bold;
        border: none;
    }

    .btn-seterusnya:hover {
        background: #152a6b;
        color: white;
    }

    .btn-sebelumnya {
        background: #6c757d;
        color: white;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: bold;
        border: none;
    }

    .btn-sebelumnya:hover {
        background: #5a6268;
        color: white;
    }

    .btn-tambah {
        background: #00a988;
        color: white;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: bold;
        border: none;
    }

    .btn-tambah:hover {
        background: #008f73;
        color: white;
    }

    .btn-edit,
    .btn-delete {
        background: transparent;
        border: none;
        padding: 5px 8px;
        cursor: pointer;
        color: #6c757d;
    }

    .btn-edit:hover {
        color: #1F3A8A;
    }

    .btn-delete:hover {
        color: #dc3545;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    .spec-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .spec-table thead {
        background: #1F3A8A;
        color: white;
    }

    .spec-table th,
    .spec-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #dee2e6;
    }

    .spec-table th {
        font-weight: bold;
        text-align: center;
    }

    .spec-table tbody tr {
        background: white;
    }

    .spec-table tbody tr:hover {
        background: #f8f9fa;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
    }

    .modal-title {
        font-size: 18px;
        font-weight: bold;
        color: #1F3A8A;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 15px 20px;
        display: flex;
        justify-content: flex-end;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-dialog {
        position: relative;
        width: auto;
        max-width: 500px;
        margin: 1.75rem auto;
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, .2);
        border-radius: 0.3rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, .5);
        outline: 0;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }

    .modal-backdrop.fade {
        opacity: 0;
    }

    .modal-backdrop.show {
        opacity: 0.5;
    }

    select[multiple] {
        min-height: 100px;
        padding: 8px 12px;
    }

    select[multiple] option {
        padding: 5px;
    }

    .text-muted {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
        display: block;
    }

    .success-icon {
        color: #00a988;
        margin-bottom: 20px;
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb Navigation --}}
        <nav class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">STOS</a></li>
                <li class="breadcrumb-item active">Cipta Projek Pembelian Terus</li>
            </ol>
        </nav>

        {{-- Progress Bar --}}
        <div class="progress-wrapper">
            <div class="progress-step" id="step1Indicator">
                <div class="step-number">1</div>
                <div class="step-label">Cipta Projek</div>
            </div>
            <div class="progress-step" id="step2Indicator">
                <div class="step-number">2</div>
                <div class="step-label">Maklumat Spesifikasi</div>
            </div>
            <div class="progress-step" id="step3Indicator">
                <div class="step-number">3</div>
                <div class="step-label">Kod Bidang</div>
            </div>
        </div>

        {{-- STEP 1: Cipta Projek --}}
        <div class="step-content active" id="step1Content">
            <h4 class="form-title">CIPTA PROJEK UNTUK PEMBELIAN TERUS</h4>

            {{-- Form --}}
            <form>
                {{-- Single Column Fields --}}
                <div class="form-row-horizontal">
                    <label class="form-label required">Tajuk Perolehan</label>
                    <div class="form-control-wrapper">
                        <textarea class="form-control" rows="3" placeholder="Masukkan tajuk perolehan">BEKALAN BARANGAN PERSEKOLAHAN</textarea>
                    </div>
                </div>

                <div class="form-row-horizontal">
                    <label class="form-label required">Disediakan Untuk PTJ</label>
                    <div class="form-control-wrapper">
                        <div class="input-group">
                            <input type="text" class="form-control" value="BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Two Column Fields --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label required">No. Rujukan Fail</label>
                            <div class="form-control-wrapper">
                                <input type="text" class="form-control" value="SH/DF/TRG">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label required">Harga Indikatif Jabatan</label>
                            <div class="form-control-wrapper">
                                <input type="text" class="form-control" placeholder="Masukkan harga indikatif">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label">Tarikh Buka</label>
                            <div class="form-control-wrapper">
                                <input type="text" class="form-control" value="17/09/2021" placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label">Tarikh Tutup</label>
                            <div class="form-control-wrapper">
                                <input type="text" class="form-control" value="17/10/2021" placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label">Zon / Lokasi</label>
                            <div class="form-control-wrapper">
                                <div class="radio-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="zon_lokasi" id="zon_ya" value="ya">
                                        <label class="form-check-label" for="zon_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="zon_lokasi" id="zon_tidak" value="tidak" checked>
                                        <label class="form-check-label" for="zon_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label required">Sumber Peruntukan</label>
                            <div class="form-control-wrapper">
                                <div class="radio-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sumber_peruntukan" id="sumber_pembangunan" value="pembangunan" checked>
                                        <label class="form-check-label" for="sumber_pembangunan">Pembangunan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sumber_peruntukan" id="sumber_mengurus" value="mengurus">
                                        <label class="form-check-label" for="sumber_mengurus">Mengurus</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label">Lokaliti Liputan</label>
                            <div class="form-control-wrapper">
                                <select class="form-select">
                                    <option value="">-- Sila Pilih --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label required">Terbuka Kepada</label>
                            <div class="form-control-wrapper">
                                <div class="radio-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="terbuka_kepada" id="terbuka_bumiputra" value="bumiputra">
                                        <label class="form-check-label" for="terbuka_bumiputra">Bumiputra</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="terbuka_kepada" id="terbuka_semua" value="semua" checked>
                                        <label class="form-check-label" for="terbuka_semua">Semua</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-row-horizontal">
                            <label class="form-label required">Kategori Perolehan</label>
                            <div class="form-control-wrapper">
                                <select class="form-select">
                                    <option value="ICT" selected>ICT</option>
                                    <option value="">-- Sila Pilih --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Empty column for alignment --}}
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-simpan">Simpan</button>
                    <button type="button" class="btn btn-seterusnya" onclick="showStep(2)">Seterusnya</button>
                </div>
            </form>
        </div>

        {{-- STEP 2: Maklumat Spesifikasi --}}
        <div class="step-content" id="step2Content">
            <h4 class="form-title">MAKLUMAT SPESIFIKASI KAJIAN</h4>

            <table class="spec-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Bil.</th>
                        <th>Item</th>
                        <th style="width: 150px;">Kuantiti</th>
                        <th style="width: 120px;">Tindakan</th>
                    </tr>
                </thead>
                <tbody id="specTableBody">

                </tbody>
            </table>

            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="btn btn-tambah" onclick="openSpecificationModal()">Tambah</button>
            </div>

            {{-- Navigation Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-sebelumnya" onclick="showStep(1)">Sebelumnya</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-simpan">Simpan</button>
                    <button type="button" class="btn btn-seterusnya" onclick="showStep(3)">Seterusnya</button>
                </div>
            </div>
        </div>

        {{-- STEP 3: Kod Bidang --}}
        <div class="step-content" id="step3Content">
            <h4 class="form-title">KOD BIDANG</h4>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-row-horizontal">
                        <label class="form-label">Kod Bidang MOF</label>
                        <div class="form-control-wrapper">
                            <select class="form-select" id="mofCodes" multiple>
                                <option value="">-- Sila Pilih --</option>
                                <option value="1">Kod MOF 1</option>
                                <option value="2">Kod MOF 2</option>
                                <option value="3">Kod MOF 3</option>
                            </select>
                            <small class="text-muted">Tekan Ctrl untuk pilih berbilang</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-row-horizontal">
                        <label class="form-label">Gred CIDB</label>
                        <div class="form-control-wrapper">
                            <select class="form-select" id="cidbGrade">
                                <option value="">-- Sila Pilih --</option>
                                <option value="1">Gred 1</option>
                                <option value="2">Gred 2</option>
                                <option value="3">Gred 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-row-horizontal">
                        <label class="form-label">Bidang Pengkhususan CIDB</label>
                        <div class="form-control-wrapper">
                            <select class="form-select" id="cidbCodes" multiple>
                                <option value="">-- Sila Pilih --</option>
                                <option value="1">Bidang CIDB 1</option>
                                <option value="2">Bidang CIDB 2</option>
                                <option value="3">Bidang CIDB 3</option>
                            </select>
                            <small class="text-muted">Tekan Ctrl untuk pilih berbilang</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-sebelumnya" onclick="showStep(2)">Sebelumnya</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-simpan" onclick="saveProject()">Simpan</button>
                    <button type="button" class="btn btn-seterusnya" onclick="publishProject()">Terbitkan</button>
                </div>
            </div>
        </div>

        {{-- STEP 4: Success Message Page for Simpan --}}
        <div class="step-content" id="step4Content" style="display: none;">
            <div style="text-align: center; padding: 60px 20px;">
                <div style="margin-bottom: 30px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#00a988" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3 style="color: #1F3A8A; font-weight: bold; margin-bottom: 20px;">Projek Berjaya Disimpan!</h3>
                <p style="color: #6c757d; font-size: 16px; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Projek pembelian terus anda telah berjaya disimpan sebagai draf. Anda boleh mengemaskini projek ini kemudian atau menerbitkannya apabila sudah siap.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sebelumnya" onclick="showStep(3)">Kembali</button>
                    <button type="button" class="btn btn-seterusnya" onclick="goToNewProject()">Cipta Projek Baru</button>
                    <button type="button" class="btn btn-simpan" onclick="goToList()">Lihat Senarai Projek</button>
                </div>
            </div>
        </div>

        {{-- STEP 5: Success Message Page for Terbitkan --}}
        <div class="step-content" id="step5Content" style="display: none;">
            <div style="text-align: center; padding: 60px 20px;">
                <div style="margin-bottom: 30px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#00a988" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3 style="color: #1F3A8A; font-weight: bold; margin-bottom: 20px;">Projek Berjaya Diterbitkan!</h3>
                <p style="color: #6c757d; font-size: 16px; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Projek pembelian terus anda telah berjaya diterbitkan. Projek ini kini aktif dan boleh diakses oleh syarikat-syarikat yang layak.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sebelumnya" onclick="showStep(3)">Kembali</button>
                    <button type="button" class="btn btn-seterusnya" onclick="goToNewProject()">Cipta Projek Baru</button>
                    <button type="button" class="btn btn-simpan" onclick="goToList()">Lihat Senarai Projek</button>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal: Cipta Spesifikasi --}}
<div class="modal fade" id="specificationModal" tabindex="-1" aria-labelledby="specificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="specificationModalLabel">Cipta Spesifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="border: none; background: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <form id="specificationForm">
                    <div class="mb-3">
                        <label class="form-label required">Nama Item</label>
                        <input type="text" class="form-control" id="itemName" value="Monitor">
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Kuantiti</label>
                        <input type="number" class="form-control" id="itemQuantity" value="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">SST</label>
                        <select class="form-select" id="itemSST">
                            <option value="">Ya/Tidak</option>
                            <option value="Ya" selected>Ya</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-simpan" onclick="saveSpecification()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    let specificationCounter = 1;

    function showStep(step) {
        console.log('showStep called with:', step);

        // Hide all steps
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.remove('active');
            content.style.display = 'none';
        });

        // Remove active and done classes from all step indicators
        document.querySelectorAll('.progress-step').forEach(indicator => {
            indicator.classList.remove('active', 'done');
        });

        // Mark previous steps as done (blue) - only for steps 1-3
        for (let i = 1; i < step && i <= 3; i++) {
            const prevIndicator = document.getElementById('step' + i + 'Indicator');
            if (prevIndicator) {
                prevIndicator.classList.add('done');
            }
        }

        // Mark current step as active (blue) - only for steps 1-3
        if (step <= 3) {
            const stepIndicator = document.getElementById('step' + step + 'Indicator');
            if (stepIndicator) {
                stepIndicator.classList.add('active');
            }
        }

        // Show selected step content
        const stepContent = document.getElementById('step' + step + 'Content');
        if (stepContent) {
            stepContent.classList.add('active');
            stepContent.style.display = 'block';
        } else {
            console.error('Step content not found:', 'step' + step + 'Content');
        }

        currentStep = step;
    }

    function saveProject() {
        // TODO: Add actual save logic here (AJAX call to backend)
        console.log('Saving project...');

        // Mark all steps as done
        document.querySelectorAll('.progress-step').forEach(indicator => {
            indicator.classList.remove('active');
            indicator.classList.add('done');
        });

        // Hide all step contents
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.remove('active');
            content.style.display = 'none';
        });

        // Show success page for Simpan
        const successContent = document.getElementById('step4Content');
        if (successContent) {
            successContent.style.display = 'block';
            successContent.classList.add('active');
        }

        currentStep = 4;
    }

    function publishProject() {
        // TODO: Add actual publish logic here (AJAX call to backend)
        console.log('Publishing project...');

        // Mark all steps as done
        document.querySelectorAll('.progress-step').forEach(indicator => {
            indicator.classList.remove('active');
            indicator.classList.add('done');
        });

        // Hide all step contents
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.remove('active');
            content.style.display = 'none';
        });

        // Show success page for Terbitkan
        const successContent = document.getElementById('step5Content');
        if (successContent) {
            successContent.style.display = 'block';
            successContent.classList.add('active');
        }

        currentStep = 5;
    }

    // Make showStep available globally
    window.showStep = showStep;

    function openSpecificationModal() {
        // Reset form
        document.getElementById('itemName').value = '';
        document.getElementById('itemQuantity').value = '';
        document.getElementById('itemSST').value = '';

        // Open modal - check if Bootstrap is available
        const modalElement = document.getElementById('specificationModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            // Fallback: show modal using jQuery or plain JavaScript
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modalBackdrop';
            document.body.appendChild(backdrop);
        }
    }

    function editSpecification(index) {
        // TODO: Load existing data and open modal
        openSpecificationModal();
    }

    function deleteSpecification(index) {
        if (confirm('Adakah anda pasti mahu memadam item ini?')) {
            // TODO: Remove row from table
            const tbody = document.getElementById('specTableBody');
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 0) {
                rows[index - 1].remove();
            }
        }
    }

    function saveSpecification() {
        const itemName = document.getElementById('itemName').value;
        const itemQuantity = document.getElementById('itemQuantity').value;
        const itemSST = document.getElementById('itemSST').value;

        // Add or update row in table
        const tbody = document.getElementById('specTableBody');
        const newRow = tbody.insertRow();
        newRow.innerHTML = `
            <td style="text-align: center;">${specificationCounter}</td>
            <td>${itemName || ''}</td>
            <td>${itemQuantity || ''}</td>
            <td style="text-align: center;">
                <button type="button" class="btn-edit" onclick="editSpecification(${specificationCounter})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </button>
                <button type="button" class="btn-delete" onclick="deleteSpecification(${specificationCounter})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </td>
        `;

        specificationCounter++;

        // Close modal
        closeModal();
    }

    function closeModal() {
        const modalElement = document.getElementById('specificationModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        } else {
            // Fallback: hide modal
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            const backdrop = document.getElementById('modalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }

    // Close modal when clicking close button
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.querySelector('#specificationModal .btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        // Close modal when clicking outside
        const modalElement = document.getElementById('specificationModal');
        if (modalElement) {
            modalElement.addEventListener('click', function(e) {
                if (e.target === modalElement) {
                    closeModal();
                }
            });
        }
    });

    function goToNewProject() {
        window.location.href = '{{ route("pembelianTerus.createProject") }}';
    }

    function goToList() {
        // TODO: Update with actual route when available
        window.location.href = '#';
    }

    // Initialize step 1 as active when page loads
    document.addEventListener('DOMContentLoaded', function() {
        showStep(1);

        // Initialize multi-select styling if needed
        const multiSelects = document.querySelectorAll('select[multiple]');
        multiSelects.forEach(select => {
            select.style.minHeight = '100px';
        });
    });
</script>

@endsection