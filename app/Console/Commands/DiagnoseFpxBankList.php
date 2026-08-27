<?php

namespace App\Console\Commands;

use App\Models\Fpx;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

/**
 * One-off diagnostic for the "Fpx.php:225 Undefined array key 1" crash —
 * mirrors Fpx::bankList()'s exact request-building/signing logic but prints
 * every step instead of parsing the response blindly, so a malformed/ERROR
 * response from PayNet is visible instead of causing a fatal error.
 *
 * Temporary — safe to delete once the bank-list bug is resolved.
 */
class DiagnoseFpxBankList extends Command
{
    protected $signature = 'fpx:diagnose-banklist {gateway_id=3}';

    protected $description = 'Diagnose the FPX bankList() request/signature/response for a gateway';

    public function handle(): int
    {
        $gateway = \DB::table('gateways')->find($this->argument('gateway_id'));

        if (!$gateway) {
            $this->error("Gateway id={$this->argument('gateway_id')} tidak wujud.");
            return 1;
        }

        $this->info("Gateway: {$gateway->merchant_code} | endpoint_url={$gateway->endpoint_url}");

        $fpx = new Fpx([
            'request_type'  => 'BE',
            'msg_token'     => '01',
            'merchant_id'   => $gateway->merchant_code,
            'version'       => $gateway->version,
            'reference_url' => $gateway->endpoint_url,
        ]);

        $this->line("private_key path: {$fpx->private_key}");

        if (!is_readable($fpx->private_key)) {
            $this->error('Fail private key TIDAK boleh dibaca.');
            return 1;
        }

        $content = file_get_contents($fpx->private_key);
        $key = openssl_pkey_get_private($content);

        if ($key === false) {
            $this->error('openssl_pkey_get_private() pulangkan false:');
            while ($e = openssl_error_string()) {
                $this->error("  $e");
            }
            return 1;
        }

        $this->info('Private key sah.');

        $params = Arr::only($fpx->request_keys, ['fpx_msgType', 'fpx_msgToken', 'fpx_version', 'fpx_sellerExId']);
        ksort($params);
        $sourceString = implode('|', $params);

        $ok = openssl_sign($sourceString, $signature, $key, OPENSSL_ALGO_SHA1);
        $this->line('source_string: ' . $sourceString);
        $this->info('openssl_sign() berjaya: ' . var_export($ok, true));

        $params['fpx_checkSum'] = strtoupper(bin2hex($signature));
        ksort($params);

        $this->line('params yang dihantar:');
        foreach ($params as $k => $v) {
            $this->line("  $k => $v");
        }

        $parts = parse_url($gateway->endpoint_url);
        $bankListUrl = "{$parts['scheme']}://{$parts['host']}/FPXMain/RetrieveBankList";
        $this->line("URL: $bankListUrl");

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($bankListUrl, [
                'form_params' => $params,
                'verify'      => config('services.fpx.verify_tls', true),
            ]);
            $this->info('HTTP status: ' . $response->getStatusCode());
            $this->info('RAW BODY:');
            $this->line((string) $response->getBody());
        } catch (\Throwable $e) {
            $this->error('Permintaan HTTP gagal: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
