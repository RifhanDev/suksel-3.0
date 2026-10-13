{{--
	Modal "Pilih Kaedah Penilaian" untuk butang Lantik Jawatan Kuasa.

	Dikongsi oleh senarai tender admin (/tender) dan senarai agensi, supaya
	kedua-duanya tidak terpesong. Sertakan pasangannya, _pilih_peringkat_script,
	dalam bahagian skrip halaman.

	Gaya dipush ke stack 'styles'. Sebelum ini ia berada dalam @section('styles')
	di tenders/index.blade.php, tetapi layout v3 hanya ada @stack('styles') —
	jadi CSS itu tidak pernah dimuatkan langsung.
--}}

@push('styles')
	<style>
        .peringkat-option {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            background: #fff;
            transition: border-color 0.15s, background 0.15s;
        }

        .peringkat-option:hover {
            border-color: #fca5a5;
            background: #fff5f5;
        }

        .peringkat-option.selected {
            border-color: #c41e3a;
            background: #fef2f2;
            box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.08);
        }

        .peringkat-option-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            flex-shrink: 0;
            transition: background 0.15s, color 0.15s;
        }

        .peringkat-option.selected .peringkat-option-icon {
            background: #fee2e2;
            color: #c41e3a;
        }

        .peringkat-option-check {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: transparent;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
        }

        .peringkat-option.selected .peringkat-option-check {
            background: #c41e3a;
            border-color: #c41e3a;
            color: #fff;
        }
	</style>
@endpush

    <div class="modal fade" id="modalPilihPeringkat" tabindex="-1" aria-labelledby="modalPilihPeringkatLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-1" id="modalPilihPeringkatLabel" style="font-size:1.05rem;">
                            Pilih Kaedah Penilaian
                        </h5>
                        <p class="text-muted mb-0" style="font-size:0.8rem;">
                            Pilih kaedah penilaian sebelum melantik jawatankuasa.
                        </p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="d-flex flex-column gap-3" id="peringkatOptionGroup">

                        <label class="peringkat-option selected" data-value="1">
                            <input type="radio" name="modal_kaedah" value="1" class="d-none" checked>
                            <div class="peringkat-option-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7 5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2z"/><path d="M17 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2"/><path d="M14 14V6l-2 2"/></g></svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark" style="font-size:0.88rem;">1 Peringkat</div>
                                <div class="text-muted" style="font-size:0.75rem;">Penilaian teknikal &amp; kewangan serentak</div>
                            </div>
                            <div class="peringkat-option-check">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                        </label>

                        <label class="peringkat-option" data-value="2">
                            <input type="radio" name="modal_kaedah" value="2" class="d-none">
                            <div class="peringkat-option-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M7 5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2z"/><path d="M17 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2"/><path d="M12 8a2 2 0 1 1 4 0c0 .591-.417 1.318-.816 1.858L12 14.001h4"/></g></svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark" style="font-size:0.88rem;">2 Peringkat</div>
                                <div class="text-muted" style="font-size:0.75rem;">Penilaian teknikal dahulu, kemudian kewangan</div>
                            </div>
                            <div class="peringkat-option-check">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                        </label>

                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-form btn-form-primary" id="btnTeruskanLantik">
                        Teruskan
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>
