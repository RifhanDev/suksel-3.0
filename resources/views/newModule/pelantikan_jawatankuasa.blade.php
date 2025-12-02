@extends('layouts.modern')

@section('content')

<style>

.tender-link{
    color:#3751FF;
    font-weight:600;
    text-decoration:underline;
    cursor:pointer;
}

/* =====================
   TABS STYLE
===================== */
.committee-tabs{
    display:flex;
    background:#f1f3ff;
    border-radius:8px;
    overflow:hidden;
    margin-bottom:20px;
}

.committee-tab{
    flex:1;
    border:0;
    background:transparent;
    padding:12px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.committee-tab:hover{
    background:#e6e9ff;
}

.committee-tab.active{
    background:#3751FF;
    color:#fff;
}

/* =====================
   TABLE STYLE
===================== */

.table thead th{
    text-align:center;
    vertical-align:middle;
}

.table tbody td{
    vertical-align:middle;
}

input[type="checkbox"]{
    width:16px;
    height:16px;
}

.form-select{
    padding:6px 10px;
}

.catatan-box{
    background:#f3f3f3;
    padding:15px;
    border-radius:6px;
    margin-bottom:20px;
}

</style>


<!-- ===================== PAGE LIST ====================== -->

<div id="pageList">

<div class="card p-4">

<h4 class="fw-bold mb-4">SENARAI TENDER</h4>

<div class="row mb-4 g-2">
    <div class="col-md-3"><input type="text" class="form-control" placeholder="No Tender"></div>
    <div class="col-md-3"><input type="text" class="form-control" placeholder="Tajuk Perolehan"></div>
    <div class="col-md-3">
        <select class="form-select">
            <option>Status</option>
            <option>Diluluskan</option>
            <option>Dalam Proses</option>
        </select>
    </div>
    <div class="col-md-3"><input type="date" class="form-control"></div>
</div>

<button class="btn btn-primary w-100 mb-3">Tapis</button>

<table class="table table-bordered">
<thead class="text-white" style="background:#2C3E9E">
<tr>
    <th>No Tender</th>
    <th>Tajuk Perolehan</th>
    <th>Tarikh</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<tr>
    <td class="text-center">QT21000000023741</td>
    <td>
        <span class="tender-link" onclick="openPage2()">
            TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX
        </span>
    </td>
    <td class="text-center">3/3/2024</td>
    <td class="text-center">Dalam Proses</td>
</tr>
</tbody>
</table>

</div>
</div>


<!-- ===================== PAGE DETAIL ====================== -->

<div id="pageDetail" style="display:none">

<div class="card p-4">

<div class="row mb-3">
    <div class="col-md-8">
        <strong>No Tender:</strong> QT21000000023741 <br>
        <strong>PTJ:</strong> BAHAGIAN PENTADBIRAN – CAWANGAN KEWANGAN – KEMENTERIAN KEWANGAN
    </div>
    <div class="col-md-4 text-end">
        <strong>Status:</strong> Menunggu Penyerahan Tender
    </div>
</div>

<hr>

<h5 class="fw-bold">Maklumat Jawatankuasa</h5>

<div class="committee-tabs">
    <button class="committee-tab active" onclick="switchCommittee('spec')"> Jawatankuasa Spesifikasi</button>
    <button class="committee-tab" onclick="switchCommittee('open')">Jawatankuasa Pembuka</button>
    <button class="committee-tab" onclick="switchCommittee('tech')">Jawatankuasa Penilaian Teknikal</button>
    <button class="committee-tab" onclick="switchCommittee('fin')">Jawatankuasa Penilaian Kewangan</button>
</div>


<!-- ===================== TAB CONTENT ====================== -->

<div id="committeeContent">

@foreach(['spec','open','tech','fin'] as $tab)

<div id="tab-{{ $tab }}" @if($tab!='spec') style="display:none" @endif>

<!-- ===================== TABLE ====================== -->

<table class="table table-bordered align-middle">

<thead class="text-white" style="background:#2C3E9E">
<tr>
    <th width="40"><input type="checkbox"></th>
    <th>No IC</th>
    <th>Nama</th>
    <th>Jawatan</th>
    <th>Email</th>
    <th width="70">Gred</th>
    <th width="70">P&amp;P</th>
    <th width="150">Peranan</th>
</tr>
</thead>

<tbody>

@php
$rows = [
    ["100002480022","Sarah Binti Hasan","KETUA PENOLONG SETIAUSAHA KANAN","sarah@selangor.gov.my","G52","Ya","Pengerusi"],
    ["100002480024","Haris Bin Ali","PENOLONG SETIAUSAHA","haris@selangor.gov.my","G41","Tidak","Setiausaha"],
    ["100002480023","Kamil Bin Latif","PENOLONG SETIAUSAHA","kamil@selangor.gov.my","G41","Tidak","Ahli"],
];
@endphp

@foreach($rows as $r)
<tr>
    <td><input type="checkbox"></td>
    <td>{{ $r[0] }}</td>
    <td>{{ $r[1] }}</td>
    <td>{{ $r[2] }}</td>
    <td>{{ $r[3] }}</td>
    <td>{{ $r[4] }}</td>
    <td>{{ $r[5] }}</td>
    <td>
        <select class="form-select">
            <option {{ $r[6]=='Pengerusi'?'selected':'' }}>Pengerusi</option>
            <option {{ $r[6]=='Setiausaha'?'selected':'' }}>Setiausaha</option>
            <option {{ $r[6]=='Ahli'?'selected':'' }}>Ahli</option>
        </select>
    </td>
</tr>
@endforeach

<tr>
    <tr class="add-row">
    <td></td>

    <!-- No IC dropdown -->
    <td>
        <select class="form-select">
            <option selected disabled>Masukkan No. IC</option>
        </select>
    </td>

    <!-- Empty fields -->
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>

    <!-- Peranan dropdown -->
    <td>
        <select class="form-select">
            <option selected disabled>&nbsp;</option>
            <option>Pengerusi</option>
            <option>Setiausaha</option>
            <option>Ahli</option>
        </select>
    </td>
</tr>
    <td colspan="6"></td>
</tr>

</tbody>
</table>


<!-- ACTION BUTTONS -->
<div class="d-flex justify-content-end gap-2 mb-4">
    <button class="btn btn-success">Tambah</button>
    <button class="btn btn-danger">Hapus</button>
</div>


<!-- CATATAN -->
<div class="catatan-box">

<div class="row">
    <div class="col-md-6">
        <label class="fw-bold">Catatan</label>
        <textarea class="form-control" rows="3">Catatan untuk jawatankuasa {{ strtoupper($tab) }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="fw-bold">Dokumen Sokongan</label><br>
        <button class="btn btn-success mt-2">Muat Naik</button>
    </div>
</div>

</div>


<!-- MAIN ACTION -->
<div class="d-flex justify-content-end gap-2">
    <button class="btn btn-primary">Simpan</button>
    <button class="btn btn-info text-white">Laporan</button>
    <button class="btn btn-success">Hantar Pemakluman</button>
</div>


</div>

@endforeach

</div>


<hr>

<div class="text-end mt-3">
    <button class="btn btn-danger" onclick="backToList()">Kembali</button>
</div>

</div>

</div>


<!-- ===================== JAVASCRIPT ====================== -->

<script>

function openPage2(){
    pageList.style.display='none';
    pageDetail.style.display='block';
}

function backToList(){
    pageDetail.style.display='none';
    pageList.style.display='block';
}

function switchCommittee(type){

    document.querySelectorAll('.committee-tab')
        .forEach(btn => btn.classList.remove('active'));

    document.querySelectorAll('#committeeContent > div')
        .forEach(div => div.style.display='none');

    document.querySelector(`[onclick="switchCommittee('${type}')"]`)
        .classList.add('active');

    document.getElementById(`tab-${type}`).style.display='block';
}

</script>

@endsection
