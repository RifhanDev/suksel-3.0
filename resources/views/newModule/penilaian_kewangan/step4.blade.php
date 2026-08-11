{{-- Step 4: Penyediaan Laporan --}}

@php
    $isSubmitted = !empty($laporanRecord?->submitted_at) || !empty($readOnly);
    $savedJustifikasi = $laporanRecord?->pengesyoran_justifikasi ?? [];
    if (empty($savedJustifikasi) || !is_array($savedJustifikasi)) {
        $savedJustifikasi = ['Dengan ini, JPT mengesyorkan petender yang melepasi untuk melaksanakan projek ini untuk dibawa ke mesyuarat Jawatankuasa Sebut Harga berdasarkan justifikasi yang ditetapkan.'];
    }
@endphp

<div id="step4-main" class="step4-kewangan-pane">
    <div class="container-fluid mt-2 px-0">

        {{-- Header Banner --}}
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary-subtle p-2.5 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #e0f2fe;">
                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Laporan Penilaian Kewangan</h5>
                <p class="text-secondary small mb-0">Penyediaan laporan penilaian kewangan mengikut peringkat dan pengesyoran jawatankuasa.</p>
            </div>
        </div>

        {{-- RUMUSAN KEPUTUSAN PENILAIAN KEWANGAN (SEMUA PEMBEKAL) --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary px-3 py-1.5 rounded-pill font-monospace me-2.5">Rumusan</span>
                    <h6 class="fw-bold text-dark mb-0">RUMUSAN KEPUTUSAN PENILAIAN KEWANGAN (SEMUA PEMBEKAL)</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive rounded-3 border bg-white shadow-sm">
                    <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 6%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 26%; font-size: 0.725rem; letter-spacing: 0.5px;">KOD / NAMA PEMBEKAL</th>
                                <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 18%; font-size: 0.725rem; letter-spacing: 0.5px;">PEMATUHAN DOKUMENTASI</th>
                                <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 18%; font-size: 0.725rem; letter-spacing: 0.5px;">PENYATA BULANAN BANK</th>
                                <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 18%; font-size: 0.725rem; letter-spacing: 0.5px;">SPESIFIKASI KEWANGAN</th>
                                <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 14%; font-size: 0.725rem; letter-spacing: 0.5px;">KEPUTUSAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rumusanLaporanData ?? [] as $idx => $r)
                                <tr>
                                    <td class="text-center py-3 font-monospace fw-bold">{{ $idx + 1 }}</td>
                                    <td class="text-start py-3">
                                        <div class="fw-bold text-dark">{{ $r['name'] }}</div>
                                        @if(!empty($r['kod']))
                                            <div class="small text-muted font-monospace">{{ $r['kod'] }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center py-3">
                                        @if($r['step1_status'] === 'melepasi')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1 rounded-pill fw-bold">Melepasi</span>
                                        @elseif($r['step1_status'] === 'tidak_melepasi')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1 rounded-pill fw-bold">Tidak Melepasi</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-1 rounded-pill">Belum Dinilai</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-3">
                                        @if($r['step2_status'] === 'melepasi')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1 rounded-pill fw-bold">Melepasi</span>
                                        @elseif($r['step2_status'] === 'tidak_melepasi')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1 rounded-pill fw-bold">Tidak Melepasi</span>
                                        @elseif($r['step2_status'] === '-')
                                            <span class="text-muted fw-bold">-</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-1 rounded-pill">Belum Dinilai</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-3">
                                        @if($r['step3_status'] === 'melepasi')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1 rounded-pill fw-bold">Melepasi</span>
                                        @elseif($r['step3_status'] === 'tidak_melepasi')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1 rounded-pill fw-bold">Tidak Melepasi</span>
                                        @elseif($r['step3_status'] === '-')
                                            <span class="text-muted fw-bold">-</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-1 rounded-pill">Belum Dinilai</span>
                                        @endif
                                    </td>
                                    <td class="text-center py-3">
                                        @if($r['is_layak'])
                                            <span class="badge bg-success px-3 py-1.5 rounded-pill font-monospace fw-bold fs-6">LAYAK</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-1.5 rounded-pill font-monospace fw-bold fs-6">TIDAK LAYAK</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tiada rekod pembekal dijumpai.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <form id="formStep4Laporan" autocomplete="off">
            @csrf
            <input type="hidden" name="tender" value="{{ $tender_no }}">

            {{-- PERINGKAT 1: Penilaian Pematuhan Dokumentasi --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary px-3 py-1.5 rounded-pill font-monospace me-2.5">Peringkat 1</span>
                        <h6 class="fw-bold text-dark mb-0">PENILAIAN PEMATUHAN DOKUMENTASI</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    {{-- Senarai Pembekal Melepasi --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>Senarai Pembekal Yang Melepasi Penilaian Pematuhan Dokumentasi
                        </h6>
                        <div class="table-responsive rounded-3 border bg-white shadow-sm">
                            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 85%; font-size: 0.725rem; letter-spacing: 0.5px;">ULASAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembekalMelepasi ?? [] as $i => $p)
                                        <tr>
                                            <td class="text-center py-2.5 font-monospace fw-bold">{{ $p['kod'] ?? ($i + 1) }}</td>
                                            <td class="text-start py-2.5">{{ $p['ulasan'] ?? 'Mematuhi semua syarat pematuhan dokumentasi.' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-3" style="font-size: 0.875rem;">
                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod pembekal melepasi dijumpai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Senarai Pembekal Tidak Melepasi --}}
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">
                            <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>Senarai Pembekal Tidak Melepasi Penilaian Pematuhan Dokumentasi
                        </h6>
                        <div class="table-responsive rounded-3 border bg-white shadow-sm mb-3">
                            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 85%; font-size: 0.725rem; letter-spacing: 0.5px;">ULASAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembekalTidakMelepasi ?? [] as $i => $p)
                                        <tr>
                                            <td class="text-center py-2.5 font-monospace fw-bold text-danger">{{ $p['kod'] ?? ($i + 1) }}</td>
                                            <td class="text-start py-2.5">{{ $p['ulasan'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-3" style="font-size: 0.875rem;">
                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod dijumpai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="rounded-3 p-3 bg-light border">
                            <label class="form-label fw-bold text-dark small mb-1.5 d-flex align-items-center gap-1.5">
                                <i class="bi bi-chat-left-text text-primary"></i>Justifikasi / Keputusan JPT (Peringkat 1):
                            </label>
                            <textarea name="catatan_peringkat1" class="form-control bg-white" rows="2" style="font-size: 0.875rem;" {{ $isSubmitted ? 'readonly' : '' }}>{{ old('catatan_peringkat1', $laporanRecord?->catatan_peringkat1 ?? ('Sehubungan dengan itu, JPT bersetuju untuk mengambil ' . count($pembekalMelepasi ?? []) . ' penyebut harga untuk ke Penilaian Peringkat Kedua.')) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PERINGKAT 2: Penilaian Penyata Bulanan Bank --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary px-3 py-1.5 rounded-pill font-monospace me-2.5">Peringkat 2</span>
                        <h6 class="fw-bold text-dark mb-0">PENILAIAN PENYATA BULANAN BANK</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    @php
                        $step2Melepasi = collect($rumusanLaporanData ?? [])->filter(fn($r) => $r['step2_status'] === 'melepasi')->values();
                        $step2TidakMelepasi = collect($rumusanLaporanData ?? [])->filter(fn($r) => $r['step2_status'] === 'tidak_melepasi')->values();
                    @endphp

                    {{-- Senarai Pembekal Melepasi --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>Senarai Pembekal Yang Melepasi Penilaian Penyata Bulanan Bank
                        </h6>
                        <div class="table-responsive rounded-3 border bg-white shadow-sm">
                            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 85%; font-size: 0.725rem; letter-spacing: 0.5px;">ULASAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($step2Melepasi as $i => $p)
                                        <tr>
                                            <td class="text-center py-2.5 font-monospace fw-bold">{{ $p['kod'] }}</td>
                                            <td class="text-start py-2.5">Penyata bank lengkap dan mematuhi kriteria kelayakan.</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-3" style="font-size: 0.875rem;">
                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod pembekal melepasi dijumpai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Senarai Pembekal Tidak Melepasi --}}
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">
                            <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>Senarai Pembekal Tidak Melepasi Penilaian Penyata Bulanan Bank
                        </h6>
                        <div class="table-responsive rounded-3 border bg-white shadow-sm mb-3">
                            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 15%; font-size: 0.725rem; letter-spacing: 0.5px;">BIL</th>
                                        <th class="py-2.5 px-3 text-start text-uppercase text-secondary fw-bold" style="width: 85%; font-size: 0.725rem; letter-spacing: 0.5px;">ULASAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($step2TidakMelepasi as $i => $p)
                                        <tr>
                                            <td class="text-center py-2.5 font-monospace fw-bold text-danger">{{ $p['kod'] }}</td>
                                            <td class="text-start py-2.5">Tidak mematuhi kriteria kelayakan penyata bulanan bank.</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-3" style="font-size: 0.875rem;">
                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod dijumpai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="rounded-3 p-3 bg-light border">
                            <label class="form-label fw-bold text-dark small mb-1.5 d-flex align-items-center gap-1.5">
                                <i class="bi bi-chat-left-text text-primary"></i>Justifikasi / Keputusan JPT (Peringkat 2):
                            </label>
                            <textarea name="catatan_peringkat2" class="form-control bg-white" rows="2" style="font-size: 0.875rem;" {{ $isSubmitted ? 'readonly' : '' }}>{{ old('catatan_peringkat2', $laporanRecord?->catatan_peringkat2 ?? 'Sehubungan dengan itu, JPT bersetuju untuk mengambil penyebut harga yang melepasi untuk ke Penilaian Peringkat Ketiga.') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PERINGKAT 3: Penilaian Pematuhan Spesifikasi Kewangan --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary px-3 py-1.5 rounded-pill font-monospace me-2.5">Peringkat 3</span>
                        <h6 class="fw-bold text-dark mb-0">PENILAIAN PEMATUHAN SPESIFIKASI KEWANGAN</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    @php
                        $r3Data = $rumusanStep3Data ?? [
                            'passing_percentage' => 50,
                            'pembekal_melepasi' => [],
                            'pembekal_tidak_melepasi' => [],
                        ];
                    @endphp

                    {{-- Senarai Pembekal Melepasi --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>Senarai Pembekal Yang Melepasi Penilaian Kewangan
                        </h6>
                        <div class="table-responsive rounded-3 border bg-white shadow-sm mb-3">
                            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 20%; font-size: 0.725rem; letter-spacing: 0.5px;">KEDUDUKAN</th>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 40%; font-size: 0.725rem; letter-spacing: 0.5px;">KOD PEMBEKAL</th>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 40%; font-size: 0.725rem; letter-spacing: 0.5px;">JUMLAH SKOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($r3Data['pembekal_melepasi'] as $p)
                                        <tr>
                                            <td class="text-center py-2.5">
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1 rounded-pill font-monospace fw-bold">{{ $p['kedudukan'] }}</span>
                                            </td>
                                            <td class="text-center py-2.5 font-monospace fw-bold text-dark">{{ $p['kod'] }}</td>
                                            <td class="text-center py-2.5 font-monospace fw-bold text-primary">{{ $p['score_fmt'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3" style="font-size: 0.875rem;">
                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod pembekal melepasi dijumpai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="small text-secondary fw-medium">Penetapan Penanda Aras Tahap Lulus (%):</span>
                            <div class="input-group input-group-sm" style="width: 90px;">
                                <input type="text" class="form-control text-center font-monospace fw-bold bg-white" value="{{ (float)($r3Data['passing_percentage'] ?? 50) }}" readonly aria-label="Penanda aras tahap lulus">
                                <span class="input-group-text bg-white fw-bold">%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Senarai Pembekal Tidak Melepasi --}}
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">
                            <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>Senarai Pembekal Tidak Melepasi Penilaian Kewangan
                        </h6>
                        <div class="table-responsive rounded-3 border bg-white shadow-sm mb-3">
                            <table class="table table-hover align-middle mb-0 w-100" style="font-size: 0.85rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 40%; font-size: 0.725rem; letter-spacing: 0.5px;">KOD PEMBEKAL</th>
                                        <th class="py-2.5 px-3 text-center text-uppercase text-secondary fw-bold" style="width: 60%; font-size: 0.725rem; letter-spacing: 0.5px;">JUMLAH SKOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($r3Data['pembekal_tidak_melepasi'] as $p)
                                        <tr>
                                            <td class="text-center py-2.5 font-monospace fw-bold text-dark">{{ $p['kod'] }}</td>
                                            <td class="text-center py-2.5 font-monospace fw-bold text-danger">{{ $p['score_fmt'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-3" style="font-size: 0.875rem;">
                                                <i class="bi bi-inbox me-1 fs-5"></i>Tiada rekod dijumpai.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="rounded-3 p-3 bg-light border">
                            <label class="form-label fw-bold text-dark small mb-1.5 d-flex align-items-center gap-1.5">
                                <i class="bi bi-chat-left-text text-primary"></i>Justifikasi / Keputusan JPT (Peringkat 3):
                            </label>
                            <textarea name="catatan_peringkat3" class="form-control bg-white" rows="2" style="font-size: 0.875rem;" {{ $isSubmitted ? 'readonly' : '' }}>{{ old('catatan_peringkat3', $laporanRecord?->catatan_peringkat3 ?? 'Sehubungan dengan itu, JPT bersetuju untuk mengambil penyebut harga yang melepasi untuk ke Peringkat Pengesyoran.') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PERINGKAT 4: Pengesyoran Jawatankuasa --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4" id="pengesyoran-section-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success px-3 py-1.5 rounded-pill font-monospace me-2.5">Peringkat 4</span>
                            <h6 class="fw-bold text-dark mb-0">PENGESYORAN JAWATANKUASA</h6>
                        </div>
                        @if(!$isSubmitted)
                            <button type="button" id="btnTambahPengesyoran4" class="btn btn-sm btn-success px-3 fw-bold d-inline-flex align-items-center gap-1">
                                <i class="bi bi-plus-circle"></i>
                                <span>Tambah Pengesyoran</span>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="pengesyoran-list-4">
                        @foreach($savedJustifikasi as $index => $itemText)
                            <div class="pengesyoran-item mb-3 p-3 rounded-3 bg-light border">
                                <label class="form-label fw-semibold text-dark small mb-2 d-flex align-items-center gap-1.5">
                                    <i class="bi bi-pencil-square text-primary"></i>Teks Pengesyoran JPT:
                                </label>
                                <textarea name="pengesyoran_justifikasi[]" class="form-control bg-white" rows="3" style="font-size: 0.875rem;" {{ $isSubmitted ? 'readonly' : '' }}>{{ $itemText }}</textarea>
                                @if(!$isSubmitted)
                                    <div class="text-end mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-pengesyoran {{ $loop->first ? 'd-none' : '' }} px-3 font-monospace">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 pt-2 mb-3">
                <button type="button" id="btnStep4Sebelumnya" class="btn btn-outline-secondary btn-sebelumnya px-4 fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i>
                    <span>Sebelumnya</span>
                </button>
                <div class="d-flex gap-2">
                    @if(!$isSubmitted)
                        <button type="button" id="btnStep4SimpanDraft" class="btn btn-outline-secondary px-4 fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-save"></i>
                            <span>Simpan Draft</span>
                        </button>
                        <button type="button" id="btnStep4Hantar" class="btn btn-primary px-4 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-send-check"></i>
                            <span>Hantar Laporan</span>
                        </button>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-4 py-2.5 rounded-3 fw-bold font-monospace d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Laporan telah dihantar pada {{ $laporanRecord?->submitted_at ? $laporanRecord->submitted_at->format('d/m/Y h:i A') : '' }}</span>
                        </span>
                    @endif
                </div>
            </div>
        </form>

    </div>
</div> {{-- /#step4-main --}}

{{-- Success state after Hantar --}}
<div id="step4-success" class="{{ $isSubmitted ? '' : 'd-none' }}" style="min-height:420px; display:flex; align-items:center; justify-content:center;">
    <div class="card border-0 shadow-lg rounded-3 text-center p-5 max-w-lg mx-auto" style="max-width: 520px; background: #fff;">
        <div class="mb-3">
            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center p-3" style="width: 80px; height: 80px;">
                <i class="bi bi-check-circle-fill text-success fs-1"></i>
            </div>
        </div>
        <h4 class="fw-bold text-dark mb-2">Penghantaran Berjaya!</h4>
        <p class="text-secondary small mb-4">Laporan Penilaian Kewangan telah berjaya disahkan dan dihantar ke peringkat seterusnya (Status Process ID: 11).</p>
        <div>
            <button type="button" id="btnStep4CloseSuccess" class="btn btn-primary px-5 py-2.5 fw-bold rounded-3 d-inline-flex align-items-center gap-2">
                <i class="bi bi-house-door"></i>
                <span>Tutup & Kembali</span>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnHantar = document.getElementById('btnStep4Hantar');
    const btnSimpanDraft = document.getElementById('btnStep4SimpanDraft');
    const btnSebelumnya = document.getElementById('btnStep4Sebelumnya');
    const formLaporan = document.getElementById('formStep4Laporan');
    const mainWrap = document.getElementById('step4-main');
    const successWrap = document.getElementById('step4-success');
    const btnClose = document.getElementById('btnStep4CloseSuccess');
    const btnTambahPengesyoran = document.getElementById('btnTambahPengesyoran4');
    const pengesyoranList = document.getElementById('pengesyoran-list-4');

    if (btnClose) {
        btnClose.addEventListener('click', function () {
            window.location.href = "{{ route('penilaianKewangan') }}";
        });
    }

    if (btnSebelumnya) {
        btnSebelumnya.addEventListener('click', function () {
            const tab = document.querySelector('#rumusan-3-tab');
            if (tab) tab.click();
        });
    }

    // Tambah pengesyoran: clone blok pengesyoran dan append di bawah
    if (btnTambahPengesyoran && pengesyoranList) {
        btnTambahPengesyoran.addEventListener('click', function (e) {
            e.preventDefault();
            const firstItem = pengesyoranList.querySelector('.pengesyoran-item');
            if (!firstItem) return;

            const clone = firstItem.cloneNode(true);
            const ta = clone.querySelector('textarea');
            if (ta) {
                ta.value = '';
                ta.removeAttribute('readonly');
            }

            const removeBtn = clone.querySelector('.btn-remove-pengesyoran');
            if (removeBtn) removeBtn.classList.remove('d-none');

            pengesyoranList.appendChild(clone);

            // Pastikan item pertama tidak boleh dibuang jika 1 sahaja
            updateRemoveButtons();
        });

        // Event delegation untuk buang pengesyoran
        pengesyoranList.addEventListener('click', function (e) {
            const target = e.target.closest('.btn-remove-pengesyoran');
            if (!target) return;

            const items = pengesyoranList.querySelectorAll('.pengesyoran-item');
            if (items.length <= 1) return;

            const item = target.closest('.pengesyoran-item');
            if (item) item.remove();

            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            const items = pengesyoranList.querySelectorAll('.pengesyoran-item');
            items.forEach((item, index) => {
                const btn = item.querySelector('.btn-remove-pengesyoran');
                if (btn) {
                    if (index === 0 && items.length === 1) {
                        btn.classList.add('d-none');
                    } else {
                        btn.classList.remove('d-none');
                    }
                }
            });
        }
    }

    // Save Draft
    if (btnSimpanDraft && formLaporan) {
        btnSimpanDraft.addEventListener('click', function (e) {
            e.preventDefault();
            const formData = new FormData(formLaporan);

            btnSimpanDraft.disabled = true;
            btnSimpanDraft.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...';

            fetch("{{ route('penilaianKewangan.simpanLaporan') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                btnSimpanDraft.disabled = false;
                btnSimpanDraft.innerHTML = '<i class="bi bi-save"></i><span>Simpan Draft</span>';

                if (ok) {
                    if (window.toastr) {
                        toastr.success(data.message || 'Draft laporan disimpan.');
                    } else {
                        alert(data.message || 'Draft laporan disimpan.');
                    }
                } else {
                    alert(data.message || 'Ralat semasa menyimpan draft.');
                }
            })
            .catch(err => {
                btnSimpanDraft.disabled = false;
                btnSimpanDraft.innerHTML = '<i class="bi bi-save"></i><span>Simpan Draft</span>';
                alert('Ralat sambungan pelayan.');
            });
        });
    }

    // Submit Final Report
    if (btnHantar && formLaporan && mainWrap && successWrap) {
        btnHantar.addEventListener('click', function (e) {
            e.preventDefault();

            if (!confirm('Adakah anda pasti untuk menghantar Laporan Penilaian Kewangan ini? Selepas penghantaran, status tender dan keputusan pembekal akan dikemaskini.')) {
                return;
            }

            const formData = new FormData(formLaporan);

            btnHantar.disabled = true;
            btnHantar.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menghantar...';

            fetch("{{ route('penilaianKewangan.hantar') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                btnHantar.disabled = false;
                btnHantar.innerHTML = '<i class="bi bi-send-check"></i><span>Hantar Laporan</span>';

                if (ok) {
                    mainWrap.style.display = 'none';
                    successWrap.classList.remove('d-none');
                } else {
                    alert(data.message || 'Ralat semasa menghantar laporan penilaian kewangan.');
                }
            })
            .catch(err => {
                btnHantar.disabled = false;
                btnHantar.innerHTML = '<i class="bi bi-send-check"></i><span>Hantar Laporan</span>';
                alert('Ralat sambungan pelayan.');
            });
        });
    }
});
</script>
