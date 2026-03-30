{{-- Step 3: Pematuhan Spesifikasi Kewangan (Kewangan + Rumusan) --}}
<ul class="nav nav-pills custom-tab-size mb-3" role="tablist">
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link active" data-bs-toggle="tab" href="#kewangan-3" role="tab" aria-selected="true">Kewangan</a>
    </li>
    <li class="nav-item waves-effect waves-light">
        <a class="nav-link" data-bs-toggle="tab" href="#rumusan-3" role="tab" aria-selected="false">Rumusan</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="kewangan-3" role="tabpanel" aria-labelledby="kewangan-3-tab">
        <h4 class="card-title card-title-grey mb-0">CADANGAN KEWANGAN</h4>
        <p class="card-title-desc text-primary fst-italic mb-3">Klik butang Menilai untuk meneruskan penilaian.</p>

        <div class="table-responsive">
            <table class="table table-bordered dt-responsive nowrap w-100 align-middle">
                <thead>
                    <tr>
                        <th class="text-center">Tajuk / Dokumen</th>
                        <th class="text-center">Mekanisma</th>
                        <th class="text-center">Status Penilaian</th>
                        <th class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Perkhidmatan Penilaian Forensik Keatas Sistem XXXX</td>
                        <td class="text-center">Spesifikasi</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                data-dokumen="Perkhidmatan Penilaian Forensik Keatas Sistem XXXX">Papar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Maklumat Profil Petender</td>
                        <td class="text-center">Borang Atas Talian</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                data-dokumen="Maklumat Profil Petender">Papar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Salinan Sijil Pendaftaran dengan Kementerian Kewangan</td>
                        <td class="text-center">Petender Muat Naik</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                data-dokumen="Salinan Sijil Pendaftaran dengan Kementerian Kewangan">Papar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Surat Akuan Pembida</td>
                        <td class="text-center">PTJ Muat Naik</td>
                        <td class="text-center">Selesai</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-papar-semakan-kewangan"
                                data-bs-toggle="modal" data-bs-target="#modalSemakanKetepatanDokumenKewangan"
                                data-dokumen="Surat Akuan Pembida">Papar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row mb-3 mt-2">
            <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="rumusan-3" role="tabpanel" aria-labelledby="rumusan-3-tab">
        <div class="px-0">
            <div class="row g-0">
                <div class="col-12 rounded-top border border-bottom-0 bg-light px-3 py-2 fw-bold">
                    SENARAI PEMBEKAL YANG MELEPASI PENILAIAN SPESIFIKASI KEWANGAN
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-primary text-center text-white">
                        <tr>
                            <th>Bil</th>
                            <th>Ulasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1/2</td>
                            <td class="text-center">XXX</td>
                        </tr>
                        <tr>
                            <td class="text-center">2/2</td>
                            <td class="text-center">XXX</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row my-3 align-items-center g-2">
                <div class="col-sm-auto">Bilangan Pembekal</div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control text-center" value="2" readonly aria-label="Bilangan pembekal melepasi step 3">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmLayakStep3">
                        <label class="form-check-label" for="confirmLayakStep3">
                            Saya mengesahkan petender diatas layak untuk penilaian peringkat seterusnya.
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-0">
                <div class="col-12 border border-bottom-0 bg-light px-3 py-2 fw-bold">
                    SENARAI PEMBEKAL TIDAK MELEPASI PENILAIAN SPESIFIKASI KEWANGAN
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-primary text-center text-white">
                        <tr>
                            <th>Bil</th>
                            <th>Ulasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" colspan="2">Tiada rekod dijumpai</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row my-3 mb-4 align-items-center g-2">
                <div class="col-sm-auto">Bilangan Pembekal</div>
                <div class="col-sm-2 col-md-1">
                    <input type="text" class="form-control text-center" value="0" readonly aria-label="Bilangan pembekal tidak melepasi step 3">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between flex-wrap gap-2">
                    <button type="button" class="btn btn-primary btn-sebelumnya">Sebelumnya</button>
                    <button type="button" class="btn btn-primary btn-seterusnya">Seterusnya</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#penilaian .btn-sebelumnya').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tab = document.querySelector('#penyata-bank-tab');
            if (tab) tab.click();
        });
    });
});
</script>

