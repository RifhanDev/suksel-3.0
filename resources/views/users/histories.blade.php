@extends('layouts.v3.master')

@section('styles')
<style>
	.page-title-text {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .title-pipe {
        font-size: 1.5rem;
        color: #cbd5e1;
        font-weight: 300;
        margin: 0 15px;
    }

    .vendor-highlight-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--sg-red);
        letter-spacing: 0.5px;
        text-shadow: 0 2px 4px rgba(196, 30, 58, 0.1); 
    }
	
    .stats-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .stats-card-header {
        padding: 20px 24px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 10px;
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
    }
    .table-modern tbody td {
        padding: 16px 20px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody tr:hover { background-color: #fff9f9; }

    .profile-card {
        background: linear-gradient(160deg, var(--sg-red) 0%, var(--sg-red-dark) 100%);
        color: white;
        border-radius: 12px;
        padding: 2rem;
        position: sticky;
        top: 120px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(196, 30, 58, 0.2);
    }
    .profile-card::after {
        content: ''; position: absolute; bottom: -30px; right: -30px; width: 120px; height: 120px;
        background: var(--sg-yellow); opacity: 0.15; border-radius: 30px; transform: rotate(45deg); pointer-events: none;
    }
    .profile-avatar {
        width: 80px; height: 80px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(5px);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800; color: white;
        margin-bottom: 1.5rem;
    }

    .link-slide-underline {
        position: relative; text-decoration: none !important; transition: color 0.3s ease-in-out; padding-bottom: 3px;
    }
    .link-slide-underline:hover { color: var(--sg-red) !important; }
    .link-slide-underline::after {
        content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0;
        background-color: var(--sg-red); transition: width 0.3s ease-in-out;
    }
    .link-slide-underline:hover::after { width: 100%; }
</style>
@endsection

@section('content')

<div class="mb-4 d-flex align-items-center flex-wrap">
	<h2 class="page-title-text m-0">
		Aktiviti Pengguna
	</h2>
	<span class="title-pipe">|</span>
	<span class="vendor-highlight-text">
		{{ $view_user->name }}
	</span>
</div>

<div class="row g-4">
    
    <!-- LEFT -->
    <div class="col-lg-3">
        <div class="profile-card">
            
            <div class="profile-avatar">
                {{ strtoupper(substr($view_user->name, 0, 1)) }}
            </div>

            <h5 class="fw-bold mb-1">{{ $view_user->name }}</h5>
            <p class="text-white-50 small mb-4">{{ $view_user->email }}</p>

            <hr class="border-white opacity-25 my-4">

            <div class="d-flex flex-column gap-3">
                @if ($view_user->agency)
                    <div>
                        <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Agensi</label>
                        <div class="fw-bold fs-6">{{ $view_user->agency->name }}</div>
                    </div>
                @endif

                @if ($view_user->vendor)
                    <div>
                        <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Syarikat</label>
                        <div class="fw-bold fs-6">{{ $view_user->vendor->name }}</div>
                    </div>
                @endif

                <div>
                    <label class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Peranan</label>
                    <div class="d-flex flex-wrap gap-1 mt-1">
                        @foreach ($view_user->roles as $role)
                            <span class="badge bg-white bg-opacity-25 border border-white border-opacity-25 text-white fw-normal">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- RIGHT -->
    <div class="col-lg-9">
        <div class="stats-card mb-4">
            
            <div class="stats-card-header">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2 text-dark">
				<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-2" style="width: 32px; height: 32px;">
					<svg style="color: var(--sg-red);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
				</div>
                    Log Aktiviti
                </h5>
            </div>

            <div class="card-body p-2">
                <div class="table-responsive">
                    <table data-path="{{ asset('users/' . $view_user->id . '/histories') }}"
                        class="DT-index table table-modern w-100 mb-0">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Tarikh</th>
                                <th>Aktiviti</th>
                                <th style="width: 25%;">Pengguna Ketiga</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start pb-5">
            @if ($view_user->vendor)
                <a href="{{ asset('vendors/' . $view_user->vendor_id) }}" class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2 link-slide-underline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span class="fw-bold">Maklumat Syarikat</span>
                </a>
            @else
                <a href="{{ asset('users') }}" class="btn btn-link text-secondary text-decoration-none d-flex align-items-center gap-2 link-slide-underline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span class="fw-bold">Senarai Pengguna</span>
                </a>
            @endif
        </div>

    </div>
</div>

@endsection

@section('scripts')
	{{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
	<script type="text/javascript">
		$('.DT-index').each(function() {
			var target = $(this);
			var path = target.data('path');
			var DT = target.DataTable({
				ajax: path,
				columns: [{
						data: 'created_at',
						name: 'created_at'
					},
					{
						data: 'action',
						name: 'action'
					},
					{
						data: '3p_id',
						name: '3p_id'
					},
				],
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
				aaSorting: []
			});
		});
	</script>
@endsection