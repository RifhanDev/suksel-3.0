@extends('layouts.default')

@section('styles')
    <style>
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
			margin-bottom: 5px;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #737373;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <h1>Semakan Syarikat</h1>
            <p>Sila masukan maklumat ke dalam medan yang disediakan di bawah untuk semakan syarikat dengan Sistem Tender
                Online Selangor 2.0</p>

            {!! Former::vertical_open(action('HomeController@doCompanySearch')) !!}
            {!! Former::text('company_no')->label('No. SSM')->help('No syarikat atau perniagaan yang didaftarkan oleh SSM')->required() !!}
            <div class="separator">dan</div>
            {!! Former::text('mof_no')->label('No. Rujukan Pendaftaran MOF')->help(
                    'No Rujukan Pendaftaran yang tertera di atas Sijil Akuan Pendaftaran Syarikat Kementerian Kewangan Malaysia',
                ) !!}
            <div class="separator">atau</div>
            {!! Former::text('cidb_no')->label('No. Pendaftaran CIDB')->help('No Pendaftaran yang tertera di atas Perakuan Pendaftaran CIDB') !!}
            <div class="separator">atau</div>
            {!! Former::text('company_name')->label('Nama Syarikat/Perniagaan')->help('Nama syarikat atau perniagaan yang didaftarkan oleh SSM') !!}
            {!! Former::submit('Semak')->class('btn bg-blue-selangor') !!}
            {!! Former::close() !!}
        </div>

        <div class="col-lg-3">
            @include('layouts._register')
        </div>
    </div>
@endsection
