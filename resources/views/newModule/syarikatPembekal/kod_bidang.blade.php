@extends('layouts.v3.master')

@section('content')

<style>
.kod-header {
    background:#E5E5E5;
    padding:10px 15px;
    font-weight:600;
    border:1px solid #ccc;
}

.kod-table {
    border:1px solid #ccc;
}

.kod-table th {
    width:25%;
    vertical-align:top;
    background:#F9FAFB;
    font-weight:600;
}

.kod-table td {
    vertical-align:top;
    line-height:1.6;
}

.kod-dan {
    font-weight:600;
    margin:8px 0;
}
</style>

<div class="card">
    <div class="kod-header">
        Kod-kod Bidang
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered kod-table mb-0">
            <tbody>
                <tr>
                    <th>Kod Bidang MOF</th>
                    <td>

                        <strong>120601</strong> PERTAHANAN DAN KESELAMATAN &gt; 
                        PERLINDUNGAN KEBAKARAN &gt; SISTEM PENCEGAH KEBAKARAN
                        <div class="kod-dan">DAN</div>

                        <strong>210102</strong> ICT &gt; BEKALAN DAN PERKHIDMATAN BAGI SEKTOR 
                        TEKNOLOGI MAKLUMAT DAN KOMUNIKASI &gt; PERALATAN DAN KELENGKAPAN 
                        KOMPUTER, PERKAKASAN DAN KOMPONEN &gt; HARDWARE (HIGH END TECHNOLOGY) – 
                        ALL TYPES OF SERVER, MAINFRAME, HIGH END PRINTERS, SAN, NAS INCLUDING 
                        MAINTENANCE
                        <div class="kod-dan">DAN</div>

                        <strong>210105</strong> ICT &gt; BEKALAN DAN PERKHIDMATAN BAGI SEKTOR 
                        TEKNOLOGI MAKLUMAT DAN KOMUNIKASI &gt; PERALATAN DAN KELENGKAPAN 
                        KOMPUTER, PERKAKASAN DAN KOMPONEN &gt; TELECOMMUNICATION/NETWORKING – 
                        SUPPLY PRODUCT, INFRASTRUCTURE, SERVICES INCLUDING MAINTENANCE 
                        (LAN/WAN/INTERNET/WIRE)
                        <div class="kod-dan">DAN</div>

                        <strong>220301</strong> PERKHIDMATAN &gt; PENYELENGGARAAN / PEMBAIKAN 
                        ALAT HAWA DINGIN &gt; ALAT HAWA DINGIN (WINDOW, SPLIT, BERPUSAT)

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
