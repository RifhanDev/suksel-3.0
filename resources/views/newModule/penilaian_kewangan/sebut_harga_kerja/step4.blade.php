{{-- LANGKAH 4: PENYEDIAAN LAPORAN (SEBUT HARGA KERJA) --}}
{{-- Ringkasan Menyeluruh (Tanpa sub-tab Kewangan/Rumusan) --}}
<style>
    #laporan .report-hero {
        background: linear-gradient(135deg, #fff 0%, #fff7f7 100%);
        border: 1px solid #fecaca;
        border-radius: 1rem;
        box-shadow: 0 .35rem 1rem rgba(15, 23, 42, .05);
    }
    #laporan .report-section {
        border: 1px solid #e2e8f0;
        border-radius: .9rem;
        background: #fff;
        box-shadow: 0 .25rem .8rem rgba(15, 23, 42, .045);
        overflow: hidden;
    }
    #laporan .report-section-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: .85rem;
        padding: 1rem 1.1rem;
        background: linear-gradient(110deg, #fff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e2e8f0;
    }
    #laporan .report-section-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        flex: 0 0 auto;
        border-radius: .75rem;
        color: #b42318;
        background: #fee2e2;
    }
    #laporan .report-table {
        font-size: .84rem;
    }
    #laporan .report-table thead th {
        padding-top: .85rem;
        padding-bottom: .85rem;
        color: #fff !important;
        background: #1e293b !important;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .045em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    #laporan .report-table tbody td {
        padding-top: .75rem;
        padding-bottom: .75rem;
    }
    #laporan .report-field-card {
        height: 100%;
        border: 1px solid #e2e8f0;
        border-radius: .9rem;
        background: #fff;
        box-shadow: 0 .25rem .8rem rgba(15, 23, 42, .045);
    }
    #laporan .report-field-card textarea,
    #laporan .report-field-card input {
        border-color: #cbd5e1;
    }
    #laporan .report-field-card textarea:focus,
    #laporan .report-field-card input:focus {
        border-color: #f87171;
        box-shadow: 0 0 0 .2rem rgba(220, 38, 38, .1);
    }
    @media (max-width: 575.98px) {
        #laporan .report-section-header { padding: .85rem; }
        #laporan .report-hero { padding: 1rem !important; }
    }
</style>

<div class="tab-pane fade" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">

    {{-- Report Header --}}
    <div class="report-hero d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 p-3 p-md-4 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle rounded-3 text-danger flex-shrink-0" style="width: 54px; height: 54px;">
                <i class="bi bi-file-earmark-bar-graph-fill fs-3"></i>
            </div>
            <div>
                <div class="text-danger text-uppercase fw-bold mb-1" style="font-size: .68rem; letter-spacing: .08em;">Langkah 4 · Laporan Akhir</div>
                <h5 class="fw-bold mb-0 text-dark">Penyediaan Laporan Penilaian Kewangan & Kerja</h5>
                <p class="text-secondary small mb-0">Laporan bersepadu merangkumi Pematuhan Dokumentasi (Langkah 1), Kemampuan Kewangan (Langkah 2), dan Penilaian Kerja (Langkah 3).</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <span class="badge bg-white text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-semibold shadow-sm">
                <i class="bi bi-shield-check me-1"></i>Sebut Harga Kerja (Type 2)
            </span>
        </div>
    </div>

    {{-- Info Banner --}}
    <div class="d-flex align-items-start gap-3 rounded-3 px-3 py-3 mb-4" style="background:#fff7ed; border:1px solid #fed7aa; font-size:0.84rem; color:#7c2d12;">
        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white text-danger flex-shrink-0" style="width: 34px; height: 34px;"><i class="bi bi-info-lg"></i></span>
        <div>
            <div class="fw-bold mb-1">Semakan akhir laporan</div>
            Ringkasan di bawah memaparkan status semua petender merentasi tiga fasa penilaian. Semak senarai akhir, lengkapkan justifikasi dan perakuan sebelum menghantar laporan.
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- SEKSYEN A: RINGKASAN PEMATUHAN DOKUMEN     --}}
    {{-- ========================================== --}}
    <section class="report-section mb-4">
        <div class="report-section-header">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2 py-1">A</span>
                <span class="report-section-icon"><i class="bi bi-folder-check fs-5"></i></span>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Ringkasan Pematuhan Dokumentasi Mandatori <span class="text-secondary fw-normal">· Langkah 1</span></h6>
            </div>
            <span class="badge bg-white text-secondary border rounded-pill px-3 py-2 font-monospace" id="step4SecABadge">5 Petender</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 w-100 report-table">
                <thead>
                    <tr>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 6%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 12%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD</th>
                        <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 32%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PETENDER</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 18%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">STATUS DOKUMEN</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 16%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KEPUTUSAN SARINGAN</th>
                        <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">CATATAN</th>
                    </tr>
                </thead>
                <tbody id="step4TableSecABody">
                    {{-- Rendered by JS --}}
                </tbody>
            </table>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- SEKSYEN B: RINGKASAN KEMAMPUAN KEWANGAN    --}}
    {{-- ========================================== --}}
    <section class="report-section mb-4">
        <div class="report-section-header">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2 py-1">B</span>
                <span class="report-section-icon"><i class="bi bi-wallet2 fs-5"></i></span>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Ringkasan Kemampuan Kewangan & Had Modal <span class="text-secondary fw-normal">· Langkah 2</span></h6>
            </div>
            <span class="badge bg-white text-secondary border rounded-pill px-3 py-2 font-monospace" id="step4SecBBadge">0 Petender Dinilai</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 w-100 report-table">
                <thead>
                    <tr>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 6%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 12%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD</th>
                        <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 28%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PETENDER</th>
                        <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 14%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">MODAL PUSINGAN (RM)</th>
                        <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 14%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">PURATA BANK (RM)</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 14%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KEPUTUSAN</th>
                        <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">CATATAN PENILAI</th>
                    </tr>
                </thead>
                <tbody id="step4TableSecBBody">
                    {{-- Rendered by JS --}}
                </tbody>
            </table>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- SEKSYEN C: RINGKASAN PENILAIAN KERJA       --}}
    {{-- ========================================== --}}
    <section class="report-section mb-4">
        <div class="report-section-header">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2 py-1">C</span>
                <span class="report-section-icon"><i class="bi bi-tools fs-5"></i></span>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Ringkasan Penilaian Kerja, Prestasi & Kakitangan <span class="text-secondary fw-normal">· Langkah 3</span></h6>
            </div>
            <span class="badge bg-white text-secondary border rounded-pill px-3 py-2 font-monospace" id="step4SecCBadge">0 Petender Dinilai</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 w-100 report-table">
                <thead>
                    <tr>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 6%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BIL</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 12%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KOD</th>
                        <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="width: 28%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">NAMA PETENDER</th>
                        <th class="py-2.5 px-3 text-end text-uppercase text-white fw-bold" style="width: 14%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">BAKI KERJA (RM)</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 12%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KAKITANGAN</th>
                        <th class="py-2.5 px-3 text-center text-uppercase text-white fw-bold" style="width: 14%; font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">KEPUTUSAN</th>
                        <th class="py-2.5 px-3 text-start text-uppercase text-white fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; background-color: #1e293b !important;">CATATAN PENILAI</th>
                    </tr>
                </thead>
                <tbody id="step4TableSecCBody">
                    {{-- Rendered by JS --}}
                </tbody>
            </table>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- SEKSYEN D: SENARAI AKHIR PETENDER LAYAK    --}}
    {{-- ========================================== --}}
    <section class="report-section mb-4">
        <div class="card-header py-3 px-3 px-md-4 text-white d-flex flex-wrap justify-content-between align-items-center gap-2" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2 py-1">D</span>
                <h6 class="fw-bold mb-0 text-white" style="font-size: 0.95rem;">
                    <i class="bi bi-award-fill text-warning me-1"></i>Senarai Akhir Petender Yang Layak Dipertimbangkan
                </h6>
            </div>
            <span class="badge bg-success rounded-pill font-monospace px-3 py-2" id="step4SecDBadge">0 Petender Layak</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100 report-table">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-3 text-center fw-bold text-dark text-uppercase" style="width: 8%; font-size: 0.75rem;">KEDUDUKAN</th>
                            <th class="py-3 px-3 text-center fw-bold text-dark text-uppercase" style="width: 12%; font-size: 0.75rem;">KOD</th>
                            <th class="py-3 px-3 text-start fw-bold text-dark text-uppercase" style="width: 28%; font-size: 0.75rem;">NAMA PETENDER & CIDB</th>
                            <th class="py-3 px-3 text-end fw-bold text-dark text-uppercase" style="width: 16%; font-size: 0.75rem;">HARGA TAWARAN (RM)</th>
                            <th class="py-3 px-3 text-center fw-bold text-dark text-uppercase" style="width: 14%; font-size: 0.75rem;">TEMPOH SIAP</th>
                            <th class="py-3 px-3 text-center fw-bold text-dark text-uppercase" style="width: 12%; font-size: 0.75rem;">STATUS</th>
                            <th class="py-3 px-3 text-start fw-bold text-dark text-uppercase" style="font-size: 0.75rem;">SYOR PENILAI</th>
                        </tr>
                    </thead>
                    <tbody id="step4TableSecDBody">
                        {{-- Rendered by JS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- ULASAN, PERAKUAN & SYOR JAWATANKUASA       --}}
    {{-- ========================================== --}}
    <div class="row g-4 mb-4">
        {{-- Justifikasi & Syor --}}
        <div class="col-12 col-lg-7">
            <div class="report-field-card p-3 p-md-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="report-section-icon"><i class="bi bi-pencil-square fs-5"></i></span>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Justifikasi & Syor Jawatankuasa Penilaian</h6>
                        <p class="text-secondary small mb-0">Lengkapkan ulasan yang akan disertakan dalam laporan akhir.</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold mb-2">
                        Ulasan Keseluruhan Kewangan & Keupayaan Kerja:
                    </label>
                    <textarea class="form-control" id="laporanJustifikasiText" rows="5" placeholder="Nyatakan ulasan terperinci mengenai kelayakan petender dan syor perolehan...">Dengan ini, JPT mengesyorkan penyebut harga yang melepasi untuk melaksanakan projek ini untuk dibawa ke mesyuarat Jawatankuasa Sebut Harga berdasarkan justifikasi yang ditetapkan.</textarea>
                </div>
                <div>
                    <label class="form-label small text-secondary fw-semibold mb-2">
                        Syarat Tambahan / Arahan Khas (Jika Ada):
                    </label>
                    <input type="text" class="form-control" id="laporanSyaratKhasInput" value="" placeholder="Syarat-syarat khas...">
                </div>
            </div>
        </div>

        {{-- Perakuan Penilai --}}
        <div class="col-12 col-lg-5">
            <div class="report-field-card p-3 p-md-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="report-section-icon"><i class="bi bi-person-check-fill fs-5"></i></span>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Perakuan Pegawai Penilai Kewangan</h6>
                        <p class="text-secondary small mb-0">Sahkan semakan sebelum menghantar laporan.</p>
                    </div>
                </div>
                <div class="p-3 rounded-3 bg-light border mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="perakuanPegawaiPenilai" name="perakuan_pegawai_penilai">
                        <label class="form-check-label extra-small fw-semibold text-dark" for="perakuanPegawaiPenilai" style="line-height: 1.4;">
                            Saya dengan ini memperakui bahawa penilaian ini telah dilaksanakan secara bebas, telus dan saksama mengikut Arahan Perbendaharaan dan Pekeliling Perolehan Kerajaan Negeri Selangor yang berkuatkuasa.
                        </label>
                    </div>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Pegawai Penilai:</span>
                        <span class="fw-semibold text-dark">AHMAD HAZMI BIN ISMAIL</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Jawatan:</span>
                        <span class="fw-semibold text-dark">Pegawai Kewangan W41</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Tarikh Penilaian:</span>
                        <span class="fw-semibold font-monospace text-dark">{{ date('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 p-3 p-md-4 mt-4 rounded-3 border bg-white shadow-sm">
        <button type="button" class="btn btn-sebelumnya d-inline-flex align-items-center gap-1" id="btnPrevStep4">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Langkah 3 (Penilaian Kerja)</span>
        </button>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" class="btn btn-outline-secondary px-4 fw-semibold d-inline-flex align-items-center gap-2" id="btnCetakDrafLaporan">
                <i class="bi bi-printer"></i>
                <span>Laporan</span>
            </button>
            <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5" id="btnSimpanDrafLaporan">
                <i class="bi bi-floppy"></i>
                <span>Simpan Draf</span>
            </button>
            <button type="button" class="btn btn-success d-inline-flex align-items-center gap-1.5 px-3 py-2 fw-bold" id="btnHantarPenilaianAkhir">
                <i class="bi bi-send-check-fill"></i>
                <span>Hantar Penilaian Kewangan</span>
            </button>
        </div>
    </div>

</div>
