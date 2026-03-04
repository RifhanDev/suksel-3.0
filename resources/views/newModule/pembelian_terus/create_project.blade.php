@extends('layouts.v3.master')

@section('content')

<style>
    /* Progress Bar Styles */
    .progress-wrapper {
        position: relative;
    }

    .progress-step {
        position: relative;
        z-index: 1;
        flex: 1;
    }

    /* Connector line */
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 18px;
        /* center of circle (36px / 2) */
        left: 50%;
        width: 100%;
        height: 3px;
        background: var(--topbar-border, #e5e7eb);
        z-index: 0;
    }

    /* Active / done line */
    .progress-step.active:not(:last-child)::after,
    .progress-step.done:not(:last-child)::after {
        background: var(--sg-red);
    }

    /* Reset future steps */
    .progress-step.active~.progress-step:not(:last-child)::after {
        background: var(--topbar-border, #e5e7eb);
    }

    /* Step circle */
    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--topbar-border, #e5e7eb);
        color: var(--topbar-text, #374151);
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Active & done states */
    .progress-step.active .step-number,
    .progress-step.done .step-number {
        background: var(--sg-red);
        color: #fff;
    }

    .step-label {
        font-size: 13px;
        color: var(--topbar-text, #374151);
    }

    .progress-step.active .step-label,
    .progress-step.done .step-label {
        color: var(--sg-red-dark);
    }


    /* Form Styles */
    .form-title {
        font-size: 18px;
        font-weight: bold;
        color: var(--sg-red-dark);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--topbar-border, #e5e7eb);
    }

    .form-label.required::after {
        content: " *";
        color: #dc3545;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--sg-red);
        box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
    }

    .form-control:read-only,
    .form-select:disabled,
    textarea:read-only {
        background-color: #f9fafb;
        cursor: not-allowed;
        color: #10b981;
        font-weight: 500;
    }

    .form-control:read-only:focus,
    textarea:read-only:focus {
        border-color: #d1d5db;
        box-shadow: none;
    }

    .btn-or-dan {
        background: var(--sg-red);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-or-dan:hover {
        background: var(--sg-red-deep);
        color: white;
    }

    /* Button Styles */
    .btn-simpan {
        background: var(--sg-bg);
        color: var(--sg-black);
        border: 1px solid var(--sg-black);
    }

    .btn-simpan:hover {
        background: var(--sg-black);
        color: var(--sg-bg);
        border-color: var(--sg-black);
    }

    .btn-seterusnya {
        background: var(--sg-red);
        color: white;
        border: none;
    }

    .btn-seterusnya:hover {
        background: var(--sg-red-deep);
        color: white;
    }

    .btn-sebelumnya {
        background: var(--topbar-text, #374151);
        color: white;
        border: none;
    }

    .btn-sebelumnya:hover {
        background: var(--sg-black);
        color: white;
    }

    .btn-tambah {
        background: var(--sg-red);
        color: white;
        border: none;
    }

    .btn-tambah:hover {
        background: var(--sg-red-dark);
        color: white;
    }

    .btn-edit,
    .btn-delete {
        background: transparent;
        border: none;
        padding: 5px 8px;
        cursor: pointer;
        color: var(--topbar-text, #374151);
    }

    .btn-edit:hover {
        color: var(--sg-red);
    }

    .btn-delete:hover {
        color: var(--sg-red-dark);
    }


    .spec-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .spec-table thead {
        background: var(--sg-red);
        color: white;
    }

    .spec-table th,
    .spec-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid var(--topbar-border, #e5e7eb);
    }

    .spec-table th {
        font-weight: bold;
        text-align: center;
        color: white;
    }

    .spec-table tbody tr {
        background: white;
    }

    .spec-table tbody tr:hover {
        background: var(--sg-bg);
    }

    .spec-table td {
        color: var(--sg-black);
    }

    .modal-title {
        color: var(--sg-red-dark);
    }
</style>

<div class="card">
    <div class="card-body p-4">

        {{-- Breadcrumb Navigation --}}
        <nav class="py-2 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">STOS</a></li>
                <li class="breadcrumb-item active fw-semibold">Cipta Projek Pembelian Terus</li>
            </ol>
        </nav>

        {{-- Progress Bar --}}


        <div class="progress-wrapper d-flex align-items-center my-4 my-md-5 px-3 px-md-4">
            <div class="progress-step text-center active" id="step1Indicator">
                <div class="step-number mx-auto mb-2 fw-bold">1</div>
                <div class="step-label fw-semibold">Cipta Projek</div>
            </div>

            <div class="progress-step text-center" id="step2Indicator">
                <div class="step-number mx-auto mb-2 fw-bold">2</div>
                <div class="step-label fw-semibold">Kod Bidang</div>
            </div>

            <div class="progress-step text-center" id="step3Indicator">
                <div class="step-number mx-auto mb-2 fw-bold">3</div>
                <div class="step-label fw-semibold">Maklumat Spesifikasi</div>
            </div>
        </div>


        {{-- STEP 1: Cipta Projek --}}
        <div class="d-none" id="step1Content">
            <h4 class="form-title">CIPTA PROJEK UNTUK PEMBELIAN TERUS</h4>

            {{-- Form --}}
            <form>
                {{-- Single Column Fields --}}
                <div class="d-flex align-items-center mb-3">
                    <label class="form-label required w-25 me-3 text-end mb-0">Tajuk Perolehan</label>
                    <div class="flex-fill">
                        <textarea class="form-control" rows="3" placeholder="Masukkan tajuk perolehan">BEKALAN BARANGAN PERSEKOLAHAN</textarea>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <label class="form-label required w-25 me-3 text-end mb-0">Disediakan Untuk PTJ</label>
                    <div class="flex-fill">
                        <div class="input-group">
                            <input type="text" class="form-control" value="BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN">
                            <span class="input-group-text" style="background: var(--sg-bg); border-color: var(--topbar-border, #e5e7eb); cursor: pointer;">
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
                        <div class="d-flex align-items-center">
                            <label class="form-label required w-50 me-3 text-end mb-0">No. Rujukan Fail</label>
                            <div class="flex-fill">
                                <input type="text" class="form-control" value="SH/DF/TRG">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label required w-50 me-3 text-end mb-0">Harga Indikatif Jabatan</label>
                            <div class="flex-fill">
                                <input type="text" class="form-control" placeholder="Masukkan harga indikatif">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label w-50 me-3 text-end mb-0">Tarikh Buka</label>
                            <div class="flex-fill">
                                <input type="text" class="form-control" value="17/09/2021" placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label w-50 me-3 text-end mb-0">Tarikh Tutup</label>
                            <div class="flex-fill">
                                <input type="text" class="form-control" value="17/10/2021" placeholder="DD/MM/YYYY">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label w-50 me-3 text-end mb-0">Zon / Lokasi</label>
                            <div class="flex-fill">
                                <div class="d-flex gap-3">
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
                        <div class="d-flex align-items-center">
                            <label class="form-label required w-50 me-3 text-end mb-0">Sumber Peruntukan</label>
                            <div class="flex-fill">
                                <div class="d-flex gap-3">
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
                        <div class="d-flex align-items-center">
                            <label class="form-label w-50 me-3 text-end mb-0">Lokaliti Liputan</label>
                            <div class="flex-fill">
                                <select class="form-select">
                                    <option value="">-- Sila Pilih --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="form-label required w-50 me-3 text-end mb-0">Terbuka Kepada</label>
                            <div class="flex-fill">
                                <div class="d-flex gap-3">
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
                        <div class="d-flex align-items-center">
                            <label class="form-label required w-50 me-3 text-end mb-0">Kategori Perolehan</label>
                            <div class="flex-fill">
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
                    <button type="button" class="btn btn-simpan px-4 py-2 rounded fw-bold">Simpan</button>
                    <button type="button" class="btn btn-seterusnya px-4 py-2 rounded fw-bold" onclick="showStep(2)">Seterusnya</button>
                </div>
            </form>
        </div>

        {{-- STEP 2: Maklumat Spesifikasi --}}
        <div class="d-none" id="step2Content">
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
                <button type="button" class="btn btn-tambah px-4 py-2 rounded fw-bold" onclick="openSpecificationModal()">Tambah</button>
            </div>

            {{-- Navigation Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-sebelumnya px-4 py-2 rounded fw-bold" onclick="showStep(1)">Sebelumnya</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-simpan px-4 py-2 rounded fw-bold">Simpan</button>
                    <button type="button" class="btn btn-seterusnya px-4 py-2 rounded fw-bold" onclick="showStep(3)">Seterusnya</button>
                </div>
            </div>
        </div>

        {{-- STEP 3: Kod Bidang --}}
        <div class="d-none" id="step3Content">
            <h4 class="form-title">KOD BIDANG</h4>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <label class="form-label w-50 me-3 text-end mb-0">Kod Bidang MOF</label>
                        <div class="flex-fill">
                            <select class="form-select" id="mofCodes">
                                <option value="">-- Sila Pilih --</option>
                                <option value="1">Kod MOF 1</option>
                                <option value="2">Kod MOF 2</option>
                                <option value="3">Kod MOF 3</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <label class="form-label w-50 me-3 text-end mb-0">Gred CIDB</label>
                        <div class="flex-fill">
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
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <label class="form-label w-50 me-3 text-end mb-0">Bidang Pengkhususan CIDB</label>
                        <div class="flex-fill">
                            <select class="form-select" id="cidbCodes">
                                <option value="">-- Sila Pilih --</option>
                                <option value="1">Bidang CIDB 1</option>
                                <option value="2">Bidang CIDB 2</option>
                                <option value="3">Bidang CIDB 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-sebelumnya px-4 py-2 rounded fw-bold" onclick="showStep(2)">Sebelumnya</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-simpan px-4 py-2 rounded fw-bold" onclick="saveProject()">Simpan</button>
                    <button type="button" class="btn btn-seterusnya px-4 py-2 rounded fw-bold" onclick="publishProject()">Terbitkan</button>
                </div>
            </div>
        </div>

        {{-- STEP 4: Success Message Page for Simpan --}}
        <div class="d-none" id="step4Content">
            <div class="text-center py-5 px-4">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--sg-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3 class="fw-bold mb-3" style="color: var(--sg-red-dark);">Projek Berjaya Disimpan!</h3>
                <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 600px;">
                    Projek pembelian terus anda telah berjaya disimpan sebagai draf. Anda boleh mengemaskini projek ini kemudian atau menerbitkannya apabila sudah siap.
                </p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-sebelumnya px-4 py-2 rounded fw-bold" onclick="showStep(3)">Kembali</button>
                    <button type="button" class="btn btn-seterusnya px-4 py-2 rounded fw-bold" onclick="goToNewProject()">Cipta Projek Baru</button>
                    <button type="button" class="btn btn-simpan px-4 py-2 rounded fw-bold" onclick="goToList()">Lihat Senarai Projek</button>
                </div>
            </div>
        </div>

        {{-- STEP 5: Success Message Page for Terbitkan --}}
        <div class="d-none" id="step5Content">
            <div class="text-center py-5">
                <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--success-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                </div>
                <h3 class="fw-bold mb-3" >Projek Berjaya Diterbitkan!</h3>
                <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 600px;">
                    Projek pembelian terus anda telah berjaya diterbitkan. Projek ini kini aktif dan boleh diakses oleh syarikat-syarikat yang layak.
                </p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-sebelumnya px-4 py-2 rounded fw-bold" onclick="showStep(3)">Kembali</button>
                    <button type="button" class="btn btn-seterusnya px-4 py-2 rounded fw-bold" onclick="goToNewProject()">Cipta Projek Baru</button>
                    <button type="button" class="btn btn-simpan px-4 py-2 rounded fw-bold" onclick="goToList()">Lihat Senarai Projek</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <button type="button" class="btn btn-simpan px-4 py-2 rounded fw-bold" onclick="saveSpecification()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    let specificationCounter = 1;

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('[id$="Content"]').forEach(content => {
            content.classList.add('d-none');
            content.classList.remove('d-block');
        });

        // Remove active and done classes from all step indicators
        document.querySelectorAll('.progress-step').forEach(indicator => {
            indicator.classList.remove('active', 'done');
        });

        // Mark previous steps as done - only for steps 1-3
        for (let i = 1; i < step && i <= 3; i++) {
            const prevIndicator = document.getElementById('step' + i + 'Indicator');
            if (prevIndicator) prevIndicator.classList.add('done');
        }

        // Mark current step as active - only for steps 1-3
        if (step <= 3) {
            const stepIndicator = document.getElementById('step' + step + 'Indicator');
            if (stepIndicator) stepIndicator.classList.add('active');
        }

        // Show selected step content
        const stepContent = document.getElementById('step' + step + 'Content');
        if (stepContent) {
            stepContent.classList.remove('d-none');
            stepContent.classList.add('d-block');
        }

        currentStep = step;
    }

    function showSuccessPage(step) {
        document.querySelectorAll('.progress-step').forEach(indicator => {
            indicator.classList.remove('active');
            indicator.classList.add('done');
        });

        document.querySelectorAll('[id$="Content"]').forEach(content => {
            content.classList.add('d-none');
            content.classList.remove('d-block');
        });

        const successContent = document.getElementById('step' + step + 'Content');
        if (successContent) {
            successContent.classList.remove('d-none');
            successContent.classList.add('d-block');
        }

        currentStep = step;
    }

    function saveProject() {
        // TODO: Add actual save logic here (AJAX call to backend)
        showSuccessPage(4);
    }

    function publishProject() {
        // TODO: Add actual publish logic here (AJAX call to backend)
        showSuccessPage(5);
    }

    // Make showStep available globally
    window.showStep = showStep;

    function openSpecificationModal() {
        document.getElementById('itemName').value = '';
        document.getElementById('itemQuantity').value = '';
        document.getElementById('itemSST').value = '';

        const modalElement = document.getElementById('specificationModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            new bootstrap.Modal(modalElement).show();
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
            if (modal) modal.hide();
        }
    }


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
    });
</script>

@endsection