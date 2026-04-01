@extends('layouts.v3.master')

@section('styles')
	<link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
@endsection

@section('content')
	<!-- Page Header -->
	<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
		<div class="mb-3 mb-lg-0">
			<h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Kemaskini Berita</h3>
			<p class="text-muted small m-0">Sistem Tender Online Selangor</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-3 bg-white px-3 py-2 rounded-2 shadow-sm border">
			<div class="d-flex align-items-center gap-2">
				<span class="badge bg-light text-dark border">TARIKH</span>
				<span class="small text-muted fw-bold">{{ date('d/m/Y') }}</span>
			</div>
		</div>
	</div>

	<form action="{{ url('news/' . $news->id) }}" method="POST">
		@csrf
		@method('PUT')

		@include('news.form')

		<!-- Action Buttons -->
		<div class="stats-card">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
					<a href="{{ asset('news') }}" class="btn btn-secondary d-flex align-items-center gap-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
						Kembali ke Senarai
					</a>
					<div class="d-flex gap-2 flex-wrap">
						@if ($news->canUpdate())
							@if ($news->publish)
								<a href="{{ asset('news/' . $news->id . '/publish') }}" class="btn btn-warning d-flex align-items-center gap-2 link-confirm">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
									Batal Siar
								</a>
							@else
								<a href="{{ asset('news/' . $news->id . '/publish') }}" class="btn btn-success d-flex align-items-center gap-2 link-confirm">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
									Siar
								</a>
							@endif
						@endif
						@if ($news->canDelete())
							<form action="{{ action('NewsController@destroy', $news->id) }}" method="POST" class="d-inline delete-form">
								@csrf
								@method('DELETE')
								<button type="button" class="btn btn-danger d-flex align-items-center gap-2 confirm-delete">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
									Padam
								</button>
							</form>
						@endif
						<button type="submit" class="btn btn-selangor d-flex align-items-center gap-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
							Kemaskini Berita
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection

@section('scripts')
	<script src="{{ asset('custom_library/ckeditor/ckeditor.js') }}"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			if ($("#organization_unit_id").length) {
				$("#organization_unit_id").selectize();
			}

			if ($("#tender_id").length) {
				$("#tender_id").selectize({
					valueField: 'id',
					labelField: 'name',
					searchField: 'name',
					create: false,
					render: {
						option: function(item, escape) {
							return '<div><strong>' + escape(item.ref_number) + '</strong> ' + escape(item
								.name) + '</div>';
						}
					},
					load: function(query, callback) {
						if (!query.length) return callback();
						$.ajax({
							url: '/tenders/select?q=' + query,
							type: 'GET',
							success: function(res) {
								callback(res);
							},
							error: function() {
								callback();
							}
						})
					}
				});
			}

			if (document.getElementById('notification')) {
				CKEDITOR.replace('notification', {
					toolbarGroups: [{
							name: 'document',
							groups: ['mode', 'document', 'doctools']
						},
						{
							name: 'clipboard',
							groups: ['clipboard', 'undo']
						},
						{
							name: 'editing',
							groups: ['find', 'selection', 'spellchecker', 'editing']
						},
						{
							name: 'forms',
							groups: ['forms']
						},
						{
							name: 'insert',
							groups: ['insert']
						},
						'/',
						{
							name: 'basicstyles',
							groups: ['basicstyles', 'cleanup']
						},
						{
							name: 'paragraph',
							groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
						},
						{
							name: 'links',
							groups: ['links']
						},
						'/',
						{
							name: 'styles',
							groups: ['styles']
						},
						{
							name: 'colors',
							groups: ['colors']
						},
						{
							name: 'tools',
							groups: ['tools']
						},
						{
							name: 'others',
							groups: ['others']
						},
						{
							name: 'about',
							groups: ['about']
						}
					],
					removeButtons: 'Flash,Iframe,Form,TextField,Checkbox,Radio,Textarea,Select,Button,ImageButton,HiddenField'
				});
			}
		});
	</script>
@endsection
