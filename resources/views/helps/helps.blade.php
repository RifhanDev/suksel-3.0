<div class="accordion-item">
	<h2 class="accordion-header" id="help-title-{{ $help->id }}">
		<button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
			data-bs-target="#help-{{ $help->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
			aria-controls="help-{{ $help->id }}">
			<i class="ti ti-help-circle me-2"></i>{{ $help->question }}
		</button>
	</h2>
	<div id="help-{{ $help->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
		aria-labelledby="help-title-{{ $help->id }}" data-bs-parent="#helps">
		<div class="accordion-body">
			<div class="mb-3">
				{!! $help->answer !!}
			</div>
			@if (Auth::user() && Auth::user()->hasRole('Admin'))
				<div class="d-flex gap-2 pt-3 border-top">
					<a href="{{ route('helps.edit', $help->id) }}" class="btn btn-sm btn-warning btn-modern">
						<i class="ti ti-edit me-1"></i>Kemaskini
					</a>
					{!! Former::open(url('helps/' . $help->id))->class('form-inline') !!}
					{!! Former::hidden('_method', 'DELETE') !!}
					<button type="button" class="btn btn-sm btn-danger btn-modern confirm-delete">
						<i class="ti ti-trash me-1"></i>Padam
					</button>
					{!! Former::close() !!}
				</div>
			@endif
		</div>
	</div>
</div>
