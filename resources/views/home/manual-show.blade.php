@extends('layouts.default')

@section('content')
	<div class="page-header">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<h2 class="page-title">Panduan Pengguna</h2>
					<div class="text-muted mt-1">Manual: {{ ucfirst($manual) }}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page-body">
		<div class="container-xl">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Panduan {{ ucfirst($manual) }}</h3>
						</div>
						<div class="card-body">
							<div class="empty">
								<div class="empty-icon">
									<i class="ti ti-book"></i>
								</div>
								<p class="empty-title">Panduan Tidak Tersedia</p>
								<p class="empty-subtitle text-muted">
									Panduan untuk {{ $manual }} akan tersedia tidak lama lagi.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
