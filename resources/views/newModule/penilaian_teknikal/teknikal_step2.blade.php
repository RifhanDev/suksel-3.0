{{-- Step 2: Pematuhan Spesifikasi Teknikal (Teknikal tab + Rumusan tab + modal) --}}
<style>
    .spesifikasi-cadangan-box {
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 8px 10px;
        background: #f8fafc;
        font-size: 0.8rem;
        line-height: 1.45;
        min-height: 2.5rem;
        white-space: pre-wrap;
    }
</style>
<!-- Inner tabs for step 2 -->
<ul class="nav segmented-tabs mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#teknikal-2" role="tab" aria-selected="true">Teknikal</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#rumusan-2" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content">
    {{-- Teknikal tab --}}
    <div class="tab-pane fade show active" id="teknikal-2" role="tabpanel" aria-labelledby="teknikal-2-tab">
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="content-card-icon" style="width: 42px; height: 42px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-1" style="font-size: 1.05rem; letter-spacing: -0.2px;">Penilaian Spesifikasi Teknikal</h4>
                <p class="text-muted mb-0" style="font-size: 0.78rem;">Semak dan nilai pematuhan spesifikasi teknikal bagi setiap petender.</p>
            </div>
        </div>
        <div class="guideline-card mb-3">
            <div class="guideline-card-header" style="margin-bottom: 0;">
                <span class="guideline-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </span>
                <span class="guideline-item-text mb-0">Klik butang <span class="highlight">Menilai</span> untuk menilai pematuhan spesifikasi teknikal bagi setiap petender.</span>
            </div>
        </div>
        <table class="table table-bordered table-slate dt-responsive nowrap w-100">
            <thead class="table-primary">
                <tr>
                    <th class="text-center" style="width: 60px;">No.</th>
                    <th class="text-center">Tajuk / Dokumen</th>
                    <th class="text-center">Mekanisma</th>
                    <th class="text-center">Status Penilaian</th>
                    <th class="text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($step2Items as $item)
                    <tr data-item-uuid="{{ $item['uuid'] }}">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td class="text-center">{{ $item['mechanism_label'] }}</td>
                        <td class="text-center">
                            <span class="badge-status {{ $item['status_badge_class'] }} status-penilaian-badge">{{ $item['status_label'] }}</span>
                        </td>
                        <td class="text-center">
                            {{-- Label follows status: fully evaluated = Papar --}}
                            @php $btnLabel = $item['status_label'] === 'Telah Dinilai' ? 'Papar' : 'Menilai'; @endphp
                            @if ($item['is_spesifikasi'])
                                <button type="button" class="btn-form btn-form-success btn-spesifikasi-menilai" data-bs-toggle="modal" data-bs-target="#modalPenilaianSpesifikasiTeknikal">{{ $btnLabel }}</button>
                            @elseif ($item['is_item_level_scored'])
                                <button type="button" class="btn-form btn-form-success btn-borang-menilai" data-bs-toggle="modal" data-bs-target="#modalPenilaianBorangAtasTalianTeknikal" data-item-uuid="{{ $item['uuid'] }}" data-tajuk="{{ $item['title'] }}">{{ $btnLabel }}</button>
                            @else
                                <button type="button" class="btn-form btn-form-success" disabled title="Belum tersedia">Menilai</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tiada item spesifikasi untuk tender ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="row mb-3 px-3">
            <div class="col-md-12 d-flex justify-content-between">
                <button type="button" class="btn-form btn-form-secondary btn-sebelumnya">Sebelumnya</button>
                <button type="button" class="btn-form btn-form-primary btn-seterusnya">Seterusnya</button>
            </div>
        </div>
    </div>

    {{-- Rumusan tab --}}
    <div class="tab-pane fade" id="rumusan-2" role="tabpanel" aria-labelledby="rumusan-2-tab">
        <div class="mt-2">

            {{-- SECTION 1: Pembekal Melepasi (jadual) + Penanda Aras (sebelah kanan) --}}
            <div class="mb-4">
                {{-- Full-width title above so the right card aligns with the table header --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="rumusan-icon" style="background: #dcfce7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </span>
                    <div>
                        <div class="rumusan-heading-title">Pembekal Melepasi Penilaian Teknikal</div>
                        <div class="rumusan-heading-sub">Senarai pembekal yang mencapai penanda aras tahap lulus.</div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-lg-row gap-3 align-items-start">

                    {{-- KIRI: jadual pembekal melepasi --}}
                    <div style="flex: 1 1 auto; min-width: 0;">
                        <table class="table rumusan-table align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 130px;">Kedudukan</th>
                                    <th class="text-center" style="width: 130px;">Bil</th>
                                    <th>Markah Teknikal (%)</th>
                                </tr>
                            </thead>
                            <tbody id="rumusanStep2MelepasiTbody">
                                <tr>
                                    <td colspan="3" class="text-center text-muted" style="padding: 18px 16px;">Memuatkan...</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <span class="rumusan-total-label">Jumlah Pembekal Melepasi</span>
                                        <span class="rumusan-total-value" id="rumusanStep2MelepasiTotal" style="color: #16a34a;">0</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- KANAN: Penetapan Penanda Aras Tahap Lulus — tinggi asli, dipin di atas (tidak regang) --}}
                    <div style="flex: 0 0 300px;">
                        <div class="content-card p-0"
                            style="border: 1px solid #e2e8f0; box-shadow: 0 6px 20px rgba(15, 23, 42, 0.1); border-radius: 12px;">
                            <div class="content-card-header p-3 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="content-card-icon" style="width: 34px; height: 34px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <circle cx="12" cy="12" r="6"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                        </svg>
                                    </div>
                                    <h3 class="content-card-title mb-0" style="font-size: 0.8rem;">Penetapan Penanda Aras Tahap Lulus</h3>
                                </div>
                            </div>
                            <div class="content-card-body p-3 text-center">
                                <div class="d-flex align-items-baseline justify-content-center gap-1">
                                    <input type="text" value="0" readonly aria-label="Penanda aras tahap lulus" id="rumusanStep2TahapLulus"
                                        class="fw-bold border-0 bg-transparent p-0 text-center"
                                        style="font-size: 2.8rem; line-height: 1; color: #1e293b; width: 5.5ch;">
                                    <span class="fw-bold" style="font-size: 1.4rem; color: #94a3b8;">%</span>
                                </div>
                                <span class="fw-bold text-uppercase mt-1 d-block"
                                    style="font-size: 0.6rem; letter-spacing: 0.6px; color: #94a3b8;">Markah Minimum Lulus</span>
                                <p class="text-muted mb-0 mt-2 mx-auto" style="font-size: 0.75rem; max-width: 240px;">
                                    Skor minimum yang perlu dicapai petender untuk melepasi penilaian teknikal.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shown to members who may evaluate but not finalise; toggled once the session loads. --}}
            <div class="pengerusi-only-note d-none" id="pengerusiOnlyNoteStep2">
                <i class="bi bi-info-circle-fill"></i>
                <span>Pengesahan dan penghantaran hanya boleh dilakukan oleh <strong>Pengerusi Jawatankuasa</strong>. Anda masih boleh menyemak rumusan di halaman ini.</span>
            </div>

            <label for="confirmLayakStep2"
                class="pengesahan-card d-flex align-items-center gap-3 p-3 rounded-3 mb-4"
                style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 3px solid var(--sg-red, #c41e3a); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); cursor: {{ $spesifikasiConfirmed ? 'default' : 'pointer' }};">
                <input class="form-check-input flex-shrink-0" type="checkbox" id="confirmLayakStep2"
                    style="width: 1.3rem; height: 1.3rem; cursor: {{ $spesifikasiConfirmed ? 'default' : 'pointer' }};" @checked($spesifikasiConfirmed) @disabled($spesifikasiConfirmed)>
                <span class="d-flex flex-column">
                    <span class="fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.4;">Saya mengesahkan petender di atas layak untuk dinilai oleh Jawatankuasa Kewangan.</span>
                    <span class="text-muted" style="font-size: 0.78rem;">Tandakan pengesahan ini untuk membuka penilaian peringkat seterusnya.</span>
                </span>
            </label>

            {{-- SECTION 2: Pembekal Tidak Melepasi --}}
            <div class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="rumusan-icon" style="background: #fee2e2;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </span>
                    <div>
                        <div class="rumusan-heading-title">Pembekal Tidak Melepasi Penilaian Teknikal</div>
                        <div class="rumusan-heading-sub">Senarai pembekal yang tidak mencapai penanda aras tahap lulus.</div>
                    </div>
                </div>
                <table class="table rumusan-table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 130px;">Bil</th>
                            <th>Markah Teknikal (%)</th>
                        </tr>
                    </thead>
                    <tbody id="rumusanStep2TidakMelepasiTbody">
                        <tr>
                            <td colspan="2" class="text-center text-muted" style="padding: 18px 16px;">Memuatkan...</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <span class="rumusan-total-label">Jumlah Pembekal Tidak Melepasi</span>
                                <span class="rumusan-total-value" id="rumusanStep2TidakMelepasiTotal" style="color: #dc2626;">0</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- "Kembali" goes to this step's own Teknikal sub-tab, not back to Langkah 1. --}}
            <div class="d-flex justify-content-between">
                <button type="button" class="btn-form btn-form-secondary" id="btnKembaliRumusanStep2">Kembali</button>
                <button type="button" class="btn-form btn-form-primary btn-seterusnya">Seterusnya</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 1: PENILAIAN SPESIFIKASI TEKNIKAL - SENARAI PEMBEKAL (opens when user clicks Menilai on main table) --}}
<div class="modal fade" id="modalPenilaianSpesifikasiTeknikal" tabindex="-1" aria-labelledby="modalPenilaianSpesifikasiTeknikalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="content-card-icon" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalPenilaianSpesifikasiTeknikalLabel" style="font-size: 1.05rem; letter-spacing: -0.2px;">Penilaian Cadangan Teknikal</h5>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Tajuk / Dokumen: <span id="modalPenilaianSpesifikasiTajuk">-</span></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="guideline-card mb-3">
                    <div class="guideline-card-header" style="margin-bottom: 0;">
                        <span class="guideline-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </span>
                        <span class="guideline-item-text mb-0">Pastikan semua senarai semak lengkap dinilai dan butang <span class="highlight">Menilai</span> bertukar kepada <span class="highlight">Papar</span>.</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-slate align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Bil</th>
                                <th>Skor Automatik</th>
                                <th>Skor Manual</th>
                                <th>Jumlah Skor</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="spesifikasiRollupTbody">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Memuatkan...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 2: SENARAI SPESIFIKASI TEKNIKAL (8 columns, Simpan only - opened from row Menilai in modal above) --}}
<div class="modal fade" id="modalSenaraiSpesifikasiTeknikal" tabindex="-1" aria-labelledby="modalSenaraiSpesifikasiTeknikalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 1400px;">
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
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalSenaraiSpesifikasiTeknikalLabel" style="font-size: 1.05rem; letter-spacing: -0.2px;">Senarai Spesifikasi Teknikal</h5>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Nilai pematuhan spesifikasi bagi setiap item, berdasarkan cadangan petender.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-slate align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th style="min-width: 260px;">Item / Spesifikasi</th>
                                <th style="width: 110px;">Kekerapan</th>
                                <th style="width: 110px;">Unit Ukuran</th>
                                <th style="min-width: 260px;">Cadangan Petender</th>
                                <th style="width: 150px;">Skor Automatik</th>
                                <th style="width: 160px;">Skor Manual</th>
                                <th style="min-width: 220px;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="spesifikasiDetailTbody">
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Memuatkan...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer teknikal-modal-footer justify-content-between gap-2">
                <button type="button" class="btn-form btn-form-secondary" id="btnKembaliSenaraiSpesifikasiTeknikal">Kembali</button>
                <button type="button" class="btn-form btn-form-success" id="btnSimpanSenaraiSpesifikasiTeknikal">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Borang Atas Talian: one flat modal, no rollup->detail level like Spesifikasi has. --}}
<div class="modal fade" id="modalPenilaianBorangAtasTalianTeknikal" tabindex="-1" aria-labelledby="modalPenilaianBorangAtasTalianTeknikalLabel" aria-hidden="true">
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
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalPenilaianBorangAtasTalianTeknikalLabel" style="font-size: 1.05rem; letter-spacing: -0.2px;">Penilaian Dokumen Teknikal</h5>
                        <p class="text-muted mb-0" style="font-size: 0.78rem;">Tajuk / Dokumen: <span id="modalPenilaianBorangTajuk">-</span></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="guideline-card mb-3">
                    <div class="guideline-card-header" style="margin-bottom: 0;">
                        <span class="guideline-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </span>
                        <span class="guideline-item-text mb-0"><span class="highlight">Skor Pematuhan</span> dipaparkan sebagai rujukan daripada keputusan Langkah 1 (Pematuhan Dokumentasi). Masukkan <span class="highlight">Skor Manual</span> bagi setiap pembekal berdasarkan semakan dokumen.</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-slate align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 110px;">Kod Pembekal</th>
                                <th style="min-width: 160px;">Dokumen</th>
                                <th style="width: 130px;">Status Penyerahan</th>
                                <th style="width: 130px;">Skor Pematuhan</th>
                                <th style="width: 140px;">Skor Manual</th>
                                <th style="min-width: 220px;">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="borangRowsTbody">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Memuatkan...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer teknikal-modal-footer justify-content-end gap-2">
                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="button" class="btn-form btn-form-success" id="btnSimpanBorangAtasTalianTeknikal">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Langkah 2: Spesifikasi Teknikal ─────────────────────────────────
    const STEP2_TENDER_IDENTIFIER = '{{ $tender->uuid }}';
    const STEP2_CSRF_TOKEN = '{{ csrf_token() }}';
    const SPESIFIKASI_ROLLUP_URL_TEMPLATE = '{{ route('penilaianTeknikal.spesifikasiRollup', ['tender' => $tender->id, 'checklistItemUuid' => '__ITEM__']) }}';
    const SPESIFIKASI_DETAIL_URL_TEMPLATE = '{{ route('penilaianTeknikal.spesifikasiDetail', ['tender' => $tender->id, 'checklistItemUuid' => '__ITEM__', 'vendorId' => '__VENDOR__']) }}';
    const SIMPAN_SPESIFIKASI_URL = '{{ route('penilaianTeknikal.simpanSpesifikasi') }}';

    const modalRollup = document.getElementById('modalPenilaianSpesifikasiTeknikal');
    const modalDetail = document.getElementById('modalSenaraiSpesifikasiTeknikal');
    const rollupTbody = document.getElementById('spesifikasiRollupTbody');
    const detailTbody = document.getElementById('spesifikasiDetailTbody');

    let activeItemUuid = null;
    let activeDetailVendorId = null;
    let pendingDetailVendorId = null;
    let reopenRollupAfterDetail = false;

    // The same checklist item also appears in Langkah 1's table, so an unscoped selector would
    // hit that row instead — every lookup here must be scoped to #teknikal-2.
    function updateStep2ItemBadge(itemUuid, itemStatus) {
        if (!itemUuid || !itemStatus) return;

        const pane = document.getElementById('teknikal-2');
        const row = pane ? pane.querySelector('tr[data-item-uuid="' + itemUuid + '"]') : null;
        const badge = row ? row.querySelector('.status-penilaian-badge') : null;
        if (!badge) return;

        badge.textContent = itemStatus.label;
        badge.className = 'badge-status ' + itemStatus.badge_class + ' status-penilaian-badge';

        // Action button also flips Menilai <-> Papar to match the new status.
        const actionBtn = row.querySelector('.btn-spesifikasi-menilai, .btn-borang-menilai');
        if (actionBtn) {
            actionBtn.textContent = itemStatus.label === 'Telah Dinilai' ? 'Papar' : 'Menilai';
        }
    }

    // Read-only once confirmed (or the tender is fully submitted) — Langkah 3's ranking already
    // used these scores, so further edits would make that ranking stale.
    function isStep2Locked() {
        const confirmed = typeof SPESIFIKASI_CONFIRMED !== 'undefined' && SPESIFIKASI_CONFIRMED;
        const submitted = typeof FULLY_SUBMITTED !== 'undefined' && FULLY_SUBMITTED;
        return confirmed || submitted;
    }

    // Row reservation applies only while Langkah 2 is still editable.
    function step2LockingActive() {
        return !isStep2Locked() && EvaluationSession.lockingActive();
    }

    /** Reserving an already-scored row is an update, so the prompt says so. */
    function step2ReserveDialog(evaluatorName) {
        if (!evaluatorName) {
            return {
                title: 'Nilai Pembekal Ini?',
                html: 'Anda akan mula menilai pembekal ini. Ahli jawatankuasa lain tidak akan dapat menilai pembekal yang sama sehingga anda selesai.',
                confirmText: 'Ya, Mula Menilai',
                icon: 'warning',
            };
        }

        return {
            title: 'Pembekal Ini Telah Dinilai',
            html: 'Pembekal ini telah dinilai oleh <strong>' + EvaluationSession.escapeHtml(evaluatorName) +
                '</strong>.<br><br>Adakah anda mahu membukanya semula untuk <strong>mengemas kini</strong> penilaian tersebut?',
            confirmText: 'Ya, Kemas Kini',
            icon: 'warning',
        };
    }

    function borangEvaluatorName(vendorId) {
        return borangTbody
            .querySelector('tr[data-vendor-id="' + vendorId + '"] .pematuhan-lock-note')
            ?.getAttribute('data-evaluator') || null;
    }

    function step2LockNote(state, lock, evaluatorName) {
        if (state === 'mine') {
            return '<span class="lock-note lock-note-mine"><i class="bi bi-unlock-fill"></i>Sedang dinilai oleh anda</span>';
        }
        if (state === 'other') {
            return '<span class="lock-note lock-note-other"><i class="bi bi-lock-fill"></i>Sedang dinilai oleh ' +
                EvaluationSession.escapeHtml(lock ? lock.user_name : 'ahli lain') + '</span>';
        }
        // Free, but already recorded — name who did it, same as Langkah 1.
        if (evaluatorName) {
            return '<span class="lock-note lock-note-done"><i class="bi bi-check-circle-fill"></i>Telah dinilai oleh ' +
                EvaluationSession.escapeHtml(evaluatorName) + '</span>';
        }
        return '';
    }

    // Disables every field + hides Simpan when locked; otherwise labels it Simpan/Kemaskini.
    function applyStep2ModalState(tbodyEl, saveBtnId, hasExisting) {
        const locked = isStep2Locked();

        if (tbodyEl) {
            tbodyEl.querySelectorAll('input, select, textarea').forEach(function(el) {
                el.disabled = locked;
            });
        }

        const saveBtn = saveBtnId ? document.getElementById(saveBtnId) : null;
        if (!saveBtn) return;

        if (locked) {
            saveBtn.style.display = 'none';
            return;
        }
        saveBtn.style.display = '';
        saveBtn.textContent = hasExisting ? 'Kemaskini' : 'Simpan';
    }

    function renderSpesifikasiRollup(rows) {
        rollupTbody.innerHTML = '';

        if (!rows.length) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 5;
            td.className = 'text-center text-muted py-3';
            td.textContent = 'Tiada pembekal disenarai pendek untuk tender ini.';
            tr.appendChild(td);
            rollupTbody.appendChild(tr);
            return;
        }

        rows.forEach(function(row) {
            const tr = document.createElement('tr');
            tr.setAttribute('data-vendor-id', row.vendor_id);

            const tdBil = document.createElement('td');
            tdBil.className = 'text-center';
            tdBil.textContent = row.kod_pembekal || '-';

            const tdAuto = document.createElement('td');
            tdAuto.className = 'text-center';
            tdAuto.textContent = row.skor_automatik;

            const tdManual = document.createElement('td');
            tdManual.className = 'text-center';
            tdManual.textContent = row.skor_manual;

            const tdJumlah = document.createElement('td');
            tdJumlah.className = 'text-center';
            tdJumlah.textContent = row.jumlah_skor;

            const tdTindakan = document.createElement('td');
            tdTindakan.className = 'text-center';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-form btn-form-success btn-spesifikasi-detail';
            btn.setAttribute('data-vendor-id', row.vendor_id);
            btn.textContent = row.is_complete ? 'Papar' : 'Menilai';
            tdTindakan.appendChild(btn);

            const note = document.createElement('div');
            note.className = 'pematuhan-lock-note';
            if (row.evaluator_name) note.setAttribute('data-evaluator', row.evaluator_name);
            tdTindakan.appendChild(note);

            tr.append(tdBil, tdAuto, tdManual, tdJumlah, tdTindakan);
            rollupTbody.appendChild(tr);
        });

        paintRollupLockState();
    }

    function paintRollupLockState() {
        if (!activeItemUuid) return;

        rollupTbody.querySelectorAll('tr[data-vendor-id]').forEach(function(tr) {
            const vendorId = tr.getAttribute('data-vendor-id');
            const btn = tr.querySelector('.btn-spesifikasi-detail');
            const note = tr.querySelector('.pematuhan-lock-note');
            if (!btn || !note) return;

            const evaluatorName = note.getAttribute('data-evaluator');

            if (!step2LockingActive()) {
                btn.disabled = false;
                note.innerHTML = step2LockNote('free', null, evaluatorName);
                return;
            }

            const state = EvaluationSession.lockState(activeItemUuid, vendorId);
            const lock = EvaluationSession.findLock(activeItemUuid, vendorId);

            btn.disabled = (state === 'other');
            note.innerHTML = step2LockNote(state, lock, evaluatorName);
        });
    }

    /** Refetches this item's locks and the latest scores, then repaints the rollup. */
    function refreshRollup() {
        if (!activeItemUuid) return;

        return $.when(
            EvaluationSession.fetchLocks(activeItemUuid),
            $.ajax({ url: SPESIFIKASI_ROLLUP_URL_TEMPLATE.replace('__ITEM__', activeItemUuid), method: 'GET', cache: false })
        ).done(function(lockRes, rollupRes) {
            EvaluationSession.setItemLocks(activeItemUuid, (lockRes[0].data && lockRes[0].data.locks) || []);
            renderSpesifikasiRollup(rollupRes[0].rows || []);
            updateStep2ItemBadge(activeItemUuid, rollupRes[0].item_status);
        });
    }

    function loadSpesifikasiRollup(itemUuid, itemTitle) {
        activeItemUuid = itemUuid;
        const tajukEl = document.getElementById('modalPenilaianSpesifikasiTajuk');
        if (tajukEl) tajukEl.textContent = itemTitle;
        rollupTbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Memuatkan...</td></tr>';

        $.get(SPESIFIKASI_ROLLUP_URL_TEMPLATE.replace('__ITEM__', itemUuid))
            .done(function(data) {
                renderSpesifikasiRollup(data.rows || []);
            })
            .fail(function() {
                rollupTbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-3">Ralat memuatkan senarai pembekal.</td></tr>';
            });
    }

    // Cadangan Petender is the vendor's actual answer, shown read-only, not an input field.
    function buildCadanganCell(row) {
        const td = document.createElement('td');
        const box = document.createElement('div');
        box.className = 'spesifikasi-cadangan-box';
        box.textContent = row.vendor_cadangan || '-';
        td.appendChild(box);
        return td;
    }

    // Blocks non-numeric characters outright — digits, one decimal point, control keys only.
    function restrictToNumericInput(input) {
        input.addEventListener('keydown', function(event) {
            if (event.ctrlKey || event.metaKey || event.altKey) return;
            if (['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes(event.key)) return;
            if (/^[0-9]$/.test(event.key)) return;
            if (event.key === '.' && !input.value.includes('.')) return;
            event.preventDefault();
        });

        input.addEventListener('paste', function(event) {
            const pasted = (event.clipboardData || window.clipboardData).getData('text');
            if (!/^\d*\.?\d*$/.test(pasted)) event.preventDefault();
        });
    }

    // Clamps to [min, max] after the value changes — Skor Manual only, against the real max_score.
    function clampNumericInput(input, min, max) {
        if (input.value === '' || isNaN(parseFloat(input.value))) return;

        let value = parseFloat(input.value);
        if (max !== null && value > max) value = max;
        if (min !== null && value < min) value = min;
        if (String(value) !== input.value) input.value = value;
    }

    // Skor Automatik only: blocks the keystroke and snaps to max the instant it would be exceeded.
    function snapToMaxOnExceed(input, max) {
        if (max === null || max === undefined) return;

        input.addEventListener('keydown', function(event) {
            if (event.ctrlKey || event.metaKey || event.altKey) return;
            if (!/^[0-9]$/.test(event.key)) return;

            const start = input.selectionStart ?? input.value.length;
            const end = input.selectionEnd ?? input.value.length;
            const nextValue = input.value.slice(0, start) + event.key + input.value.slice(end);

            if (!isNaN(parseFloat(nextValue)) && parseFloat(nextValue) > max) {
                event.preventDefault();
                input.value = String(max);
            }
        });

        input.addEventListener('paste', function() {
            setTimeout(function() {
                if (!isNaN(parseFloat(input.value)) && parseFloat(input.value) > max) input.value = String(max);
            }, 0);
        });
    }

    // Skor Automatik input is stored as input_value; the score itself is always recomputed server-side.
    function buildScoreCells(row) {
        const tdAuto = document.createElement('td');
        tdAuto.className = 'text-center';
        const tdManual = document.createElement('td');
        tdManual.className = 'text-center';

        if (row.score_mode === 'auto') {
            const wrap = document.createElement('div');
            wrap.className = 'd-flex flex-column align-items-center gap-1';

            let input;
            if (row.response_type === 'yes_no') {
                input = document.createElement('select');
                input.className = 'form-select form-select-sm spesifikasi-auto-input';
                [['', 'Sila Pilih'], ['yes', 'Ya'], ['no', 'Tidak']].forEach(function(opt) {
                    const option = document.createElement('option');
                    option.value = opt[0];
                    option.textContent = opt[1];
                    if (opt[0] === '') option.disabled = true;
                    if ((row.input_value || '') === opt[0]) option.selected = true;
                    input.appendChild(option);
                });
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.inputMode = 'decimal';
                input.className = 'form-control form-control-sm text-center spesifikasi-auto-input';
                input.style.width = '4.5rem';
                input.style.flex = '0 0 auto';
                input.placeholder = 'Nilai';
                input.value = row.input_value || '';

                const boundMax = (row.input_max !== null && row.input_max !== undefined) ? parseFloat(row.input_max) : null;

                restrictToNumericInput(input);
                snapToMaxOnExceed(input, boundMax);

                const inputRow = document.createElement('div');
                inputRow.className = 'd-flex align-items-center justify-content-center flex-nowrap';
                inputRow.style.gap = '0.35rem';
                inputRow.appendChild(input);

                if (row.input_max !== null && row.input_max !== undefined) {
                    const maxLabel = document.createElement('span');
                    maxLabel.className = 'text-nowrap small text-muted';
                    maxLabel.textContent = '/ ' + row.input_max;
                    inputRow.appendChild(maxLabel);
                }

                wrap.appendChild(inputRow);
                input = null;
            }
            if (input) wrap.appendChild(input);

            const scoreLabel = document.createElement('span');
            scoreLabel.className = 'small text-muted';
            scoreLabel.textContent = 'Skor: ' + ((row.skor_automatik !== null && row.skor_automatik !== undefined) ? row.skor_automatik : '-');
            wrap.appendChild(scoreLabel);

            if (row.matched === false) {
                const warn = document.createElement('div');
                warn.className = 'text-danger small';
                warn.textContent = 'Tiada padanan skema pemarkahan';
                wrap.appendChild(warn);
            }

            tdAuto.appendChild(wrap);
            tdManual.textContent = '-';
        } else {
            tdAuto.textContent = '-';
            const wrap = document.createElement('div');
            wrap.className = 'd-flex align-items-center justify-content-center flex-nowrap';
            wrap.style.gap = '0.35rem';
            const input = document.createElement('input');
            input.type = 'text';
            input.inputMode = 'decimal';
            input.className = 'form-control form-control-sm text-center spesifikasi-skor-manual';
            input.style.width = '4.5rem';
            input.style.flex = '0 0 auto';
            input.value = (row.skor_manual !== null && row.skor_manual !== undefined) ? row.skor_manual : '';

            restrictToNumericInput(input);

            input.addEventListener('input', function() {
                clampNumericInput(input, 0, row.max_score);
            });

            const span = document.createElement('span');
            span.className = 'text-nowrap';
            span.textContent = '/ ' + row.max_score;
            wrap.append(input, span);
            tdManual.appendChild(wrap);
        }

        return [tdAuto, tdManual];
    }

    function renderSpesifikasiDetail(rows) {
        detailTbody.innerHTML = '';

        if (!rows.length) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 7;
            td.className = 'text-center text-muted py-3';
            td.textContent = 'Tiada spesifikasi untuk item ini.';
            tr.appendChild(td);
            detailTbody.appendChild(tr);
            return;
        }

        rows.forEach(function(row) {
            const tr = document.createElement('tr');

            if (row.kind === 'item') {
                tr.className = 'table-light fw-bold';
                const tdTitle = document.createElement('td');
                tdTitle.className = 'text-start';
                tdTitle.textContent = row.title || '-';
                const tdQty = document.createElement('td');
                tdQty.className = 'text-center';
                tdQty.textContent = (row.quantity !== null && row.quantity !== undefined) ? row.quantity : '-';
                const tdUnit = document.createElement('td');
                tdUnit.className = 'text-center';
                tdUnit.textContent = row.unit || '-';
                tr.append(tdTitle, tdQty, tdUnit);
                for (let i = 0; i < 4; i++) tr.appendChild(document.createElement('td'));
                detailTbody.appendChild(tr);
                return;
            }

            tr.setAttribute('data-detail-uuid', row.detail_uuid);
            tr.setAttribute('data-score-mode', row.score_mode);

            const tdDesc = document.createElement('td');
            tdDesc.className = 'text-start ps-4';
            tdDesc.textContent = row.description || '-';
            const tdQty = document.createElement('td');
            tdQty.className = 'text-center text-muted';
            tdQty.textContent = '-';
            const tdUnit = document.createElement('td');
            tdUnit.className = 'text-center text-muted';
            tdUnit.textContent = '-';

            const tdCadangan = buildCadanganCell(row);
            const [tdAuto, tdManual] = buildScoreCells(row);

            const tdCatatan = document.createElement('td');
            const catatanInput = document.createElement('textarea');
            catatanInput.rows = 2;
            catatanInput.className = 'form-control form-control-sm spesifikasi-catatan';
            catatanInput.placeholder = 'Catatan';
            catatanInput.value = row.catatan || '';
            tdCatatan.appendChild(catatanInput);

            tr.append(tdDesc, tdQty, tdUnit, tdCadangan, tdAuto, tdManual, tdCatatan);
            detailTbody.appendChild(tr);
        });
    }

    function openSpesifikasiDetail(itemUuid, vendorId) {
        activeDetailVendorId = vendorId;
        detailTbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">Memuatkan...</td></tr>';

        bootstrap.Modal.getOrCreateInstance(modalDetail).show();

        $.get(SPESIFIKASI_DETAIL_URL_TEMPLATE.replace('__ITEM__', itemUuid).replace('__VENDOR__', vendorId))
            .done(function(data) {
                const rows = data.rows || [];
                renderSpesifikasiDetail(rows);

                // Any detail row with a saved value means Kemaskini instead of Simpan.
                const hasExisting = rows.some(function(row) {
                    if (row.kind !== 'detail') return false;
                    const adaInput = row.input_value !== null && row.input_value !== undefined && row.input_value !== '';
                    const adaManual = row.skor_manual !== null && row.skor_manual !== undefined;
                    return adaInput || adaManual;
                });
                applyStep2ModalState(detailTbody, 'btnSimpanSenaraiSpesifikasiTeknikal', hasExisting);
            })
            .fail(function() {
                detailTbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-3">Ralat memuatkan spesifikasi.</td></tr>';
            });
    }

    if (modalRollup) {
        modalRollup.addEventListener('show.bs.modal', function(ev) {
            const trigger = ev.relatedTarget;
            if (!trigger || !trigger.classList.contains('btn-spesifikasi-menilai')) return;
            const row = trigger.closest('tr[data-item-uuid]');
            const itemUuid = row ? row.getAttribute('data-item-uuid') : '';
            const itemTitle = row ? row.children[1].textContent.trim() : '';
            if (itemUuid) loadSpesifikasiRollup(itemUuid, itemTitle);
        });

        modalRollup.addEventListener('shown.bs.modal', function() {
            if (!step2LockingActive()) return;
            EvaluationSession.startPolling('step2Rollup', refreshRollup, 5000);
        });

        modalRollup.addEventListener('hidden.bs.modal', function() {
            EvaluationSession.stopPolling('step2Rollup');

            if (pendingDetailVendorId !== null) {
                const vendorId = pendingDetailVendorId;
                pendingDetailVendorId = null;
                gateThenOpenDetail(vendorId);
                return;
            }

            // Genuinely closed (not navigating to Modal 2) — re-verify the badge from the server.
            if (!activeItemUuid) return;
            const itemUuidToVerify = activeItemUuid;
            $.ajax({
                url: SPESIFIKASI_ROLLUP_URL_TEMPLATE.replace('__ITEM__', itemUuidToVerify),
                method: 'GET',
                cache: false,
            })
                .done(function(data) {
                    updateStep2ItemBadge(itemUuidToVerify, data.item_status);
                });
        });

        rollupTbody.addEventListener('click', function(ev) {
            const btn = ev.target.closest('.btn-spesifikasi-detail');
            if (!btn || btn.disabled) return;
            pendingDetailVendorId = parseInt(btn.getAttribute('data-vendor-id'), 10);
            bootstrap.Modal.getInstance(modalRollup)?.hide();
        });
    }

    /**
     * Opening a vendor's scores reserves that row, so the click is gated:
     * already mine reopens straight away, someone else's is blocked, free asks first.
     * Runs with the rollup already hidden — Bootstrap allows only one open modal.
     */
    function gateThenOpenDetail(vendorId) {
        if (!step2LockingActive()) {
            openSpesifikasiDetail(activeItemUuid, vendorId);
            return;
        }

        const itemUuid = activeItemUuid;
        const state = EvaluationSession.lockState(itemUuid, vendorId);

        if (state === 'mine') {
            openSpesifikasiDetail(itemUuid, vendorId);
            return;
        }

        const reopenRollup = function() {
            refreshRollup();
            bootstrap.Modal.getOrCreateInstance(modalRollup).show();
        };

        if (state === 'other') {
            const lock = EvaluationSession.findLock(itemUuid, vendorId);
            EvaluationSession.confirmDialog({
                title: 'Sedang Dinilai',
                html: 'Pembekal ini sedang dinilai oleh <strong>' +
                    EvaluationSession.escapeHtml(lock ? lock.user_name : 'ahli lain') +
                    '</strong>. Sila pilih pembekal lain atau tunggu sehingga selesai.',
                icon: 'info',
                confirmText: 'OK',
                showCancel: false,
            }).then(reopenRollup);
            return;
        }

        const itemTitle = document.getElementById('modalPenilaianSpesifikasiTajuk')?.textContent || '';

        // Reopening a vendor a colleague already scored is an update, not a fresh evaluation.
        const evaluatorName = rollupTbody
            .querySelector('tr[data-vendor-id="' + vendorId + '"] .pematuhan-lock-note')
            ?.getAttribute('data-evaluator');

        const dialog = evaluatorName
            ? {
                title: 'Pembekal Ini Telah Dinilai',
                html: 'Spesifikasi bagi pembekal ini telah dinilai oleh <strong>' +
                    EvaluationSession.escapeHtml(evaluatorName) +
                    '</strong>.<br><br>Adakah anda mahu membukanya semula untuk <strong>mengemas kini</strong> penilaian tersebut?',
                confirmText: 'Ya, Kemas Kini',
                icon: 'warning',
            }
            : {
                title: 'Nilai Pembekal Ini?',
                html: 'Anda akan mula menilai spesifikasi bagi pembekal ini. Ahli jawatankuasa lain tidak akan dapat menilai pembekal yang sama sehingga anda selesai.',
                confirmText: 'Ya, Mula Menilai',
                icon: 'warning',
            };

        EvaluationSession.confirmDialog(dialog).then(function(confirmed) {
            if (!confirmed) {
                reopenRollup();
                return;
            }

            EvaluationSession.acquireLock(itemUuid, vendorId, itemTitle)
                .done(function() {
                    EvaluationSession.addLocalLock(itemUuid, vendorId);
                    openSpesifikasiDetail(itemUuid, vendorId);
                })
                .fail(function(xhr) {
                    if (xhr.status === 409) {
                        const holder = xhr.responseJSON?.data?.held_by_name || 'ahli lain';
                        EvaluationSession.confirmDialog({
                            title: 'Pembekal Ini Baru Sahaja Diambil',
                            html: 'Pembekal ini baru sahaja diambil oleh ' + EvaluationSession.escapeHtml(holder) + '. Sila pilih pembekal lain.',
                            icon: 'danger',
                            confirmText: 'OK',
                            showCancel: false,
                        }).then(reopenRollup);
                        return;
                    }
                    showToast('error', xhr.responseJSON?.message || 'Gagal memulakan penilaian pembekal ini.');
                    reopenRollup();
                });
        });
    }

    if (modalDetail) {
        modalDetail.addEventListener('hidden.bs.modal', function() {
            if (reopenRollupAfterDetail) {
                reopenRollupAfterDetail = false;
                bootstrap.Modal.getOrCreateInstance(modalRollup).show();
            }
        });
    }

    const btnSimpanSpesifikasi = document.getElementById('btnSimpanSenaraiSpesifikasiTeknikal');
    if (btnSimpanSpesifikasi) {
        btnSimpanSpesifikasi.addEventListener('click', function() {
            const rows = [];
            let hasError = false;

            detailTbody.querySelectorAll('tr[data-detail-uuid]').forEach(function(tr) {
                const scoreMode = tr.getAttribute('data-score-mode');
                const detailUuid = tr.getAttribute('data-detail-uuid');
                const catatan = tr.querySelector('.spesifikasi-catatan')?.value || '';

                const row = { detail_uuid: detailUuid, catatan: catatan || null };

                if (scoreMode === 'auto') {
                    const autoInput = tr.querySelector('.spesifikasi-auto-input');
                    row.input_value = (autoInput && autoInput.value) ? autoInput.value : null;
                } else {
                    const manualInput = tr.querySelector('.spesifikasi-skor-manual');
                    const rawManual = manualInput ? manualInput.value : '';
                    if (rawManual === '') {
                        manualInput?.classList.add('is-invalid');
                        hasError = true;
                    } else {
                        manualInput?.classList.remove('is-invalid');
                        row.skor_manual = rawManual;
                    }
                }

                rows.push(row);
            });

            if (hasError) {
                showToast('warning', 'Sila lengkapkan Skor Manual bagi setiap baris.');
                return;
            }

            if (!rows.length) return;

            setButtonBusy(btnSimpanSpesifikasi, 'Menyimpan...');

            $.ajax({
                url: SIMPAN_SPESIFIKASI_URL,
                method: 'POST',
                data: {
                    _token: STEP2_CSRF_TOKEN,
                    tender: STEP2_TENDER_IDENTIFIER,
                    vendor_id: activeDetailVendorId,
                    checklist_item_uuid: activeItemUuid,
                    rows: rows,
                },
            }).done(function(res) {
                showToast('success', res.message || 'Penilaian spesifikasi telah disimpan.');

                if (res.rollup) {
                    const btn = rollupTbody.querySelector('.btn-spesifikasi-detail[data-vendor-id="' + res.rollup.vendor_id + '"]');
                    if (btn) {
                        const rowEl = btn.closest('tr');
                        rowEl.children[1].textContent = res.rollup.skor_automatik;
                        rowEl.children[2].textContent = res.rollup.skor_manual;
                        rowEl.children[3].textContent = res.rollup.jumlah_skor;
                        btn.textContent = res.rollup.is_complete ? 'Papar' : 'Menilai';
                    }
                }

                updateStep2ItemBadge(activeItemUuid, res.item_status);

                const finish = function() {
                    reopenRollupAfterDetail = true;
                    bootstrap.Modal.getInstance(modalDetail)?.hide();
                };

                // Finished with this vendor — release the row for the rest of the committee.
                if (step2LockingActive() && EvaluationSession.lockState(activeItemUuid, activeDetailVendorId) === 'mine') {
                    const itemTitle = document.getElementById('modalPenilaianSpesifikasiTajuk')?.textContent || '';
                    EvaluationSession.completeRows(
                        activeItemUuid,
                        [{ vendor_id: activeDetailVendorId }],
                        itemTitle
                    ).always(finish);
                } else {
                    finish();
                }
            }).fail(function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'Ralat semasa menyimpan penilaian spesifikasi.');
            }).always(function() {
                clearButtonBusy(btnSimpanSpesifikasi);
            });
        });
    }

    const btnKembaliSenaraiSpesifikasi = document.getElementById('btnKembaliSenaraiSpesifikasiTeknikal');
    if (btnKembaliSenaraiSpesifikasi) {
        btnKembaliSenaraiSpesifikasi.addEventListener('click', function() {
            reopenRollupAfterDetail = true;
            bootstrap.Modal.getInstance(modalDetail)?.hide();
        });
    }

    // ── Langkah 2: Borang Atas Talian (Senarai Pengalaman Kerja / Kerja Dalam Tangan) ──
    const BORANG_ROWS_URL_TEMPLATE = '{{ route('penilaianTeknikal.borangRows', ['tender' => $tender->id, 'checklistItemUuid' => '__ITEM__']) }}';
    const SIMPAN_BORANG_URL = '{{ route('penilaianTeknikal.simpanBorang') }}';

    const modalBorang = document.getElementById('modalPenilaianBorangAtasTalianTeknikal');
    const borangTbody = document.getElementById('borangRowsTbody');
    let activeBorangItemUuid = null;
    let borangDocViewerReturnModal = null;

    function buildPenyerahanBadge(row) {
        const span = document.createElement('span');
        span.className = 'badge-status ' + row.status_penyerahan_badge;
        span.textContent = row.status_penyerahan;
        return span;
    }

    function buildPematuhanBadge(row) {
        if (row.skor_pematuhan === null) {
            const span = document.createElement('span');
            span.className = 'text-muted small';
            span.textContent = 'Belum Dinilai';
            return span;
        }
        const badge = document.createElement('span');
        badge.className = 'badge-status ' + (row.skor_pematuhan ? 'badge-status-success' : 'badge-status-danger');
        badge.textContent = row.skor_pematuhan ? 'Mematuhi' : 'Tidak Mematuhi';
        return badge;
    }

    /** Merges fresh values into existing rows on a poll tick — never rebuilds DOM and
     *  never touches a row the current user holds, so in-progress typing survives. */
    function updateBorangRowValues(rows) {
        rows.forEach(function(row) {
            const vendorId = String(row.vendor_id);
            if (EvaluationSession.lockState(activeBorangItemUuid, vendorId) === 'mine') return;

            const tr = borangTbody.querySelector('tr[data-vendor-id="' + vendorId + '"]');
            if (!tr) return;

            const tdPenyerahan = tr.querySelector('.borang-status-penyerahan-cell');
            if (tdPenyerahan) {
                tdPenyerahan.innerHTML = '';
                tdPenyerahan.appendChild(buildPenyerahanBadge(row));
            }

            const tdPematuhan = tr.querySelector('.borang-skor-pematuhan-cell');
            if (tdPematuhan) {
                tdPematuhan.innerHTML = '';
                tdPematuhan.appendChild(buildPematuhanBadge(row));
            }

            const skorInput = tr.querySelector('.borang-skor-manual');
            if (skorInput) skorInput.value = (row.skor_manual !== null && row.skor_manual !== undefined) ? row.skor_manual : '';

            const catatanInput = tr.querySelector('.borang-catatan');
            if (catatanInput) catatanInput.value = row.catatan || '';

            const note = tr.querySelector('.pematuhan-lock-note');
            if (note) {
                if (row.evaluator_name) note.setAttribute('data-evaluator', row.evaluator_name);
                else note.removeAttribute('data-evaluator');
            }
        });
    }

    function renderBorangRows(rows) {
        borangTbody.innerHTML = '';

        if (!rows.length) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 6;
            td.className = 'text-center text-muted py-3';
            td.textContent = 'Tiada pembekal disenarai pendek untuk tender ini.';
            tr.appendChild(td);
            borangTbody.appendChild(tr);
            return;
        }

        const tajukEl = document.getElementById('modalPenilaianBorangTajuk');
        const tajuk = tajukEl ? tajukEl.textContent : '';

        rows.forEach(function(row) {
            const tr = document.createElement('tr');
            tr.setAttribute('data-vendor-id', row.vendor_id);

            const tdKod = document.createElement('td');
            tdKod.className = 'text-center';
            tdKod.textContent = row.kod_pembekal || '-';

            const tdDokumen = document.createElement('td');
            tdDokumen.className = 'text-center';
            if (row.doc_mode === 'upload') {
                // Uploaded files route through the same gated doc viewer as the online-form
                // case below — opening one reserves the row, same as every other Lihat link.
                const files = row.files || [];
                if (!files.length) {
                    tdDokumen.textContent = '-';
                } else {
                    const wrap = document.createElement('div');
                    wrap.className = 'd-flex flex-column align-items-center gap-1';
                    files.forEach(function(file) {
                        const link = document.createElement('a');
                        link.href = 'javascript:void(0);';
                        link.className = 'btn-lihat-borang-teknikal2 text-primary text-decoration-none d-inline-flex align-items-center gap-1';
                        link.setAttribute('data-doc-url', file.url || '');
                        link.setAttribute('data-doc-title', (tajuk ? tajuk + ' — ' : '') + (file.name || 'Dokumen'));
                        const icon = document.createElement('i');
                        icon.className = 'bi bi-file-earmark-pdf-fill';
                        icon.setAttribute('aria-hidden', 'true');
                        link.appendChild(icon);
                        link.appendChild(document.createTextNode(' ' + (file.name || 'Dokumen')));
                        wrap.appendChild(link);
                    });
                    tdDokumen.appendChild(wrap);
                }
            } else if (row.doc_url) {
                const link = document.createElement('a');
                link.href = 'javascript:void(0);';
                link.className = 'btn-lihat-borang-teknikal2 text-primary text-decoration-none d-inline-flex align-items-center gap-1';
                link.setAttribute('data-doc-url', row.doc_url);
                link.setAttribute('data-doc-title', (tajuk ? tajuk + ' — ' : '') + 'Pembekal ' + (row.kod_pembekal || ''));
                const icon = document.createElement('i');
                icon.className = 'bi bi-file-earmark-pdf-fill';
                icon.setAttribute('aria-hidden', 'true');
                link.appendChild(icon);
                link.appendChild(document.createTextNode(' Lihat'));
                tdDokumen.appendChild(link);
            } else {
                tdDokumen.textContent = '-';
            }

            // Fallback trigger for vendors with no document at all — every other case
            // reserves the row through its own Lihat link instead.
            const mula = document.createElement('a');
            mula.href = 'javascript:void(0);';
            mula.className = 'borang-mula-menilai text-primary text-decoration-none d-inline-flex align-items-center gap-1 d-none';
            mula.innerHTML = '<i class="bi bi-eye" aria-hidden="true"></i> Mula Menilai';
            tdDokumen.appendChild(mula);

            const tdPenyerahan = document.createElement('td');
            tdPenyerahan.className = 'text-center borang-status-penyerahan-cell';
            tdPenyerahan.appendChild(buildPenyerahanBadge(row));

            const tdPematuhan = document.createElement('td');
            tdPematuhan.className = 'text-center borang-skor-pematuhan-cell';
            tdPematuhan.appendChild(buildPematuhanBadge(row));

            const tdManual = document.createElement('td');
            tdManual.className = 'text-center';
            const wrap = document.createElement('div');
            wrap.className = 'd-flex align-items-center justify-content-center flex-nowrap';
            wrap.style.gap = '0.35rem';
            const input = document.createElement('input');
            input.type = 'text';
            input.inputMode = 'decimal';
            input.className = 'form-control form-control-sm text-center borang-skor-manual';
            input.style.width = '4.5rem';
            input.style.flex = '0 0 auto';
            input.value = (row.skor_manual !== null && row.skor_manual !== undefined) ? row.skor_manual : '';

            restrictToNumericInput(input);

            input.addEventListener('input', function() {
                clampNumericInput(input, 0, row.max_score);
            });

            const span = document.createElement('span');
            span.className = 'text-nowrap';
            span.textContent = '/ ' + row.max_score;
            wrap.append(input, span);
            tdManual.appendChild(wrap);

            const tdCatatan = document.createElement('td');
            const catatanInput = document.createElement('textarea');
            catatanInput.rows = 2;
            catatanInput.className = 'form-control form-control-sm borang-catatan';
            catatanInput.placeholder = 'Catatan';
            catatanInput.value = row.catatan || '';
            tdCatatan.appendChild(catatanInput);

            const note = document.createElement('div');
            note.className = 'pematuhan-lock-note';
            if (row.evaluator_name) note.setAttribute('data-evaluator', row.evaluator_name);
            tdCatatan.appendChild(note);

            tr.append(tdKod, tdDokumen, tdPenyerahan, tdPematuhan, tdManual, tdCatatan);
            borangTbody.appendChild(tr);
        });

        paintBorangLockState();
    }

    function paintBorangLockState() {
        if (!activeBorangItemUuid) return;

        borangTbody.querySelectorAll('tr[data-vendor-id]').forEach(function(tr) {
            const vendorId = tr.getAttribute('data-vendor-id');
            const note = tr.querySelector('.pematuhan-lock-note');
            const skor = tr.querySelector('.borang-skor-manual');
            const catatan = tr.querySelector('.borang-catatan');
            const lihatLinks = tr.querySelectorAll('.btn-lihat-borang-teknikal2');
            const mula = tr.querySelector('.borang-mula-menilai');

            const evaluatorName = note.getAttribute('data-evaluator');

            if (!step2LockingActive()) {
                note.innerHTML = step2LockNote('free', null, evaluatorName);
                if (mula) mula.classList.add('d-none');
                return;
            }

            const state = EvaluationSession.lockState(activeBorangItemUuid, vendorId);
            const lock = EvaluationSession.findLock(activeBorangItemUuid, vendorId);

            note.innerHTML = step2LockNote(state, lock, evaluatorName);
            if (skor) skor.disabled = (state !== 'mine');
            if (catatan) catatan.disabled = (state !== 'mine');
            lihatLinks.forEach(function(l) { l.classList.toggle('disabled', state === 'other'); });
            // Only offered when there is no document link to reserve through.
            if (mula) mula.classList.toggle('d-none', state !== 'free' || lihatLinks.length > 0);
        });
    }

    /** Refetches this item's locks and the latest saved rows, then repaints. */
    function refreshBorang() {
        if (!activeBorangItemUuid) return;

        return $.when(
            EvaluationSession.fetchLocks(activeBorangItemUuid),
            $.ajax({ url: BORANG_ROWS_URL_TEMPLATE.replace('__ITEM__', activeBorangItemUuid), method: 'GET', cache: false })
        ).done(function(lockRes, borangRes) {
            EvaluationSession.setItemLocks(activeBorangItemUuid, (lockRes[0].data && lockRes[0].data.locks) || []);
            updateBorangRowValues(borangRes[0].rows || []);
            paintBorangLockState();
        });
    }

    /** Reserves one borang row, then repaints so its fields unlock. */
    function acquireBorangRow(vendorId) {
        const itemUuid = activeBorangItemUuid;
        const itemTitle = document.getElementById('modalPenilaianBorangTajuk')?.textContent || '';

        return EvaluationSession.acquireLock(itemUuid, vendorId, itemTitle)
            .done(function() {
                EvaluationSession.addLocalLock(itemUuid, vendorId);
                paintBorangLockState();
            })
            .fail(function(xhr) {
                if (xhr.status === 409) {
                    const holder = xhr.responseJSON?.data?.held_by_name || 'ahli lain';
                    showToast('warning', 'Pembekal ini baru sahaja diambil oleh ' + holder + '.');
                    refreshBorang();
                    return;
                }
                showToast('error', xhr.responseJSON?.message || 'Gagal memulakan penilaian pembekal ini.');
            });
    }

    function loadBorangRows(itemUuid, itemTitle) {
        activeBorangItemUuid = itemUuid;
        const tajukEl = document.getElementById('modalPenilaianBorangTajuk');
        if (tajukEl) tajukEl.textContent = itemTitle;
        borangTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Memuatkan...</td></tr>';

        $.get(BORANG_ROWS_URL_TEMPLATE.replace('__ITEM__', itemUuid))
            .done(function(data) {
                const rows = data.rows || [];
                renderBorangRows(rows);

                const hasExisting = rows.some(function(row) {
                    return row.skor_manual !== null && row.skor_manual !== undefined;
                });
                applyStep2ModalState(borangTbody, 'btnSimpanBorangAtasTalianTeknikal', hasExisting);
            })
            .fail(function() {
                borangTbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">Ralat memuatkan senarai pembekal.</td></tr>';
            });
    }

    if (modalBorang) {
        modalBorang.addEventListener('show.bs.modal', function(ev) {
            const trigger = ev.relatedTarget;
            if (!trigger || !trigger.classList.contains('btn-borang-menilai')) return;
            const itemUuid = trigger.getAttribute('data-item-uuid') || '';
            const itemTitle = trigger.getAttribute('data-tajuk') || '';
            if (itemUuid) loadBorangRows(itemUuid, itemTitle);
        });

        modalBorang.addEventListener('shown.bs.modal', function() {
            if (!step2LockingActive()) return;
            EvaluationSession.startPolling('step2Borang', refreshBorang, 5000);
        });

        // Reserving a row has no document to open — it is its own action.
        modalBorang.addEventListener('click', function(ev) {
            const mula = ev.target.closest('.borang-mula-menilai');
            if (!mula) return;

            const vendorId = mula.closest('tr[data-vendor-id]')?.getAttribute('data-vendor-id');
            if (!vendorId || !step2LockingActive()) return;

            const evaluatorName = borangEvaluatorName(vendorId);
            EvaluationSession.stopPolling('step2Borang');

            modalBorang.addEventListener('hidden.bs.modal', function onHidden() {
                modalBorang.removeEventListener('hidden.bs.modal', onHidden);

                EvaluationSession.confirmDialog(step2ReserveDialog(evaluatorName)).then(function(confirmed) {
                    const reopen = function() { bootstrap.Modal.getOrCreateInstance(modalBorang).show(); };
                    if (!confirmed) { reopen(); return; }
                    acquireBorangRow(vendorId).always(reopen);
                });
            }, { once: true });

            bootstrap.Modal.getInstance(modalBorang)?.hide();
        });

        // Also fires when navigating to the doc viewer — harmless, just one extra AJAX call.
        modalBorang.addEventListener('hidden.bs.modal', function() {
            EvaluationSession.stopPolling('step2Borang');
            if (!activeBorangItemUuid) return;
            const itemUuidToVerify = activeBorangItemUuid;
            $.ajax({
                url: BORANG_ROWS_URL_TEMPLATE.replace('__ITEM__', itemUuidToVerify),
                method: 'GET',
                cache: false,
            })
                .done(function(data) {
                    updateStep2ItemBadge(itemUuidToVerify, data.item_status);
                });
        });

        // Reuses #modalViewDokumenTeknikal from teknikal.blade.php, wired separately here
        // since that file's helpers are scoped to its own closure.
        const modalViewDocStep2 = document.getElementById('modalViewDokumenTeknikal');
        const iframeViewDocStep2 = document.getElementById('iframeViewDokumenTeknikal');
        const titleViewDocStep2 = document.getElementById('modalViewDokumenTeknikalLabel');
        const btnKembaliViewDocStep2 = document.getElementById('btnKembaliViewDokumenTeknikal');

        if (modalViewDocStep2) {
            modalBorang.addEventListener('click', function(ev) {
                const link = ev.target.closest('.btn-lihat-borang-teknikal2');
                if (!link || link.classList.contains('disabled')) return;

                const url = link.getAttribute('data-doc-url');
                const docTitle = link.getAttribute('data-doc-title') || 'Dokumen';
                const vendorId = link.closest('tr[data-vendor-id]')?.getAttribute('data-vendor-id');
                const itemUuid = activeBorangItemUuid;

                const openDocViewer = function() {
                    if (titleViewDocStep2) titleViewDocStep2.textContent = docTitle;
                    if (iframeViewDocStep2) {
                        iframeViewDocStep2.style.height = '300px';
                        iframeViewDocStep2.src = url ? url.trim() : 'about:blank';
                    }
                    borangDocViewerReturnModal = modalBorang;
                    bootstrap.Modal.getOrCreateInstance(modalViewDocStep2).show();
                };

                EvaluationSession.stopPolling('step2Borang');

                modalBorang.addEventListener('hidden.bs.modal', function onHidden() {
                    modalBorang.removeEventListener('hidden.bs.modal', onHidden);

                    if (!step2LockingActive() || EvaluationSession.lockState(itemUuid, vendorId) === 'mine') {
                        openDocViewer();
                        return;
                    }

                    // Opening the document reserves the row, same as Langkah 1.
                    EvaluationSession.confirmDialog(step2ReserveDialog(borangEvaluatorName(vendorId))).then(function(confirmed) {
                        if (!confirmed) {
                            bootstrap.Modal.getOrCreateInstance(modalBorang).show();
                            return;
                        }
                        acquireBorangRow(vendorId).done(openDocViewer).fail(function() {
                            bootstrap.Modal.getOrCreateInstance(modalBorang).show();
                        });
                    });
                }, { once: true });

                bootstrap.Modal.getInstance(modalBorang)?.hide();
            });

            if (btnKembaliViewDocStep2) {
                btnKembaliViewDocStep2.addEventListener('click', function() {
                    const returnModal = borangDocViewerReturnModal;
                    borangDocViewerReturnModal = null;

                    if (returnModal) {
                        const reopenReturnModal = function() {
                            modalViewDocStep2.removeEventListener('hidden.bs.modal', reopenReturnModal);
                            bootstrap.Modal.getOrCreateInstance(returnModal).show();
                        };
                        modalViewDocStep2.addEventListener('hidden.bs.modal', reopenReturnModal);
                    }
                    bootstrap.Modal.getInstance(modalViewDocStep2)?.hide();
                });
            }
        }
    }

    const btnSimpanBorang = document.getElementById('btnSimpanBorangAtasTalianTeknikal');
    if (btnSimpanBorang) {
        btnSimpanBorang.addEventListener('click', function() {
            const locking = step2LockingActive();
            const rows = [];
            let hasError = false;

            borangTbody.querySelectorAll('tr[data-vendor-id]').forEach(function(tr) {
                const vendorId = tr.getAttribute('data-vendor-id');

                // Under locking you may only submit rows you hold.
                if (locking && EvaluationSession.lockState(activeBorangItemUuid, vendorId) !== 'mine') return;

                const skorInput = tr.querySelector('.borang-skor-manual');
                const skorManual = skorInput ? skorInput.value : '';
                const catatan = tr.querySelector('.borang-catatan')?.value || '';

                // An empty score would save as "dinilai" with no value — block it here.
                if (skorManual === '') {
                    skorInput?.classList.add('is-invalid');
                    hasError = true;
                } else {
                    skorInput?.classList.remove('is-invalid');
                }

                rows.push({
                    vendor_id: vendorId,
                    skor_manual: skorManual === '' ? null : skorManual,
                    catatan: catatan || null,
                });
            });

            if (!activeBorangItemUuid) return;

            if (hasError) {
                showToast('warning', 'Sila lengkapkan Skor Manual bagi setiap baris.');
                return;
            }

            if (!rows.length) {
                showToast('warning', locking
                    ? 'Tiada penilaian untuk disimpan. Klik Lihat atau Mula Menilai pada pembekal yang ingin dinilai.'
                    : 'Tiada penilaian untuk disimpan.');
                return;
            }

            setButtonBusy(btnSimpanBorang, 'Menyimpan...');

            $.ajax({
                url: SIMPAN_BORANG_URL,
                method: 'POST',
                data: {
                    _token: STEP2_CSRF_TOKEN,
                    tender: STEP2_TENDER_IDENTIFIER,
                    checklist_item_uuid: activeBorangItemUuid,
                    rows: rows,
                },
            }).done(function(res) {
                showToast('success', res.message || 'Penilaian dokumen telah disimpan.');

                updateStep2ItemBadge(activeBorangItemUuid, res.item_status);

                const finish = function() {
                    bootstrap.Modal.getInstance(modalBorang)?.hide();
                };

                if (locking) {
                    const itemTitle = document.getElementById('modalPenilaianBorangTajuk')?.textContent || '';
                    EvaluationSession.completeRows(
                        activeBorangItemUuid,
                        rows.map(function(r) { return { vendor_id: r.vendor_id }; }),
                        itemTitle
                    ).always(finish);
                } else {
                    finish();
                }
            }).fail(function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'Ralat semasa menyimpan penilaian dokumen.');
            }).always(function() {
                clearButtonBusy(btnSimpanBorang);
            });
        });
    }

    // Reloaded every time this tab is shown, not just once, to reflect the latest saves.
    const RUMUSAN_STEP2_URL = '{{ route('penilaianTeknikal.rumusanPenilaianTeknikal', ['tender' => $tender->id]) }}';

    function renderRumusanStep2(data) {
        const layak = data.layak || [];
        const tidakLayak = data.tidak_layak || [];

        const tahapLulusEl = document.getElementById('rumusanStep2TahapLulus');
        if (tahapLulusEl) {
            const pct = data.passing_percentage || 0;
            tahapLulusEl.value = (pct % 1 === 0) ? pct : pct.toFixed(2);
        }

        const melepasiTbody = document.getElementById('rumusanStep2MelepasiTbody');
        if (melepasiTbody) {
            melepasiTbody.innerHTML = '';
            if (!layak.length) {
                melepasiTbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted" style="padding: 18px 16px;">Tiada rekod dijumpai</td></tr>';
            } else {
                layak.forEach(function(row) {
                    const tr = document.createElement('tr');
                    const tdKedudukan = document.createElement('td');
                    tdKedudukan.className = 'text-center';
                    tdKedudukan.textContent = row.kedudukan;
                    const tdBil = document.createElement('td');
                    tdBil.className = 'text-center';
                    tdBil.textContent = row.kod_pembekal || '-';
                    const tdJumlah = document.createElement('td');
                    tdJumlah.textContent = (row.peratus ?? 0) + '%';
                    tr.append(tdKedudukan, tdBil, tdJumlah);
                    melepasiTbody.appendChild(tr);
                });
            }
        }

        const melepasiTotal = document.getElementById('rumusanStep2MelepasiTotal');
        if (melepasiTotal) melepasiTotal.textContent = layak.length;

        const tidakMelepasiTbody = document.getElementById('rumusanStep2TidakMelepasiTbody');
        if (tidakMelepasiTbody) {
            tidakMelepasiTbody.innerHTML = '';
            if (!tidakLayak.length) {
                tidakMelepasiTbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted" style="padding: 18px 16px;">Tiada rekod dijumpai</td></tr>';
            } else {
                tidakLayak.forEach(function(row) {
                    const tr = document.createElement('tr');
                    const tdBil = document.createElement('td');
                    tdBil.className = 'text-center';
                    tdBil.textContent = row.kod_pembekal || '-';
                    const tdJumlah = document.createElement('td');
                    tdJumlah.textContent = (row.peratus ?? 0) + '%';
                    tr.append(tdBil, tdJumlah);
                    tidakMelepasiTbody.appendChild(tr);
                });
            }
        }

        const tidakMelepasiTotal = document.getElementById('rumusanStep2TidakMelepasiTotal');
        if (tidakMelepasiTotal) tidakMelepasiTotal.textContent = tidakLayak.length;
    }

    const rumusanStep2TabLink = document.querySelector('a[href="#rumusan-2"]');
    if (rumusanStep2TabLink) {
        rumusanStep2TabLink.addEventListener('shown.bs.tab', function() {
            $.get(RUMUSAN_STEP2_URL)
                .done(renderRumusanStep2)
                .fail(function() {
                    showToast('error', 'Ralat memuatkan rumusan penilaian teknikal.');
                });
        });
    }

    document.querySelectorAll('#penilaian .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            showOuterStep('pematuhan-tab');
        });
    });

    // Uses the Tab API directly rather than .click() — the reliable pattern here.
    const btnKembaliRumusanStep2 = document.getElementById('btnKembaliRumusanStep2');
    if (btnKembaliRumusanStep2) {
        btnKembaliRumusanStep2.addEventListener('click', function() {
            const teknikalTab = document.querySelector('a[data-bs-toggle="tab"][href="#teknikal-2"]');
            if (teknikalTab) bootstrap.Tab.getOrCreateInstance(teknikalTab).show();
        });
    }

});
</script>
@endpush
