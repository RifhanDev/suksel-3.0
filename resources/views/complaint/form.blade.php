@section('styles')
	<style>
		.fixed_width {
			resize: vertical;
		}

		.user-info-card {
			background-color: #f8f9fa;
			border: 1px solid #dee2e6;
			border-radius: 0.375rem;
			padding: 1rem;
			margin-bottom: 1.5rem;
		}

		.user-info-row {
			display: flex;
			align-items: center;
			margin-bottom: 0.5rem;
		}

		.user-info-row:last-child {
			margin-bottom: 0;
		}

		.user-info-label {
			font-weight: 600;
			color: #495057;
			min-width: 120px;
			margin-right: 0.5rem;
		}

		.user-info-value {
			color: #212529;
		}
	</style>
@endsection

@if (auth()->check())
	<?php $user = auth()->user(); ?>
	<div class="user-info-card">
		<h5 class="mb-3">
			<i class="ti ti-user me-2"></i>Maklumat Pengguna
		</h5>
		<div class="user-info-row">
			<span class="user-info-label">
				<i class="ti ti-user me-1"></i>Nama:
			</span>
			<span class="user-info-value">{{ $user->name }}</span>
		</div>
		<div class="user-info-row">
			<span class="user-info-label">
				<i class="ti ti-mail me-1"></i>Email:
			</span>
			<span class="user-info-value">{{ $user->email }}</span>
		</div>
		@if ($user->roles->count() > 0)
			<div class="user-info-row">
				<span class="user-info-label">
					<i class="ti ti-shield me-1"></i>Peranan:
				</span>
				<span class="user-info-value">
					@foreach ($user->roles as $role)
						<span class="badge bg-primary me-1">{{ $role->name }}</span>
					@endforeach
				</span>
			</div>
		@endif
		@if ($user->hasRole('Vendor') && $user->vendor)
			<div class="user-info-row">
				<span class="user-info-label">
					<i class="ti ti-building me-1"></i>Nama Syarikat:
				</span>
				<span class="user-info-value">{{ $user->vendor->name }}</span>
			</div>
		@endif
		@if ($user->agency)
			<div class="user-info-row">
				<span class="user-info-label">
					<i class="ti ti-building me-1"></i>Agensi:
				</span>
				<span class="user-info-value">{{ $user->agency->name }}</span>
			</div>
		@endif
	</div>
@endif

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

	@if (!empty($modules))
		<div class="col-md-12 mb-3">
			<label class="form-label required">
				<i class="ti ti-category me-1"></i>Isu utama / Modul sistem
			</label>
			<select name="module" id="complaint-module" class="form-select" required>
				<option value="">-- Sila pilih modul --</option>
				@foreach ($modules as $key => $label)
					<option value="{{ $key }}" {{ old('module') === $key ? 'selected' : '' }}>{{ $label }}</option>
				@endforeach
			</select>
			<small class="text-muted">Pilih bahagian sistem yang berkaitan dengan aduan anda.</small>
		</div>

		{{-- Tender field: show when "Tender / Sebut Harga" is selected. Always render wrap so JS can show/hide it. --}}
		<div class="col-md-12 mb-3" id="complaint-tender-wrap" style="display: none;">
			@if (isset($userTenders) && $userTenders->isNotEmpty())
				<label class="form-label" id="complaint-tender-label">
					<i class="ti ti-file-text me-1"></i>Pilih Tender / Sebut Harga
				</label>
				<select name="tender_id" id="complaint-tender-id" class="form-select">
					<option value="">-- Sila pilih tender --</option>
					@foreach ($userTenders as $t)
						<option value="{{ $t->id }}" {{ old('tender_id') == $t->id ? 'selected' : '' }}>
							{{ $t->ref_number }} – {{ $t->name }} ({{ $t->type === 'quotation' ? 'Sebut Harga' : 'Tender' }})
						</option>
					@endforeach
				</select>
				<small class="text-muted">Hanya tender yang anda layak dipaparkan.</small>
			@elseif (auth()->check())
				<div class="alert alert-info mb-0">
					<i class="ti ti-info-circle me-2"></i>Tiada tender untuk dipilih. Anda boleh terus hantar aduan tanpa memilih
					tender tertentu.
				</div>
			@else
				<div class="alert alert-secondary mb-0">
					<i class="ti ti-info-circle me-2"></i>Daftar masuk untuk memilih tender tertentu (jika ada).
				</div>
			@endif
		</div>
	@endif

	<div class="col-md-12 mb-3">
		<label class="form-label required">
			<i class="ti ti-notes me-1"></i>Kandungan
		</label>
		{!! Former::textarea('content')->id('content')->addClass('fixed_width')->rows(10)->label(false)->placeholder('Masukkan kandungan aduan anda')->required()->class('form-control') !!}
	</div>

	@if (config('captcha.site'))
		<div class="col-md-12 mb-3">
			<div class="g-recaptcha" data-sitekey="{{ config('captcha.site') }}"></div>
		</div>
	@else
		<div class="col-md-12 mb-3">
			<input type="hidden" name="g-recaptcha-response" value="bypass-no-key">
		</div>
	@endif
</div>

@section('scripts')
	@if (config('captcha.site'))
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	@endif
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var moduleSelect = document.getElementById('complaint-module');
			var tenderWrap = document.getElementById('complaint-tender-wrap');
			var tenderSelect = document.getElementById('complaint-tender-id');
			if (!moduleSelect || !tenderWrap) return;

			function toggleTender() {
				var isTender = moduleSelect.value === 'tender';
				tenderWrap.style.display = isTender ? 'block' : 'none';
				if (tenderSelect) {
					tenderSelect.value = isTender ? tenderSelect.value : '';
					tenderSelect.disabled = !isTender;
					tenderSelect.required = isTender && tenderSelect.options && tenderSelect.options.length > 1;
				}
			}

			moduleSelect.addEventListener('change', toggleTender);
			toggleTender();
		});
	</script>
@endsection
