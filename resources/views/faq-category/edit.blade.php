@extends('layouts.default')
@section('content')
	<h2>Kemaskini Kategori Soalan ChatBot</h2>
	<hr>
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	<form id="saveForm" name="saveForm" action="{{ route('chatbot-manager.category.update', ["category" => $data->enc_id]) }}" method="POST" class="form-horizontal">
		@csrf
		@method('PUT')
		<div class="form-group">
			<label for="name" class="control-label col-lg-3 col-sm-3">Nama</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="name" name="name" type="text" value="{{ $data->name }}">
				@error('name')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="" class="control-label col-lg-3 col-sm-3"></label>
			<div class="col-lg-9 col-sm-9">
				<div id="show_none_btn_div" class="form-group form-check pl-4">
					<input type="checkbox" class="form-check-input" id="show_none_btn" name="show_none_btn" value="1" {{ ($data->show_none_btn ?? 0) == "1" ? "checked" : "" }}>
					<label class="form-check-label" for="show_none_btn">Papar butang <q>Bukan disenarai diatas</q></label>
				</div>
				
				<span for="show_none_btn_div">**Nota: Papar pilihan tambahan jika soalan yang ingin ditanya tidak wujud di pangkalan data</span>
			</div>
		</div>

		<div class="well">
			<button type="submit" form="saveForm" class="btn btn-primary">Kemaskini</button>
			<button type="button" class="btn btn-danger" id="delete" name="delete" data-toggle="modal" data-target="#deleteModal">Padam</button>

			<a href="{{ route('chatbot-manager.category.index') }}" class="btn btn-default pull-right">Senarai Kategori Soalan ChatBot</a>
		</div>
	</form>




	<!-- Modal -->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&emsp;<b>Amaran</b></h4>
				</div>
				<div class="modal-body">
					<h5><b>Adakah anda pasti untuk memadam rekod ini ?</b></h5>
				</div>
				<div class="modal-footer">
					<div class="pull-right">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
					</div>
					<form action="{{ route('chatbot-manager.category.destroy', ['category' => $data->enc_id]) }}" method="POST">
						@csrf
						<input type="hidden" name="_method" value="DELETE">
						<div class="pl-1">
							<button type="submit" class="btn btn-primary">Ya</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection