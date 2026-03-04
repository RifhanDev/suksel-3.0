@extends('layouts.v3.master')

@section('styles')
<style>
    .page-title-text { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; }
    .stats-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        position: relative;
    }
    .stats-card::before {
        content: ''; position: absolute; top: -25px; right: -25px; width: 80px; height: 80px;
        background: var(--sg-red); opacity: 0.03; border-radius: 20px; transform: rotate(45deg); pointer-events: none;
    }
    .stats-card-header {
        padding: 20px 16px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .stats-card-title {
        margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px;
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
    .table-modern tbody tr:hover { background-color: #fafafa; }
</style>
@endsection

@section('content')
<!-- HEADER -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
    <div class="mb-3 mb-lg-0">
        <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Tetapan Pembayaran</h3>
        <p class="text-muted small m-0">Pengurusan saluran pembayaran dan tetapan gateway.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border">TARIKH</span>
            <span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
        </div>
    </div>
</div>

<div class="stats-card mb-4">
    <div class="stats-card-header">
        <h3 class="stats-card-title">
            <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
            </div>
            Maklumat Gateway
        </h3>
        @if (App\Gateway::canCreate())
            <a href="{{ asset('gateways/create') }}" class="btn btn-selangor d-flex align-items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Masukkan Tetapan Baru
            </a>
        @endif
    </div>
    <div class="card-body p-2">
        <div class="table-responsive">
            <table data-path="/gateways" class="DT-index table table-modern w-100 mb-0">
                <thead>
                    <tr>
                        <th width="15%">Agensi</th>
                        <th width="12%">Saluran</th>
                        <th width="15%">ID Merchant</th>
                        <th width="8%">Versi</th>
                        <th width="8%">Active</th>
                        <th width="8%">Utama</th>
                        <th width="120px">Tindakan</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/datatables.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.DT-index').each(function() {
            var target = $(this);
            var path = target.data('path');
            var DT = target.DataTable({
                ajax: path,
                columns: [
                    { data: 'organization_unit_id', name: 'organization_unit_id' },
                    { data: 'type', name: 'type' },
                    { data: 'merchant_code', name: 'merchant_code' },
                    { data: 'version', name: 'version' },
                    { data: 'active', name: 'active' },
                    { data: 'default', name: 'default' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                serverSide: true,
                stateSave: true,
                language: {
                    sEmptyTable: "Tiada data",
                    sInfo: "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
                    sInfoEmpty: "Paparan 0 hingga 0 dari 0 rekod",
                    sInfoFiltered: "(Ditapis dari jumlah _MAX_ rekod)",
                    sInfoPostFix: "", sInfoThousands: ",",
                    sLengthMenu: "Papar _MENU_ rekod",
                    sLoadingRecords: "Diproses...", sProcessing: "Sedang diproses...",
                    sSearch: "Carian:", sZeroRecords: "Tiada padanan rekod yang dijumpai.",
                    oPaginate: { sFirst: "Pertama", sPrevious: "Sebelum", sNext: "Kemudian", sLast: "Akhir" },
                    oAria: { sSortAscending: ": diaktifkan kepada susunan lajur menaik", sSortDescending: ": diaktifkan kepada susunan lajur menurun" }
                },
                aaSorting: [], pageLength: 25, responsive: true, order: [[0, 'asc']]
            });
        });
    });
</script>
@endsection
