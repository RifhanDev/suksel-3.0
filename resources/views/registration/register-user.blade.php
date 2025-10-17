@extends('layouts.default')
@section('content')
    <h2>Pendaftaran Pengguna</h2>

    <div class="alert alert-info">
        Pernah mendaftar dengan Sistem Tender Online Selangor?&nbsp;&nbsp;&nbsp;<a
                href="{{ action('HomeController@companySearch') }}" class="btn btn-xs btn-primary">Semak Pendaftaran
            Syarikat</a>
    </div>

    <div class="portlet box">
        <div class="portlet-body ">
            {!! Former::open(action('RegistrationController@storeRegisterUser'))->addClass('form-uppercase')->autocomplete("false") !!}
             
            <div>
                {!! Former::select('organization_unit_id')
                    ->label('Agensi')
                    ->options(App\OrganizationUnit::pluck('name', 'id'))
                    ->required() !!}
                {!! Former::select('role_applied')
                    ->label('Peranan')
                    ->options(App\Role::where('name', 'like', 'Agency%')->pluck('name', 'id'), null)
                    ->required() !!}
                {!! Former::text('name')
                    ->label('Nama Pendaftar')
                    ->required() !!}
                {!! Former::email('email')
                    ->label('Alamat Emel')
                    ->required()
                    ->addClass('x-uppercase') !!}
                {!! Former::password('password')
                    ->label('Kata Laluan')
                    ->help('Sekurang-kurangnya 8 aksara, satu simbol, satu nombor, satu huruf besar dan satu huruf kecil. Sila tukar kata laluan setiap 90 hari')
                    ->required()
                    ->addClass('x-uppercase') !!}
                {!! Former::password('password_confirmation')
                    ->label('Pengesahan Kata Laluan')
                    ->help('Masukan semula kata laluan')
                    ->required()
                    ->addClass('x-uppercase') !!}
                <div class="form-group required">
                    <div class="col-lg-3 col-sm-3"></div>
                    <div class="col-lg-9 col-sm-9">
                        <input type="submit" value="Sahkan Alamat Emel" class="btn btn-lg blue">
                    </div>
                </div>
            </div>
            {!! Former::close() !!}
        </div>
    </div>
@stop
