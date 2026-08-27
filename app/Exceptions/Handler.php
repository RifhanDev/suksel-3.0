<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Temporary — Laravel's prepareException() converts TokenMismatchException
        // into a plain HttpException(419) BEFORE dispatching to renderable()
        // callbacks, so a callback type-hinted for TokenMismatchException itself
        // never matches (confirmed: it never fired). Checking the status code on
        // HttpException instead — same pattern as the existing 403 handler below.
        // Logs then returns null so Laravel's default 419 response/view is still
        // used unchanged. Safe to remove once the respond() 419 is resolved.
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            \Illuminate\Support\Facades\Log::warning('419 TokenMismatchException', [
                'method'          => $request->method(),
                'full_url'        => $request->fullUrl(),
                'has_session'     => $request->hasSession(),
                'session_id'      => $request->hasSession() ? $request->session()->getId() : null,
                'has_token_field' => $request->has('_token'),
                'token_field'     => $request->input('_token'),
                'expected_token'  => $request->hasSession() ? $request->session()->token() : null,
                'cookies'         => $request->cookies->all(),
                'ip'              => $request->ip(),
            ]);

            return null;
        });

        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 403 && config('app.env') === 'production') {
                return response()->view('errors.404', [], 404);
            }
        });
    }
}