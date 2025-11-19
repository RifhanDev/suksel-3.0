<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Transaction;
use App\Fpx;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Log;

class FPXRequery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requery:fpx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-query FPX transaction';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transactions = Transaction::whereHas('gateway', function($q){
                            $q->where('type', 'fpx');
                        })->where('method', 'fpx')
                        ->where('status', 'pending')
                        ->whereNotNull('gateway_id')->orderBy('created_at')->limit(50)->get();
        
        foreach ($transactions as $transaction) {

            if($transaction->type == 'subscription') {
                $description = 'Langganan Tender Selangor';
            }

            if($transaction->type == 'purchase') {
                $description = 'Beli Dokumen Tender Selangor';
            }

            $fpx = new Fpx([
                    'amount'       => $transaction->amount,
                    'merchant_id'  => $transaction->gateway->merchant_code,
                    'prefix'       => $transaction->gateway->transaction_prefix,
                    'order_number' => $transaction->number,
                    'description'  => $description,
                    'user_email'   => $transaction->user->email,
                    'request_type' => 'AE'
            ]);

            

            $data = $transaction->gateway_data;

            if(count($data) > 0) {
                $data['fpx_msgType'] = 'AE';
                $fpx->prefill($data);
            }

            $fpx->sign();

            $url    = $transaction->gateway->daemon_url;
            $params = $fpx->request_keys;

            try {
                    $client = new Client(['verify' => false]);
                    $response = $client->request('POST', $url, [
                        'form_params' => $params,
                        'debug' => false
                    ]);     
            }
            catch(Exception $e) {
                Log::info('Gagal menghubungi FPX untuk transaksi '.$transaction->number);
                continue;
            }
            $response = $response->getBody()->getContents();
            $response = explode('&', $response);
            $data     = [];

            Log::info($response);

            if(count($response) > 1) {
                foreach($response as $resp) {
                    $resp = explode('=', $resp);
                    $data[$resp[0]] = $resp[1];
                }

                ksort($data);
                
                switch(true) {
                    case $data['fpx_debitAuthCode'] == '00' && $data['fpx_creditAuthCode'] == '00':
                        $transaction->status            = 'success';
                        $transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'SUCCESSFUL');
                        break;
                    case $data['fpx_debitAuthCode'] == '99':
                        $transaction->status            = 'pending_authorization';
                        $transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'PENDING FOR AUTHORIZER TO APPROVE');
                        break;
                    default:
                        $transaction->status            = 'failed';
                        $transaction->response_message  = sprintf('%s|%s - %s', $data['fpx_debitAuthCode'], $data['fpx_creditAuthCode'], 'UNSUCCESSFUL');
                        break;
                }

                $message = [];
                foreach($data as $key => $value)
                    $message[] = "{$key}: {$value}";

                $transaction->response_code     = implode('|', [$data['fpx_debitAuthCode'], $data['fpx_creditAuthCode']]);
                $transaction->gateway_reference = $data['fpx_fpxTxnId'];
                $transaction->gateway_auth      = implode('|', [$data['fpx_debitAuthNo'], $data['fpx_creditAuthNo']]);
                $transaction->gateway_response  = implode(' | ', $message);
                $transaction->save();
            }

        }

         \Log::info("Cron is working fine!");
    }
}
