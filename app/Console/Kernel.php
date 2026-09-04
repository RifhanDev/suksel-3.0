<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendEligibleEmail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send:eligible-email')->everyTwoMinutes();
        // withoutOverlapping: penjadual menembak mengikut jam, bukan mengikut sama
        // ada larian sebelumnya sudah tamat. Tanpa ini, satu larian perlahan
        // menyebabkan larian bertindih yang mengulangi pertanyaan berat yang sama
        // pada jadual transaksi sejuta baris. Pasangan kepada kunci Cache dalam
        // TransactionsController::queue_fpx_requery(), yang melindungi laluan
        // HTTP bagi kerja yang sama.
        $schedule->command('requery:fpx')->everyMinute()->withoutOverlapping();
        $schedule->command('send:account-review-request')
            ->cron('0 0 1 3,9 *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
