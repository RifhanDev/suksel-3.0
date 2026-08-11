@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/components/tender-show.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/custom-table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/modal-confirm.css') }}" rel="stylesheet">

    <style>
        /* Row delete — same treatment as the Justifikasi rows in Penilaian Teknikal. */
        .btn-hapus-baris {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #f5c2c7;
            border-radius: 8px;
            background: #fdecef;
            color: #dc2626;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-hapus-baris:hover {
            border-color: #dc2626;
            background: #dc2626;
            color: #fff;
        }
    </style>
@endsection

@section('content')

    <!-- Tender info card -->
    <div class="tender-header-card mb-4">
        <div class="tender-page-header">
            <div class="tender-ref-label">
                <span class="tender-type-label">{{ $jenisPerolehanLabel ?? 'Sebut Harga' }}</span>
                <span class="tender-ref-sep">&middot;</span>
                <span class="tender-ref-no">{{ $noTender }}</span>
            </div>
            <h2 class="tender-title-main mb-3">{{ $tender->name ?: '-' }}</h2>

            <div class="row g-3 pb-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">PTJ</span>
                        <span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $tender->tenderer?->name ?: '-' }}</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Tempoh Sah Laku Tawaran (Hari)</span>
                        <span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $tempohSahLaku['tempoh'] ?: '-' }}</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Sah Laku Tawaran Tamat</span>
                        <span class="fw-semibold text-dark" style="font-size:0.88rem;">{{ $tempohSahLaku['tamat'] ?: '-' }}</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted fw-semibold text-uppercase" style="font-size:0.67rem; letter-spacing:0.5px;">Status</span>
                        <div>
                            <span class="badge-status badge-status-warning">Dalam Proses</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Senarai Cadangan Pembekal -->
    <div class="content-card p-0 mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <line x1="7" y1="8" x2="17" y2="8"></line>
                        <line x1="7" y1="12" x2="17" y2="12"></line>
                        <line x1="7" y1="16" x2="17" y2="16"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0">Senarai Cadangan Pembekal</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Kemaskini maklumat cadangan pembekal.</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">

            {{-- Hanya berkaitan untuk bekalan/perkhidmatan — kerja tidak dipilih ikut item. --}}
            @if ($tunjukPemilihanItem ?? true)
                <div class="row g-3 mb-4">
                    <div class="col-12 col-lg-6">
                        <label for="pemilihan_berdasarkan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Pemilihan Berdasarkan</label>
                        <input type="text" id="pemilihan_berdasarkan" class="form-control bg-light" value="Item" readonly>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="kaedah_memuktamadkan_pembekal" class="form-label small fw-bold text-secondary text-uppercase mb-1">Kaedah Memuktamadkan Pembekal</label>
                        <input type="text" id="kaedah_memuktamadkan_pembekal" class="form-control bg-light" value="Pemilihan Terus" readonly>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table id="dt_pembekal" class="table table-bordered table-slate align-middle w-100 mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Pembekal</th>
                            <th width="260px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_pembekal">
                        <tr>
                            <td colspan="2" class="text-center text-muted">Memuatkan...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Senarai Surat Niat / Surat Setuju Terima -->
    <div class="content-card p-0 mb-4">
        <div class="content-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="content-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                </div>
                <div>
                    <h3 class="content-card-title mb-0">Senarai Surat Niat / Surat Setuju Terima</h3>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">Surat yang telah dijana bagi pembekal di atas.</p>
                </div>
            </div>
        </div>

        <div class="content-card-body p-4">
            <div class="table-responsive">
                <table id="dt_loa" class="table table-bordered table-slate align-middle w-100 mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No. LOI/LOA</th>
                            <th width="140px">Jenis</th>
                            <th width="150px">Status</th>
                            <th width="260px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_surat">
                        <tr>
                            <td colspan="4" class="text-center text-muted">Memuatkan...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex justify-content-end gap-2 mb-4">
        <button type="button" class="btn-form btn-form-success" id="btn-hantar">Hantar</button>
    </div>

@endsection

@push('modals')
    <!-- Penyediaan Surat Setuju Terima -->
    <div class="modal fade" id="modalSST" tabindex="-1" aria-labelledby="modalSSTLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="content-card-icon" style="width: 42px; height: 42px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalSSTLabel" style="font-size: 1.05rem; letter-spacing: -0.2px;">Penyediaan Surat Setuju Terima (SST)</h5>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Lengkapkan maklumat mengikut tab di bawah.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">

                    <ul class="nav segmented-tabs w-100 mb-4" id="sstTab" role="tablist">
                        <li class="nav-item flex-fill">
                            <a class="nav-link active w-100" data-bs-toggle="tab" href="#pembekal-pane" role="tab" aria-selected="true">Perincian Pembekal</a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link w-100" data-bs-toggle="tab" href="#bon-pane" role="tab" aria-selected="false">Bon Pelaksanaan</a>
                        </li>
                        <li class="nav-item flex-fill">
                            <a class="nav-link w-100" data-bs-toggle="tab" href="#sst-pane" role="tab" aria-selected="false">Surat Setuju Terima &amp; Lampiran</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- Tab 1 --}}
                        <div class="tab-pane fade show active" id="pembekal-pane" role="tabpanel">

                            {{-- Seksyen: Perincian Pembekal --}}
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="content-card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem; letter-spacing: -0.2px;">Perincian Pembekal</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.76rem;">Maklumat syarikat dan pegawai untuk dihubungi.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="pembekal_nama" class="form-label small fw-bold text-secondary text-uppercase mb-1">Pembekal</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_nama" name="pembekal_nama" readonly>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="pembekal_mof" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. MOF</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_mof" name="pembekal_mof" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="pembekal_cbp" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Pendaftaran Cukai</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_cbp" name="pembekal_cbp" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="pembekal_cbp_date" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Kuatkuasa Pendaftaran Cukai</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_cbp_date" name="pembekal_cbp_date" value="-" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="pembekal_ep_date" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Pengisytiharan di eP</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_ep_date" name="pembekal_ep_date" value="-" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="pembekal_alamat" class="form-label small fw-bold text-secondary text-uppercase mb-1">Alamat</label>
                                    <textarea class="form-control bg-light" id="pembekal_alamat" name="pembekal_alamat" rows="2" readonly></textarea>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <label for="pembekal_pic" class="form-label small fw-bold text-secondary text-uppercase mb-1">Pegawai Untuk Dihubungi</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_pic" name="pembekal_pic" readonly>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <label for="pembekal_tel" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Telefon</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_tel" name="pembekal_tel" readonly>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <label for="pembekal_fax" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Faks</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_fax" name="pembekal_fax" readonly>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <label for="pembekal_hp" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Telefon Bimbit</label>
                                    <input type="text" class="form-control bg-light" id="pembekal_hp" name="pembekal_hp" readonly>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="pembekal_email" class="form-label small fw-bold text-secondary text-uppercase mb-1">Email</label>
                                    <input type="email" class="form-control bg-light" id="pembekal_email" name="pembekal_email" readonly>
                                </div>

                                {{-- Cawangan — belum digunakan, dikekalkan untuk keperluan akan datang.
                                <div class="col-12 col-lg-3">
                                    <label for="pembekal_cawangan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Cawangan</label>
                                    <select class="form-select" id="pembekal_cawangan" name="pembekal_cawangan">
                                        <option value="" selected>Sila Pilih</option>
                                        <option value="hq">HQ</option>
                                        <option value="cawangan">Cawangan</option>
                                    </select>
                                </div>
                                --}}
                            </div>

                            {{-- Seksyen: Maklumat Surat Setuju Terima --}}
                            <div class="d-flex align-items-center gap-3 mb-3 mt-4 pt-4 border-top">
                                <div class="content-card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem; letter-spacing: -0.2px;">Maklumat Surat Setuju Terima</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.76rem;">Rujukan dokumen, maklumat perolehan dan nilai tawaran.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="sst_no_dokumen" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Dokumen</label>
                                    <input type="text" class="form-control bg-light" id="sst_no_dokumen" name="sst_no_dokumen" readonly>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="sst_rujukan_fail" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Rujukan Fail</label>
                                    <input type="text" class="form-control bg-light" id="sst_rujukan_fail" name="sst_rujukan_fail" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="sst_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Surat Setuju Terima <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="sst_tajuk" name="sst_tajuk" rows="2"></textarea>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_kaedah_perolehan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Kaedah Perolehan</label>
                                    <input type="text" class="form-control bg-light" id="sst_kaedah_perolehan" name="sst_kaedah_perolehan" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_jenis_tender" class="form-label small fw-bold text-secondary text-uppercase mb-1">Jenis Sebut Harga / Tender</label>
                                    <input type="text" class="form-control bg-light" id="sst_jenis_tender" name="sst_jenis_tender" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_kategori_perolehan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Kategori Jenis Perolehan</label>
                                    <input type="text" class="form-control bg-light" id="sst_kategori_perolehan" name="sst_kategori_perolehan" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_harga_tawaran" class="form-label small fw-bold text-secondary text-uppercase mb-1">Harga Tawaran (RM)</label>
                                    <input type="text" class="form-control text-end bg-light" id="sst_harga_tawaran" name="sst_harga_tawaran" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_cukai" class="form-label small fw-bold text-secondary text-uppercase mb-1">Cukai Jualan / Cukai Perkhidmatan (%) <span class="text-danger">*</span></label>
                                    <select class="form-select" id="sst_cukai" name="sst_cukai">
                                        <option value="0" selected>0%</option>
                                        <option value="6">6%</option>
                                        <option value="8">8%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_total" class="form-label small fw-bold text-secondary text-uppercase mb-1">Amaun Keseluruhan Kontrak (RM)</label>
                                    <input type="text" class="form-control text-end fw-bold bg-light" id="sst_total" name="sst_total" readonly>
                                </div>
                            </div>

                            {{-- Semua pilihan Ya/Tidak dikumpul dalam satu baris --}}
                            <div class="row g-3 mt-2">
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Insurans / Jaminan <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-4 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="insurans" id="insurans_ya" value="ya">
                                            <label class="form-check-label small" for="insurans_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="insurans" id="insurans_tidak" value="tidak" checked>
                                            <label class="form-check-label small" for="insurans_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Bon Pelaksanaan</label>
                                    <div class="d-flex gap-4 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="bon" id="bon_ya" value="ya" disabled>
                                            <label class="form-check-label small" for="bon_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="bon" id="bon_tidak" value="tidak" disabled checked>
                                            <label class="form-check-label small" for="bon_tidak">Tidak</label>
                                        </div>
                                    </div>
                                    {{-- Radio disabled tidak dihantar dengan borang — hidden ini yang bawa nilainya. --}}
                                    <input type="hidden" name="bon" id="bon_hidden" value="tidak">
                                    <div class="text-muted mt-1" style="font-size: 0.7rem;">Ditetapkan automatik mengikut amaun kontrak.</div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Perlu Semakan Atas Talian <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-4 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="semakan" id="semakan_ya" value="ya">
                                            <label class="form-check-label small" for="semakan_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="semakan" id="semakan_tidak" value="tidak" checked>
                                            <label class="form-check-label small" for="semakan_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">PROTEGE-RTW <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-4 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="protege" id="protege_ya" value="ya">
                                            <label class="form-check-label small" for="protege_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="protege" id="protege_tidak" value="tidak" checked>
                                            <label class="form-check-label small" for="protege_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Seksyen: Perincian Kontrak --}}
                            <div class="d-flex align-items-center gap-3 mb-3 mt-4 pt-4 border-top">
                                <div class="content-card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                        <rect x="8" y="2" width="8" height="4" rx="1"></rect>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem; letter-spacing: -0.2px;">Perincian Kontrak</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.76rem;">Tempoh, pentadbir dan tarikh kuat kuasa kontrak.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="kontrak_jenis" class="form-label small fw-bold text-secondary text-uppercase mb-1">Jenis Kontrak</label>
                                    <input type="text" class="form-control bg-light" id="kontrak_jenis" name="kontrak_jenis" readonly>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="kontrak_agensi" class="form-label small fw-bold text-secondary text-uppercase mb-1">Agensi</label>
                                    <input type="text" class="form-control bg-light" id="kontrak_agensi" name="kontrak_agensi" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="kontrak_tempoh" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tempoh Kontrak (Bulan)</label>
                                    <input type="number" class="form-control bg-light" id="kontrak_tempoh" name="kontrak_tempoh" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="kontrak_mula" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Kuatkuasa Kontrak <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg datepicker" id="kontrak_mula" name="kontrak_mula" placeholder="dd/mm/yyyy" autocomplete="off">
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="kontrak_tamat" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Tamat Kontrak</label>
                                    <input type="text" class="form-control bg-light" id="kontrak_tamat" name="kontrak_tamat" placeholder="dd/mm/yyyy" readonly>
                                </div>

                                {{-- Pentadbir Kontrak — KIV, belum digunakan buat masa ini.
                                <div class="col-12 col-lg-6">
                                    <label for="kontrak_pentadbir" class="form-label small fw-bold text-secondary text-uppercase mb-1">Pentadbir Kontrak <span class="text-danger">*</span></label>
                                    <select class="form-select" id="kontrak_pentadbir" name="kontrak_pentadbir">
                                        <option value="ptj248" selected>Pentadbir Kontrak Kementerian Ptj248</option>
                                        <option value="lain">Lain-lain</option>
                                    </select>
                                </div>
                                --}}

                                <div class="col-12 col-lg-4">
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Perjanjian Diperlukan <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-4 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kontrak_perjanjian" id="perjanjian_ya" value="ya" checked>
                                            <label class="form-check-label small" for="perjanjian_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kontrak_perjanjian" id="perjanjian_tidak" value="tidak">
                                            <label class="form-check-label small" for="perjanjian_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Tab 2 --}}
                        <div class="tab-pane fade" id="bon-pane" role="tabpanel">

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="content-card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem; letter-spacing: -0.2px;">Bon Pelaksanaan</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.76rem;">Pengiraan nilai bon berdasarkan nilai kontrak.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-lg-3">
                                    <label for="bon_tempoh" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tempoh Kontrak (Bulan)</label>
                                    <input type="text" class="form-control bg-light" id="bon_tempoh" name="bon_tempoh" readonly>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <label for="bon_nilai_kontrak" class="form-label small fw-bold text-secondary text-uppercase mb-1">Nilai Kontrak (RM)</label>
                                    <input type="text" class="form-control text-end bg-light" id="bon_nilai_kontrak" name="bon_nilai_kontrak" readonly>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <label for="bon_peratus" class="form-label small fw-bold text-secondary text-uppercase mb-1">Peratusan Bon (%)</label>
                                    <input type="text" class="form-control text-end bg-light" id="bon_peratus" name="bon_peratus" readonly>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <label for="bon_nilai" class="form-label small fw-bold text-secondary text-uppercase mb-1">Nilai Bon (RM)</label>
                                    <input type="text" class="form-control text-end fw-bold bg-light" id="bon_nilai" name="bon_nilai" readonly>
                                    <div class="text-muted mt-1" style="font-size: 0.7rem;" id="bon_nilai_perkataan"></div>
                                </div>
                            </div>

                        </div>

                        {{-- Tab 3 --}}
                        <div class="tab-pane fade" id="sst-pane" role="tabpanel">

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="content-card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                                        <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                                        <line x1="2" y1="2" x2="9" y2="9"></line>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem; letter-spacing: -0.2px;">Penandatangan Surat Setuju Terima</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.76rem;">Pegawai yang menandatangani surat bagi pihak Kerajaan.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-lg-4">
                                    <label for="sst_tarikh_kerajaan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh SST Ditandatangani Oleh Kerajaan</label>
                                    <input type="text" class="form-control bg-light" id="sst_tarikh_kerajaan" name="sst_tarikh_kerajaan" placeholder="dd/mm/yyyy" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_penandatangan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Penandatangan Surat Setuju Terima</label>
                                    <input type="text" class="form-control bg-light" id="sst_penandatangan" name="sst_penandatangan" value="-" readonly>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <label for="sst_jawatan_penandatangan" class="form-label small fw-bold text-secondary text-uppercase mb-1">Jawatan Penandatangan Surat Setuju Terima</label>
                                    <input type="text" class="form-control bg-light" id="sst_jawatan_penandatangan" name="sst_jawatan_penandatangan" value="-" readonly>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-3 mt-4 pt-4 border-top">
                                <div class="content-card-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem; letter-spacing: -0.2px;">Surat Setuju Terima dan Lampiran</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.76rem;">Dokumen yang dijana bersama surat setuju terima.</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mb-3">
                                <button type="button" class="btn-form btn-form-secondary" id="btn-muat-turun-semua">Muat Turun Semua</button>
                                <button type="button" class="btn-form btn-form-success" id="btn-tambah-dokumen">Tambah</button>
                            </div>

                            <div class="table-responsive">
                                <table id="tbl_dokumen_sst" class="table table-bordered table-slate align-middle w-100 mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th width="60px">No.</th>
                                            <th>Kandungan</th>
                                            <th width="240px">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-jenis="sistem">
                                            <td class="text-center">1</td>
                                            <td>Surat Setuju Terima (Termasuk Lampiran A)</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" class="text-decoration-none small fw-semibold" id="link-surat-setuju-terima" target="_blank">Muat Turun</a>
                                            </td>
                                        </tr>
                                        <tr data-jenis="sistem">
                                            <td class="text-center">2</td>
                                            <td>Lampiran B - Surat Akuan Pembidaan Berjaya</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" class="text-decoration-none small fw-semibold" id="link-surat-akuan-pembida-berjaya" target="_blank">Muat Turun</a>
                                            </td>
                                        </tr>
                                        <tr data-jenis="sistem">
                                            <td class="text-center">3</td>
                                            <td>Lampiran C - Surat Akuan Sumpah Syarikat</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" class="text-decoration-none small fw-semibold" id="link-surat-akuan-sumpah-syarikat" target="_blank">Muat Turun</a>
                                            </td>
                                        </tr>
                                        {{-- Perakuan Penerimaan SST dan Maklumat Insurans — disembunyikan
                                             sehingga dokumen tersedia. --}}
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-form btn-form-secondary" id="btn-sst-kembali">Kembali</button>
                    <button type="button" class="btn-form btn-form-primary" id="btn-sst-seterusnya">Simpan &amp; Seterusnya</button>
                    <button type="button" class="btn-form btn-form-primary btn-simpan-sst" id="btn-sst-simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

@endpush

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            const SST_TENDER = '{{ $tender->uuid }}';
            const SST_CSRF = '{{ csrf_token() }}';
            const SST_TEMPOH_KONTRAK = @json($tempohKontrak);
            const URL_PEMBEKAL = '{{ route('penyediaanSST.pembekal', ['tender' => $tender->id]) }}';
            const URL_SURAT = '{{ route('penyediaanSST.senaraiSurat', ['tender' => $tender->id]) }}';
            const URL_SST = '{{ route('penyediaanSST.sst', ['tender' => $tender->id, 'vendorId' => 'VENDOR_ID']) }}';
            const URL_SIMPAN = '{{ route('penyediaanSST.simpan') }}';
            const URL_JANA = '{{ route('penyediaanSST.jana') }}';
            const URL_HANTAR = '{{ route('penyediaanSST.hantar') }}';
            const URL_MUAT_NAIK = '{{ route('penyediaanSST.muatNaikDokumen', ['tender' => $tender->id]) }}';
            const URL_SURAT_SETUJU_TERIMA = '{{ route('penyediaanSST.suratSetujuTerima', ['tender' => $tender->id, 'vendorId' => 'VENDOR_ID']) }}';
            const URL_SURAT_AKUAN_PEMBIDA_BERJAYA = '{{ route('penyediaanSST.suratAkuanPembidaBerjaya', ['tender' => $tender->id, 'vendorId' => 'VENDOR_ID']) }}';
            const URL_SURAT_AKUAN_SUMPAH_SYARIKAT = '{{ route('penyediaanSST.suratAkuanSumpahSyarikat', ['tender' => $tender->id, 'vendorId' => 'VENDOR_ID']) }}';

            // Download buttons shown against each generated letter.
            const DOKUMEN_SURAT = [
                { label: 'SST', url: URL_SURAT_SETUJU_TERIMA },
                { label: 'Lampiran B', url: URL_SURAT_AKUAN_PEMBIDA_BERJAYA },
                { label: 'Lampiran C', url: URL_SURAT_AKUAN_SUMPAH_SYARIKAT },
            ];
            const URL_SENARAI = '{{ route('indexPenyediaanSST') }}';

            let vendorAktif = null;

            // Same helpers as teknikal.blade.php — they are page-local there, not global.
            function setButtonBusy(button, busyLabel) {
                if (! button || button.disabled) return;
                button.dataset.originalText = button.textContent;
                button.disabled = true;
                button.textContent = busyLabel;
            }

            function clearButtonBusy(button) {
                if (! button) return;
                button.disabled = false;
                if (button.dataset.originalText) {
                    button.textContent = button.dataset.originalText;
                    delete button.dataset.originalText;
                }
            }

            function selDom(tag, text, className) {
                const el = document.createElement(tag);
                el.textContent = text ?? '-';
                if (className) el.className = className;
                return el;
            }

            function muatPembekal() {
                $.get(URL_PEMBEKAL).done(function (res) {
                    const tbody = document.getElementById('tbody_pembekal');
                    const rows = res.rows || [];
                    tbody.innerHTML = '';

                    if (! rows.length) {
                        tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tiada pembekal berjaya direkodkan.</td></tr>';
                        return;
                    }

                    rows.forEach(function (row) {
                        const tr = document.createElement('tr');
                        tr.append(selDom('td', row.nama));

                        const td = document.createElement('td');
                        td.className = 'text-center';

                        // Once the letter is generated the row moves to Senarai Surat, so
                        // there is nothing left to do here.
                        if (row.status === 'submitted') {
                            td.appendChild(selDom('span', 'Telah dijana', 'badge-status badge-status-success'));
                        } else {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'btn-form btn-form-primary btn-sedia-sst';
                            btn.dataset.vendorId = row.vendor_id;
                            btn.textContent = 'Sedia Surat Setuju Terima';
                            td.appendChild(btn);
                        }

                        tr.appendChild(td);
                        tbody.appendChild(tr);
                    });
                }).fail(function () {
                    document.getElementById('tbody_pembekal').innerHTML =
                        '<tr><td colspan="2" class="text-center text-muted">Ralat memuatkan senarai pembekal.</td></tr>';
                });
            }

            function muatSurat() {
                $.get(URL_SURAT).done(function (res) {
                    const tbody = document.getElementById('tbody_surat');
                    const rows = res.rows || [];
                    tbody.innerHTML = '';

                    if (! rows.length) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tiada surat dijana lagi.</td></tr>';
                        return;
                    }

                    rows.forEach(function (row) {
                        const tr = document.createElement('tr');
                        tr.append(selDom('td', row.document_no), selDom('td', row.jenis));

                        const tdStatus = document.createElement('td');
                        tdStatus.className = 'text-center';
                        tdStatus.appendChild(row.status === 'submitted'
                            ? selDom('span', 'Dijana', 'badge-status badge-status-success')
                            : selDom('span', 'Draf', 'badge-status badge-status-warning'));
                        tr.appendChild(tdStatus);

                        // One download per generated document, all for this vendor.
                        const tdAksi = document.createElement('td');
                        tdAksi.className = 'text-center';

                        if (row.status === 'submitted') {
                            const kumpulan = document.createElement('div');
                            kumpulan.className = 'd-flex justify-content-center gap-2 flex-wrap';

                            DOKUMEN_SURAT.forEach(function (dokumen) {
                                const a = document.createElement('a');
                                a.href = dokumen.url.replace('VENDOR_ID', row.vendor_id);
                                a.target = '_blank';
                                a.className = 'btn-form btn-form-secondary';
                                a.textContent = dokumen.label;
                                kumpulan.appendChild(a);
                            });

                            tdAksi.appendChild(kumpulan);
                        } else {
                            tdAksi.textContent = '-';
                        }

                        tr.appendChild(tdAksi);
                        tbody.appendChild(tr);
                    });
                }).fail(function () {
                    document.getElementById('tbody_surat').innerHTML =
                        '<tr><td colspan="4" class="text-center text-muted">Ralat memuatkan senarai surat.</td></tr>';
                });
            }

            // Blank the form first so a previous vendor's values never linger.
            function kosongkanBorang() {
                $('#modalSST input[type="text"], #modalSST input[type="email"], #modalSST textarea').val('');
                $('#sst_penandatangan, #sst_jawatan_penandatangan').val('-');
                $('#sst_cukai').val('0');
                $('#tbl_dokumen_sst tbody tr[data-jenis="pengguna"]').remove();
            }

            function isiBorang(pembekal, sst) {
                kosongkanBorang();

                $('#pembekal_nama').val(pembekal.nama);
                $('#pembekal_mof').val(pembekal.mof_ref_no);
                $('#pembekal_cbp').val(pembekal.gst_no);
                $('#pembekal_cbp_date').val('-');
                $('#pembekal_ep_date').val('-');
                $('#pembekal_alamat').val(pembekal.alamat);
                $('#pembekal_pic').val(pembekal.pegawai);
                $('#pembekal_tel').val(pembekal.tel);
                $('#pembekal_fax').val(pembekal.faks);
                $('#pembekal_hp').val(pembekal.tel_bimbit);
                $('#pembekal_email').val(pembekal.email);

                $('#sst_harga_tawaran').val(formatWang(Number(sst?.offer_price ?? pembekal.harga_tawaran ?? 0)));
                $('#kontrak_tempoh').val(SST_TEMPOH_KONTRAK ?? '');

                // Before signing, show today's date as a live preview; once signed, show the locked-in date.
                $('#sst_tarikh_kerajaan').val(sst?.signed_at
                    ? formatTarikh(new Date(sst.signed_at + 'T00:00:00'))
                    : formatTarikh(new Date()));

                // Documents can only be opened once a record exists.
                $('#link-surat-setuju-terima')
                    .attr('href', sst ? URL_SURAT_SETUJU_TERIMA.replace('VENDOR_ID', vendorAktif) : 'javascript:void(0)')
                    .toggleClass('text-muted', ! sst);
                $('#link-surat-akuan-pembida-berjaya')
                    .attr('href', sst ? URL_SURAT_AKUAN_PEMBIDA_BERJAYA.replace('VENDOR_ID', vendorAktif) : 'javascript:void(0)')
                    .toggleClass('text-muted', ! sst);
                $('#link-surat-akuan-sumpah-syarikat')
                    .attr('href', sst ? URL_SURAT_AKUAN_SUMPAH_SYARIKAT.replace('VENDOR_ID', vendorAktif) : 'javascript:void(0)')
                    .toggleClass('text-muted', ! sst);

                if (sst) {
                    $('#sst_no_dokumen').val(sst.document_no);
                    $('#sst_rujukan_fail').val(sst.file_reference_no);
                    $('#sst_tajuk').val(sst.title);
                    $('#sst_cukai').val(String(sst.tax_rate ?? 0));
                    $('#insurans_ya').prop('checked', !! sst.insurance);
                    $('#insurans_tidak').prop('checked', ! sst.insurance);
                    $('#semakan_ya').prop('checked', !! sst.online_verification);
                    $('#semakan_tidak').prop('checked', ! sst.online_verification);
                    $('#protege_ya').prop('checked', !! sst.protege_rtw);
                    $('#protege_tidak').prop('checked', ! sst.protege_rtw);
                    $('#perjanjian_ya').prop('checked', !! sst.agreement_required);
                    $('#perjanjian_tidak').prop('checked', ! sst.agreement_required);
                    $('#kontrak_mula').val(sst.effective_date ? formatTarikh(new Date(sst.effective_date + 'T00:00:00')) : '');

                    (sst.documents || []).forEach(function (d) {
                        $('#btn-tambah-dokumen').trigger('click');

                        const baris = $('#tbl_dokumen_sst tbody tr[data-jenis="pengguna"]:last');
                        baris.find('input[type="text"]').val(d.document_name);

                        if (d.path) {
                            baris.data('fail', d);
                            baris.find('td:last').append(
                                $('<div class="text-muted mt-1" style="font-size:0.7rem;"></div>').text(d.original_name || '')
                            );
                        }
                    });
                }

                kiraAmaunKontrak();
                kiraTarikhTamat();
            }

            $('#tbody_pembekal').on('click', '.btn-sedia-sst', function () {
                vendorAktif = Number(this.dataset.vendorId);

                $.when(
                    $.get(URL_PEMBEKAL),
                    $.get(URL_SST.replace('VENDOR_ID', vendorAktif))
                ).done(function (pembekalRes, sstRes) {
                    const pembekal = (pembekalRes[0].rows || []).find(function (r) {
                        return Number(r.vendor_id) === vendorAktif;
                    }) || {};

                    isiBorang(pembekal, sstRes[0].sst);
                    showSstTab(0);
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalSST')).show();
                }).fail(function () {
                    showToast('error', 'Ralat memuatkan maklumat pembekal.');
                });
            });

            function kumpulPayload() {
                const documents = [];
                $('#tbl_dokumen_sst tbody tr[data-jenis="pengguna"]').each(function () {
                    const documentName = $(this).find('input[type="text"]').val();
                    if (! documentName) return;

                    // Populated by the upload handler once the file lands on disk.
                    const fail = $(this).data('fail') || {};

                    documents.push({
                        document_name: documentName,
                        original_name: fail.original_name || null,
                        stored_name: fail.stored_name || null,
                        path: fail.path || null,
                        mime_type: fail.mime_type || null,
                        size: fail.size || null,
                    });
                });

                return {
                    _token: SST_CSRF,
                    tender: SST_TENDER,
                    vendor_id: vendorAktif,
                    file_reference_no: $('#sst_rujukan_fail').val(),
                    title: $('#sst_tajuk').val(),
                    offer_price: nilaiWang($('#sst_harga_tawaran').val()),
                    tax_rate: $('#sst_cukai').val(),
                    insurance: $('#insurans_ya').is(':checked') ? 1 : 0,
                    online_verification: $('#semakan_ya').is(':checked') ? 1 : 0,
                    protege_rtw: $('#protege_ya').is(':checked') ? 1 : 0,
                    contract_duration: $('#kontrak_tempoh').val() || 0,
                    effective_date: huraiTarikhIso($('#kontrak_mula').val()),
                    agreement_required: $('#perjanjian_ya').is(':checked') ? 1 : 0,
                    documents: documents,
                };
            }

            function huraiTarikhIso(nilai) {
                const t = huraiTarikh(nilai);
                if (! t) return null;
                const dua = (n) => String(n).padStart(2, '0');
                return t.getFullYear() + '-' + dua(t.getMonth() + 1) + '-' + dua(t.getDate());
            }

            // Ends the step for the whole tender, then returns to the listing.
            $('#btn-hantar').on('click', function () {
                const btn = this;
                setButtonBusy(btn, 'Menghantar...');

                $.post(URL_HANTAR, { _token: SST_CSRF, tender: SST_TENDER }).done(function (res) {
                    window.location.href = URL_SENARAI + '?toast=success&message='
                        + encodeURIComponent(res.message || 'Penyediaan Surat Setuju Terima berjaya dihantar.');
                }).fail(function (xhr) {
                    clearButtonBusy(btn);
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menghantar.');
                });
            });

            // Saves the draft without generating the letter, then moves to the next tab.
            $('#btn-sst-seterusnya').on('click', function () {
                const btn = this;
                setButtonBusy(btn, 'Menyimpan...');

                $.post(URL_SIMPAN, kumpulPayload()).done(function (res) {
                    showToast('success', res.message || 'Maklumat SST telah disimpan.');
                    showSstTab(Math.min(currentSstTab() + 1, sstTabs.length - 1));
                }).fail(function (xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menyimpan maklumat SST.');
                }).always(function () {
                    clearButtonBusy(btn);
                });
            });

            // Generates this vendor's letter only — the tender is advanced by Hantar on the page.
            $('.btn-simpan-sst').on('click', function () {
                const btn = this;
                setButtonBusy(btn, 'Menjana...');

                $.post(URL_JANA, kumpulPayload()).done(function (res) {
                    bootstrap.Modal.getInstance(document.getElementById('modalSST'))?.hide();
                    muatPembekal();
                    muatSurat();
                    showToast('success', res.message || 'Surat Setuju Terima berjaya dijana.');
                }).fail(function (xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menjana Surat Setuju Terima.');
                }).always(function () {
                    clearButtonBusy(btn);
                });
            });

            // Amaun Keseluruhan Kontrak = Harga Tawaran + cukai. Cukai is a rate, so it is
            // added on top of the price — at 0% the total stays equal to Harga Tawaran.
            function nilaiWang(value) {
                return parseFloat(String(value).replace(/[^0-9.-]/g, '')) || 0;
            }

            function formatWang(value) {
                return value.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Bon Pelaksanaan wajib bila amaun kontrak mencapai ambang ini (RM200,000 ke atas).
            const AMBANG_BON = 200000;

            // Insurans hanya dicadangkan pada mulanya — sekali pengguna memilih sendiri,
            // pilihan mereka tidak lagi ditulis ganti bila amaun berubah.
            let insuransDipilihPengguna = false;
            $('input[name="insurans"]').on('change', function () {
                insuransDipilihPengguna = true;
            });

            function terapkanSyaratAmaun() {
                const perlu = nilaiWang($('#sst_total').val()) >= AMBANG_BON;

                $('#bon_ya').prop('checked', perlu);
                $('#bon_tidak').prop('checked', ! perlu);
                $('#bon_hidden').val(perlu ? 'ya' : 'tidak');

                if (! insuransDipilihPengguna) {
                    $('#insurans_ya').prop('checked', perlu);
                    $('#insurans_tidak').prop('checked', ! perlu);
                }
            }

            // Nombor ke perkataan Melayu — dipaparkan di bawah Nilai Bon (cth "SEMBILAN RIBU").
            function perkataanNombor(n) {
                const satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Lapan', 'Sembilan'];

                if (n === 0) return 'Kosong';
                if (n < 10) return satuan[n];
                if (n === 10) return 'Sepuluh';
                if (n === 11) return 'Sebelas';
                if (n < 20) return satuan[n - 10] + ' Belas';

                if (n < 100) {
                    const baki = n % 10;
                    return satuan[Math.floor(n / 10)] + ' Puluh' + (baki ? ' ' + satuan[baki] : '');
                }

                if (n < 1000) {
                    const ratus = Math.floor(n / 100);
                    const baki = n % 100;
                    return (ratus === 1 ? 'Seratus' : satuan[ratus] + ' Ratus') + (baki ? ' ' + perkataanNombor(baki) : '');
                }

                if (n < 1000000) {
                    const ribu = Math.floor(n / 1000);
                    const baki = n % 1000;
                    return (ribu === 1 ? 'Seribu' : perkataanNombor(ribu) + ' Ribu') + (baki ? ' ' + perkataanNombor(baki) : '');
                }

                if (n < 1000000000) {
                    const juta = Math.floor(n / 1000000);
                    const baki = n % 1000000;
                    return perkataanNombor(juta) + ' Juta' + (baki ? ' ' + perkataanNombor(baki) : '');
                }

                return String(n);
            }

            function perkataanWang(jumlah) {
                const ringgit = Math.floor(jumlah);
                const sen = Math.round((jumlah - ringgit) * 100);

                return (perkataanNombor(ringgit) + (sen ? ' Dan ' + perkataanNombor(sen) + ' Sen' : '')).toUpperCase();
            }

            // Tab Bon Pelaksanaan — semua nilai diambil/dikira, tiada input pengguna.
            // Peratusan hanya dikira bila Bon Pelaksanaan (tab 1) = Ya.
            function kiraBonPelaksanaan() {
                $('#bon_tempoh').val($('#kontrak_tempoh').val());
                $('#bon_nilai_kontrak').val($('#sst_total').val());

                if ($('#bon_hidden').val() !== 'ya') {
                    $('#bon_peratus').val('-');
                    $('#bon_nilai').val('-');
                    $('#bon_nilai_perkataan').text('');
                    return;
                }

                const nilaiKontrak = nilaiWang($('#sst_total').val());
                const peratus = nilaiKontrak > 500000 ? 5 : 2.5;
                const nilaiBon = nilaiKontrak * peratus / 100;

                $('#bon_peratus').val(peratus);
                $('#bon_nilai').val(formatWang(nilaiBon));
                $('#bon_nilai_perkataan').text(perkataanWang(nilaiBon));
            }

            function kiraAmaunKontrak() {
                const harga = nilaiWang($('#sst_harga_tawaran').val());
                const kadarCukai = nilaiWang($('#sst_cukai').val());

                $('#sst_total').val(formatWang(harga + (harga * kadarCukai / 100)));
                terapkanSyaratAmaun();
                kiraBonPelaksanaan();
            }

            $('#sst_cukai').on('change', kiraAmaunKontrak);
            kiraAmaunKontrak();

            $('#modalSST .datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            function huraiTarikh(nilai) {
                const bahagian = String(nilai).split('/');

                if (bahagian.length !== 3) {
                    return null;
                }

                const [hari, bulan, tahun] = bahagian.map(Number);

                return (hari && bulan && tahun) ? new Date(tahun, bulan - 1, hari) : null;
            }

            function formatTarikh(tarikh) {
                const dua = (n) => String(n).padStart(2, '0');

                return dua(tarikh.getDate()) + '/' + dua(tarikh.getMonth() + 1) + '/' + tarikh.getFullYear();
            }

            // Tarikh Tamat = Tarikh Kuatkuasa + Tempoh Kontrak, tolak sehari — kontrak 12 bulan
            // mulai 01/01/2026 tamat 31/12/2026, bukan 01/01/2027.
            function kiraTarikhTamat() {
                const mula = huraiTarikh($('#kontrak_mula').val());
                const bulan = parseInt($('#kontrak_tempoh').val(), 10);

                if (! mula || ! bulan) {
                    $('#kontrak_tamat').val('');
                    return;
                }

                const hari = mula.getDate();
                mula.setMonth(mula.getMonth() + bulan);

                // Bulan sasaran boleh lebih pendek (31 Jan + 1 bulan melimpah ke Mac) —
                // setDate(0) tarik balik ke hari terakhir bulan yang betul.
                if (mula.getDate() !== hari) {
                    mula.setDate(0);
                }

                mula.setDate(mula.getDate() - 1);

                $('#kontrak_tamat').val(formatTarikh(mula));
            }

            $('#kontrak_mula').on('change changeDate', kiraTarikhTamat);
            kiraTarikhTamat();

            // Baris sistem tidak boleh dihapus — hanya baris tambahan pengguna ada butang hapus.
            function nomborSemulaDokumen() {
                $('#tbl_dokumen_sst tbody tr').each(function (i) {
                    $(this).find('td').eq(0).text(i + 1);
                });
            }

            $('#btn-tambah-dokumen').on('click', function () {
                $('#tbl_dokumen_sst tbody').append(
                    '<tr data-jenis="pengguna">' +
                        '<td class="text-center"></td>' +
                        '<td><input type="text" class="form-control form-control-sm" name="dokumen_kandungan[]" placeholder="Nyatakan kandungan dokumen"></td>' +
                        '<td>' +
                            '<div class="d-flex align-items-center gap-2">' +
                                '<input type="file" class="form-control form-control-sm" name="dokumen_fail[]">' +
                                '<button type="button" class="btn-hapus-baris" title="Hapus baris" aria-label="Hapus baris">' +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
                                '</button>' +
                            '</div>' +
                        '</td>' +
                    '</tr>'
                );
                nomborSemulaDokumen();
            });

            $('#tbl_dokumen_sst').on('click', '.btn-hapus-baris', function () {
                $(this).closest('tr').remove();
                nomborSemulaDokumen();
            });

            // Upload as soon as a file is picked so only metadata rides along with Simpan.
            $('#tbl_dokumen_sst').on('change', 'input[type="file"]', function () {
                const input = this;
                const baris = $(input).closest('tr');

                if (! input.files.length) {
                    baris.removeData('fail');
                    return;
                }

                const data = new FormData();
                data.append('file', input.files[0]);
                data.append('_token', SST_CSRF);

                $(input).prop('disabled', true);

                $.ajax({
                    url: URL_MUAT_NAIK,
                    method: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                }).done(function (res) {
                    baris.data('fail', res);
                    showToast('success', 'Fail berjaya dimuat naik.');
                }).fail(function (xhr) {
                    input.value = '';
                    baris.removeData('fail');
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa memuat naik fail.');
                }).always(function () {
                    $(input).prop('disabled', false);
                });
            });

            // Modal SST wizard — Kembali/Seterusnya walk this order, and the footer
            // buttons swap so only the ones valid for the current tab are shown.
            const sstTabs = ['#pembekal-pane', '#bon-pane', '#sst-pane'];

            function currentSstTab() {
                return sstTabs.findIndex(function (pane) {
                    return $(pane).hasClass('active');
                });
            }

            function syncSstFooter() {
                const i = currentSstTab();
                $('#btn-sst-kembali').toggle(i > 0);
                $('#btn-sst-seterusnya').toggle(i < sstTabs.length - 1);
                $('#btn-sst-simpan').toggle(i === sstTabs.length - 1);
            }

            function showSstTab(index) {
                $('#sstTab a[href="' + sstTabs[index] + '"]').tab('show');
            }

            $('#sstTab a[data-bs-toggle="tab"]').on('shown.bs.tab', syncSstFooter);

            $('#btn-sst-kembali').on('click', function () {
                showSstTab(Math.max(currentSstTab() - 1, 0));
            });

            syncSstFooter();
            muatPembekal();
            muatSurat();
        });
    </script>
@endsection
