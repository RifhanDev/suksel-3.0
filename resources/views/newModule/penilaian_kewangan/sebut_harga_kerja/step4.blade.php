{{-- LANGKAH 4: PENYEDIAAN LAPORAN (SEBUT HARGA KERJA) --}}
{{-- Ringkasan Menyeluruh (Tanpa sub-tab Kewangan/Rumusan) --}}
<div class="tab-pane fade" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">

    {{-- Report Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center">
            <div class="bg-danger-subtle p-2.5 rounded-3 me-3 text-danger">
                <i class="bi bi-file-earmark-bar-graph-fill fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Penyediaan Laporan Penilaian Kewangan & Kerja</h5>
                <p class="text-secondary small mb-0">Laporan bersepadu merangkumi Pematuhan Dokumentasi (Langkah 1), Kemampuan Kewangan (Langkah 2), dan Penilaian Kerja (Langkah 3).</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-2 rounded-2 fw-semibold">
                <i class="bi bi-shield-check me-1"></i>Sebut Harga Kerja (Type 2)
            </span>
        </div>
    </div>

    {{-- Info Banner --}}
    <div class="rounded-3 px-3 py-2.5 d-inline-flex align-items-center gap-2 mb-4 w-100" style="background:#f8fafc; border:1px solid #e2e8f0; font-size:0.825rem; color:#334155;">
        <i class="bi bi-info-circle-fill text-danger fs-5 flex-shrink-0"></i>
        <div>
            Ringkasan di bawah memaparkan status kemajuan bagi semua petender merentasi ketiga-tiga fasa penilaian secara automatik. Sila semak perakuan akhir dan lengkapkan ulasan syor jawatankuasa sebelum menghantar laporan penilaian.
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- SEKSYEN A: RINGKASAN PEMATUHAN DOKUMEN     --}}
    {{-- ========================================== --}}
    <div class="card border border-light-subtle rounded-3 shadow-none mb-4 overflow-hidden">
        <div class="card-header bg-light py-2.5 px-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2">A</span>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Ringkasan Pematuhan Dokumentasi Mandatori (Langkah 1)</h6>
            </div>
            <span class="badge bg-white text-secondary border font-monospace" id="step4SecABadge">5 Petender</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                <thead style="background-color: #1e293b;">
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
    </div>

    {{-- ========================================== --}}
    {{-- SEKSYEN B: RINGKASAN KEMAMPUAN KEWANGAN    --}}
    {{-- ========================================== --}}
    <div class="card border border-light-subtle rounded-3 shadow-none mb-4 overflow-hidden">
        <div class="card-header bg-light py-2.5 px-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2">B</span>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Ringkasan Penilaian Kemampuan Kewangan & Had Modal (Langkah 2)</h6>
            </div>
            <span class="badge bg-white text-secondary border font-monospace" id="step4SecBBadge">0 Petender Dinilai</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                <thead style="background-color: #1e293b;">
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
    </div>

    {{-- ========================================== --}}
    {{-- SEKSYEN C: RINGKASAN PENILAIAN KERJA       --}}
    {{-- ========================================== --}}
    <div class="card border border-light-subtle rounded-3 shadow-none mb-4 overflow-hidden">
        <div class="card-header bg-light py-2.5 px-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2">C</span>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Ringkasan Penilaian Kerja, Prestasi & Kakitangan (Langkah 3)</h6>
            </div>
            <span class="badge bg-white text-secondary border font-monospace" id="step4SecCBadge">0 Petender Dinilai</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                <thead style="background-color: #1e293b;">
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
    </div>

    {{-- ========================================== --}}
    {{-- SEKSYEN D: SENARAI AKHIR PETENDER LAYAK    --}}
    {{-- ========================================== --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden" style="border: 1px solid #cbd5e1 !important;">
        <div class="card-header py-3 px-3.5 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white rounded-pill px-2">D</span>
                <h6 class="fw-bold mb-0 text-white" style="font-size: 0.95rem;">
                    <i class="bi bi-award-fill text-warning me-1"></i>Senarai Akhir Petender Yang Layak Dipertimbangkan
                </h6>
            </div>
            <span class="badge bg-success font-monospace px-2.5 py-1.5" id="step4SecDBadge">0 Petender Layak</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100">
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
    </div>

    {{-- ========================================== --}}
    {{-- ULASAN, PERAKUAN & SYOR JAWATANKUASA       --}}
    {{-- ========================================== --}}
    <div class="row g-4 mb-4">
        {{-- Justifikasi & Syor --}}
        <div class="col-12 col-lg-7">
            <div class="card border border-light-subtle rounded-3 shadow-none p-3 h-100 bg-white">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="bi bi-pencil-square text-danger me-2"></i>Justifikasi & Syor Jawatankuasa Penilaian
                </h6>
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold mb-1">
                        Ulasan Keseluruhan Kewangan & Keupayaan Kerja:
                    </label>
                    <textarea class="form-control form-control-sm" id="laporanJustifikasiText" rows="4" placeholder="Nyatakan ulasan terperinci mengenai kelayakan petender dan syor perolehan...">Berdasarkan penilaian pematuhan dokumentasi, kedudukan modal pusingan, dan komitmen kerja semasa, petender yang disenaraikan telah membuktikan keupayaan kewangan serta teknikal yang mencukupi untuk melaksanakan skop kerja projek ini mengikut jadual yang ditetapkan tanpa risiko kegagalan.</textarea>
                </div>
                <div>
                    <label class="form-label small text-secondary fw-semibold mb-1">
                        Syarat Tambahan / Arahan Khas (Jika Ada):
                    </label>
                    <input type="text" class="form-control form-control-sm" id="laporanSyaratKhasInput" value="Petender disyorkan dikehendaki mengemukakan Bon Pelaksanaan dalam tempoh 14 hari selepas penerimaan Surat Setuju Terima (SST)." placeholder="Syarat-syarat khas...">
                </div>
            </div>
        </div>

        {{-- Perakuan Penilai --}}
        <div class="col-12 col-lg-5">
            <div class="card border border-light-subtle rounded-3 shadow-none p-3 h-100 bg-white">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="bi bi-person-check-fill text-danger me-2"></i>Perakuan Pegawai Penilai Kewangan
                </h6>
                <div class="p-2.5 rounded-2 bg-light border mb-3">
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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 pt-3 border-top">
        <button type="button" class="btn btn-sebelumnya d-inline-flex align-items-center gap-1" id="btnPrevStep4">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Langkah 3 (Penilaian Kerja)</span>
        </button>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-danger d-inline-flex align-items-center gap-1.5" id="btnCetakDrafLaporan">
                <i class="bi bi-printer"></i>
                <span>Cetak Draf Laporan (PDF)</span>
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
