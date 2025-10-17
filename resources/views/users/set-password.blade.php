@extends('layouts.default')
@section('content')

	<?php $user = Auth::user();?>

	<h2 class="tender-title">Tukar Kata Laluan</h2>
	{!! Former::open( url('users/'.$currentUser->id.'/reset_password')) !!}
		{!! Former::hidden('_method', 'PUT') !!}
		{!! Former::populate($currentUser) !!}
		
		@if($currentUser->hasRole('Vendor'))
			{!! Former::text('vendor')
				->forceValue($currentUser->vendor->name)
				->label('Nama Syarikat')
				->disabled() !!}
		@endif
		
		{!! Former::text('name')->disabled()->label('Nama') !!}
		{!! Former::text('email')->disabled()->label('Alamat Emel') !!}
		{!! Former::password('password')
			->required()
			->pattern('.{8,}')
			->help('Sekurang-kurangnya 8 aksara dan kombinasi antara abjad dan nombor, huruf besar dan kecil. Sila tukar kata laluan setiap 90 hari')
			->label('Kata Laluan') !!}
		{!! Former::password('password_confirmation')
			-> required()
			->label('Pastikan Kata Laluan') !!}
		
		<div class="well">
			<input type="submit" value="Kemaskini" class="btn btn-primary">
			
				@if($currentUser->hasRole('Vendor'))
					<a href="{{ asset('vendors/'.$currentUser->vendor_id) }}" class="btn btn-default pull-right">Maklumat Syarikat</a>
				@else
					@if(App\User::canList())
						<a href="{{ asset('users') }}" class="btn btn-default pull-right">Senarai Pengguna</a>
					@endif
					
					@if(isset($currentUser))
						@if($user->canUpdate())
							<a href="{{ asset('users/'.$currentUser->id.'/edit') }}" class="btn btn-default">Maklumat Pengguna</a>
						@endif
					@endif
				@endif
		</div>
	{!! Former::close() !!}

@endsection
