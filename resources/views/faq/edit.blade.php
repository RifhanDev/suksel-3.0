@extends('layouts.default')
@section('content')
	<h2>Kemaskini Soalan ChatBot</h2>
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
	
	<form id="saveForm" name="saveForm" action="{{ route('chatbot-manager.question.update', ["question" => $data->enc_id]) }}" method="POST" class="form-horizontal">
		@csrf
		@method('PUT')
		<div class="form-group">
			<label for="question" class="control-label col-lg-3 col-sm-3">Soalan</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="question" name="question" type="text" value="{{ old('question') ?? $data->question }}">
				@error('question')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="answer" class="control-label col-lg-3 col-sm-3">Jawapan</label>
			<div class="col-lg-9 col-sm-9">
				<input class="form-control" required="" id="answer" name="answer" type="text" value="{{ old('answer') ?? $data->answer }}">
				@error('answer')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>
		
		<div class="form-group">
			<label for="faq_category_id" class="control-label col-lg-3 col-sm-3">Kategori Soalan</label>
			<div class="col-lg-9 col-sm-9">
				<select name="faq_category_id" id="faq_category_id" class="form-control">
					<option value="0">-Sila Pilih-</option>
					@foreach ($faq_categories as $faq_category)
						<option value="{{ $faq_category->id }}" {{ ((old('faq_category_id') ?? $data->faq_category_id) == $faq_category->id) ? 'selected' : '' }}>{{ $faq_category->name }}</option>
					@endforeach
				</select>
				@error('faq_category_id')
					<div class="alert alert-danger p-0 m-0">** {{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label for="" class="control-label col-lg-3 col-sm-3"></label>
			<div class="col-lg-9 col-sm-9">
				<div id="require_input_text_div" class="form-group form-check pl-4">
					<input type="checkbox" class="form-check-input" id="require_input_text" name="require_input_text" value="1" {{ (old('require_input_text') ?? ($data->require_input_text ?? 0) )  == "1" ? "checked" : "" }}>
					<label class="form-check-label" for="require_input_text">Perlukan Jawapan</label>
				</div>
				
				<span for="require_input_text_div">**Nota: Penanya perlu menjawab di dalam satu perenggan</span>
			</div>
		</div>

		<div class="form-group">
			<label for="" class="control-label col-lg-3 col-sm-3"></label>
			<div class="col-lg-9 col-sm-9">
				<div id="require_input_attachment_div" class="form-group form-check pl-4">
					<input type="checkbox" class="form-check-input" id="require_input_attachment" name="require_input_attachment" value="1" {{ (old('require_input_attachment') ?? ($data->require_input_attachment ?? 0) ) == "1" ? "checked" : "" }}>
					<label class="form-check-label" for="require_input_attachment">Perlukan Gambar Lampiran</label>
				</div>
				
				<span for="require_input_attachment_div">**Nota: Penanya perlu memuatnaik gambar sebagai lampiran (Satu Gambar sahaja)</span>
			</div>
		</div>

		<div class="well">
			<button type="submit" form="saveForm" class="btn btn-primary">Kemaskini</button>
			<button type="button" class="btn btn-danger" id="delete" name="delete" data-toggle="modal" data-target="#deleteModal">Padam</button>

			<a href="{{ route('chatbot-manager.question.index') }}" class="btn btn-default pull-right">Senarai Soalan ChatBot</a>
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
					<form action="{{ route('chatbot-manager.question.destroy', ['question' => $data->enc_id]) }}" method="POST">
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