{{-- Step 4: Penyediaan Laporan --}}

<div id="step4-main">
<!-- Penilaian Peringkat Pertama -->
<h5 class="fw-bold mt-3">PENILAIAN PERINGKAT PERTAMA:</h5>

<div class="mb-3 mt-2">
    <div class="card-title card-title-grey">SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PEMATUHAN DOKUMENTASI</div>
    <table class="table table-bordered text-center align-middle mt-2">
        <thead class="table-primary text-white">
            <tr>
                <th>BIL</th>
                <th>ULASAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1/2</td>
                <td>XXX</td>
            </tr>
            <tr>
                <td>2/2</td>
                <td>XXX</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="mb-3">
    <div class="card-title card-title-grey">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PEMATUHAN DOKUMENTASI</div>
    <table class="table table-bordered text-center align-middle mt-2">
        <thead class="table-primary text-white">
            <tr>
                <th>BIL</th>
                <th>ULASAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">Tiada rekod dijumpai</td>
            </tr>
        </tbody>
    </table>
    <textarea class="form-control mt-2" rows="2">Sehubungan dengan itu, JPT bersetuju untuk mengambil xx penyebut harga iaitu XX untuk ke Penilaian Peringkat Kedua</textarea>
</div>

<!-- Penilaian Peringkat Kedua -->
<h5 class="fw-bold mt-4">PENILAIAN PERINGKAT KEDUA:</h5>

<div class="mb-3 mt-2">
    <div class="card-title card-title-grey">SENARAI PEMBEKAL YANG MELEPASI PENILAIAN PENYATA BULANAN BANK</div>
    <table class="table table-bordered text-center align-middle mt-2">
        <thead class="table-primary text-white">
            <tr>
                <th>BIL</th>
                <th>ULASAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1/2</td>
                <td>XXX</td>
            </tr>
            <tr>
                <td>2/2</td>
                <td>XXX</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="mb-3">
    <div class="card-title card-title-grey">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN PENYATA BULANAN BANK</div>
    <table class="table table-bordered text-center align-middle mt-2">
        <thead class="table-primary text-white">
            <tr>
                <th>BIL</th>
                <th>ULASAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">Tiada rekod dijumpai</td>
            </tr>
        </tbody>
    </table>
    <textarea class="form-control mt-2" rows="2">Sehubungan dengan itu, JPT bersetuju untuk mengambil xx penyebut harga iaitu XX untuk ke Penilaian Peringkat Ketiga.</textarea>
</div>

<!-- Penilaian Peringkat Ketiga -->
<h5 class="fw-bold mt-4">PENILAIAN PERINGKAT KETIGA:</h5>

<div class="mb-3 mt-2">
    <div class="card-title card-title-grey">SENARAI PEMBEKAL MELEPASI PENILAIAN KEWANGAN</div>
    <table class="table table-bordered text-center align-middle mt-2">
        <thead class="table-primary text-white">
            <tr>
                <th>KEDUDUKAN</th>
                <th>BIL</th>
                <th>JUMLAH SKOR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>1/2</td>
                <td>100</td>
            </tr>
            <tr>
                <td>2</td>
                <td>2/2</td>
                <td>100</td>
            </tr>
        </tbody>
    </table>

    <div class="row mt-2 align-items-center g-2">
        <div class="col-auto pe-0">Penetapan Penanda Aras Tahap Lulus (%)</div>
        <div class="col-sm-2 col-md-1">
            <input type="text" class="form-control form-control-sm text-center" value="51" readonly>
        </div>
    </div>
</div>

<div class="mb-3">
    <div class="card-title card-title-grey">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN KEWANGAN</div>
    <table class="table table-bordered text-center align-middle mt-2">
        <thead class="table-primary text-white">
            <tr>
                <th>KOD PEMBEKAL</th>
                <th>JUMLAH SKOR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">Tiada rekod dijumpai</td>
            </tr>
        </tbody>
    </table>
    <textarea class="form-control mt-2" rows="2">Sehubungan dengan itu, JPT bersetuju untuk mengambil xx penyebut harga iaitu XX untuk ke Peringkat Pengesyoran.</textarea>
</div>

<div class="mb-3" id="pengesyoran-section-4">
    <!-- Pengesyoran -->
    <div class="card-title card-title-grey">PENGESYORAN</div>
    <div id="pengesyoran-list-4">
        <div class="pengesyoran-item mb-3">
            <textarea class="form-control" rows="2">
Dengan ini, JPT mengesyorkan XX (bil) untuk melaksanakan (NAMA PROJEK) untuk dibawa ke mesyuarat Jawatankuasa Sebut Harga PSU(K) berdasarkan justifikasi seperti berikut:
            </textarea>
            <div class="text-end mt-2">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-pengesyoran d-none">Buang</button>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button id="btnTambahPengesyoran4" class="btn btn-success">Tambah</button>
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center gap-2 mt-4">
    <button type="button" id="btnStep4Sebelumnya" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success">Laporan</button>
        <button type="button" id="btnStep4Hantar" class="btn btn-primary">Hantar</button>
    </div>
</div>

</div> {{-- /#step4-main --}}

{{-- Success state after Hantar --}}
<div id="step4-success" class="d-none" style="min-height:400px;display:flex;align-items:center;justify-content:center;">
    <div class="text-center py-5">
        <div class="mb-4">
            <img src="{{ asset('success.png') }}" alt="Berjaya dihantar" style="width:90px;max-width:100%;height:auto;">
        </div>
        <p class="mb-4 fw-semibold" style="font-size:42px;">Maklumat telah berjaya dihantar</p>
        <button type="button" id="btnStep4CloseSuccess" class="btn btn-primary px-5 py-2">Tutup</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnHantar = document.getElementById('btnStep4Hantar');
    const btnSebelumnya = document.getElementById('btnStep4Sebelumnya');
    const mainWrap = document.getElementById('step4-main');
    const successWrap = document.getElementById('step4-success');
    const btnClose = document.getElementById('btnStep4CloseSuccess');
    const btnTambahPengesyoran = document.getElementById('btnTambahPengesyoran4');
    const pengesyoranList = document.getElementById('pengesyoran-list-4');

    if (btnHantar && mainWrap && successWrap) {
        btnHantar.addEventListener('click', function (e) {
            e.preventDefault();
            mainWrap.style.display = 'none';
            successWrap.classList.remove('d-none');
        });
    }

    if (btnClose) {
        btnClose.addEventListener('click', function () {
            window.location.href = "{{ route('penilaianKewangan') }}";
        });
    }

    if (btnSebelumnya) {
        btnSebelumnya.addEventListener('click', function () {
            const tab = document.querySelector('#penilaian-tab');
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
            if (ta) ta.value = '';

            const removeBtn = clone.querySelector('.btn-remove-pengesyoran');
            if (removeBtn) removeBtn.classList.remove('d-none');

            pengesyoranList.appendChild(clone);

            // Pastikan item pertama tidak boleh dibuang
            const firstRemove = firstItem.querySelector('.btn-remove-pengesyoran');
            if (firstRemove) firstRemove.classList.add('d-none');
        });

        // Event delegation untuk buang pengesyoran
        pengesyoranList.addEventListener('click', function (e) {
            const target = e.target;
            if (!target.classList.contains('btn-remove-pengesyoran')) return;

            const items = pengesyoranList.querySelectorAll('.pengesyoran-item');
            if (items.length <= 1) return; // sekurang-kurangnya satu kekal

            const item = target.closest('.pengesyoran-item');
            if (item) item.remove();
        });
    }
});
</script>

