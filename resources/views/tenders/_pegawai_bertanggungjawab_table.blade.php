@php
    $pegawaiDisplay = $pegawaiDisplay ?? \App\Support\TenderPegawaiPresenter::for($tender);
    $pegawai1 = $pegawaiDisplay->pegawai1();
    $pegawai2 = $pegawaiDisplay->pegawai2();
    $showTwoColumns = $pegawaiDisplay->hasPegawai2();
    $tableClass = $tableClass ?? 'table mb-0';
    $headerBg = $headerBg ?? 'background:#f8fafc;';
@endphp

@if ($showTwoColumns)
    <table class="{{ $tableClass }}">
        <thead>
            <tr>
                <th colspan="2" class="text-center" style="{{ $headerBg }}">Pegawai Bertanggungjawab 1</th>
                <th colspan="2" class="text-center" style="{{ $headerBg }}">Pegawai Bertanggungjawab 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Nama</th><td>{{ $pegawai1['nama'] }}</td>
                <th>Nama</th><td>{{ $pegawai2['nama'] }}</td>
            </tr>
            <tr>
                <th>E-mel</th><td>{{ $pegawai1['emel'] }}</td>
                <th>E-mel</th><td>{{ $pegawai2['emel'] }}</td>
            </tr>
            <tr>
                <th>No. Tel</th><td>{{ $pegawai1['tel'] }}</td>
                <th>No. Tel</th><td>{{ $pegawai2['tel'] }}</td>
            </tr>
            <tr>
                <th>Jabatan</th><td>{{ $pegawai1['jabatan'] }}</td>
                <th>Jabatan</th><td>{{ $pegawai2['jabatan'] }}</td>
            </tr>
        </tbody>
    </table>
@else
    <table class="{{ $tableClass }}">
        <thead>
            <tr>
                <th colspan="2" class="text-center" style="{{ $headerBg }}">Pegawai Bertanggungjawab</th>
            </tr>
        </thead>
        <tbody>
            <tr><th>Nama</th><td>{{ $pegawai1['nama'] }}</td></tr>
            <tr><th>E-mel</th><td>{{ $pegawai1['emel'] }}</td></tr>
            <tr><th>No. Tel</th><td>{{ $pegawai1['tel'] }}</td></tr>
            <tr><th>Jabatan</th><td>{{ $pegawai1['jabatan'] }}</td></tr>
        </tbody>
    </table>
@endif
