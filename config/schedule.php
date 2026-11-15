<?php

/*
|--------------------------------------------------------------------------
| Scheduled task switches
|--------------------------------------------------------------------------
|
| The schedule itself lives in routes/console.php (Laravel 11 does not load
| app/Console/Kernel.php). Every task is gated here so that turning the
| scheduler on for an environment — e.g. a staging server running on a clone
| of production data — never starts emailing real users unless that task has
| been switched on explicitly.
|
| Nothing runs at all without this cron entry on the server:
|   * * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
|
| Check what will actually run with:  php artisan schedule:list
|
*/

return [

    // Re-send mail_queues rows still in status N, i.e. emails that never
    // reached the mail server (see App\Console\Commands\RetryUnsentMail).
    'mail_retry' => [
        'enabled' => env('SCHEDULE_MAIL_RETRY', true),

        // Leave rows younger than this alone: their first send (dispatched
        // ->afterResponse() when the email is created) may still be in flight,
        // and racing it would deliver the same email twice.
        'min_age_minutes' => (int) env('MAIL_RETRY_MIN_AGE_MINUTES', 5),

        // Never resend anything older than this. Stops a fresh deploy on a
        // cloned database from mass-sending a backlog of old emails.
        'window_hours' => (int) env('MAIL_RETRY_WINDOW_HOURS', 72),

        // Rows handled per run. Each send can wait on the mail server's SMTP
        // timeout, so this keeps a single run short.
        'batch_size' => (int) env('MAIL_RETRY_BATCH_SIZE', 20),
    ],

    // Re-query pending FPX transactions with PayNet. On by default in
    // production only: elsewhere the transactions table is usually a data
    // clone, and querying PayNet for those would only produce noise.
    'fpx_requery' => env('SCHEDULE_FPX_REQUERY', env('APP_ENV') === 'production'),

    // These three email real users, so they are off unless explicitly enabled.
    'eligible_email' => env('SCHEDULE_ELIGIBLE_EMAIL', false),
    'account_review_request' => env('SCHEDULE_ACCOUNT_REVIEW_REQUEST', false),
    'periodic_password_reset' => env('SCHEDULE_PERIODIC_PASSWORD_RESET', false),

];
