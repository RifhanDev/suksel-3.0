<?php

namespace App\Providers;

use App\Support\BotManFileCache;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Container\LaravelContainer;
use BotMan\BotMan\Storages\Drivers\FileStorage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('botman', function ($app) {
            $storage = new FileStorage(storage_path('botman'));
            $cache = new BotManFileCache();

            $botman = BotManFactory::create(
                config('botman', []),
                $cache,
                $app->make('request'),
                $storage
            );

            $botman->setContainer(new LaravelContainer($app));

            return $botman;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share user variable with all views (skip when view already has 'user', e.g. mailables)
        View::composer('*', function ($view) {
            if (! array_key_exists('user', $view->getData())) {
                $view->with('user', Auth::user());
            }
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

        Validator::extend('email_domain', function ($attribute, $value, $parameters) {
            if (! is_string($value) || ! str_contains($value, '@')) {
                return false;
            }

            $domain = strtolower(ltrim(strrchr($value, '@'), '@'));
            if ($domain === '') {
                return false;
            }

            $allowedSuffixes = $parameters !== [] ? $parameters : ['gov.my'];

            foreach ($allowedSuffixes as $suffix) {
                $suffix = strtolower($suffix);
                if ($domain === $suffix || str_ends_with($domain, '.'.$suffix)) {
                    return true;
                }
            }

            return false;
        });
    }
}
