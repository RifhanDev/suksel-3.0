
{!! Former::text('code')
		->label('Kod')
		->required() !!}
{!! Former::text('name')
		->label('Nama')
		->required() !!}
{!! Former::select('type')
		->options(App\Code::$type)
		->label('Agensi / Jenis')
		->required() !!}
