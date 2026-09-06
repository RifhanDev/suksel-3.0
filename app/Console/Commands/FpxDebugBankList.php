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
 * pada sesi shell interaktif gagal secara mengelirukan: openssl_sign() gugur
 * senyap dalam sesi itu kerana OPENSSL_CONF hanya ditetapkan untuk unit
 * systemd php-fpm, bukan shell root biasa — checksum kosong dihantar,
 * PayNet menolak, dan hasilnya kelihatan seperti "tiada bank" walaupun
 * bukan itu puncanya. Arahan ini memuatkan tetapan OPENSSL_CONF yang sama
 * sebelum menandatangan, supaya hasilnya boleh dipercayai.
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
        } else {
            $this->warn("Fail {$opensslConf} tidak dijumpai — teruskan tanpanya, tandatangan mungkin gagal senyap pada RHEL.");
        }

        $gateway = DB::table('gateways')->where('type', 'fpx')->first();

        if (! $gateway) {
            $this->error('Tiada baris gateway type=fpx dijumpai.');

            return self::FAILURE;
        }

        $this->info("endpoint_url = {$gateway->endpoint_url}");

        $fpx = new Fpx([
            'request_type'  => 'BE',
            'msg_token'     => '01',
            'merchant_id'   => $gateway->merchant_code,
            'version'       => $gateway->version,
            'reference_url' => $gateway->endpoint_url,
        ]);

        $banks = $fpx->bankList();

        if (! is_array($banks) || $banks === [] || array_keys($banks) === [0]) {
            $this->error('Respons PayNet tidak sah atau kosong — kemungkinan tandatangan masih gagal.');
            $this->line('Isi mentah:');
            $this->line(print_r($banks, true));

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
