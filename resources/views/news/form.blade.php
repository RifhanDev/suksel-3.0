<div class="card modern-form-card">
	<div class="card-header">
		<h3 class="card-title">
			<i class="ti ti-news"></i>
			Maklumat Berita
		</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<!-- Title Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label required">
					<i class="ti ti-file-text"></i>
					Tajuk
				</label>
				{!! Former::text('title')->label(false)->placeholder('Masukkan tajuk berita')->required()->class('form-control') !!}
			</div>

			<!-- Content Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label required">
					<i class="ti ti-file-description"></i>
					Kandungan
				</label>
				<textarea class="form-control" rows="4" required="true" id="notification" name="notification">{!! isset($news) ? $news->notification : '' !!}</textarea>
				<div id="notification-editor" class="summernote">{!! isset($news) ? $news->notification : '' !!}</div>
			</div>

			<!-- Agency Field (Admin only) -->
			@if (Auth::user()->hasRole('Admin'))
				<div class="col-md-12 mb-3">
					<label class="form-label required">
						<i class="ti ti-building"></i>
						Agensi
					</label>
					{!! Former::select('organization_unit_id')->label(false)->options(App\OrganizationUnit::all()->pluck('name', 'id'))->required()->class('form-control') !!}
				</div>
			@endif

			<!-- Tender Field -->
			<div class="col-md-12 mb-3">
				<label class="form-label">
					<i class="ti ti-file-text"></i>
					Tender
				</label>
				{!! Former::select('tender_id')->label(false)->options([])->placeholder('Sila cari menggunakan nama tender atau no rujukan...')->class('form-control') !!}
			</div>
		</div>
	</div>
</div>
