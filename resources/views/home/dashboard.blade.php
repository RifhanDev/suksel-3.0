@extends('layouts.v3.master')

@section('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row mb-4">
        <div class="col-lg-9 col-xs-12">
            <ul class="nav nav-pills nav-justified gap-2 mb-4">
                <li class="nav-item">
                    <a class="nav-link active bg-blue-selangor text-white rounded-pill fw-bold shadow-sm"
                        href="{{ asset('dashboard') }}">Maklumat Tender / Sebut Harga</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bg-white text-dark rounded-pill fw-bold border" href="{{ asset('vendor') }}">Maklumat
                        Syarikat</a>
                </li>
            </ul>

            <div class="modern-card p-4">
                <div class="row stacked-form">
                    <div class="col-lg-3 mb-4 mb-lg-0">
                        <ul class="nav nav-pills flex-column gap-2 border-end pe-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link active fw-bold text-start w-100 d-flex justify-content-between align-items-center rounded-3 p-3"
                                    data-bs-toggle="pill" data-bs-target="#db-recom" type="button" role="tab">
                                    Anggaran Tender / Sebut Harga Layak
                                    <span class="badge bg-danger rounded-pill">{{ count($eligibles) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link fw-bold text-start w-100 d-flex justify-content-between align-items-center rounded-3 p-3"
                                    data-bs-toggle="pill" data-bs-target="#db-docs" type="button" role="tab">
                                    Dokumen Dibeli
                                    <span class="badge bg-secondary rounded-pill">{{ count($purchases) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link fw-bold text-start w-100 d-flex justify-content-between align-items-center rounded-3 p-3"
                                    data-bs-toggle="pill" data-bs-target="#db-invites" type="button" role="tab">
                                    Tender Terhad
                                    <span class="badge bg-secondary rounded-pill">{{ count($invites) }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-start w-100 p-3 rounded-3" data-bs-toggle="pill"
                                    data-bs-target="#db-refund" type="button" role="tab">
                                    Pemulangan Semula
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-start w-100 p-3 rounded-3" data-bs-toggle="pill"
                                    data-bs-target="#db-penilaian-prestasi" type="button" role="tab">
                                    Penilaian Prestasi
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content col-lg-9 ps-lg-4">
                        <div class="tab-pane fade show active" id="db-recom" role="tabpanel">
                            @if (count($eligibles) > 0)
                                <div class="stats-card">
                                    <div class="stats-card-body p-2">
                                        <div class="table-responsive">
                                            <table class="DT2 table table-modern table-hover align-middle mb-0 w-100">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="text-uppercase text-muted small fw-bold py-3 ps-4">Tender
                                                            / Sebut Harga</th>
                                                        <th class="text-uppercase text-muted small fw-bold py-3 pe-4">Tarikh
                                                            Tutup</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($eligibles as $tender)
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="fw-bold text-dark">{{ $tender->tenderer->name }}
                                                                </div>
                                                                <div class="small text-muted mb-1">{{ $tender->ref_number }}
                                                                </div>
                                                                <a href="{{ asset('tenders/' . $tender->id) }}"
                                                                    class="text-decoration-none text-primary fw-bold"
                                                                    style="font-size: 0.9rem;">{{ $tender->name }}</a>
                                                            </td>
                                                            <td class="pe-4 text-nowrap">
                                                                <span class="badge bg-light text-dark border p-2">
                                                                    <i class="icon-calendar me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($tender->submission_datetime)->format('j M Y') }}
                                                                    <span class="text-muted ms-1">12:00 PM</span>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center gap-2">
                                    <i class="icon-info"></i> Tiada tender yang layak buat masa ini.
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="db-docs" role="tabpanel">
                            @if (count($purchases) > 0)
                                <div class="stats-card">
                                    <div class="stats-card-body p-2">
                                        <div class="table-responsive">
                                            <table class="DT3 table table-modern table-hover align-middle mb-0 w-100">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="text-uppercase text-muted small fw-bold py-3 ps-4">Tender
                                                            / Sebut Harga</th>
                                                        <th class="text-uppercase text-muted small fw-bold py-3"
                                                            style="width: 20%;">Tarikh Tutup</th>
                                                        <th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4"
                                                            style="width: 15%;">Tindakan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($purchases as $purchase)
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="fw-bold text-dark">
                                                                    {{ $purchase->tender->tenderer->name }}</div>
                                                                <div class="small text-muted mb-1">
                                                                    {{ $purchase->tender->ref_number }}</div>
                                                                <a href="{{ asset('tenders/' . $purchase->tender->id) }}"
                                                                    class="text-decoration-none text-primary fw-bold"
                                                                    style="font-size: 0.9rem;">{{ $purchase->tender->name }}</a>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-light text-dark border p-2">
                                                                    <i class="icon-calendar me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($purchase->tender->submission_datetime)->format('j M Y') }}
                                                                    <span class="text-muted ms-1">12:00 PM</span>
                                                                </span>
                                                            </td>
                                                            <td class="pe-4 text-center">
                                                                <div class="d-flex flex-column gap-2">
                                                                    <a href="{{ asset('tenders/' . $purchase->tender_id . '/receipt/' . $purchase->id) }}"
                                                                        target="_blank"
                                                                        class="btn-action btn-action-slate w-100 justify-content-center">
                                                                        <i class="icon-printer"></i> Resit
                                                                    </a>
                                                                    <a href="{{ asset('tenders/' . $purchase->tender_id . '/document/' . $purchase->id) }}"
                                                                        target="_blank"
                                                                        class="btn-action btn-action-slate w-100 justify-content-center">
                                                                        <i class="icon-doc"></i> No. Siri
                                                                    </a>
                                                                    <a href="{{ asset('tenders/' . $purchase->tender_id) }}#tf-doc2"
                                                                        target="_blank"
                                                                        class="btn-action btn-action-blue w-100 justify-content-center">
                                                                        <i class="icon-cloud-download"></i> Muat Turun
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center gap-2">
                                    <i class="icon-info"></i> Tiada dokumen yang dibeli.
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="db-invites" role="tabpanel">
                            @if (count($invites) > 0)
                                <div class="stats-card">
                                    <div class="stats-card-body p-2">
                                        <div class="table-responsive">
                                            <table class="DT2 table table-modern table-hover align-middle mb-0 w-100">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="text-uppercase text-muted small fw-bold py-3 ps-4">
                                                            Tender / Sebut Harga</th>
                                                        <th class="text-uppercase text-muted small fw-bold py-3 pe-4"
                                                            style="width: 20%;">Tarikh Tutup</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($invites as $invite)
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="fw-bold text-dark">
                                                                    {{ $invite->tender->tenderer->name }}</div>
                                                                <div class="small text-muted mb-1">
                                                                    {{ $invite->tender->ref_number }}</div>
                                                                <a href="{{ asset('tenders/' . $invite->tender->id) }}"
                                                                    class="text-decoration-none text-primary fw-bold"
                                                                    style="font-size: 0.9rem;">{{ $invite->tender->name }}</a>
                                                            </td>
                                                            <td class="pe-4 text-nowrap">
                                                                <span class="badge bg-light text-dark border p-2">
                                                                    <i class="icon-calendar me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($invite->tender->submission_datetime)->format('j M Y') }}
                                                                    <span class="text-muted ms-1">12:00 PM</span>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info d-flex align-items-center gap-2">
                                    <i class="icon-info"></i> Tiada jemputan tender buat masa ini.
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="db-refund" role="tabpanel">
                            <div class="alert-selangor mb-4">
                                <div class="alert-selangor-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                        </path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                </div>
                                <div class="small lh-sm">
                                    <strong>Arahan/Makluman Berkaitan:</strong>
                                    <ol class="mt-2 mb-0 ps-3">
                                        <li class="mb-1">Muat turun 'Templat Surat Permohonan' yang disediakan.</li>
                                        <li class="mb-1">Sila <b>tukar</b> kandungan dokumen tersebut yang berwarna <span
                                                class="text-danger">merah</span> dengan maklumat pemohon dan
                                            <b>hitamkan</b> semula.</li>
                                        <li>Selepas permohonan diluluskan oleh BPM, <span
                                                class="text-decoration-underline">semua penyata, resit, surat dan borang
                                                yang lengkap wajib dicetak dan dihantar secara pos / fizikal</span> ke:<br>
                                            <b>Bahagian Khidmat Pengurusan, Unit Kewangan, Tingkat 17,<br>Bangunan Sultan
                                                Salahuddin Abdul Aziz Shah,<br>40503 Shah Alam, Selangor Darul Ehsan</b>
                                        </li>
                                    </ol>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <a href="{{ route('refunds.create') }}" type="button"
                                    class="btn-form btn-form-primary">
                                    <i class="icon-plus"></i> Permohonan Baru
                                </a>
                                <a download href="{{ asset('file/Template Surat Permohonan Pelanggan 2022.docx') }}"
                                    type="button" class="btn-form btn-form-amber">
                                    <i class="icon-cloud-download"></i> Templat Surat Permohonan
                                </a>
                            </div>
                            <hr>
                            <div class="stats-card">
                                <div class="stats-card-body p-2">
                                    <div class="table-responsive">
                                        <table class="DT4 table table-modern table-hover align-middle mb-0 w-100">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-uppercase text-muted small fw-bold py-3 ps-4">No
                                                        Rujukan</th>
                                                    <th class="text-uppercase text-muted small fw-bold py-3">Tarikh Dimohon
                                                    </th>
                                                    <th class="text-uppercase text-muted small fw-bold py-3">No Resit</th>
                                                    <th class="text-uppercase text-muted small fw-bold py-3">Tarikh
                                                        Dikemaskini</th>
                                                    <th class="text-uppercase text-muted small fw-bold py-3">Status</th>
                                                    <th class="text-uppercase text-muted small fw-bold py-3">Amaun</th>
                                                    <th
                                                        class="text-uppercase text-center text-muted small fw-bold py-3 pe-4">
                                                        Tindakan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($refunds as $refund)
                                                    <tr>
                                                        <td class="ps-4 fw-bold text-dark">{{ $refund->ref_num }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($refund->created_at)) }}</td>
                                                        <td>{{ $refund->receipt }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($refund->updated_at)) }}</td>
                                                        <td>
                                                            @if ($refund->status == 'Lulus' || $refund->status == 'Diluluskan')
                                                                <span
                                                                    class="badge bg-success rounded-pill px-3">{{ $refund->status }}</span>
                                                            @elseif($refund->status == 'Ditolak')
                                                                <span
                                                                    class="badge bg-danger rounded-pill px-3">{{ $refund->status }}</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-warning text-dark rounded-pill px-3">{{ $refund->status }}</span>
                                                            @endif
                                                        </td>
                                                        <td><strong>RM {{ number_format($refund->amount, 2) }}</strong>
                                                        </td>
                                                        <td class="pe-4 text-center">
                                                            <a href="{{ route('refunds.show', $refund->id) }}"
                                                                class="btn-action btn-action-blue">Papar</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Content Tab - Penilaian Prestasi Syarikat --}}
                        @include('home.tab-contents.penilaian-prestasi')

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-12">
            @include('layouts._news')
        </div>
    </div>

@endsection

@section('scripts')
    {{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/news.js') }}"></script> --}}
    <script>
        $('.DT2').DataTable();
        $('.DT3').DataTable({
            order: [
                [1, 'desc']
            ]
        });
        $('.DT4').DataTable({
            order: [
                [1, 'desc']
            ]
        });
    </script>
@endsection
