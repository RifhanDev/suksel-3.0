@extends('layouts.v3.master')

@section('styles')
<style>
    .page-title-text {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

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
        padding: 20px 24px;
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

    .table-modern tbody tr:hover {
        background-color: #fff9f9;
    }

    .badge-date {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        padding: 0.4em 0.8em;
    }
</style>
@endsection

@section('content')

	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
		<!-- Title -->
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Tender / Sebutharga</h3>
			<p class="text-muted small m-0">Paparan maklumat tender terkini di bawah Sistem e-Perolehan Selangor.</p>
		</div>
	</div>

	<div class="card border shadow-sm mb-2 rounded-3">
		<div class="card-body p-3">

			<div class="row g-2 align-items-end">

				<div class="col-12 col-lg-2">
					<label for="filter_no_tender" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
					<input type="text" id="filter_no_tender" class="form-control form-control-sm" placeholder="Cth: JPS/01">
				</div>

				<div class="col-12 col-lg-4">
					<label for="filter_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
					<input type="text" id="filter_tajuk" class="form-control form-control-sm" placeholder="Cari tajuk projek...">
				</div>

				<div class="col-6 col-lg-2">
					<label for="filter_status" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
					<select id="filter_status" class="form-select form-select-sm">
						<option value="">Semua</option>
						<option value="belum_disiarkan">Belum Disiarkan</option>
					</select>
				</div>

				<div class="col-6 col-lg-2">
					<label for="filter_tarikh" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh</label>
					<input type="text" id="filter_tarikh" class="form-control form-control-lg datepicker" placeholder="dd/mm/yyyy">
				</div>

				<div class="col-12 col-lg-2">
					<div class="d-flex gap-2">
						<button type="button" id="" class="btn btn-md btn-light border w-100">
							Reset
						</button>
						<button type="button" id="" class="btn btn-md btn-selangor fw-medium w-100">
							Tapis
						</button>
					</div>
				</div>

			</div>

		</div>
	</div>

	<div class="stats-card mb-4">
		<div class="stats-card-header">
			<h3 class="stats-card-title">
				<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
				</div>
				Senarai Tender
			</h3>
		</div>

		<div class="card-body p-2">
			<div class="table-responsive">
				<table data-path="" class=" table table-modern w-100 mb-0">
					<thead>
						<tr>
							<th class="text-center">Maklumat Tender</th>
							<th class="text-center" width="150px">Tarikh Jual</th>
							<th class="text-center" width="150px">Tarikh Tutup</th>
							<th class="text-center" width="150px">Harga (RM)</th>
							<th class="text-center" width="150px">Tindakan</th>
						</tr>
					</thead>
					<tbody>
                         <tr>
                            <td>MEMBEKAL RANGSUM PUKAL (AIR MINERAL) UNTUK BANGUNAN KERAJAAN</td>
                            <td class="text-center">03/01/2026</td>
                            <td class="text-center">01/05/2026</td>
                            <td class="text-center">193,000.00</td>
                            <td class="text-center">
                                <a href="{{ route('penyediaanSuratNiat') }}" class="btn btn-sm btn-info text-white" title="Kemaskini">
                                    Kemaskini
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>PROJEK MENAIKTARAF JALAN PELABUHAN UTARA DARI KLANG CONTAINER TERMINAL</td>
                            <td class="text-center">27/02/2026</td>
                            <td class="text-center">31/07/2026</td>
                            <td class="text-center">5,800,000.00</td>
                            <td class="text-center">
                            <a href="{{ route('penyediaanSuratNiat') }}" class="btn btn-sm btn-info text-white" title="Kemaskini">
                                    Kemaskini
                                </a>
                            </td>
                        </tr>
                    </tbody>
				</table>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {

            
        });
	</script>
@endsection
