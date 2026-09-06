<?php

namespace App\Console\Commands;

use App\Models\Fpx;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Debug helper: panggil RetrieveBankList terus dan tunjuk sama ada
 * SBI Bank A/B/C (TEST0021/22/23) wujud dalam respons PayNet.
 *
 * Wujud kerana diagnosis serupa yang ditaip terus dalam `php artisan tinker`
 * gagal secara mengelirukan dua kali berturut-turut: mula-mula openssl_sign()
 * gugur senyap (OPENSSL_CONF hanya ditetapkan untuk unit systemd php-fpm,
 * bukan shell root biasa), kemudian satu tanda kurung tercicir semasa menaip
 * semula ungkapan panjang pada konsol VNC yang tiada copy-paste. Arahan ini
 * memuatkan OPENSSL_CONF yang betul dan mencetak SETIAP input yang menentukan
 * hasil, supaya kegagalan seterusnya menunjuk terus kepada puncanya.
 */
class FpxDebugBankList extends Command
{
    protected $signature = 'fpx:debug-banklist';

    protected $description = 'Panggil RetrieveBankList PayNet terus dan semak sama ada SBI Bank A/B/C wujud';

    public function handle(): int
    {
        $opensslConf = '/etc/pki/tls/openssl-fpx.cnf';

        if (is_file($opensslConf)) {
            putenv("OPENSSL_CONF={$opensslConf}");
            $this->line("OPENSSL_CONF   = {$opensslConf}");
        } else {
            $this->warn("OPENSSL_CONF   = {$opensslConf} TIDAK DIJUMPAI (tandatangan mungkin gagal senyap pada RHEL)");
        }

        $gateway = DB::table('gateways')->where('type', 'fpx')->first();

        if (! $gateway) {
            $this->error('Tiada baris gateway type=fpx dijumpai.');

            return self::FAILURE;
        }

        $fpx = new Fpx([
            'request_type'  => 'BE',
            'msg_token'     => '01',
            'merchant_id'   => $gateway->merchant_code,
            'version'       => $gateway->version,
            'reference_url' => $gateway->endpoint_url,
        ]);

        // Setiap nilai di bawah menentukan sama ada PayNet menerima permintaan.
        // Dicetak sebelum panggilan dibuat supaya satu larian sudah cukup untuk
        // mengenal pasti input mana yang salah bila PayNet menolak.
        $this->line("merchant_code  = {$gateway->merchant_code}");
        $this->line("version        = {$gateway->version}");
        $this->line("exchange_id    = {$fpx->exchange_id}");
        $this->line("seller_id      = {$fpx->seller_id}");
        $this->line("endpoint_url   = {$gateway->endpoint_url}");
        $this->line('bank list URL  = ' . Fpx::bankListUrl($gateway->endpoint_url));

        $key = $fpx->private_key;
        $this->line("kunci privat   = {$key}");
        $this->line('  wujud?       = ' . (is_file($key) ? 'YA' : 'TIDAK — inilah puncanya'));
        $this->line('  boleh baca?  = ' . (is_readable($key) ? 'YA' : 'TIDAK — semak kebenaran fail'));

        if (! is_file($key) || ! is_readable($key)) {
            $this->error('Kunci privat tidak boleh dibaca — tandatangan pasti gagal. Berhenti di sini.');

            return self::FAILURE;
        }

        $this->line('');

        try {
            $banks = $fpx->bankList();
        } catch (\Throwable $e) {
            $this->error('Panggilan gagal: ' . get_class($e) . ': ' . $e->getMessage());
            $this->line('Badan mentah PayNet: ' . var_export($fpx->last_response_body, true));

            return self::FAILURE;
        }

        $this->line('source string  = ' . $fpx->source_string);
        $this->line('');

        if (! is_array($banks) || $banks === []) {
            $this->error('PayNet tidak memulangkan senarai bank.');
            $this->line('Badan mentah yang PayNet balas:');
            $this->line(var_export($fpx->last_response_body, true));

            return self::FAILURE;
        }

        $this->info('Jumlah bank dipulangkan PayNet: ' . count($banks));

        foreach (['TEST0021', 'TEST0022', 'TEST0023'] as $code) {
            $this->line($code . ' -> ' . (array_key_exists($code, $banks) ? 'ADA (status=' . $banks[$code] . ')' : 'TIADA'));
        }

        $this->line('');
        $this->line('Semua kod yang dipulangkan:');
        $this->line(implode(', ', array_keys($banks)));

        return self::SUCCESS;
    }
}
