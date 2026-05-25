@push('styles')
<style>
    .pegawai-section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pegawai-section-label::before {
        content: '';
        display: block;
        width: 20px;
        height: 2px;
        background: var(--sg-red, #c41e3a);
        border-radius: 2px;
        flex-shrink: 0;
    }
</style>
@endpush

<!-- SECTION: PEGAWAI BERTANGGUNGJAWAB -->
<div class="content-card mb-4 p-0">

    <div class="review-section-header">
        <div class="section-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div>
            <h6>Pegawai Bertanggungjawab</h6>
            <small>Maklumat pegawai yang bertanggungjawab terhadap iklan tender ini</small>
        </div>
    </div>

    <div class="p-4 d-flex flex-column gap-4">

        {{-- ── Pegawai 1 ── --}}
        <div>
            <div class="pegawai-section-label">Pegawai Bertanggungjawab 1 <span class="text-danger">*</span></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Nama</label>
                    <input type="text" class="form-control bg-light" name="pegawai1_nama"
                        value="{{ Auth::user()->name ?? '' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">E-mel</label>
                    <input type="text" class="form-control bg-light" name="pegawai1_emel"
                        value="{{ Auth::user()->email ?? '' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">No. Telefon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="pegawai1_tel" value="{{ $meta['pegawai']['pegawai1']['tel'] ?? '' }}" placeholder="Contoh: 03-5544 1234">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="pegawai1_jabatan" value="{{ $meta['pegawai']['pegawai1']['jabatan'] ?? '' }}" placeholder="Contoh: Bahagian Perolehan">
                </div>
            </div>
        </div>

        <hr class="my-0 opacity-25">

        {{-- ── Pegawai 2 ── --}}
        <div>
            <div class="pegawai-section-label">Pegawai Bertanggungjawab 2 <span class="text-muted fw-normal fst-italic" style="font-size:0.68rem;">(Pilihan)</span></div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Nama</label>
                    <select id="pegawai2_nama_select" name="pegawai2_nama" placeholder="Cari nama pegawai..."></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">E-mel</label>
                    <input type="text" class="form-control bg-light" id="pegawai2_emel" name="pegawai2_emel" readonly placeholder="-">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">No. Telefon</label>
                    <input type="text" class="form-control" name="pegawai2_tel">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small">Jabatan</label>
                    <input type="text" class="form-control" name="pegawai2_jabatan">
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    var pegawaiData = [
        { id: 1, nama: 'Ahmad Hafizuddin bin Roslan',    email: 'ahmad.hafiz@selangor.gov.my' },
        { id: 2, nama: 'Siti Nurhaliza binti Kamarudin', email: 'siti.nurhaliza@selangor.gov.my' },
        { id: 3, nama: 'Mohd Faizal bin Abdullah',        email: 'mohd.faizal@selangor.gov.my' },
        { id: 4, nama: 'Nurul Ain binti Hashim',          email: 'nurul.ain@selangor.gov.my' },
        { id: 5, nama: 'Zulkifli bin Mohd Yusoff',        email: 'zulkifli.yusoff@selangor.gov.my' },
    ];

    $('#pegawai2_nama_select').selectize({
        valueField    : 'id',
        labelField    : 'nama',
        searchField   : 'nama',
        options       : pegawaiData,
        maxItems      : 1,
        create        : false,
        placeholder   : 'Cari nama pegawai...',
        dropdownParent: 'body',
        render: {
            option: function (item, escape) {
                return '<div><strong>' + escape(item.nama) + '</strong>' +
                    '<br><small class="text-muted">' + escape(item.email) + '</small></div>';
            }
        },
        onChange: function (value) {
            if (!value) { $('#pegawai2_emel').val(''); return; }
            var selected = pegawaiData.find(function (p) { return String(p.id) === String(value); });
            if (selected) $('#pegawai2_emel').val(selected.email);
        }
    });
});
</script>
@endpush
