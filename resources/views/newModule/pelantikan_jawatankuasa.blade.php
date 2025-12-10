@extends('layouts.v3.master')

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

<!-- Header -->
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
    <button class="committee-tab active" onclick="switchCommittee('spec')">Jawatankuasa Spesifikasi</button>
    <button class="committee-tab" onclick="switchCommittee('open')">Jawatankuasa Pembuka</button>
    <button class="committee-tab" onclick="switchCommittee('tech')">Jawatankuasa Penilaian Teknikal</button>
    <button class="committee-tab" onclick="switchCommittee('fin')">Jawatankuasa Penilaian Kewangan</button>
</div>

<div id="committeeContent">

@foreach(['spec','open','tech','fin'] as $tab)

<div id="tab-{{ $tab }}" @if($tab != 'spec') style="display:none" @endif>

<table class="table table-bordered js-table">

<thead class="text-white" style="background:#2C3E9E">
<tr>
    <th width="40"><input type="checkbox" class="check-all"></th>
    <th>No IC</th>
    <th>Nama</th>
    <th>Jawatan</th>
    <th>Email</th>
    <th width="70">Gred</th>
    <th width="70">P&P</th>
    <th width="150">Peranan</th>
</tr>
</thead>

<tbody>

@php

$tabData = [

'spec' => [
    ["100002480022","Sarah Binti Hasan","KETUA PENOLONG SETIAUSAHA KANAN","sarah@selangor.gov.my","G52","Ya","Pengerusi"],
    ["100002480024","Haris Bin Ali","PENOLONG SETIAUSAHA","haris@selangor.gov.my","G41","Tidak","Setiausaha"],
    ["100002480025","Nur Aina Sofea","PEGAWAI TADBIR","aina@selangor.gov.my","G41","Tidak","Ahli"],
    ["100002480026","Farid Hakimi","PEGAWAI IT","farid@selangor.gov.my","G41","Tidak","Ahli"],
],

'open' => [
    ["200001110001","Mohd Firdaus","KETUA PENOLONG SETIAUSAHA","firdaus@selangor.gov.my","G52","Ya","Pengerusi"],
    ["200001110002","Nabila Rashid","PENOLONG SETIAUSAHA","nabila@selangor.gov.my","G41","Tidak","Setiausaha"],
    ["200001110003","Syafiq Rahman","PEGAWAI TADBIR","syafiq@selangor.gov.my","G41","Tidak","Ahli"],
    ["200001110004","Amirah Azman","PEMBANTU TADBIR","amirah@selangor.gov.my","G29","Tidak","Ahli"],
],

'tech' => [
    ["300002220001","Azlan Kamarul","ARKITEK ICT","azlan@selangor.gov.my","G48","Ya","Pengerusi"],
    ["300002220002","Faizal Nasir","PENOLONG JURUTERA","faizal@selangor.gov.my","G41","Tidak","Setiausaha"],
    ["300002220003","Nur Iman","PEGAWAI ICT","iman@selangor.gov.my","G41","Tidak","Ahli"],
    ["300002220004","Hakim Arif","PELBAGAI SISTEM","hakim@selangor.gov.my","G41","Tidak","Ahli"],
],

'fin' => [
    ["400003330001","Siti Zuraida","KETUA AKAUNTAN","zuraida@selangor.gov.my","G48","Ya","Pengerusi"],
    ["400003330002","Aiman Rafi","PEGAWAI KEWANGAN","aimanf@selangor.gov.my","G41","Tidak","Setiausaha"],
    ["400003330003","Izzah Farhana","PEMBANTU KEWANGAN","izzah@selangor.gov.my","G29","Tidak","Ahli"],
    ["400003330004","Danish Hadi","ANALIS KEWANGAN","danish@selangor.gov.my","G41","Tidak","Ahli"],
]

];

@endphp


@foreach($tabData[$tab] as $r)

<tr>
<td><input type="checkbox" class="row-check"></td>
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


<!-- Add row template -->
<tr class="add-template">
<td><input type="checkbox" class="row-check"></td>

<td>
<select class="form-select">
    <option disabled selected>Masukkan No. IC</option>
</select>
</td>

<td></td>
<td></td>
<td></td>
<td></td>
<td></td>

<td>
<select class="form-select">
    <option disabled selected></option>
    <option>Pengerusi</option>
    <option>Setiausaha</option>
    <option>Ahli</option>
</select>
</td>

</tr>

</tbody>

</table>


<!-- ACTION BUTTONS -->
<div class="d-flex justify-content-end gap-2 mb-4">
    <button class="btn btn-success btn-tambah">Tambah</button>
    <button class="btn btn-danger btn-hapus">Hapus</button>
</div>


<!-- CATATAN -->
<div class="catatan-box">
<div class="row">
    <div class="col-md-6">
        <label>Catatan</label>
        <textarea class="form-control" rows="3"></textarea>
    </div>

    <div class="col-md-6">
        <label>Dokumen Sokongan</label><br>
        <button class="btn btn-success mt-2">Muat Naik</button>
    </div>
</div>
</div>


<!-- MAIN ACTION -->
<div class="d-flex justify-content-end gap-2">
    <button class="btn btn-primary btn-simpan">Simpan</button>
    <button class="btn btn-info text-white">Laporan</button>
    <button class="btn btn-success">Hantar Pemakluman</button>
</div>


</div>

@endforeach

</div>

<hr>

<div class="text-end mt-3">
    <button onclick="backToList()" class="btn btn-danger">Kembali</button>
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
    .forEach(tab => tab.classList.remove('active'));

    document.querySelector(`[onclick="switchCommittee('${type}')"]`)
    .classList.add('active');

    document.querySelectorAll('#committeeContent > div')
    .forEach(pane => pane.style.display='none');

    document.getElementById(`tab-${type}`).style.display='block';
}


/* =============================
   LOCAL TABLE FUNCTIONS
============================= */

// ADD ROW
document.querySelectorAll('.btn-tambah').forEach(btn => {
    btn.addEventListener('click', function(){

        let table = this.closest('div').previousElementSibling;
        let tbody = table.querySelector('tbody');

        let template = tbody.querySelector('.add-template');
        let clone = template.cloneNode(true);
        clone.classList.remove('add-template');

        tbody.appendChild(clone);
    });
});


// DELETE SELECTED ROWS
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function(){

        let table = this.closest('div').previousElementSibling;

        table.querySelectorAll('.row-check:checked').forEach(cb=>{
            cb.closest('tr').remove();
        });
    });
});


// SAVE POPUP
document.querySelectorAll('.btn-simpan').forEach(btn => {
    btn.addEventListener('click', function(){
        alert("Maklumat Jawatankuasa berjaya disimpan!");
    });
});

</script>

@endsection
