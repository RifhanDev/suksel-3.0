@extends('layouts.auth')
@section('content')
<div class="row login-wrapper">
    <div class="col-sm-6 col-sm-offset-3 well well-auth">
        <div class="header-logo text-center">
            <img src="/images/monitor@2x.png" alt="" width="128px">
        </div>
        <div class="text-center text-primary">
            <h1>{{trans('auth.login.title')}}</h1>
            @if ( Session::get('error') )
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            @if ( Session::get('notice') )
                <div class="alert alert-info">{{ Session::get('notice') }}</div>
            @endif
            <p>Please enter username &amp; password to begin</p>
        </div>
        <div class="well well-primary">
        {{Former::open_horizontal(action('AuthController@doLogin'))}}
            <div>
                <div class="form-group required">
                    <label for="email" class="control-label col-md-4">Email Address <sup>*</sup></label>
                    <div class="col-md-8">
                        <input class="form-control" required="true" id="email" type="text" name="email">
                    </div>
                </div>
                <div class="form-group required">
                    <label for="password" class="control-label col-md-4">Password <sup>*</sup></label>
                    <div class="col-md-8">
                        <input class="form-control" required="true" id="password" type="password" name="password">
                    </div>
                </div>
                {{Former::hidden('remember')
                    ->value(0)}}
                <div class="form-group required">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <label for="remember">
                            <input id="remember" type="checkbox" name="remember" value="1">
                            &nbsp;&nbsp;&nbsp;Remember Me
                        </label> 
                    </div>
                </div>
                <div class="form-group required">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                    <button type="submit" class="btn btn-yellow btn-block">{{trans('auth.login.title')}}</button> 
                    </div>
                </div>
            </div>
        {{Former::close()}}
        </div>
        <p class="text-right text-muted text-sm">
            Not registered? 
            <a href="{{action('AuthController@create')}}" class="label label-default label-lg">Register</a> or 
            <a href="{{action('AuthController@forgotPassword')}}" class="label label-default label-lg">Forgot Password</a>
        </p>
        <p class="text-right text-muted text-sm">
            <a target="blank" href="/docs/public/register" class="label label-default label-lg">MYPRO Online Help</a>
        </p>
    </div>
</div>
@stop
