@extends('layouts.v3.master')

@section('styles')
<style>
    .lawatan-count { display: inline-flex; flex-direction: column; align-items: center; line-height: 1.15; }
    .lawatan-count-value { font-size: 1.1rem; font-weight: 700; color: #1e293b; }
    .lawatan-count-unit { font-size: 0.7rem; color: #94a3b8; }
    .lawatan-count.is-zero .lawatan-count-value { color: #cbd5e1; }
</style>
@endsection

@section('content')

	<div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Tender / Sebutharga</h3>
			<p class="text-muted small m-0">Tender yang masih dibuka dengan lawatan tapak dan syarikat yang telah membeli dokumen.</p>
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

	<div class="content-card p-0 mb-4">
		<div class="content-card-header">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
						<circle cx="12" cy="10" r="3"></circle>
					</svg>
				</div>
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Tender ({{ $tenders->total() }})</h3>
			</div>
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4" width="60px">No.</th>
							<th class="text-uppercase text-muted small fw-bold py-3" width="200px">No. Tender / Sebut Harga</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Tajuk Perolehan</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3" width="100px">Pembeli</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3" width="100px">Lawatan</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3" width="150px">Status</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" width="130px">Tindakan</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($tenders as $tender)
							@php
								$statusBadge = match ($tender->lawatan_status['key']) {
									'selesai' => 'badge-status-success',
									'belum_disemak' => 'badge-status-warning',
									'menunggu_wakil' => 'badge-status-info',
									default => 'badge-status-neutral',
								};
							@endphp
							<tr>
								<td class="ps-4 text-muted">{{ $tenders->firstItem() + $loop->index }}</td>
								<td class="fw-semibold">{{ $tender->ref_number ?: $tender->no_tender }}</td>
								<td>
									<div>{{ $tender->name }}</div>
									<div class="text-muted small">{{ $tender->tenderer?->name ?? '-' }}</div>
								</td>
								<td class="text-center">
									<span class="lawatan-count {{ $tender->purchases_count ? '' : 'is-zero' }}">
										<span class="lawatan-count-value">{{ $tender->purchases_count }}</span>
										<span class="lawatan-count-unit">syarikat</span>
									</span>
								</td>
								<td class="text-center">
									<span class="lawatan-count {{ $tender->siteVisits->count() ? '' : 'is-zero' }}">
										<span class="lawatan-count-value">{{ $tender->siteVisits->count() }}</span>
										<span class="lawatan-count-unit">lawatan</span>
									</span>
								</td>
								<td class="text-center">
									<span class="badge-status {{ $statusBadge }}">{{ $tender->lawatan_status['label'] }}</span>
								</td>
								<td class="text-center pe-4">
									<a href="{{ route('pengesahanLawatanTapak', $tender->id) }}" class="btn btn-sm btn-info text-white d-inline-flex align-items-center gap-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M12 20h9"></path>
											<path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
										</svg>
										Kemaskini
									</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center text-muted py-4">Tiada tender yang masih dibuka dengan lawatan tapak.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			@if ($tenders->hasPages())
				<div class="d-flex justify-content-end px-3 py-3">
					{{ $tenders->links('pagination::bootstrap-5') }}
				</div>
			@endif
		</div>
	</div>

@endsection
