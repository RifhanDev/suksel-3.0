@extends('layouts.v3.master')

@section('styles')
<style>
    .page-title-text { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; }
    .stats-card {
        background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); overflow: hidden; position: relative;
    }
    .stats-card-header {
        padding: 20px 24px; background: #fff; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .stats-card-title {
        margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b;
        display: flex; align-items: center; gap: 10px;
    }
    .table-modern thead th {
        background-color: #f8fafc; color: #64748b; font-weight: 700;
        text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;
        padding: 14px 20px; border-bottom: 2px solid #e2e8f0; white-space: nowrap;
    }
    .table-modern tbody td {
        padding: 16px 20px; vertical-align: middle; color: #334155;
        font-size: 0.9rem; border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody tr:hover { background-color: #fff9f9; }
</style>
@endsection

@section('content')

	<div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Tender / Sebutharga</h3>
			<p class="text-muted small m-0">Tender dengan lawatan tapak dan syarikat yang telah membeli dokumen.</p>
		</div>
	</div>

	@if (session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<form method="get" action="{{ route('lawatanTapakUrusetia') }}" class="card border shadow-sm mb-2 rounded-3">
		<div class="card-body p-3">
			<div class="row g-2 align-items-end">
				<div class="col-12 col-lg-2">
					<label for="filter_no_tender" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender</label>
					<input type="text" name="no_tender" id="filter_no_tender" class="form-control form-control-sm"
						placeholder="Cth: TDR800034" value="{{ request('no_tender') }}">
				</div>
				<div class="col-12 col-lg-4">
					<label for="filter_tajuk" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tajuk Perolehan</label>
					<input type="text" name="tajuk" id="filter_tajuk" class="form-control form-control-sm"
						placeholder="Cari tajuk projek..." value="{{ request('tajuk') }}">
				</div>
				<div class="col-6 col-lg-2">
					<label for="filter_status" class="form-label small fw-bold text-secondary text-uppercase mb-1">Status</label>
					<select name="status" id="filter_status" class="form-select form-select-sm">
						<option value="">Semua</option>
						<option value="belum_disiarkan" @selected(request('status') === 'belum_disiarkan')>Belum Selesai</option>
					</select>
				</div>
				<div class="col-6 col-lg-2">
					<label for="filter_tarikh" class="form-label small fw-bold text-secondary text-uppercase mb-1">Tarikh Jual</label>
					<input type="text" name="tarikh" id="filter_tarikh" class="form-control form-control-sm"
						placeholder="dd/mm/yyyy" value="{{ request('tarikh') }}">
				</div>
				<div class="col-12 col-lg-2">
					<div class="d-flex gap-2">
						<a href="{{ route('lawatanTapakUrusetia') }}" class="btn btn-md btn-light border w-100">Reset</a>
						<button type="submit" class="btn btn-md btn-selangor fw-medium w-100">Tapis</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="stats-card mb-4">
		<div class="stats-card-header">
			<h3 class="stats-card-title">
				<div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-2" style="width: 36px; height: 36px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
				</div>
				Senarai Tender ({{ $tenders->count() }})
			</h3>
		</div>
		<div class="card-body p-2">
			<div class="table-responsive">
				<table class="table table-modern w-100 mb-0">
					<thead>
						<tr>
							<th>Maklumat Tender</th>
							<th class="text-center" width="120px">Pembeli</th>
							<th class="text-center" width="150px">Lawatan</th>
							<th class="text-center" width="150px">Status</th>
							<th class="text-center" width="150px">Tindakan</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($tenders as $tender)
							<tr>
								<td>
									<strong>{{ $tender->name }}</strong><br>
									<small class="text-muted">
										{{ $tender->ref_number ?: $tender->no_tender }}
										&bull; {{ $tender->tenderer?->name ?? '-' }}
									</small>
								</td>
								<td class="text-center">
									<span class="badge bg-primary">{{ $tender->purchases_count }}</span>
								</td>
								<td class="text-center">
									<span class="badge bg-secondary">{{ $tender->siteVisits->count() }}</span>
								</td>
								<td class="text-center">
									<span class="badge {{ $tender->lawatan_status['class'] }}">
										{{ $tender->lawatan_status['label'] }}
									</span>
								</td>
								<td class="text-center">
									<a href="{{ route('pengesahanLawatanTapak', $tender->id) }}" class="btn btn-sm btn-info text-white">
										Kemaskini
									</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="text-center text-muted py-4">Tiada tender dengan lawatan tapak dan pembeli dokumen.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>

@endsection
