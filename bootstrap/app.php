<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->use([
            \App\Http\Middleware\CheckForMaintenanceMode::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\BeforeMiddleware::class,
        ]);

        // Web middleware group. EncryptCookies/VerifyCsrfToken are App-namespaced
        // subclasses meant to REPLACE Laravel's framework defaults (standard
        // pre-Laravel-11 convention) — they were being appended instead, so the
        // framework's own default VerifyCsrfToken (with no except list) ran
        // FIRST and threw a 419 for payment/fpx/respond before the app's
        // excepted version ever got a chance to apply its exemption.
        $middleware->web(replace: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class => \App\Http\Middleware\EncryptCookies::class,
            // Laravel 11's actual default web-group class is ValidateCsrfToken,
            // not the VerifyCsrfToken name App\Http\Middleware\VerifyCsrfToken
            // extends — that name mismatch is why this replace() previously
            // matched nothing, leaving the framework's un-excepted default in
            // place ahead of the app's except-list version.
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CheckUser::class,
            \App\Http\Middleware\RequireTwoFactorSetup::class,
        ]);

        // API middleware group
        $middleware->api(prepend: [
            'throttle:60,1',
            'bindings',
        ]);

        // Route middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            // Restricts an action to the Pengerusi of a given jawatankuasa,
            // e.g. ->middleware('committee.pengerusi:open')
            'committee.pengerusi' => \App\Http\Middleware\EnsureCommitteeChairperson::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function ($request) {
            return $request->is('api/*') || $request->expectsJson();
        });
    })->create();
