{{-- Step 3: Penyediaan Laporan (PENILAIAN PERINGKAT PERTAMA & KEDUA) --}}

<style>
    /* LAPORAN (Step 3) — each stage is a card with a numbered index header, not a color strip. */
    .laporan-section {
        border: 1px solid #e6e8ec;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-top: 40px;
        margin-bottom: 8px;
    }

    .laporan-section--first {
        margin-top: 4px;
    }

    .laporan-phase {
        display: flex;
        align-items: center;
        gap: 22px;
        padding: 20px 26px;
        border-bottom: 1px solid #eef0f3;
    }

    .laporan-phase-index {
        display: flex;
        flex-direction: column;
        line-height: 1;
        flex-shrink: 0;
    }

    .laporan-phase-index .lbl {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
    }

    .laporan-phase-index .num {
        font-size: 2.15rem;
        font-weight: 800;
        color: #0f172a;
        font-variant-numeric: tabular-nums;
        letter-spacing: -1.5px;
    }

    /* Named .glyph, not .mark, to avoid colliding with Bootstrap's .mark utility. */
    .laporan-phase-index .glyph {
        display: inline-flex;
        align-items: center;
        height: 2.15rem;
        color: #0f172a;
    }

    .laporan-phase-rule {
        align-self: stretch;
        width: 1px;
        background: #e6e8ec;
        margin: 2px 0;
    }

    .laporan-phase-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.3px;
        line-height: 1.25;
    }

    .laporan-phase-sub {
        font-size: 0.76rem;
        color: #64748b;
        margin-top: 3px;
    }

    .laporan-section-body {
        padding: 24px 26px;
    }

    .laporan-section-body > .mb-4:last-child {
        margin-bottom: 0 !important;
    }

    .laporan-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        margin-bottom: 6px;
    }

    .laporan-textarea {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        padding: 10px 14px;
        resize: vertical;
    }

    .laporan-textarea:focus {
        border-color: #c41e3a;
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
    }

    /* contenteditable version of .laporan-textarea — needs its own min-height since a div
       has no "rows" attribute, and disabled-look styling since contenteditable ignores :disabled. */
    .laporan-richtext {
        min-height: 52px;
        overflow-y: auto;
    }

    .laporan-richtext[contenteditable="false"] {
        background-color: #e9ecef;
        opacity: 1;
    }

    .laporan-aras-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding: 16px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 100%);
    }

    .laporan-aras-info .laporan-label {
        margin-bottom: 4px;
    }

    .laporan-aras-hint {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    .laporan-aras-input {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1.5px solid #bfdbfe;
        border-radius: 10px;
        padding: 6px 14px;
        box-shadow: 0 1px 3px rgba(29, 78, 216, 0.1);
    }

    .laporan-aras-input input {
        width: 64px;
        border: none;
        outline: none;
        background: transparent;
        text-align: center;
        font-weight: 800;
        font-size: 1.35rem;
        color: #1d4ed8;
        padding: 0;
    }

    /* Hides the number spinner arrows. */
    .laporan-aras-input input::-webkit-outer-spin-button,
    .laporan-aras-input input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .laporan-aras-input input[type=number] {
        -moz-appearance: textfield;
    }

    .laporan-aras-input .pct {
        font-weight: 800;
        font-size: 1.15rem;
        color: #1d4ed8;
    }

    /* PENGESYORAN — flows as paragraphs, not stacked cards. Justifications auto-number (i, ii, iii). */
    .pengesyoran-intro {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.9rem;
        line-height: 1.6;
        padding: 12px 14px;
        resize: vertical;
    }

    .pengesyoran-intro:focus {
        border-color: var(--sg-red, #c41e3a);
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
    }

    .pengesyoran-list {
        list-style: none;
        counter-reset: justifikasi;
        margin: 0;
        padding: 0;
    }

    .pengesyoran-item {
        counter-increment: justifikasi;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding-left: 40px;
        margin-top: 12px;
    }

    .pengesyoran-item::before {
        content: counter(justifikasi, lower-roman) ".";
        flex-shrink: 0;
        width: 26px;
        margin-left: -34px;
        padding-top: 10px;
        text-align: right;
        font-size: 0.85rem;
        font-weight: 700;
        color: #94a3b8;
        font-variant-numeric: tabular-nums;
    }

    .pengesyoran-item textarea {
        flex: 1;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        line-height: 1.55;
        padding: 10px 14px;
        resize: vertical;
    }

    .pengesyoran-item textarea:focus {
        border-color: var(--sg-red, #c41e3a);
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1);
    }

    .btn-hapus-justifikasi {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        margin-top: 2px;
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

    .btn-hapus-justifikasi:hover {
        border-color: #dc2626;
        background: #dc2626;
        color: #fff;
    }

    /* One block per failed checklist item; catatan sits under its own reason, not run into the sentence. */
    .rumusan-reason-item + .rumusan-reason-item {
        margin-top: 8px;
    }

    .rumusan-reason-catatan {
        font-size: 0.78rem;
        font-style: italic;
        color: #64748b;
        margin-top: 2px;
    }
</style>

<div id="step3-main">

    {{-- Tajuk langkah --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="rumusan-icon" style="background: #fdecef;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="#c41e3a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </span>
        <div>
            <div class="rumusan-heading-title">Penyediaan Laporan</div>
            <div class="rumusan-heading-sub">Semak keputusan kedua-dua peringkat penilaian dan sediakan justifikasi untuk laporan.</div>
        </div>
    </div>

    @if (!($fullySubmitted ?? false))
        <div class="pengerusi-only-note d-none" id="pengerusiOnlyNoteStep3">
            <i class="bi bi-info-circle-fill"></i>
            <span>Laporan ini hanya boleh dikemaskini dan dihantar oleh <strong>Pengerusi Jawatankuasa</strong>. Ahli lain boleh menyemak kandungan di halaman ini.</span>
        </div>
    @endif

    {{-- PERINGKAT PERTAMA — Pematuhan Dokumentasi --}}
    <div class="laporan-section laporan-section--first">
        <div class="laporan-phase">
            <div class="laporan-phase-index">
                <span class="lbl">Peringkat</span>
                <span class="num">1</span>
            </div>
            <div class="laporan-phase-rule"></div>
            <div>
                <div class="laporan-phase-title">Pematuhan Dokumentasi</div>
                <div class="laporan-phase-sub">Semakan kelengkapan dan pematuhan dokumen yang dikemukakan pembekal.</div>
            </div>
        </div>

        <div class="laporan-section-body">

    {{-- Melepasi dokumentasi --}}
    <div class="mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="rumusan-icon" style="background: #dcfce7;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </span>
            <div>
                <div class="rumusan-heading-title">Pembekal Melepasi Pematuhan Dokumentasi</div>
                <div class="rumusan-heading-sub">Senarai pembekal yang lulus semakan pematuhan dokumentasi.</div>
            </div>
        </div>
        <table class="table table-bordered table-slate align-middle">
            <thead>
                <tr>
                    <th style="width: 130px;">BIL</th>
                    <th>ULASAN</th>
                </tr>
            </thead>
            <tbody id="laporan3PematuhanMelepasiTbody">
                <tr>
                    <td colspan="2" class="text-center text-muted">Memuatkan...</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Tidak melepasi dokumentasi --}}
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
                <div class="rumusan-heading-title">Pembekal Tidak Melepasi Pematuhan Dokumentasi</div>
                <div class="rumusan-heading-sub">Senarai pembekal yang gagal semakan pematuhan dokumentasi.</div>
            </div>
        </div>
        <table class="table table-bordered table-slate align-middle">
            <thead>
                <tr>
                    <th style="width: 130px;">BIL</th>
                    <th>ULASAN</th>
                </tr>
            </thead>
            <tbody id="laporan3PematuhanTidakMelepasiTbody">
                <tr>
                    <td colspan="2" class="text-center text-muted">Memuatkan...</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3">
            <label class="laporan-label">Catatan Laporan</label>
            <div id="laporan3CatatanPematuhan" class="form-control laporan-textarea laporan-richtext" contenteditable="true"></div>
        </div>
    </div>

        </div> {{-- /.laporan-section-body --}}
    </div> {{-- /.laporan-section (Peringkat Pertama) --}}

    {{-- PERINGKAT KEDUA — Pematuhan Spesifikasi Teknikal --}}
    <div class="laporan-section">
        <div class="laporan-phase">
            <div class="laporan-phase-index">
                <span class="lbl">Peringkat</span>
                <span class="num">2</span>
            </div>
            <div class="laporan-phase-rule"></div>
            <div>
                <div class="laporan-phase-title">Pematuhan Spesifikasi Teknikal</div>
                <div class="laporan-phase-sub">Pemarkahan pematuhan spesifikasi teknikal berbanding penanda aras lulus.</div>
            </div>
        </div>

        <div class="laporan-section-body">

    {{-- Melepasi spesifikasi --}}
    <div class="mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="rumusan-icon" style="background: #dcfce7;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </span>
            <div>
                <div class="rumusan-heading-title">Pembekal Melepasi Spesifikasi Teknikal</div>
                <div class="rumusan-heading-sub">Senarai pembekal yang mencapai penanda aras tahap lulus.</div>
            </div>
        </div>
        <table class="table table-bordered table-slate align-middle">
            <thead>
                <tr>
                    <th style="width: 130px;">KEDUDUKAN</th>
                    <th style="width: 130px;">BIL</th>
                    <th>MARKAH TEKNIKAL (%)</th>
                </tr>
            </thead>
            <tbody id="laporan3SpesifikasiMelepasiTbody">
                <tr>
                    <td colspan="3" class="text-center text-muted">Memuatkan...</td>
                </tr>
            </tbody>
        </table>

        <div class="laporan-aras-card mt-3">
            <div class="laporan-aras-info">
                <label class="laporan-label mb-0">Penetapan Penanda Aras Tahap Lulus</label>
                <div class="laporan-aras-hint">Skor minimum yang perlu dicapai pembekal untuk lulus peringkat spesifikasi teknikal.</div>
            </div>
            <div class="laporan-aras-input">
                <input type="text" id="laporan3TahapLulus" value="0" readonly aria-label="Penanda aras tahap lulus">
                <span class="pct">%</span>
            </div>
        </div>
    </div>

    {{-- Tidak melepasi spesifikasi --}}
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
                <div class="rumusan-heading-title">Pembekal Tidak Melepasi Spesifikasi Teknikal</div>
                <div class="rumusan-heading-sub">Senarai pembekal yang tidak mencapai penanda aras tahap lulus.</div>
            </div>
        </div>
        <table class="table table-bordered table-slate align-middle">
            <thead>
                <tr>
                    <th style="width: 130px;">BIL</th>
                    <th>MARKAH TEKNIKAL (%)</th>
                </tr>
            </thead>
            <tbody id="laporan3SpesifikasiTidakMelepasiTbody">
                <tr>
                    <td colspan="2" class="text-center text-muted">Memuatkan...</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3">
            <label class="laporan-label">Catatan Laporan</label>
            <div id="laporan3CatatanSpesifikasi" class="form-control laporan-textarea laporan-richtext" contenteditable="true"></div>
        </div>
    </div>

        </div> {{-- /.laporan-section-body --}}
    </div> {{-- /.laporan-section (Peringkat Kedua) --}}

    {{-- PENGESYORAN — the report's conclusion, not a numbered stage. --}}
    <div class="laporan-section" id="pengesyoran-section">
        <div class="laporan-phase">
            <div class="laporan-phase-index">
                <span class="lbl">Keputusan</span>
                <span class="glyph">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="4" y1="12" x2="18" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </span>
            </div>
            <div class="laporan-phase-rule"></div>
            <div>
                <div class="laporan-phase-title">Pengesyoran</div>
                <div class="laporan-phase-sub">Cadangan pembekal untuk dibawa ke mesyuarat Jawatankuasa Sebut Harga.</div>
            </div>
        </div>

        <div class="laporan-section-body">
            <label class="laporan-label">Justifikasi Pengesyoran</label>

            {{-- Baris pembuka — rata kiri, tanpa indentasi --}}
            <div id="laporan3PengesyoranIntro" class="form-control pengesyoran-intro laporan-richtext" contenteditable="true"></div>

            {{-- Justifikasi tambahan — berindentasi & bernombor auto (i, ii, iii …) --}}
            <ol id="pengesyoran-list" class="pengesyoran-list"></ol>

            <div class="d-flex justify-content-end mt-3">
                <button id="btnTambahPengesyoran" class="btn-form btn-form-success">Tambah</button>
            </div>
        </div>
    </div>

    {{-- "Sebelumnya" (not "Kembali") — Langkah 3 has no sub-tabs, this goes back a full step. --}}
    <div class="d-flex justify-content-between align-items-center">
        <button type="button" class="btn-form btn-form-secondary" id="btnSebelumnyaStep3">Sebelumnya</button>
        <div class="d-flex gap-2">
            <button type="button" class="btn-form btn-form-secondary" onclick="cetakLaporanTeknikal()">Laporan</button>
            @if ($fullySubmitted ?? false)
                <a href="{{ route('penilaianTeknikal') }}" class="btn-form btn-form-primary">Kembali ke Senarai</a>
            @else
                <button type="button" class="btn-form btn-form-primary" id="btnSimpanDrafLaporan">Simpan Draf</button>
                <button id="btnStep3Hantar" class="btn-form btn-form-success">Hantar</button>
            @endif
        </div>
    </div>

</div> {{-- /#step3-main --}}

{{-- Confirms before submitting — eliminates failing vendors, irreversible. --}}
<div class="modal fade" id="modalKonfirmasiHantarLaporan" tabindex="-1" aria-labelledby="modalKonfirmasiHantarLaporanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-confirm">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pt-4 pb-2 px-4">
                <div class="modal-confirm-center">
                    <div class="modal-confirm-icon modal-confirm-icon--warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <h5 class="modal-confirm-title" id="modalKonfirmasiHantarLaporanLabel">Sahkan Penghantaran</h5>
                    <p class="modal-confirm-desc">
                        Pembekal yang tidak mencapai penanda aras tahap lulus spesifikasi teknikal akan disingkirkan, dan tender akan diteruskan ke Penilaian Kewangan. Tindakan ini tidak boleh dibuat asal.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-center gap-2">
                <button type="button" class="btn-form btn-form-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-form btn-form-primary" id="btnKonfirmasiHantarLaporan">Ya, Hantar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnHantar = document.getElementById('btnStep3Hantar');
        const btnTambahPengesyoran = document.getElementById('btnTambahPengesyoran');
        const pengesyoranList = document.getElementById('pengesyoran-list');

        // Peringkat 1 & 2 previews reuse Langkah 1/2's own rumusan endpoints, reloaded every
        // time this tab is shown so it always reflects the latest saves.
        const STEP3_TENDER_IDENTIFIER = '{{ $tender->uuid }}';
        const STEP3_TENDER_NAME = @json($tender->name ?? '-');
        const STEP3_NO_TENDER = @json($tender->no_tender ?? '-');
        const STEP3_CSRF_TOKEN = '{{ csrf_token() }}';
        const RUMUSAN_PEMATUHAN_URL_STEP3 = '{{ route('penilaianTeknikal.rumusanPematuhan', ['tender' => $tender->id]) }}';
        const RUMUSAN_SPESIFIKASI_URL_STEP3 = '{{ route('penilaianTeknikal.rumusanPenilaianTeknikal', ['tender' => $tender->id]) }}';
        const LAPORAN_URL_STEP3 = '{{ route('penilaianTeknikal.laporanPenilaianTeknikal', ['tender' => $tender->id]) }}';
        const HANTAR_URL_STEP3 = '{{ route('penilaianTeknikal.hantar') }}';
        const SIMPAN_DRAF_LAPORAN_URL = '{{ route('penilaianTeknikal.simpanDrafLaporan') }}';
        const PENILAIAN_TEKNIKAL_LIST_URL = '{{ route('penilaianTeknikal') }}';

        // Accepts an initial value so saved records can rebuild rows, not just blank "Tambah" clicks.
        function buildJustifikasiItem(initialValue) {
            const li = document.createElement('li');
            li.className = 'pengesyoran-item';

            const textarea = document.createElement('textarea');
            textarea.className = 'form-control';
            textarea.rows = 2;
            textarea.placeholder = 'Nyatakan justifikasi pengesyoran…';
            textarea.value = initialValue || '';

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-hapus-justifikasi';
            removeBtn.title = 'Hapus justifikasi';
            removeBtn.setAttribute('aria-label', 'Hapus justifikasi');
            removeBtn.innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';

            li.appendChild(textarea);
            li.appendChild(removeBtn);
            return li;
        }

        // "Enam (6)" — covers realistic vendor-count ranges (1-99); falls back to the bare
        // numeral past that rather than guessing at bigger Malay number words.
        function bilanganMalay(n) {
            const ones = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Lapan', 'Sembilan'];
            let words;
            if (n === 0) {
                words = 'Sifar';
            } else if (n === 10) {
                words = 'Sepuluh';
            } else if (n === 11) {
                words = 'Sebelas';
            } else if (n < 10) {
                words = ones[n];
            } else if (n < 20) {
                words = ones[n - 10] + ' Belas';
            } else if (n < 100) {
                words = ones[Math.floor(n / 10)] + ' Puluh' + (n % 10 ? ' ' + ones[n % 10] : '');
            } else {
                words = String(n);
            }
            return words + ' (' + n + ')';
        }

        // "<strong>petender bil. 1/6, 2/6,</strong> dan <strong>5/6</strong>" — bold wraps the
        // label + all-but-last (comma-separated), " dan " stays plain, last one gets its own bold.
        function formatPetenderBoldList(items) {
            if (!items.length) return '';
            if (items.length === 1) {
                return '<strong>petender bil. ' + items[0] + '</strong>';
            }
            const allButLast = items.slice(0, -1).join(', ');
            const last = items[items.length - 1];
            return '<strong>petender bil. ' + allButLast + ',</strong> dan <strong>' + last + '</strong>';
        }

        function buildPengesyoranIntroText(topVendorKod) {
            const kod = topVendorKod || 'XX';
            return 'Dengan ini, JPT mengesyorkan petender bil. <strong>' + kod + ' LAYAK DIPERTIMBANGKAN</strong> untuk melaksanakan <strong>'
                + STEP3_TENDER_NAME + ' - No. Tender: ' + STEP3_NO_TENDER + ',</strong> berdasarkan kepada justifikasi seperti berikut:';
        }

        function renderLaporanPematuhan(data) {
            const layak = data.layak || [];
            const tidakLayak = data.tidak_layak || [];

            const melepasiTbody = document.getElementById('laporan3PematuhanMelepasiTbody');
            if (melepasiTbody) {
                melepasiTbody.innerHTML = '';
                if (!layak.length) {
                    melepasiTbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tiada rekod dijumpai</td></tr>';
                } else {
                    layak.forEach(function(row) {
                        const tr = document.createElement('tr');
                        const tdBil = document.createElement('td');
                        tdBil.className = 'text-center';
                        tdBil.textContent = row.kod_pembekal || '-';
                        const tdUlasan = document.createElement('td');
                        tdUlasan.textContent = row.ulasan || '-';
                        tr.append(tdBil, tdUlasan);
                        melepasiTbody.appendChild(tr);
                    });
                }
            }

            const tidakMelepasiTbody = document.getElementById('laporan3PematuhanTidakMelepasiTbody');
            if (tidakMelepasiTbody) {
                tidakMelepasiTbody.innerHTML = '';
                if (!tidakLayak.length) {
                    tidakMelepasiTbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tiada rekod dijumpai</td></tr>';
                } else {
                    tidakLayak.forEach(function(row) {
                        const tr = document.createElement('tr');
                        const tdBil = document.createElement('td');
                        tdBil.className = 'text-center';
                        tdBil.textContent = row.kod_pembekal || '-';
                        const tdUlasan = document.createElement('td');
                        (row.reasons || []).forEach(function(reason) {
                            const item = document.createElement('div');
                            item.className = 'rumusan-reason-item';
                            const text = document.createElement('div');
                            text.textContent = reason.text || '-';
                            item.appendChild(text);
                            if (reason.catatan) {
                                const catatan = document.createElement('div');
                                catatan.className = 'rumusan-reason-catatan';
                                catatan.textContent = 'Catatan: ' + reason.catatan;
                                item.appendChild(catatan);
                            }
                            tdUlasan.appendChild(item);
                        });
                        tr.append(tdBil, tdUlasan);
                        tidakMelepasiTbody.appendChild(tr);
                    });
                }
            }

            const elPematuhan = document.getElementById('laporan3CatatanPematuhan');
            if (elPematuhan) {
                const petenderList = layak.map(function(row) { return row.kod_pembekal || '-'; });
                elPematuhan.innerHTML = 'Sehubungan dengan itu, JPT telah menyenaraikan ' + bilanganMalay(layak.length)
                    + ' petender yang telah <strong>lulus</strong> kerana memenuhi semua syarat Penilaian Peringkat Pertama iaitu '
                    + formatPetenderBoldList(petenderList) + ' layak untuk ke peringkat seterusnya.';
            }
        }

        function renderLaporanSpesifikasi(data) {
            const layak = data.layak || [];
            const tidakLayak = data.tidak_layak || [];

            const tahapLulusEl = document.getElementById('laporan3TahapLulus');
            if (tahapLulusEl) {
                const pct = data.passing_percentage || 0;
                tahapLulusEl.value = (pct % 1 === 0) ? pct : pct.toFixed(2);
            }

            const melepasiTbody = document.getElementById('laporan3SpesifikasiMelepasiTbody');
            if (melepasiTbody) {
                melepasiTbody.innerHTML = '';
                if (!layak.length) {
                    melepasiTbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tiada rekod dijumpai</td></tr>';
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

            const tidakMelepasiTbody = document.getElementById('laporan3SpesifikasiTidakMelepasiTbody');
            if (tidakMelepasiTbody) {
                tidakMelepasiTbody.innerHTML = '';
                if (!tidakLayak.length) {
                    tidakMelepasiTbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tiada rekod dijumpai</td></tr>';
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

            const elSpesifikasi = document.getElementById('laporan3CatatanSpesifikasi');
            if (elSpesifikasi) {
                const petenderList = layak.map(function(row) { return row.kod_pembekal || '-'; });
                const pct = data.passing_percentage || 0;
                const pctText = (pct % 1 === 0) ? pct : pct.toFixed(2);
                elSpesifikasi.innerHTML = 'Sehubungan dengan itu, JPT telah menyenaraikan ' + bilanganMalay(layak.length)
                    + ' petender yang telah <strong>lulus</strong> kerana memenuhi semua syarat Penilaian Peringkat Kedua dan melebihi markah lulus '
                    + pctText + '% iaitu ' + formatPetenderBoldList(petenderList) + ' yang layak untuk ke peringkat pengesyoran.';
            }

            const elIntro = document.getElementById('laporan3PengesyoranIntro');
            if (elIntro) {
                const topVendorKod = layak.length ? layak[0].kod_pembekal : null;
                elIntro.innerHTML = buildPengesyoranIntroText(topVendorKod);
            }
        }

        // Only overwrites the predefined default when a real saved draft exists.
        function renderLaporanDraft(data) {
            if (!data) return;

            const elPematuhan = document.getElementById('laporan3CatatanPematuhan');
            if (elPematuhan && filled(data.catatan_pematuhan)) elPematuhan.innerHTML = data.catatan_pematuhan;

            const elSpesifikasi = document.getElementById('laporan3CatatanSpesifikasi');
            if (elSpesifikasi && filled(data.catatan_spesifikasi)) elSpesifikasi.innerHTML = data.catatan_spesifikasi;

            const elIntro = document.getElementById('laporan3PengesyoranIntro');
            if (elIntro && filled(data.pengesyoran_intro)) elIntro.innerHTML = data.pengesyoran_intro;

            const justifikasi = data.pengesyoran_justifikasi || [];
            if (pengesyoranList && justifikasi.length) {
                pengesyoranList.innerHTML = '';
                justifikasi.forEach(function(text) {
                    pengesyoranList.appendChild(buildJustifikasiItem(text));
                });
            }
        }

        function filled(value) {
            return typeof value === 'string' && value.trim() !== '';
        }

        // Read-only once submitted — hantar() already eliminated vendors and recorded the winner.
        function applyStep3Lock() {
            if (typeof FULLY_SUBMITTED === 'undefined' || !FULLY_SUBMITTED) return;

            ['laporan3CatatanPematuhan', 'laporan3CatatanSpesifikasi', 'laporan3PengesyoranIntro'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) el.contentEditable = 'false';
            });

            if (pengesyoranList) {
                pengesyoranList.querySelectorAll('textarea').forEach(function(el) {
                    el.disabled = true;
                });
                pengesyoranList.querySelectorAll('.btn-hapus-justifikasi').forEach(function(el) {
                    el.style.display = 'none';
                });
            }

            [btnTambahPengesyoran, document.getElementById('btnSimpanDrafLaporan'), btnHantar].forEach(function(el) {
                if (el) el.style.display = 'none';
            });
        }

        // Only the Pengerusi may draft or submit this report; everyone else views it
        // read-only. Route middleware enforces the same rule server-side. Exposed on
        // window so teknikal.blade.php's EvaluationSession.start() callback can call it
        // once the session (and can_submit) is actually known — this script's own
        // DOMContentLoaded runs before that, so it can't check EvaluationSession here.
        window.applyStep3PerananRestrictions = function() {
            if (typeof EvaluationSession === 'undefined' || EvaluationSession.state().can_submit) return;

            const note = document.getElementById('pengerusiOnlyNoteStep3');
            if (note) note.classList.remove('d-none');

            ['laporan3CatatanPematuhan', 'laporan3CatatanSpesifikasi', 'laporan3PengesyoranIntro'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) el.contentEditable = 'false';
            });

            if (pengesyoranList) {
                pengesyoranList.querySelectorAll('textarea').forEach(function(el) {
                    el.disabled = true;
                });
                pengesyoranList.querySelectorAll('.btn-hapus-justifikasi').forEach(function(el) {
                    el.style.display = 'none';
                });
            }

            [btnTambahPengesyoran, document.getElementById('btnSimpanDrafLaporan'), btnHantar].forEach(function(el) {
                if (el) el.style.display = 'none';
            });
        };

        // Only Pengerusi can ever change this draft, so it's always safe to overwrite a
        // non-Pengerusi viewer's (read-only) display with the latest save. Skipped entirely
        // for Pengerusi — refreshing under them mid-edit would wipe unsaved typing.
        function refreshLaporanContent() {
            if (typeof EvaluationSession === 'undefined' || EvaluationSession.state().can_submit) {
                return $.Deferred().resolve().promise();
            }

            return $.get(LAPORAN_URL_STEP3).done(function(data) {
                renderLaporanDraft(data);
            });
        }

        const laporanTabLink = document.getElementById('laporan-tab');
        if (laporanTabLink) {
            laporanTabLink.addEventListener('shown.bs.tab', function() {
                const canSubmit = typeof EvaluationSession !== 'undefined' && EvaluationSession.state().can_submit;

                const finish = function() {
                    applyStep3Lock();
                    if (typeof EvaluationSession !== 'undefined') {
                        EvaluationSession.startPolling('step3Laporan', refreshLaporanContent, 10000);
                    }
                };

                // Strictly serial: each call proxies to STOS, holding a php worker at both ends.
                $.get(RUMUSAN_PEMATUHAN_URL_STEP3).done(renderLaporanPematuhan).always(function() {
                    $.get(RUMUSAN_SPESIFIKASI_URL_STEP3).done(renderLaporanSpesifikasi).always(function() {
                        if (!canSubmit) return finish();
                        $.get(LAPORAN_URL_STEP3).done(renderLaporanDraft).always(finish);
                    });
                });
            });

            laporanTabLink.addEventListener('hidden.bs.tab', function() {
                if (typeof EvaluationSession !== 'undefined') EvaluationSession.stopPolling('step3Laporan');
            });
        }

        applyStep3Lock();

        // Irreversible (eliminates vendors, ends the process) — confirm via modal.
        const modalKonfirmasiHantarLaporan = document.getElementById('modalKonfirmasiHantarLaporan');
        if (btnHantar && modalKonfirmasiHantarLaporan) {
            btnHantar.addEventListener('click', function(e) {
                e.preventDefault();
                bootstrap.Modal.getOrCreateInstance(modalKonfirmasiHantarLaporan).show();
            });
        }

        // Shared by Simpan Draf and Hantar so both always send the same payload shape.
        function collectLaporanPayload() {
            const justifikasi = Array.from(pengesyoranList ? pengesyoranList.querySelectorAll('textarea') : [])
                .map(function(el) { return el.value; })
                .filter(function(text) { return filled(text); });

            return {
                _token: STEP3_CSRF_TOKEN,
                tender: STEP3_TENDER_IDENTIFIER,
                catatan_pematuhan: document.getElementById('laporan3CatatanPematuhan')?.innerHTML || '',
                catatan_spesifikasi: document.getElementById('laporan3CatatanSpesifikasi')?.innerHTML || '',
                pengesyoran_intro: document.getElementById('laporan3PengesyoranIntro')?.innerHTML || '',
                pengesyoran_justifikasi: justifikasi,
            };
        }

        // The printed report reads its text from the saved draft, so cetakLaporanTeknikal()
        // (teknikal.blade.php) flushes unsaved edits through here before opening it.
        window.simpanDrafLaporanSebelumCetak = function() {
            return $.ajax({
                url: SIMPAN_DRAF_LAPORAN_URL,
                method: 'POST',
                data: collectLaporanPayload(),
            });
        };

        // Saves report text without ending the process, so in-progress work survives a reload.
        const btnSimpanDrafLaporan = document.getElementById('btnSimpanDrafLaporan');
        if (btnSimpanDrafLaporan) {
            btnSimpanDrafLaporan.addEventListener('click', function() {
                setButtonBusy(btnSimpanDrafLaporan, 'Menyimpan...');

                $.ajax({
                    url: SIMPAN_DRAF_LAPORAN_URL,
                    method: 'POST',
                    data: collectLaporanPayload(),
                }).done(function(res) {
                    showToast('success', res.message || 'Draf laporan telah disimpan.');
                }).fail(function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menyimpan draf laporan.');
                }).always(function() {
                    clearButtonBusy(btnSimpanDrafLaporan);
                });
            });
        }

        const btnKonfirmasiHantarLaporan = document.getElementById('btnKonfirmasiHantarLaporan');
        if (btnKonfirmasiHantarLaporan) {
            btnKonfirmasiHantarLaporan.addEventListener('click', function() {
                setButtonBusy(btnKonfirmasiHantarLaporan, 'Menghantar...');

                $.ajax({
                    url: HANTAR_URL_STEP3,
                    method: 'POST',
                    data: collectLaporanPayload(),
                }).done(function(res) {
                    bootstrap.Modal.getInstance(modalKonfirmasiHantarLaporan)?.hide();
                    window.location.href = PENILAIAN_TEKNIKAL_LIST_URL + '?toast=success&message=' + encodeURIComponent(res.message || 'Penilaian teknikal berjaya dihantar.');
                }).fail(function(xhr) {
                    clearButtonBusy(btnKonfirmasiHantarLaporan);
                    bootstrap.Modal.getInstance(modalKonfirmasiHantarLaporan)?.hide();
                    showToast('error', xhr.responseJSON?.message || 'Ralat semasa menghantar laporan.');
                });
            });
        }

        if (btnTambahPengesyoran && pengesyoranList) {
            btnTambahPengesyoran.addEventListener('click', function(e) {
                e.preventDefault();
                const item = buildJustifikasiItem();
                pengesyoranList.appendChild(item);
                item.querySelector('textarea').focus();
            });

            pengesyoranList.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.btn-hapus-justifikasi');
                if (!removeBtn) return;

                const item = removeBtn.closest('.pengesyoran-item');
                if (item) item.remove();
            });
        }

        // showOuterStep() is global (teknikal.blade.php); safe to call even though this
        // script renders earlier in the document, since this only runs after DOMContentLoaded.
        const btnSebelumnyaStep3 = document.getElementById('btnSebelumnyaStep3');
        if (btnSebelumnyaStep3) {
            btnSebelumnyaStep3.addEventListener('click', function() {
                showOuterStep('penilaian-tab');
            });
        }
    });
</script>
