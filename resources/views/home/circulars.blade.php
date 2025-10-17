@extends('layouts.default')

@section('content')
	<div class="page-header">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<h2 class="page-title">Pekeliling</h2>
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
							<h3 class="card-title">Senarai Pekeliling</h3>
						</div>
						<div class="card-body">
							<div class="empty">
								<div class="empty-icon">
									<i class="ti ti-file-text"></i>
								</div>
								<p class="empty-title">Tiada Pekeliling</p>
								<p class="empty-subtitle text-muted">
									Tiada pekeliling yang tersedia pada masa ini.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
