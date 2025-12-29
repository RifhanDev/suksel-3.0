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
   STEPPER DESIGN (WORKFLOW)
================================ */
:root{
    --sg-red:#C81E1E;
    --sg-red-dark:#A4161A;
    --topbar-border:#E5E7EB;
    --topbar-text:#374151;
}

.progress-wrapper{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    position:relative;
}

/* Each step */
.progress-step{
    flex:1;
    text-align:center;
    position:relative;
    cursor:pointer;
}

/* Connector line */
.progress-step:not(:last-child)::after{
    content:'';
    position:absolute;
    top:18px; /* center of 36px circle */
    left:50%;
    width:100%;
    height:3px;
    background:var(--topbar-border);
    z-index:0;
}

/* Active & completed line */
.progress-step.active:not(:last-child)::after,
.progress-step.done:not(:last-child)::after{
    background:var(--sg-red);
}

/* Reset future steps line */
.progress-step.active ~ .progress-step:not(:last-child)::after{
    background:var(--topbar-border);
}

/* Step circle */
.step-number{
    width:36px;
    height:36px;
    border-radius:50%;
    background:var(--topbar-border);
    color:var(--topbar-text);
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto;
    font-weight:600;
    position:relative;
    z-index:2;
}

/* Active & done circle */
.progress-step.active .step-number,
.progress-step.done .step-number{
    background:var(--sg-red);
    color:#fff;
}

/* Label */
.step-label{
    margin-top:8px;
    font-size:13px;
    color:var(--topbar-text);
    font-weight:500;
}

/* Active & done label */
.progress-step.active .step-label,
.progress-step.done .step-label{
    color:var(--sg-red-dark);
    font-weight:600;
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
<div class="d-flex progress-wrapper mb-4">

    <div id="step1" class="progress-step active" onclick="goToFR1()">
        <div class="step-number">1</div>
        <div class="step-label">Peringkat Pematuhan Teknikal</div>
    </div>

    <div id="step2" class="progress-step" onclick="goToFR2()">
        <div class="step-number">2</div>
        <div class="step-label">Peringkat Pematuhan Kewangan</div>
    </div>

    <div id="step3" class="progress-step" onclick="goToFR3()">
        <div class="step-number">3</div>
        <div class="step-label">Rumusan</div>
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
        el.classList.remove("active","done");

        if(i < step) el.classList.add("done");
        if(i === step) el.classList.add("active");
    }
}


</script>

@endsection
