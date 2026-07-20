@extends('layouts.v3.master')

@section('content')

<style>
.syarat-header {
    background:#C0392B;
    color:white;
    padding:10px 15px;
    font-weight:600;
}

.syarat-card {
    background:white;
    border:1px solid #ddd;
    padding:20px;
}

.section-label {
    font-weight:600;
    margin-top:15px;
}

.tender-value {
    margin-bottom:10px;
}

ol li {
    margin-bottom:10px;
    line-height:1.6;
}

ul li {
    margin-bottom:6px;
}
</style>

<div class="card">
    <div class="syarat-header">
        Syarat Tender
    </div>

    <div class="syarat-card">

        <!-- Tajuk Tender -->
        <div class="section-label">Tajuk Tender</div>
        <div class="tender-value">
            Tender Perkhidmatan Penyelenggaraan Komprehensif Infrastruktur Pusat Data 
            Bagi Tempoh Tiga (3) Tahun Di Perbendaharaan Negeri Selangor
        </div>

        <!-- No Tender -->
        <div class="section-label">No. Tender</div>
        <div class="tender-value">
            PWNSEL/DT01/2025
        </div>

        <!-- Syarat -->
        <ol>
            <li>
                Dokumen tawaran ini hanya akan dikeluarkan kepada petender/wakil yang 
                <strong>sah sahaja</strong>. Petender <strong>wajib</strong> mengemukakan surat pendaftaran 
                serta salinan sijil Kementerian Kewangan Malaysia, Sijil Akuan Syarikat 
                Bumiputera (jika ada) dan sijil (atau bukti) pendaftaran dengan Kerajaan 
                Negeri Selangor (melalui Sistem Tender Online Negeri Selangor 2.0). 
                Dokumen tawaran yang berharga RM50.00 bagi satu (1) set boleh diperolehi 
                melalui Sistem Tender Online Selangor 
                (<a href="https://tender.selangor.my" target="_blank">https://tender.selangor.my</a>) 
                mulai <strong>20 Februari 2025 hingga 13 Mac 2025</strong>.
            </li>

            <li>
                Petender hendaklah menghantar dokumen berupa satu (1) set dokumen asal 
                (diletakkan di dalam satu sampul surat berlakri mengandungi satu (1) sampul 
                dokumen harga dan satu (1) sampul dokumen teknikal) berserta satu (1) 
                thumbdrive yang mengandungi softcopy dokumen berkaitan). Dokumen tender 
                yang telah lengkap diisi hendaklah dimasukkan ke dalam sampul surat yang 
                berlakri yang bertanda dengan Kod Tender 
                <strong>PWNSEL/DT01/2025</strong> pada bahagian atas sebelah kiri sampul surat 
                dan dimasukkan ke dalam peti tender di alamat berikut:
                <br><br>
                <strong>Bahagian Khidmat Pengurusan,</strong><br>
                Perbendaharaan Negeri Selangor Tingkat 12,<br>
                Bangunan Sultan Salahuddin Abdul Aziz Shah,<br>
                40503 Shah Alam, Selangor Darul Ehsan.
            </li>
        </ol>

        <!-- Syarat Kelayakan -->
        <div class="section-label">Syarat Kelayakan</div>
        <ul>
            <li>
                Berdaftar dengan Kementerian Kewangan Malaysia di bawah kod bidang 
                <strong>120601, 210102, 210105 dan 220301</strong>; dan
            </li>
            <li>
                Berdaftar dengan Kerajaan Negeri Selangor 
                (melalui Sistem Tender Online Negeri Selangor 2.0 – 
                <a href="https://tender.selangor.my" target="_blank">https://tender.selangor.my</a>)
            </li>
        </ul>

    </div>
</div>

@endsection
