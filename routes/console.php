<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Schedule
|--------------------------------------------------------------------------
|
| Laravel 11 reads the schedule from here; app/Console/Kernel.php is never
| loaded, which is why the schedule that used to live there never ran. Each
| task is switched on or off in config/schedule.php, and is only registered
| when enabled, so `php artisan schedule:list` shows exactly what will run.
|
*/

// withoutOverlapping() holds its lock in the cache. CACHE_DRIVER=array keeps
// that lock inside a single process, and every schedule:run from cron is a new
// process, so the lock would never be seen and runs would overlap. The file
// store is shared across processes.
Schedule::useCache('file');

if (config('schedule.mail_retry.enabled')) {
    // Lock expires after 30 minutes so a run killed mid-way cannot block
    // retries for the default 24 hours.
    Schedule::command('mail:retry-unsent')->everyFiveMinutes()->withoutOverlapping(30);
}

if (config('schedule.fpx_requery')) {
    // Overlapping runs would repeat a heavy query on a very large transactions
    // table. This pairs with the Cache lock in
    // TransactionsController::queue_fpx_requery(), which guards the same work
    // when it is triggered over HTTP.
    Schedule::command('requery:fpx')->everyMinute()->withoutOverlapping(10);
}

if (config('schedule.eligible_email')) {
    Schedule::command('send:eligible-email')->everyTwoMinutes();
}

if (config('schedule.account_review_request')) {
    Schedule::command('send:account-review-request')->cron('0 0 1 3,9 *');
}

if (config('schedule.periodic_password_reset')) {
    Schedule::command('send:periodic-password-reset')->dailyAt('08:00');
}
