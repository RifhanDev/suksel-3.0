@extends('layouts.v3.master')

@section('styles')
<style>
    .page-title-text {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
</style>
@endsection

@section('content')

	<!-- HEADER -->
	<div class="d-flex flex-column flex-lg-row justify-content-start align-items-start align-items-lg-center mb-4">
		<!-- Title -->
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Senarai Tender / Sebut Harga</h3>
			<p class="text-muted small m-0">Paparan maklumat tender terkini di bawah Sistem e-Perolehan Selangor.</p>
		</div>
	</div>

	<div class="card border shadow-sm mb-2 rounded-3">
		<div class="card-body p-3">

			<div class="row g-2 align-items-end">

				<div class="col-12 col-lg-2">
					<label for="filter_no_tender" class="form-label small fw-bold text-secondary text-uppercase mb-1">No. Tender/Sebut Harga</label>
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

	<div class="content-card p-0">
		<div class="content-card-header">
			<div class="d-flex align-items-center gap-3">
				<div class="content-card-icon" style="width: 38px; height: 38px;">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
						<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
						<polyline points="14 2 14 8 20 8"></polyline>
						<line x1="16" y1="13" x2="8" y2="13"></line>
						<line x1="16" y1="17" x2="8" y2="17"></line>
					</svg>
				</div>
				<h3 class="content-card-title" style="font-size: 1rem;">Senarai Tender / Sebut Harga</h3>
			</div>
		</div>

		<div class="content-card-body p-2">
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0 w-100">
					<thead class="bg-light">
						<tr>
							<th class="text-uppercase text-muted small fw-bold py-3 ps-4" width="60px">No.</th>
							<th class="text-uppercase text-muted small fw-bold py-3">Maklumat Tender/Sebut Harga</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3" width="150px">Tarikh Jual</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3" width="150px">Tarikh Tutup</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3" width="150px">Harga (RM)</th>
							<th class="text-uppercase text-center text-muted small fw-bold py-3 pe-4" width="150px">Tindakan</th>
						</tr>
					</thead>
					<tbody>
                        @forelse($tenders ?? [] as $item)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item['no_tender'] }}</strong><br>
                                <small>{{ $item['name'] }}</small>
                            </td>
                            <td class="text-center">{{ $item['tarikh_jual'] }}</td>
                            <td class="text-center">{{ $item['tarikh_tutup'] }}</td>
                            <td class="text-center">{{ $item['harga'] }}</td>
                            <td class="text-center pe-4">
                                <a href="{{ route('jawatankuasaPembuka', ['tender' => $item['uuid']]) }}" class="btn btn-sm btn-info text-white d-inline-flex align-items-center gap-1" title="Kemaskini">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Kemaskini
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tiada tender pada peringkat ini.</td>
                        </tr>
                        @endforelse
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
