@extends('layouts.modern')

@section('styles')
	<link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endsection

@section('content')
	{!! Former::open(url('news')) !!}
	@component('components.modern-form', [
		'title' => 'Tambah Berita Baru',
		'pretitle' => 'Sistem Tender Online',
		'icon' => 'ti-news',
		'backUrl' => asset('news'),
		'backLabel' => 'Kembali ke Senarai',
		'submitLabel' => 'Hantar Berita',
		'showViewButton' => false,
	])
		@include('news.form')
	@endcomponent
	{!! Former::close() !!}
@endsection

@section('scripts')
	{{-- <script src="https://cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script> --}}
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
