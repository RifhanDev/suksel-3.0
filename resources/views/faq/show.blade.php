@extends('layouts.default')
@section('content')
	<h2>Maklumat Soalan ChatBot</h2>
	<hr>
	<form class="form-horizontal">
		<div class="form-group">
			<label for="question" class="control-label col-lg-3 col-sm-3">Soalan</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="question" name="question" type="text" value="{{ $data->question }}" readonly>
			</div>
		</div>

		<div class="form-group">
			<label for="answer" class="control-label col-lg-3 col-sm-3">Jawapan</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="answer" name="answer" type="text" value="{{ $data->answer }}" readonly>
			</div>
		</div>
		
		<div class="form-group">
			<label for="faq_category_id" class="control-label col-lg-3 col-sm-3">Kategori Soalan</label>
			<div class="col-lg-9 col-sm-9">
				{{-- <input class="form-control" required="" id="answer" name="answer" type="text" value="{{ $data->answer }}" readonly> --}}
				<select name="faq_category_id" id="faq_category_id" class="form-control" disabled>
					<option value="0">-Sila Pilih-</option>
					@foreach ($faq_categories as $faq_category)
						<option value="{{ $faq_category->id }}" {{ ($data->faq_category_id == $faq_category->id) ? 'selected' : '' }}>{{ $faq_category->name }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group">
			<label for="" class="control-label col-lg-3 col-sm-3"></label>
			<div class="col-lg-9 col-sm-9">
				<div id="require_input_text_div" class="form-group form-check pl-4">
					<input type="checkbox" class="form-check-input" id="require_input_text" value="1" {{ ($data->require_input_text ?? 0) == "1" ? "checked" : "" }}>
					<label class="form-check-label" for="require_input_text">Perlukan Jawapan</label>
				</div>
				
				<span for="require_input_text_div">**Nota: Penanya perlu menjawab di dalam satu perenggan</span>
			</div>
		</div>

		<div class="form-group">
			<label for="" class="control-label col-lg-3 col-sm-3"></label>
			<div class="col-lg-9 col-sm-9">
				<div id="require_input_attachment_div" class="form-group form-check pl-4">
					<input type="checkbox" class="form-check-input" id="require_input_attachment" value="1" {{ ($data->require_input_attachment ?? 0) == "1" ? "checked" : "" }}>
					<label class="form-check-label" for="require_input_attachment">Perlukan Gambar Lampiran</label>
				</div>
				
				<span for="require_input_attachment_div">**Nota: Penanya perlu memuatnaik gambar sebagai lampiran (Satu Gambar sahaja)</span>
			</div>
		</div>

		<div class="well">
			<a href="{{ route('chatbot-manager.question.edit', ['question' => $data->enc_id]) }}" class="btn btn-primary pull-left">Kemaskini</a>
			<a href="{{ route('chatbot-manager.question.index') }}" class="btn btn-default pull-right">Senarai Soalan ChatBot</a><br/>&nbsp;
		</div>
	</form>

@endsection