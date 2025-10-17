{!! Former::populate($circular) !!}
{!! Former::text('title')
        ->label('Tajuk')
        ->required() !!}
<?php $file = Former::file('file')->label('Fail')->accept('pdf'); /* if(!$circular->exists()) $file = $file->required(); */ ?>
{!! $file !!}
<?php $url = Former::text('pdf_link')->label('URL ke PDF'); /* if(!$circular->exists()) $file = $file->required(); */ ?>
{!! $url !!}
{!! Former::checkbox('published')
        ->label('Siar') !!}

@section('scripts')

<script type="text/javascript">
    $("#organization_unit_id").selectize();
    $('input[name="start"], input[name="end"]').datepicker({
            format: 'd M yyyy'
    });
</script>

@endsection