<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="en"><!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="_token" content="{{csrf_token()}}">
@yield('meta')
<title>Resit Pendaftaran Vendor</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
{{ App\Libraries\Asset::tags('css')}}
@yield('styles')
</head>
<body>
<div class="container">
    <header>
	<div class="col-xs-12" style="text-align:right">Versi 1.0</div>        
	<div class="logo"><img src="/assets/images/header.png"></div>
        <div class="title">Resit Pembelian Dokumen</div>
        <div class="clearfix"></div>
    </header>

    <section class="body">
        <div class="row">
            <div class="col-xs-6 address">
                <span>Daripada</span><br>
                <strong>SUK Selangor</strong><br>
                Bangunan Sultan Salahuddin Abdul Aziz Shah<br>
                40503 Shah Alam<br>
                Selangor Darul Ehsan
            </div>

            <div class="col-xs-5 col-xs-offset-1">
                <ul class="list-unstyled">
                    @if($transaction->gateway_auth)<li>No Resit : <strong>{{$transaction->vendor_id}}-{{$transaction->gateway_reference}}</strong></li>@endif
                    <li>No Transaksi : <strong>{{$transaction->number}}</strong></li>
                    <li>Tarikh : <strong>{{\Carbon\Carbon::parse($transaction->created_at)->format('d / m / Y')}}</strong></li>
                    @if($transaction->gateway_auth)<li>Kod Pengesahan : <strong>{{$transaction->gateway_auth}}</strong></li>@endif
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6 address">
                <span>Kepada</span><br>
                <strong>{{$vendor->name}}</strong><br>
                {{nl2br($vendor->address)}}
            </div>
        </div>

        <table class="table table-bordered table-condensed">
            <thead class="blue">
                <tr>
                    <th class="align-right col-lg-1">No.</th>
                    <th>Dokumen</th>
                    <th class="col-lg-2 align-right">Jumlah (RM)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="align-right">1</td>
                    <td>
                        {{$tender->ref_number}}<br>
                        {{$tender->name}}
                    </td>
                    <td class="align-right">{{ sprintf('%.2f', $tender->price) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="align-right" colspan="2">Jumlah Keseluruhan</th>
                    <td class="align-right">{{ sprintf('%.2f', $tender->price) }}</td>
                </tr>
            </tfoot>
        </table>
    </section>
    <footer>Ini adalah cetakan komputer dan tidak perlu ditandatangan.</footer>
</div>
</body>
</html>