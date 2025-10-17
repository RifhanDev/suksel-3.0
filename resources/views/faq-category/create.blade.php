@extends('layouts.default')
@section('content')
	<h2>Tambah Kategori Soalan ChatBot</h2>
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
	<form action="{{ route('chatbot-manager.category.store') }}" method="POST" class="form-horizontal">
		@csrf
		<div class="form-group">
			<label for="name" class="control-label col-lg-3 col-sm-3">Nama</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="name" name="name" type="text" value="{{ old('name') ?? "" }}">
				@error('name')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="" class="control-label col-lg-3 col-sm-3"></label>
			<div class="col-lg-9 col-sm-9">
				<div id="show_none_btn_div" class="form-group form-check pl-4">
					<input type="checkbox" class="form-check-input" id="show_none_btn" name="show_none_btn" value="1">
					<label class="form-check-label" for="show_none_btn">Papar butang <q>Bukan disenarai diatas</q></label>
				</div>
				
				<span for="show_none_btn_div">**Nota: Papar pilihan tambahan jika soalan yang ingin ditanya tidak wujud di pangkalan data</span>
			</div>
		</div>

		<div class="well">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<a href="{{ route('chatbot-manager.category.index') }}" class="btn btn-default pull-right">Senarai Kategori Soalan ChatBot</a>
		</div>
	</form>

@endsection