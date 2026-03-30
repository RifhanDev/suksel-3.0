{{-- Step 4: Penyediaan Laporan (PENILAIAN PERINGKAT PERTAMA & KEDUA) — IDs disuffix 4 elak konflik dengan step3 --}}

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
    <div class="card-title card-title-grey">SENARAI PEMBEKAL MELEPASI PENILAIAN SPESIFIKASI TEKNIKAL</div>
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
                <td>2/2</td>
                <td>96.87</td>
            </tr>
            <tr>
                <td>2</td>
                <td>1/2</td>
                <td>91.74</td>
            </tr>
        </tbody>
    </table>

    <div class="row mt-2">
        <div class="col-md-4 d-flex align-items-center fw-bold">Penetapan Pemanda Aras Tahap Lulus (%)</div>
        <div class="col-md-2">
            <input type="number" class="form-control text-center" value="70">
        </div>
    </div>
</div>

<div class="mb-3">
    <div class="card-title card-title-grey">SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN SPESIFIKASI TEKNIKAL</div>
    <table class="table table-bordered text-center align-middle mt-2">
        <thead class="table-primary text-white">
            <tr>
                <th>BIL</th>
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
<div class="d-flex justify-content-end gap-2">
    <button class="btn btn-outline-secondary">Laporan</button>
    <button id="btnStep4Hantar" class="btn btn-primary">Hantar</button>
</div>

</div> {{-- /#step4-main --}}

{{-- Success state after Hantar --}}
<div id="step4-success" class="d-none" style="min-height:400px;display:flex;align-items:center;justify-content:center;">
    <div class="card shadow-sm text-center" style="max-width:480px;">
        <div class="card-body py-5">
            <div class="mb-3">
                {{-- Simple celebratory icon substitute --}}
                <span style="font-size:40px;">🎉</span>
            </div>
            <p class="mb-4 fw-semibold">Maklumat telah berjaya dihantar</p>
            <button type="button" id="btnStep4CloseSuccess" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnHantar = document.getElementById('btnStep4Hantar');
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
            // Simple behaviour: reload current page
            window.location.reload();
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

