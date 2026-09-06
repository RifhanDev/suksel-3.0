<?php

namespace App\Console\Commands;

use App\Models\Fpx;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Debug helper: panggil RetrieveBankList terus dan laporkan balasan PayNet.
 *
 * Wujud kerana diagnosis serupa yang ditaip terus dalam `php artisan tinker`
 * gagal secara mengelirukan dua kali berturut-turut: mula-mula openssl_sign()
 * gugur senyap (OPENSSL_CONF hanya ditetapkan untuk unit systemd php-fpm,
 * bukan shell root biasa), kemudian satu tanda kurung tercicir semasa menaip
 * semula ungkapan panjang pada konsol VNC yang tiada copy-paste. Arahan ini
 * memuatkan OPENSSL_CONF yang betul dan mencetak SETIAP input yang menentukan
 * hasil, supaya kegagalan seterusnya menunjuk terus kepada puncanya.
 *
 * PayNet menolak dengan badan 'ERROR' sahaja — tiada kod, tiada nama medan —
 * jadi meneka satu pemboleh ubah setiap larian mahal. --sweep mencuba setiap
 * gabungan versi x algoritma tandatangan sekali gus.
 */
class FpxDebugBankList extends Command
{
    protected $signature = 'fpx:debug-banklist'
        . ' {--fpx-version= : Paksa fpx_version tertentu (lalai: nilai dalam baris gateway)}'
        . ' {--msg-token= : Paksa fpx_msgToken tertentu (lalai: 01, iaitu B2C)}'
        . ' {--algo= : Algoritma tandatangan: sha1 atau sha256 (lalai: sha1)}'
        . ' {--sweep : Cuba setiap gabungan versi x algoritma dan laporkan mana yang diterima}';

    protected $description = 'Panggil RetrieveBankList PayNet terus dan laporkan balasan sebenar';

    /** @var array<string, int> */
    private array $algos = [
        'sha1'   => OPENSSL_ALGO_SHA1,
        'sha256' => OPENSSL_ALGO_SHA256,
    ];

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
            $this->error('Kunci privat tidak boleh dibaca — tandatangan pasti gagal.');

            return self::FAILURE;
        }

        $this->line('');

        $token = $this->option('msg-token') ?: '01';

        if ($this->option('sweep')) {
            return $this->sweep($gateway, $token);
        }

        $version  = $this->option('fpx-version') ?: $gateway->version;
        $algoName = strtolower($this->option('algo') ?: 'sha1');

        if (! isset($this->algos[$algoName])) {
            $this->error("Algoritma tidak dikenali: {$algoName}. Guna sha1 atau sha256.");

            return self::FAILURE;
        }

        $hasil = $this->attempt($gateway, $version, $token, $algoName);

        $this->line('source string  = ' . $hasil['source_string']);
        $this->line('algoritma      = ' . $algoName);
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

    private function sweep($gateway, string $token): int
    {
        $this->line("Menguji setiap gabungan versi x algoritma (msgToken {$token}):");
        $this->line('');

        $berjaya = [];

        foreach (['5.0', '6.0', '7.0'] as $version) {
            foreach (array_keys($this->algos) as $algoName) {
                $hasil = $this->attempt($gateway, $version, $token, $algoName);
                $this->line(sprintf('  versi %-4s %-7s -> %s', $version, $algoName, $hasil['ringkas']));

                if ($hasil['banks'] !== null) {
                    $berjaya["{$version}|{$algoName}"] = $hasil['banks'];
                }
            }
        }

        $this->line('');

        if ($berjaya === []) {
            $this->error('Tiada gabungan diterima PayNet.');
            $this->line('Bahan kripto sudah disahkan betul (fpx:check-cert), dan medan serta');
            $this->line('algoritma sudah dicuba — jadi puncanya di pihak PayNet: pengaktifan');
            $this->line('exchange id untuk UAT, atau penyenaraian IP pelayan ini.');

            return self::FAILURE;
        }

        foreach ($berjaya as $combo => $banks) {
            [$version, $algoName] = explode('|', $combo);
            $this->info("DITERIMA: versi {$version}, {$algoName} — " . count($banks) . ' bank');
            $this->reportBanks($banks);
            $this->line('');
        }

        $this->line('Jika gabungan yang diterima berbeza daripada tetapan semasa, itulah puncanya.');

        return self::SUCCESS;
    }

    /**
     * Satu panggilan RetrieveBankList. Memulangkan senarai bank apabila PayNet
     * menerimanya, atau null berserta badan mentah apabila ditolak — supaya
     * pemanggil boleh melaporkan penolakan tanpa menghentikan sweep.
     *
     * @return array{banks: array<string, string>|null, raw: string|null, source_string: string, ringkas: string}
     */
    private function attempt($gateway, string $version, string $token, string $algoName): array
    {
        $fpx = new Fpx([
            'request_type'  => 'BE',
            'msg_token'     => $token,
            'merchant_id'   => $gateway->merchant_code,
            'version'       => $version,
            'reference_url' => $gateway->endpoint_url,
        ]);

        $fpx->signature_algo = $this->algos[$algoName];

        try {
            $banks = $fpx->bankList();
        } catch (\Throwable $e) {
            return [
                'banks'         => null,
                'raw'           => $fpx->last_response_body,
                'source_string' => (string) $fpx->source_string,
                'ringkas'       => 'gagal: ' . get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        $diterima = is_array($banks) && $banks !== [];

        return [
            'banks'         => $diterima ? $banks : null,
            'raw'           => $fpx->last_response_body,
            'source_string' => (string) $fpx->source_string,
            'ringkas'       => $diterima
                ? 'OK, ' . count($banks) . ' bank'
                : 'ditolak, balasan = ' . var_export($fpx->last_response_body, true),
        ];
    }

    /** @param  array<string, string>  $banks */
    private function reportBanks(array $banks): void
    {
        foreach (['TEST0021', 'TEST0022', 'TEST0023'] as $code) {
            $this->line('  ' . $code . ' -> ' . (array_key_exists($code, $banks) ? 'ADA (status=' . $banks[$code] . ')' : 'TIADA'));
        }

        $this->line('  semua kod: ' . implode(', ', array_keys($banks)));
    }
}
