<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share user variable with all views
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });

        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => Config::get('captcha.secret'),
                    'response' => $value,
                ],
            ]);

            $body = json_decode($response->getBody());

            return $body->success;
        });
    }
}
