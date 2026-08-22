<?php

namespace App\Jobs;

use App\Http\Controllers\TransactionsController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateFpxStatus implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $transaction_id;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    // public $tries = 5;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($incoming_trans_id)
    {
        $this->transaction_id = $incoming_trans_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $route_url = route('api_fpx_requery');
        // $route_url = "https://dev-etender.aufa.com.my/api/api_fpx_requery";

        try {
            // $response = Http::withOptions([
            //     'verify' => false,
            // ])->asForm()->post($route_url, [
            //     'transaction_id' => $this->transaction_id,
            // ]);

            // NOTE: this posts to THIS app's own api_fpx_requery route, not to PayNet —
            // the PayNet call happens inside that endpoint, where TLS verification is now
            // controlled by services.fpx.verify_tls. Verification stays off here because
            // staging commonly serves a self-signed certificate to itself; tightening it
            // would break the loopback call without adding any protection on the PayNet
            // trust boundary.
            $response = Http::withOptions([
                'verify' => false,
            ])->post($route_url, [
                'transaction_id' => $this->transaction_id,
            ]);

            // Log::info("Http response: ".$response->body());

        } catch (\Exception $e) {
            
            UpdateFpxStatus::dispatch($this->transaction_id)->delay(now()->addSeconds(15));
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        UpdateFpxStatus::dispatch($this->transaction_id)->delay(now()->addSeconds(29));
    }
}
