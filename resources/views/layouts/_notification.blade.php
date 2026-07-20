@if ($danger = session('danger'))
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		{!! $danger !!}
	</div>
@endif
@if ($error = session('error'))
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		<i class="ti ti-alert-triangle"></i> {!! $error !!}
	</div>
@endif
@if ($info = session('info'))
	<div class="alert alert-info alert-dismissible" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		{!! $info !!}
	</div>
@endif
@if ($info = session('notice'))
	<div class="alert alert-info alert-dismissible" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		{!! $info !!}
	</div>
@endif
@if ($warning = session('warning'))
	<div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		{!! $warning !!}
	</div>
@endif
@if ($warning = session('alert'))
	<div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		{!! $warning !!}
	</div>
@endif
@if ($success = session('success'))
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		<i class="ti ti-check"></i> {!! $success !!}
	</div>
@endif
@if ($upload_errors = session('upload_errors'))
	@foreach ($upload_errors as $error)
		<div class="alert alert-danger" role="alert">
			{!! $error !!}
		</div>
	@endforeach
@endif
@if ($bulk_errors = session('bulk_errors'))
	<div class="alert alert-danger" role="alert">
		Sistem tidak dapat mengemaskini data bagi syarikat berikut:
		<ul>
			@foreach ($bulk_errors as $number => $name)
				<li>{{ $number }} - {{ $name }}</li>
			@endforeach
		</ul>
	</div>
@endif
