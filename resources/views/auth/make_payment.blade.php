{{App\Libraries\Asset::tags('css')}}
<style>
    body {
        background-image: url('/images/payment.jpg');
        background-position: center 50px;
        background-repeat: no-repeat;
        background-color: #f2f2f2;
        min-height: 1020px;
    }
    .text-center {
        margin-top: -82px;
    }
</style>
<div class="text-center">
    <a class="btn btn-raised btn-success" href="{{action('AuthController@paymentdone', $company->registration_code)}}">Payment Done</a>
</div>