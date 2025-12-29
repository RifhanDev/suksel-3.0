@section('styles')
	<style>
		.fixed_width {
			resize: vertical;
		}
	</style>
@endsection

<div class="row">
	@if (!auth()->check())
		<div class="col-md-12 mb-3">
			<label class="form-label required">
				<i class="ti ti-mail me-1"></i>Email
			</label>
			<input type="email" name="email" class="form-control" value="" required
				placeholder="Masukkan alamat email anda">
		</div>
	@else
		<input type="hidden" name="email" value="{{ auth()->user()->email }}">
	@endif

	<div class="col-md-12 mb-3">
		<label class="form-label required">
			<i class="ti ti-file-text me-1"></i>Subjek
		</label>
		{!! Former::text('subject')->label(false)->placeholder('Masukkan subjek aduan')->required()->class('form-control') !!}
	</div>

	<div class="col-md-12 mb-3">
		<label class="form-label required">
			<i class="ti ti-notes me-1"></i>Kandungan
		</label>
		{!! Former::textarea('content')->id('content')->addClass('fixed_width')->rows(10)->label(false)->placeholder('Masukkan kandungan aduan anda')->required()->class('form-control') !!}
	</div>

	<div class="col-md-12 mb-3">
		<div class="g-recaptcha" data-sitekey="{{ Config::get('captcha.site') }}"></div>
	</div>
</div>

@section('scripts')
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
