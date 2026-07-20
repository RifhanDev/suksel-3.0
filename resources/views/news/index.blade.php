@extends('layouts.v3.master')

@section('styles')
<style>
    .page-title-text {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .table-modern thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern tbody tr:hover {
        background-color: #fafafa;
    }

    /* ===== TABLE ===== */

    /* News Content Wrapper */
    .news-content {
        padding: 4px 0;
    }

    .news-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.5;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-title:hover {
        color: #c41e3a;
    }

    .news-excerpt {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Date Column */
    .table-modern tbody td:first-child {
        font-size: 0.875rem;
        font-weight: 500;
        color: #334155;
        white-space: nowrap;
    }

    /* Agency Column */
    .table-modern tbody td:nth-child(2) {
        font-size: 0.875rem;
        color: #475569;
    }

    /* Action Button */
    .table-modern .btn-xs,
    .table-modern .btn-primary {
        padding: 6px 16px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8rem;
        background-color: #c41e3a;
        border: none;
        color: white;
        text-decoration: none;
    }

    .table-modern .btn-xs:hover,
    .table-modern .btn-primary:hover {
        background-color: #a01830;
        color: white;
    }
</style>
@endsection

@section('content')

<!-- HEADER -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
    <div class="mb-3 mb-lg-0">
        <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Berita</h3>
        <p class="text-muted small m-0">Pengurusan berita dan makluman Sistem e-Perolehan Selangor.</p>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border w-lg-auto">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border">TARIKH</span>
            <span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
        </div>
    </div>
</div>

<div class="content-card mb-4">
    <div class="content-card-header">
        <h3 class="content-card-title d-flex align-items-center gap-2 mb-0">
            <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 20l-7-7-7 7V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
            </div>
            Maklumat Berita
        </h3>

        @if (App\News::canCreate())
            <a href="{{ action('NewsController@create') }}" class="btn btn-selangor d-flex align-items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Berita Baru
            </a>
        @endif
    </div>

    <div class="card-body p-2">
        <div class="table-responsive">
            <table data-path="/news" class="DT-index table table-modern w-100 mb-0">
                <thead>
                    <tr>
                        <th width="15%">Tarikh</th>
                        <th width="20%">Agensi</th>
                        <th>Berita</th>
                        <th width="100px">Tindakan</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
            $('.DT-index').each(function() {
                var target = $(this);
                var path = target.data('path');
                
                var columns = [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'organization_unit_id', name: 'organization_unit_id' },
                    { data: 'title', name: 'title' },
                    { 
                        data: 'actions', 
                        name: 'actions', 
                        orderable: false, 
                        searchable: false,
                        className: 'text-center'
                    }
                ];

                var DT = target.DataTable({
                    ajax: path,
                    columns: columns,
                    serverSide: true,
                    stateSave: true,
                    language: {
						sEmptyTable: "Tiada data",
						sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
						sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
						sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
						sInfoPostFix: "",
						sInfoThousands: ",",
						sLengthMenu: "Papar _MENU_ rekod",
						sLoadingRecords: "Diproses...",
						sProcessing: "Sedang diproses...",
						sSearch: "Carian:",
						sZeroRecords: "Tiada padanan rekod yang dijumpai.",
						oPaginate: {
							sFirst: "Pertama",
							sPrevious: "Sebelum",
							sNext: "Kemudian",
							sLast: "Akhir"
						},
						oAria: {
							sSortAscending: ": diaktifkan kepada susunan lajur menaik",
							sSortDescending: ": diaktifkan kepada susunan lajur menurun"
						}
					},
                    aaSorting: [],
                    // dom: '<"row px-3 py-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                    pageLength: 25,
                    responsive: true,
                    order: [[0, 'desc']]
                });
            });
        });
	</script>
@endsection