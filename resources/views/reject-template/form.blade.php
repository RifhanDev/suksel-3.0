{!! Former::text('title')->label('Tajuk')->required() !!}
{!! Former::textarea('content')->id('content-editor')->label('Kandungan')->required() !!}
{{-- {!! Former::select('applicable')->options(['1' => 'Pendaftaran/Kemaskini', '2' => 'Pemulangan Semula', '3' => 'Kebenaran Khas'])->label('Digunapakai')->required() !!} --}}
{!! Former::checkboxes('applicable')->checkboxes(['1' => 'Pendaftaran/Kemaskini', '2' => 'Pemulangan Semula', '3' => 'Kebenaran Khas'])->label('Digunapakai') !!}

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.20.0/standard-all/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content-editor');
    </script>
@endsection
