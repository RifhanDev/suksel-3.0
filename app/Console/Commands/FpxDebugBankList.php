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
    protected $signature = 'fpx:debug-banklist'
        . ' {--fpx-version= : Paksa fpx_version tertentu (lalai: nilai dalam baris gateway)}'
        . ' {--msg-token= : Paksa fpx_msgToken tertentu (lalai: 01)}'
        . ' {--sweep : Cuba setiap gabungan versi x msgToken dan laporkan mana yang diterima}';

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

        // PayNet membalas 'ERROR' tanpa menyatakan medan mana yang ditolak, jadi
        // meneka satu-satu bermakna satu larian penuh bagi setiap tekaan — mahal
        // pada konsol VNC tanpa copy-paste. Sweep mencuba setiap gabungan dan
        // melaporkan mana yang diterima dalam satu larian.
        if ($this->option('sweep')) {
            $this->line('Menguji setiap gabungan versi x msgToken:');
            $this->line('');
            $berjaya = [];

            foreach (['5.0', '6.0', '7.0'] as $version) {
                foreach (['01', '02'] as $token) {
                    $hasil = $this->attempt($gateway, $version, $token);
                    $this->line(sprintf('  versi %-4s msgToken %-3s -> %s', $version, $token, $hasil['ringkas']));

                    if ($hasil['banks'] !== null) {
                        $berjaya["{$version}|{$token}"] = $hasil['banks'];
                    }
                }
            }

            $this->line('');

            if ($berjaya === []) {
                $this->error('Tiada gabungan diterima PayNet — puncanya bukan versi/msgToken.');

                return self::FAILURE;
            }

            foreach ($berjaya as $combo => $banks) {
                [$version, $token] = explode('|', $combo);
                $this->info("DITERIMA: versi {$version}, msgToken {$token} — " . count($banks) . ' bank');
                $this->reportBanks($banks);
                $this->line('');
            }

            $this->line('Jika versi yang diterima berbeza daripada baris gateway, itulah puncanya.');

            return self::SUCCESS;
        }

        $version = $this->option('fpx-version') ?: $gateway->version;
        $token   = $this->option('msg-token') ?: '01';
        $hasil   = $this->attempt($gateway, $version, $token);

        $this->line('source string  = ' . $hasil['source_string']);
        $this->line('');

        if ($hasil['banks'] === null) {
            $this->error('PayNet tidak memulangkan senarai bank.');
            $this->line('Badan mentah yang PayNet balas:');
            $this->line(var_export($hasil['raw'], true));
            $this->line('');
            $this->line('Cuba: php artisan fpx:debug-banklist --sweep');

            return self::FAILURE;
        }

        $this->info('Jumlah bank dipulangkan PayNet: ' . count($hasil['banks']));
        $this->reportBanks($hasil['banks']);

        return self::SUCCESS;
    }

    /**
     * Satu panggilan RetrieveBankList. Memulangkan senarai bank apabila PayNet
     * menerimanya, atau null berserta badan mentah apabila ditolak — supaya
     * pemanggil boleh melaporkan penolakan tanpa menghentikan sweep.
     */
    private function attempt($gateway, string $version, string $token): array
    {
        $fpx = new Fpx([
            'request_type'  => 'BE',
            'msg_token'     => $token,
            'merchant_id'   => $gateway->merchant_code,
            'version'       => $version,
            'reference_url' => $gateway->endpoint_url,
        ]);

        try {
            $banks = $fpx->bankList();
        } catch (\Throwable $e) {
            return [
                'banks'         => null,
                'raw'           => $fpx->last_response_body,
                'source_string' => $fpx->source_string,
                'ringkas'       => 'gagal: ' . get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        $diterima = is_array($banks) && $banks !== [];

        return [
            'banks'         => $diterima ? $banks : null,
            'raw'           => $fpx->last_response_body,
            'source_string' => $fpx->source_string,
            'ringkas'       => $diterima
                ? 'OK, ' . count($banks) . ' bank'
                : 'ditolak, balasan = ' . var_export($fpx->last_response_body, true),
        ];
    }

    private function reportBanks(array $banks): void
    {
        foreach (['TEST0021', 'TEST0022', 'TEST0023'] as $code) {
            $this->line('  ' . $code . ' -> ' . (array_key_exists($code, $banks) ? 'ADA (status=' . $banks[$code] . ')' : 'TIADA'));
        }

        $this->line('  semua kod: ' . implode(', ', array_keys($banks)));
    }

}
