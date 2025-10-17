<?php $name = Former::text('name')
		->required()
		->label('Nama');
		if(isset($permission)) $name->disabled(); ?>
		{!! $name !!}
<?php $group_name = Former::text('group_name')
		->label('Nama Kumpulan')
		->useDatalist(App\Permission::select('id', 'group_name')->groupBy('group_name')->get(), 'group_name')
		->required();
		if(isset($permission)) $group_name->disabled(); ?>
		{!! $group_name !!}
{!! Former::text('display_name')
		->label('Keterangan')
		->required() !!}
