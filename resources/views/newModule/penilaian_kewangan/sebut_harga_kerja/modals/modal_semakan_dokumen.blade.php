{{-- MODAL: SEMAKAN PEMATUHAN DOKUMEN KEWANGAN (LANGKAH 1) --}}
<div class="modal fade" id="modalSemakanDokumenKerja" tabindex="-1" aria-labelledby="modalLabelSemakanDokumen" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header px-4 pt-4 pb-3 border-0 bg-light">
                <div class="d-flex align-items-center flex-grow-1 me-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 44px; height: 44px; background: #fee2e2; color: #dc2626;">
                        <i class="bi bi-file-earmark-check fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Langkah 1: Dokumentasi</span>
                            <span id="modalDocMekanisma" class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Borang Atas Talian</span>
                        </div>
                        <h5 id="modalDocTitle" class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.05rem;">Nama Dokumen Semakan</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Info notice --}}
                <div class="alert alert-light border rounded-3 d-flex align-items-center gap-2 py-2.5 px-3 mb-3 text-secondary" style="font-size: 0.8rem; background-color: #f8fafc;">
                    <i class="bi bi-info-circle-fill text-danger fs-6 flex-shrink-0"></i>
                    <div>
                        Sila semak dokumen yang dikemukakan oleh setiap petender. Tandakan <strong>Sempurna</strong> jika mematuhi syarat asas, atau <strong>Tidak Sempurna</strong> beserta catatan jika gagal.
                    </div>
                </div>

                {{-- Table of Vendors for this checklist item --}}
                <div class="table-responsive rounded-3 border bg-white shadow-sm">
                    <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                        <thead style="background-color: #1e293b; color: #ffffff;">
                            <tr>
                                <th class="text-center text-uppercase fw-bold py-2.5" style="width: 100px; font-size: 0.72rem; letter-spacing: 0.05em; background-color: #1e293b !important; color: #fff !important;">Kod</th>
                                <th class="text-start text-uppercase fw-bold py-2.5" style="width: 250px; font-size: 0.72rem; letter-spacing: 0.05em; background-color: #1e293b !important; color: #fff !important;">Nama Petender</th>
                                <th class="text-start text-uppercase fw-bold py-2.5" style="font-size: 0.72rem; letter-spacing: 0.05em; background-color: #1e293b !important; color: #fff !important;">Dokumen / Penyerahan</th>
                                <th class="text-center text-uppercase fw-bold py-2.5" style="width: 130px; font-size: 0.72rem; letter-spacing: 0.05em; background-color: #1e293b !important; color: #fff !important;">Penyerahan</th>
                                <th class="text-center text-uppercase fw-bold py-2.5" style="width: 180px; font-size: 0.72rem; letter-spacing: 0.05em; background-color: #1e293b !important; color: #fff !important;">Keputusan Pematuhan</th>
                                <th class="text-center text-uppercase fw-bold py-2.5" style="width: 220px; font-size: 0.72rem; letter-spacing: 0.05em; background-color: #1e293b !important; color: #fff !important;">Catatan / Sebab</th>
                            </tr>
                        </thead>
                        <tbody id="modalSemakanDokumenBody">
                            {{-- Dynamically rendered via JS based on checklist item --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-4 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-sm btn-danger px-4 fw-bold shadow-sm" id="btnSimpanSemakanDokumen">
                    <i class="bi bi-check2-circle me-1"></i>Simpan Penilaian
                </button>
            </div>
        </div>
    </div>
</div>
