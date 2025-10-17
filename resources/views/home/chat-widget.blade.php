@extends('layouts.default')

@section('content')
	<div class="page-header">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<h2 class="page-title">Chat Widget</h2>
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
							<h3 class="card-title">Chat Support</h3>
						</div>
						<div class="card-body">
							<div class="empty">
								<div class="empty-icon">
									<i class="ti ti-message-circle"></i>
								</div>
								<p class="empty-title">Chat Widget</p>
								<p class="empty-subtitle text-muted">
									Chat widget akan tersedia tidak lama lagi untuk memberikan bantuan kepada pengguna.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
