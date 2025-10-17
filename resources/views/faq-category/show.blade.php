@extends('layouts.default')
@section('content')
	<h2>Maklumat Kategori Soalan ChatBot</h2>
	<hr>
	<form class="form-horizontal">
		<div class="form-group">
			<label for="name" class="control-label col-lg-3 col-sm-3">Nama</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="name" name="name" type="text" value="{{ $data->name }}" readonly>
			</div>
		</div>

		<div class="form-group">
			<label for="" class="control-label col-lg-3 col-sm-3"></label>
			<div class="col-lg-9 col-sm-9">
				<div id="show_none_btn_div" class="form-group form-check pl-4">
					<input type="checkbox" class="form-check-input" id="show_none_btn" value="1" {{ ($data->show_none_btn ?? 0) == "1" ? "checked" : "" }}>
					<label class="form-check-label" for="show_none_btn">Papar butang <q>Bukan disenarai diatas</q></label>
				</div>
				
				<span for="show_none_btn_div">**Nota: Papar pilihan tambahan jika soalan yang ingin ditanya tidak wujud di pangkalan data</span>
			</div>
		</div>

		<div class="well">
			<a href="{{ route('chatbot-manager.category.edit', ['category' => $data->enc_id]) }}" class="btn btn-primary pull-left">Kemaskini</a>
			<a href="{{ route('chatbot-manager.category.index') }}" class="btn btn-default pull-right">Senarai Kategori Soalan ChatBot</a><br/>&nbsp;
		</div>
	</form>

@endsection