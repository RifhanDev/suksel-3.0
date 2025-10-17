@extends('layouts.default')
@section('content')
<div class="btn-group pull-right btn-actions">
    @if($tender->tenderer->canShow())
        <a href="{{action('OrganizationUnitsController@show', $tender->tenderer->id)}}" class="btn btn-warning">
            <i class="fa fa-group"></i> Senarai Tender oleh {{$tender->tenderer->name}}
        </a>
    @endif
    @if(App\Tender::canList())
        <a href="{{action('TendersController@index')}}" class="btn btn-primary">
            <i class="fa fa-chevron-up"></i> Senarai Tender
        </a>
    @endif
</div>
<div class="clearfix"></div>

<div class="tender-ref-number">{{$tender->ref_number}}</div>
<h2 class="tender-title">{{$tender->name}}</h2>

<table class="table table-bordered table-condensed">
    <tr>
        <th class="col-xs-3">Petender</th>
        <td>{{$tender->tenderer->name}}</td>
    </tr>
    <tr>
        <th class="col-xs-3">No. Tender</th>
        <td>{{$tender->ref_number}}</td>
    </tr>
    <tr>
        <th class="col-xs-3">Tarikh Jual</th>
        <td></td>
    </tr>
    <tr>
        <th class="col-xs-3">Tarikh Tutup</th>
        <td></td>
    </tr>
    <tr>
        <th class="col-xs-3">Masa Tutup</th>
        <td></td>
    </tr>
    <tr>
        <th class="col-xs-3">Tempat Hantar</th>
        <td>
            {{$tender->submission_location_address}}
        </td>
    </tr>
    <tr>
        <th class="col-xs-3">Tender Dibuka Kepada</th>
        <td>
            Kontrak Berasal Dari Selangor<br>
            Kontrak Berasal Dari Petaling<br>
            Bumiputera Sahaja
        </td>
    </tr>
    <tr>
        <th class="col-xs-3">Harga Dokumen</th>
        <td>RM {{$tender->price}}</td>
    </tr>
</table>

@if(Auth::user()->hasRole('Vendor'))
<div class="row">
    <div class="col-xs-4 col-xs-offset-8">
        <div class="portlet yellow-crusta box">
            <div class="portlet-title">
                <div class="caption">Pembelian Dokumen</div>
            </div>
            <div class="portlet-body">
                <center>Sila Pilih Kaedah Pembayaran</center><br>
                {{ Former::open(action('TendersController@storeBuy', [$tender->id]))}}
                <div class="btn-group btn-group-justified">
                    <div class="btn-group">
                        <button name="method" value="direct" class="btn btn-lg blue-steel">Kad Kredit</button>
                    </div>
                    <div class="btn-group">
                        <button name="method" value="direct" class="btn btn-lg blue-madison">FPX</button>
                    </div>
                </div>
                {{ Former::close()}}
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('scripts')
@stop