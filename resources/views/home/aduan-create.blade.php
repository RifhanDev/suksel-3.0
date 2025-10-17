@extends('layouts.default')

@section('content')
	<div class="page-header">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<h2 class="page-title">Aduan</h2>
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
							<h3 class="card-title">Buat Aduan</h3>
						</div>
						<div class="card-body">
							<form>
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label class="form-label">Nama</label>
											<input type="text" class="form-control" placeholder="Masukkan nama anda">
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label class="form-label">Email</label>
											<input type="email" class="form-control" placeholder="Masukkan email anda">
										</div>
									</div>
								</div>
								<div class="mb-3">
									<label class="form-label">Subjek</label>
									<input type="text" class="form-control" placeholder="Masukkan subjek aduan">
								</div>
								<div class="mb-3">
									<label class="form-label">Keterangan</label>
									<textarea class="form-control" rows="5" placeholder="Masukkan keterangan aduan anda"></textarea>
								</div>
								<div class="form-footer">
									<button type="submit" class="btn btn-primary">Hantar Aduan</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
