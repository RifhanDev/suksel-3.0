@extends('layouts.v3.master')

@section('content')
<style>
     :root{
        --brand-red:#B11217;
        --soft-grey:#e9ecef;
        --line:#d9dde5;

        --btn-primary: var(--brand-red);
        --btn-primary-hover:#991014;

        --btn-teal:#19c1a7;
        --btn-green:#17a34a;
    }

    .section-bar{
        background:var(--soft-grey);
        font-weight:800;
        text-transform:uppercase;
        font-size:12px;
        padding:8px 12px;
        border-radius:2px;
        margin:12px 0 10px;
    }

    .title-center{
        text-align:center;
        font-weight:800;
        font-size:12px;
        margin:0 0 12px;
        text-transform:uppercase;
    }

    .meta-wrap{
        display:flex;
        gap:40px;
        justify-content:center;
        margin:10px 0 14px;
        flex-wrap:wrap;
        font-size:11px;
    }
    .meta-col{
        min-width:360px;
        line-height:1.6;
    }
    .meta-row{
        display:flex;
        gap:10px;
    }
    .meta-k{ width:170px; color:#111; font-weight:700; }
    .meta-v{ color:#111; }

    .table-form{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
        background:#fff;
        border:1px solid var(--line);
    }
    .table-form th, .table-form td{
        border:1px solid var(--line);
        padding:8px 8px;
        font-size:10.5px;
        vertical-align:middle;
    }
    .table-form thead th{
        background:var(--brand-red);
        color:#fff;
        font-weight:800;
        text-align:center;
        white-space:nowrap;
    }

    .btn-kembali{
        background:var(--btn-primary);
        color:#fff;
        border:0;
        padding:9px 18px;
        border-radius:4px;
        font-weight:800;
        min-width:110px;
    }
    .btn-simpan{
        background:var(--btn-teal);
        color:#fff;
        border:0;
        padding:9px 18px;
        border-radius:4px;
        font-weight:800;
        min-width:110px;
    }
    .btn-hantar{
        background:var(--btn-dark);
        color:#fff;
        border:0;
        padding:9px 18px;
        border-radius:4px;
        font-weight:800;
        min-width:110px;
    }

    /* Success modal */
    .modal-card{
        border-radius:10px;
        border:0;
        box-shadow:0 10px 30px rgba(0,0,0,.15);
        padding:18px 18px 14px;
        text-align:center;
    }
    .confetti{ width:44px;height:44px;margin:6px auto 8px; }
    .btn-modal{
        background:var(--btn-dark);
        color:#fff;
        border:0;
        padding:10px 20px;
        border-radius:6px;
        font-weight:800;
        min-width:110px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="section-bar">RINGKASAN LAPORAN</div>
    <div class="title-center">RINGKASAN LAPORAN TENDER</div>

    {{-- Meta ringkasan (kiri/kanan) --}}
    <div class="meta-wrap">
        <div class="meta-col">
            <div class="meta-row"><div class="meta-k">Jenis Tender</div><div class="meta-v">: Konvensional</div></div>
            <div class="meta-row"><div class="meta-k">Tarikh Tender Diiklankan</div><div class="meta-v">: **************</div></div>
            <div class="meta-row"><div class="meta-k">Tarikh Tender Ditutup</div><div class="meta-v">: **************</div></div>
            <div class="meta-row"><div class="meta-k">Tarikh Luput Sahlaku Tender</div><div class="meta-v">: **************</div></div>
            <div class="meta-row"><div class="meta-k">Tarikh Lawatan Tapak</div><div class="meta-v">: 7/11/2024</div></div>
            <div class="meta-row"><div class="meta-k">Tempoh Siap Maksimum</div><div class="meta-v">: 104 Minggu</div></div>
        </div>

        <div class="meta-col">
            <div class="meta-row"><div class="meta-k">Peruntukan (PDA)</div><div class="meta-v">: </div></div>
            <div class="meta-row"><div class="meta-k">Anggaran Jabatan</div><div class="meta-v">: RM6,120,000.00</div></div>
            <div class="meta-row"><div class="meta-k">Harga Cut-Off</div><div class="meta-v">: RM5,279,000.00</div></div>
            <div class="meta-row"><div class="meta-k">Harga Adjusted Mean</div><div class="meta-v">: RM5,321,181.14</div></div>
            <div class="meta-row"><div class="meta-k">Modal Mudah Cair Terlaras</div><div class="meta-v">:</div></div>
            <div class="meta-row"><div class="meta-k">i) Bawah Cut-Off (5%)</div><div class="meta-v">: RM283,600.00</div></div>
            <div class="meta-row"><div class="meta-k">ii) Atas Cut-Off (3%)</div><div class="meta-v">: RM170,160.00</div></div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table-form">
            <thead>
                <tr>
                    <th style="width:55px;">Bil</th>
                    <th style="width:90px;">Rujukan Tender</th>
                    <th>Nama Kontraktor</th>
                    <th style="width:60px;">Gred</th>
                    <th style="width:85px;">Taraf</th>
                    <th style="width:55px;">Lokasi</th>
                    <th style="width:95px;">Harga (RM)</th>
                    <th style="width:70px;">%BWAM</th>
                    <th style="width:70px;">Tempoh (Minggu)</th>
                    <th style="width:110px;">Kesempurnaan Tender</th>
                    <th style="width:110px;">Kecukupan Dokumen Wajib</th>
                    <th style="width:110px;">Modal Minimum</th>
                    <th style="width:85px;">Kerja Semasa</th>
                    <th style="width:85px;">Keputusan</th>
                    <th style="width:85px;">Pengesyoran</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rows = [
                        ['bil'=>6,'ruj'=>'27/53','nama'=>'MZ PROBINA','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,798,852.00','bwam'=>'-18.24%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'4,798,852.00','kerja'=>'Memuaskan','keputusan'=>'Lulus'],
                        ['bil'=>7,'ruj'=>'34/53','nama'=>'SIERRA AQUATECH SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,830,689.40','bwam'=>'-17.64%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'4,830,689.40','kerja'=>'T.K.S','keputusan'=>'Lulus'],
                        ['bil'=>8,'ruj'=>'24/53','nama'=>'RMS BINJAYA SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,864,594.40','bwam'=>'-17.00%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'4,864,594.40','kerja'=>'Memuaskan','keputusan'=>'Lulus'],
                        ['bil'=>9,'ruj'=>'37/53','nama'=>'ZEFHILL (M) SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,956,328.00','bwam'=>'-15.28%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'4,956,328.00','kerja'=>'-','keputusan'=>'Lulus'],
                        ['bil'=>10,'ruj'=>'30/53','nama'=>'YAKIN TELUS SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'4,980,824.00','bwam'=>'-14.82%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'4,980,824.00','kerja'=>'T.K.S','keputusan'=>'Lulus'],
                        ['bil'=>11,'ruj'=>'32/53','nama'=>'ARMADA KASIH SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'5,010,773.00','bwam'=>'-14.25%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'5,010,773.00','kerja'=>'T.K.S','keputusan'=>'Lulus'],
                        ['bil'=>12,'ruj'=>'2/53','nama'=>'UER VENTURES SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'5,018,400.10','bwam'=>'-14.11%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'5,018,400.10','kerja'=>'Memuaskan','keputusan'=>'Lulus'],
                        ['bil'=>13,'ruj'=>'48/53','nama'=>'LANDASAN LURUS RESOURCES SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'5,040,151.00','bwam'=>'-13.70%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'5,040,151.00','kerja'=>'-','keputusan'=>'Gagal'],
                        ['bil'=>14,'ruj'=>'7/53','nama'=>'JUJUR PERANGSANG SDN. BHD.','gred'=>'G6','taraf'=>'BUMIPUTERA','lokasi'=>'0','harga'=>'5,050,444.00','bwam'=>'-13.51%','tempoh'=>'104','sempurna'=>'✓','dok'=>'Cukup','modal'=>'5,050,444.00','kerja'=>'T.K.S','keputusan'=>'Lulus'],
                    ];
                @endphp

                @foreach($rows as $r)
                    <tr>
                        <td class="text-center">{{ $r['bil'] }}</td>
                        <td class="text-center">{{ $r['ruj'] }}</td>
                        <td>{{ $r['nama'] }}</td>
                        <td class="text-center">{{ $r['gred'] }}</td>
                        <td class="text-center">{{ $r['taraf'] }}</td>
                        <td class="text-center">{{ $r['lokasi'] }}</td>
                        <td class="text-end">{{ $r['harga'] }}</td>
                        <td class="text-center">{{ $r['bwam'] }}</td>
                        <td class="text-center">{{ $r['tempoh'] }}</td>
                        <td class="text-center">{{ $r['sempurna'] }}</td>
                        <td class="text-center">{{ $r['dok'] }}</td>
                        <td class="text-end">{{ $r['modal'] }}</td>
                        <td class="text-center">{{ $r['kerja'] }}</td>
                        <td class="text-center">{{ $r['keputusan'] }}</td>
                        <td class="text-center">
                            <input type="checkbox">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <button type="button" class="btn-kembali" onclick="history.back()">Kembali</button>

        <div class="d-flex gap-2">
            <button type="button" class="btn-simpan" onclick="showSuccessModalB15()">Simpan</button>
            <button type="button" class="btn-hantar" onclick="alert('Hantar: sambung logic backend bila ready.')">Hantar</button>
        </div>
    </div>

</div>

{{-- MODAL: SIMPAN SUCCESS --}}
<div class="modal fade" id="successModalB15" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content modal-card">
            <svg class="confetti" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 42 L34 28 L30 50 Z" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M38 16 C44 20, 48 24, 52 30" stroke="#19c1a7" stroke-width="3" fill="none"/>
                <path d="M40 40 L52 46" stroke="#19c1a7" stroke-width="3"/>
                <path d="M22 18 L18 12" stroke="#19c1a7" stroke-width="3"/>
            </svg>

            <div class="fw-bold" style="font-size:16px;margin-bottom:14px;">
                Maklumat telah berjaya disimpan
            </div>

            <button type="button" class="btn-modal" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<script>
    function showSuccessModalB15(){
        const modal = new bootstrap.Modal(document.getElementById('successModalB15'));
        modal.show();
    }
</script>
@endsection