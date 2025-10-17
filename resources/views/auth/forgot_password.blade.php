@extends('layouts.default')
@section('content')
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <h1 class="text-center">Lupa Kata Laluan</h1>
        <br>
        {{Former::open_vertical(action('AuthController@doForgotPassword'))}}
            <div class="form-group">
                <div class="input-append input-group">
                    <input class="form-control" placeholder="{{trans('auth.register.email')}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
                    <span class="input-group-btn">
                        <input class="btn btn-primary" type="submit" value="Seterusnya">
                    </span>
                </div>
            </div>
        {{Former::close()}}
    </div>
 @stop
