@extends('layouts.default')
@section('content')
@if($user && $vendor = $user->vendor)
    @if($vendor->canParticipateInTenders())
        <div class="row">
            <div class="col-md-3">
                <h3>Tender Matching</h3>
                <br>
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="label label-primary pull-right">+14d</div>
                                Sebutharga Membekal Perabot Dan Kelengkapan Pejabat Majlis Perbandaran Kajang
                            </li>
                            <br>
                            <li class="list-group-item">
                                <div class="label label-success pull-right">Win</div>
                                CADANGAN KERJA-KERJA PENYELENGGARAAN LANDSKAP LEMBUT BAGI KAWASAN SUNGAI PELEK HINGGA TELUK MERBAU UNTUK MAJLIS PERBANDARAN SEPANG
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <h3>Tenders Participated</h3>
                <br>
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="label label-danger pull-right">Today</div>
                                Cadangan Kerja-Kerja Membekal, Memasang, Menguji Dan Mengujiterima Sistem Penyaman Udara Jenis “Split Unit” Dan Kerja-Kerja Yang Berkaitan Di Stor Fail Berpusat Jabatan Bangunan Kompleks Majlis Perbandaran Selayang.
                            </li>
                            <br>
                            <li class="list-group-item">
                                <div class="label label-primary pull-right">+14d</div>
                                Sebutharga Membekal Perabot Dan Kelengkapan Pejabat Majlis Perbandaran Kajang
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <h3>Tender Evaluating</h3>
                <br>
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="label label-primary pull-right">3/12</div>
                                Cadangan Membekal, Menghantar, Memasang Dan Mengujiterima Sistem Pelarasan Berpusat Dan Sistem Perundangan Berpusat Untuk Majlis Bandaraya Shah Alam.
                            </li>                
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <h3>Tender Closed</h3>
                <br>
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="label label-danger pull-right">Lost</div>
                                Cadangan sistem pelintas jalan sementara berdekatan LRT Asia Jaya dan Hotel Cyrstal Crown untuk Majlis Bandaraya Petaling Jaya, Selangor
                            </li>
                            <br>
                            <li class="list-group-item">
                                <div class="label label-default pull-right">Cancel</div>
                                Perolehan Pembangunan Sistem MyAset Bagi Bahagian Pengurusan Aset Dan Kualiti (BPAK), Jabatan Khidmat Pengurusan, Majlis Perbandaran Kajang
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @elseif(!$vendor->completed)
        <div class="alert alert-info">
            Please complete your registration to participate.
            <a href="{{action('UsersController@getChangeCompanyData')}}" class="btn yellow-selangor">Complete Registration</a>
        </div>
    @elseif(!$vendor->approval_1_id)
        <div class="alert alert-info">
            Your recent updates has to be approved before you can make any changes.
        </div>
    @elseif(!$vendor->registration_paid)
        <div class="alert alert-info">
            Please make payment to complete your registration. 
            <a href="{{action('UsersController@getPayRegistration')}}" class="btn yellow-selangor">Make Payment</a>
        </div>
    @elseif($vendor->isBlacklisted())
        <div class="alert alert-danger">
            Your company is blacklisted and will not be able to participate in any tender and quotations until {{$vendor->blacklisted_until}} because of: {{$vendor->blacklist_reason}}.
            <br/>If you believe that this is an error. Please contact us.
        </div>
    @endif
@endif
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
@stop