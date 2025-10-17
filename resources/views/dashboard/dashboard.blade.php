@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-md-9">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#dashboard-active">Tender / Sebutharga Aktifxx</a></li>
          <li role="presentation"><a href="#dashboard-bidding">Pembida Tender / Sebutharga</a></li>
          <li role="presentation"><a href="#dashboard-result">Keputusan Tender / Sebutharga</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="dashboard-active">
                <div class="portlet box yellow-selangor">
                    <div class="portlet-body tabs-below no-padding">
                        <div class="tabbable-line">
                            <ul class="nav nav-pills">
                                <li class="active">
                                    <a href="#senarai_aktif_semua" data-toggle="tab">
                                    Semua </a>
                                </li>
                                <li>
                                    <a href="#senarai_aktif_tender" data-toggle="tab">
                                    Tender </a>
                                </li>
                                <li>
                                    <a href="#senarai_aktif_sebutharga" data-toggle="tab">
                                    Sebutharga </a>
                                </li>
                            </ul>                        
                            <div class="tab-content no-padding">
                                <div class="tab-pane active" id="senarai_aktif_semua">
                                    <table class="table no-margin">
                                        <tbody>
                                            @foreach($tenders as $tender)
                                                <tr>
                                                    <td>{{link_to_action('OrganizationUnitsController@show', $tender->tenderer->name, [$tender->organization_unit_id])}}</td>
                                                    <td><a href="{{action('TendersController@show', $tender->id)}}">{{$tender->ref_number}}<br />{{$tender->name}}</a></td>
                                                    <td>{{\Carbon\Carbon::parse($tender->submission_date)->format('j M Y')}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_aktif_tender">
                                    <table class="table no-margin">
                                        <tbody>
                                            @foreach($tenders as $tender)
                                                <tr>
                                                    <td>{{link_to_action('OrganizationUnitsController@show', $tender->tenderer->name, [$tender->organization_unit_id])}}</td>
                                                    <td><a href="{{action('TendersController@show', $tender->id)}}">{{$tender->ref_number}}<br />{{$tender->name}}</a></td>
                                                    <td>{{\Carbon\Carbon::parse($tender->submission_date)->format('j M Y')}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_aktif_sebutharga">
                                    <table class="table no-margin">
                                        <tbody>
                                            @foreach($tenders as $tender)
                                                <tr>
                                                    <td>{{link_to_action('OrganizationUnitsController@show', $tender->tenderer->name, [$tender->organization_unit_id])}}</td>
                                                    <td><a href="{{action('TendersController@show', $tender->id)}}">{{$tender->ref_number}}<br />{{$tender->name}}</a></td>
                                                    <td>{{\Carbon\Carbon::parse($tender->submission_date)->format('j M Y')}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="dashboard-bidding">
            </div>

            <div role="tabpanel" class="tab-pane" id="dashboard-result">
            </div>
        </div>
    </div>

    <div class="col-md-3">
    </div>
</div>



<div class="row">
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box yellow-selangor">
                    <div class="portlet-title">
                        <div class="caption">
                            Senarai Tender / Sebutharga Aktif
                        </div>
                    </div>
                    <div class="portlet-body tabs-below no-padding">
                        <div class="tabbable-line tabs-below">
                            <div class="tab-content no-padding">
                                <div class="tab-pane active" id="senarai_aktif_semua">
                                    <table class="table no-margin">
                                        <tbody>
                                            @foreach($tenders as $tender)
                                                <tr>
                                                    <td>{{link_to_action('OrganizationUnitsController@show', $tender->tenderer->name, [$tender->organization_unit_id])}}</td>
                                                    <td><a href="{{action('TendersController@show', $tender->id)}}">{{$tender->ref_number}}<br />{{$tender->name}}</a></td>
                                                    <td>{{\Carbon\Carbon::parse($tender->submission_date)->format('j M Y')}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_aktif_tender">
                                    <table class="table no-margin">
                                        <tbody>
                                            @foreach($tenders as $tender)
                                                <tr>
                                                    <td>{{link_to_action('OrganizationUnitsController@show', $tender->tenderer->name, [$tender->organization_unit_id])}}</td>
                                                    <td><a href="{{action('TendersController@show', $tender->id)}}">{{$tender->ref_number}}<br />{{$tender->name}}</a></td>
                                                    <td>{{\Carbon\Carbon::parse($tender->submission_date)->format('j M Y')}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_aktif_sebutharga">
                                    <table class="table no-margin">
                                        <tbody>
                                            @foreach($tenders as $tender)
                                                <tr>
                                                    <td>{{link_to_action('OrganizationUnitsController@show', $tender->tenderer->name, [$tender->organization_unit_id])}}</td>
                                                    <td><a href="{{action('TendersController@show', $tender->id)}}">{{$tender->ref_number}}<br />{{$tender->name}}</a></td>
                                                    <td>{{\Carbon\Carbon::parse($tender->submission_date)->format('j M Y')}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#senarai_aktif_semua" data-toggle="tab">
                                    Semua </a>
                                </li>
                                <li>
                                    <a href="#senarai_aktif_tender" data-toggle="tab">
                                    Tender </a>
                                </li>
                                <li>
                                    <a href="#senarai_aktif_sebutharga" data-toggle="tab">
                                    Sebutharga </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box yellow-selangor">
                    <div class="portlet-title">
                        <div class="caption">
                            Senarai Harga Pembida Terkini Tender / Sebutharga
                        </div>
                    </div>
                    <div class="portlet-body tabs-below no-padding">
                        <div class="tabbable-line tabs-below">
                            <div class="tab-content no-padding">
                                <div class="tab-pane active" id="senarai_harga_semua">
                                    <table class="table no-margin">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_harga_tender">
                                    <table class="table no-margin">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_harga_sebutharga">
                                    <table class="table no-margin">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#senarai_harga_semua" data-toggle="tab">
                                    Semua </a>
                                </li>
                                <li>
                                    <a href="#senarai_harga_tender" data-toggle="tab">
                                    Tender </a>
                                </li>
                                <li>
                                    <a href="#senarai_harga_sebutharga" data-toggle="tab">
                                    Sebutharga </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box yellow-selangor">
                    <div class="portlet-title">
                        <div class="caption">
                            Keputusan Terkini Tender / Sebutharga
                        </div>
                    </div>
                    <div class="portlet-body tabs-below no-padding">
                        <div class="tabbable-line tabs-below">
                            <div class="tab-content no-padding">
                                <div class="tab-pane active" id="senarai_keputusan_semua">
                                    <table class="table no-margin">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_keputusan_tender">
                                    <table class="table no-margin">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="senarai_keputusan_sebutharga">
                                    <table class="table no-margin">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#senarai_keputusan_semua" data-toggle="tab">
                                    Semua </a>
                                </li>
                                <li>
                                    <a href="#senarai_keputusan_tender" data-toggle="tab">
                                    Tender </a>
                                </li>
                                <li>
                                    <a href="#senarai_keputusan_sebutharga" data-toggle="tab">
                                    Sebutharga </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @if(!isset($user))
        <h3>Login</h3>
        <br>
        <div>
            {{Former::open_vertical(action('AuthController@doLogin'))}}
                {{Former::text('email')
                    ->placeholder('Email')
                    ->label('')}}
                {{Former::password('password')
                    ->placeholder('Password ')
                    ->label('')}}
                <button class="btn btn-block btn-success btn-lg">Login</button>
            {{Former::close()}}
        </div>
        <br>
        <div>
            <a href="{{action('AuthController@create')}}" class="btn blue btn-block btn-lg">Daftar</a>
        </div>
        <br>
        @endif
        <div>
            <h3>Pengumuman</h3>
            <br>
            <div id="announcements">
                <div class="general-item-list">
                    @foreach ($notifications as $notification)
                    <div class="item">
                        <div class="item-head">
                            <div class="item-details">
                                <img class="item-pic" src="/images/mbpj.png">
                                <a href="" class="item-name primary-link">MBPJ</a>
                                <span class="item-label">4 hrs ago</span>
                            </div>
                        </div>
                        <div class="item-body">
                            {{$notification->notification}}
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- <div class="list-group">
                </div> -->
            </div>
            <a class="btn btn-block btn-lg blue">Lihat Semua</a>
        </div>
    </div>
</div>
@endsection