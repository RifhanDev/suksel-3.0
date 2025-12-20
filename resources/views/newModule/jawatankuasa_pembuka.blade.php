@extends('layouts.v3.master')

@section('content')

<style>

/* ================================
   TENDER LIST HEADER
================================ */
.table-header{
    background:#1F3A8A;
    color:#fff;
    text-align:center;
}

/* ================================
   STEPPER DESIGN (IMAGE 3 STYLE)
================================ */
.stepper-wrapper{
    display:flex;
    align-items:center;
    justify-content:center;
    margin:25px 0;
}
.stepper-item{
    flex:1;
    text-align:center;
    position:relative;
    cursor:pointer;
}
.stepper-item::after{
    content:'';
    position:absolute;
    top:18px;
    left:50%;
    width:100%;
    height:3px;
    background:#C7CED8;
    z-index:0;
}
.stepper-item:last-child::after{
    display:none;
}
.step-counter{
    width:36px;
    height:36px;
    background:#9CA3AF;
    border-radius:50%;
    color:white;
    line-height:36px;
    margin:0 auto;
    font-weight:bold;
    z-index:1;
    position:relative;
}
.step-title{
    margin-top:8px;
    font-size:14px;
}
.stepper-item.active .step-counter{
    background:#10B981;
}
.stepper-item.completed .step-counter{
    background:#2563EB;
}

/* ================================
   GENERAL UI
================================ */
.section-title{
    font-weight:bold;
    margin:10px 0 10px;
}

.btn-primary{
    background-color:#A4161A !important;
    border-color:#A4161A !important;
}

.btn-primary:hover{
    background-color:#8F1215 !important;
    border-color:#8F1215 !important;
}
</style>

<div class="card">
<div class="card-body">

<!-- ========================= SENARAI TENDER ========================= -->
<div id="tenderList">

    <h4 class="fw-bold">SENARAI TENDER</h4>

    <div class="row mb-3">
        <div class="col-md-3">
            <input class="form-control" placeholder="No Tender">
        </div>

        <div class="col-md-3">
            <input class="form-control" placeholder="Tajuk Perolehan">
        </div>

        <div class="col-md-3">
            <select class="form-select">
                <option>Status</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" class="form-control">
        </div>
    </div>

    <button class="btn btn-primary w-100 mb-3">Tapis</button>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">

            <thead class="table-header">
                <tr>
                    <th>No Tender</th>
                    <th>Tajuk Perolehan</th>
                    <th>Tarikh</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr style="cursor:pointer" onclick="openWorkflow()">
                    <td>QT21000000023741</td>
                    <td class="text-primary text-decoration-underline">
                        TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX
                    </td>
                    <td>03/03/2024</td>
                    <td>Dalam Proses</td>
                </tr>
            </tbody>

        </table>
    </div>

</div>
<!-- ========================= END TENDER LIST ========================= -->



<!-- ========================= WORKFLOW ========================= -->
<div id="workflow" style="display:none;">

<!-- Tender Header -->
<div class="row mb-2">
    <div class="col-md-3"><strong>No. Tender</strong><br>QT21000000023741</div>
    <div class="col-md-3"><strong>PTJ</strong><br>BAHAGIAN PEROLEHAN – KEWANGAN</div>
    <div class="col-md-3"><strong>Status</strong><br>Menunggu Pengesahan</div>
    <div class="col-md-3"><strong>Tarikh</strong><br>17/01/2022</div>
</div>
<hr>

<!-- ========== STEPPER ========== -->
<div class="stepper-wrapper">

    <div id="step1" class="stepper-item active" onclick="goToFR1()">
        <div class="step-counter">1</div>
        <div class="step-title">Peringkat Pematuhan Teknikal</div>
    </div>

    <div id="step2" class="stepper-item" onclick="goToFR2()">
        <div class="step-counter">2</div>
        <div class="step-title">Peringkat Pematuhan Kewangan</div>
    </div>

    <div id="step3" class="stepper-item" onclick="goToFR3()">
        <div class="step-counter">3</div>
        <div class="step-title">Rumusan</div>
    </div>

</div>


<!-- ========================= FR1 ========================= -->
<div id="fr1">

<div class="section-title">PEMATUHAN CADANGAN TEKNIKAL</div>

<table class="table table-bordered">
    <thead class="table-header">
        <tr>
            <th>Tajuk / Dokumen</th>
            <th>Mekanisma</th>
            <th>Status Penilaian</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Penilaian Forensik Sistem XXXX</td>
            <td>Spesifikasi</td>
            <td>Menunggu Penyerahan</td>
            <td><button class="btn btn-success btn-sm">Semak</button></td>
        </tr>
        <tr>
            <td>Surat Pengesahan Prinsipal</td>
            <td>Petender Muat Naik</td>
            <td>Menunggu Penyerahan</td>
            <td><button class="btn btn-success btn-sm">Semak</button></td>
        </tr>
        <tr>
            <td>Senarai Kakitangan Teknikal</td>
            <td>Petender Muat Naik</td>
            <td>Menunggu Penyerahan</td>
            <td><button class="btn btn-success btn-sm">Semak</button></td>
        </tr>
    </tbody>
</table>

<div class="text-end">
    <button class="btn btn-primary" onclick="goToFR2()">Seterusnya</button>
</div>

</div>


<!-- ========================= FR2 ========================= -->
<div id="fr2" style="display:none;">

<div class="section-title">PEMATUHAN CADANGAN KEWANGAN</div>

<table class="table table-bordered">
<thead class="table-header">
<tr>
<th>Tajuk / Dokumen</th>
<th>Mekanisma</th>
<th>Status Penilaian</th>
<th>Tindakan</th>
</tr>
</thead>
<tbody>
<tr>
<td>Maklumat Profil Petender</td>
<td>Borang Atas Talian</td>
<td>Menunggu Penyerahan</td>
<td><button class="btn btn-success btn-sm">Semak</button></td>
</tr>

<tr>
<td>Penyata Bank (3 Bulan)</td>
<td>Borang Atas Talian</td>
<td>Menunggu Penyerahan</td>
<td><button class="btn btn-success btn-sm">Semak</button></td>
</tr>

<tr>
<td>Sijil Pendaftaran MOF</td>
<td>Petender Muat Naik</td>
<td>Menunggu Penyerahan</td>
<td><button class="btn btn-success btn-sm">Semak</button></td>
</tr>

<tr>
<td>Surat Akuan Pembida</td>
<td>PTJ Muat Naik</td>
<td>Menunggu Penyerahan</td>
<td><button class="btn btn-success btn-sm">Semak</button></td>
</tr>
</tbody>
</table>

<div class="d-flex justify-content-between">
    <button class="btn btn-secondary" onclick="goToFR1()">Sebelumnya</button>
    <button class="btn btn-primary" onclick="goToFR3()">Seterusnya</button>
</div>

</div>


<!-- ========================= FR3 ========================= -->
<div id="fr3" style="display:none;">

<div class="section-title">RUMUSAN PENILAIAN</div>

<table class="table table-bordered">

<thead class="table-header">
<tr>
<th>Bil</th>
<th>Nama Syarikat</th>
<th>Taraf Bumiputera</th>
<th>Harga Tawaran</th>
</tr>
</thead>

<tbody>
<tr>
<td>1/2</td>
<td>Syarikat A</td>
<td>
<select class="form-select">
<option>Ya</option>
<option>Tidak</option>
</select>
</td>
<td><input type="text" class="form-control"></td>
</tr>

<tr>
<td>2/2</td>
<td>Syarikat B</td>
<td>
<select class="form-select">
<option>Ya</option>
<option>Tidak</option>
</select>
</td>
<td><input type="text" class="form-control"></td>
</tr>
</tbody>
</table>

  <div class="mt-3">
                <label><input type="radio" name="rumusan"> Saya mengesahkan petender perlu melalui proses Cut-Off</label><br>
                <label><input type="radio" name="rumusan"> Saya mengesahkan semua petender disemak dan layak dinilai</label>
            </div>

            <!-- Tidak Layak -->
            <h5 class="mt-4">Senarai Pembekal Tidak Layak</h5>
            <table class="table table-bordered">
                <thead class="table-header">
                    <tr>
                        <th>Nama Syarikat</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="2" class="text-center">Tiada Rekod</td></tr>
                </tbody>
            </table>

<div class="d-flex justify-content-between">
    <button class="btn btn-secondary" onclick="goToFR2()">Sebelumnya</button>
    <div>
        <button class="btn btn-info">Laporan</button>
        <button class="btn btn-success">Hantar</button>
    </div>
</div>

</div>

</div>
<!-- ========================= END WORKFLOW ========================= -->

</div>
</div>


<script>

function openWorkflow()
{
    document.getElementById('tenderList').style.display = 'none';
    document.getElementById('workflow').style.display = 'block';
    showStep(1);
}

function goToFR1(){ showStep(1); }
function goToFR2(){ showStep(2); }
function goToFR3(){ showStep(3); }

function showStep(step)
{
    ["fr1","fr2","fr3"].forEach((s,i)=>{
        document.getElementById(s).style.display = ((i+1)===step) ? "block" : "none";
    });

    updateStepper(step);
}

function updateStepper(step)
{
    for(let i=1;i<=3;i++){
        let el=document.getElementById("step"+i);
        el.classList.remove("active","completed");

        if(i < step) el.classList.add("completed");
        if(i === step) el.classList.add("active");
    }
}

</script>

@endsection
