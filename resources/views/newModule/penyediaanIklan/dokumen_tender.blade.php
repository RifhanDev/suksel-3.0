<!-- SECTION: SENARAI DOKUMEN TENDER -->
<div class="content-card mb-4 p-0">

    <div class="review-section-header">
        <div class="section-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                <polyline points="13 2 13 9 20 9"></polyline>
            </svg>
        </div>
        <div>
            <h6>Senarai Dokumen Tender</h6>
            <small>Senarai dokumen yang diperlukan daripada petender</small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mb-0" style="font-size:0.82rem;">
            <thead>
                <tr>
                    <th style="width:52px;" class="text-center">No.</th>
                    <th>Tender / Sebut Harga</th>
                    <th style="width:200px;" class="text-center">Tindakan Oleh Petender</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dokumenList = [
                        ['nama' => 'Perkhidmatan Penilaian Forensik Keatas Sistem XXXX',              'tindakan' => 'Spesifikasi'],
                        ['nama' => 'Senarai Pengalaman Kerja',                                        'tindakan' => 'Borang Atas Talian'],
                        ['nama' => 'Kerja dalam Tangan',                                              'tindakan' => 'Borang Atas Talian'],
                        ['nama' => 'Surat Pengesahan Prinsipal yang lengkap ditandatangani',          'tindakan' => 'Muat Naik'],
                        ['nama' => 'Senarai Kakitangan Teknikal dan Carta Organisasi Pasukan Projek', 'tindakan' => 'Muat Naik'],
                        ['nama' => 'Salinan Resume dan Sijil Teknikal Kakitangan syarikat',           'tindakan' => 'Muat Naik'],
                        ['nama' => 'Maklumat Profil Petender',                                        'tindakan' => 'Borang Atas Talian'],
                        ['nama' => 'Penyata Bank Terkini (3 Bulan Terakhir) Syarikat',               'tindakan' => 'Borang Atas Talian'],
                        ['nama' => 'Salinan Sijil Pendaftaran dengan Kementerian Kewangan',           'tindakan' => 'Muat Naik'],
                        ['nama' => 'Salinan Sijil Akuan Syarikat Bumiputera dengan Kementerian Kewangan', 'tindakan' => 'Muat Naik'],
                        ['nama' => 'Surat Akuan Pembida Berjaya (Lampiran B)',                        'tindakan' => 'Muat Turun dan Muat Naik'],
                        ['nama' => 'Surat Akuan Sumpah Syarikat (Lampiran C)',                        'tindakan' => 'Muat Turun dan Muat Naik'],
                        ['nama' => 'Penyata Kewangan (2 Tahun) Syarikat yang telah diaudit',         'tindakan' => 'Muat Naik'],
                    ];

                    $tindakanBadge = [
                        'Spesifikasi'              => 'badge-status-info',
                        'Borang Atas Talian'       => 'badge-status-warning',
                        'Muat Naik'                => 'badge-status-success',
                        'Muat Turun dan Muat Naik' => 'badge-status-neutral',
                    ];
                @endphp

                @foreach ($dokumenList as $i => $dok)
                    <tr>
                        <td class="text-center text-muted">{{ $i + 1 }}</td>
                        <td>{{ $dok['nama'] }}</td>
                        <td class="text-center">
                            <span class="badge-status {{ $tindakanBadge[$dok['tindakan']] ?? 'badge-status-neutral' }}">
                                {{ $dok['tindakan'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
<!-- End Senarai Dokumen Tender -->
