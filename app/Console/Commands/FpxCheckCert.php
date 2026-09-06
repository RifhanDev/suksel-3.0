<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Semak sama ada kunci privat FPX benar-benar sepadan dengan sijil dalam fpx/.
 *
 * PayNet mengesahkan setiap permintaan bertandatangan menggunakan sijil merchant
 * yang mereka daftarkan. Jika kunci privat di cakera bukan pasangan kepada sijil
 * itu, SETIAP permintaan ditolak dengan badan 'ERROR' sahaja — tiada kod, tiada
 * nama medan. Gejalanya nampak sama seperti medan salah, jadi ia mudah disalah
 * diagnos (sweep versi x msgToken menolak kesemua enam gabungan sebelum ini).
 *
 * Pasangan RSA dibuktikan dengan membandingkan modulus: kunci privat dan sijil
 * yang sepadan mesti berkongsi modulus yang sama. Tarikh sah sijil turut
 * diperiksa, kerana sijil luput juga menghasilkan penolakan yang serupa.
 */
class FpxCheckCert extends Command
{
    protected $signature = 'fpx:check-cert';

    protected $description = 'Sahkan kunci privat FPX sepadan dengan sijil dalam fpx/, dan sijil belum luput';

    public function handle(): int
    {
        $opensslConf = '/etc/pki/tls/openssl-fpx.cnf';

        if (is_file($opensslConf)) {
            putenv("OPENSSL_CONF={$opensslConf}");
        }

        $gateway = DB::table('gateways')->where('type', 'fpx')->first();

        if (! $gateway) {
            $this->error('Tiada baris gateway type=fpx dijumpai.');

            return self::FAILURE;
        }

        $exchangeId = trim(explode('|', $gateway->merchant_code)[0]);
        $keyPath    = base_path() . '/fpx/' . $exchangeId . '.key';

        $this->line("exchange_id  = {$exchangeId}");
        $this->line("kunci privat = {$keyPath}");

        if (! is_readable($keyPath)) {
            $this->error('Kunci privat tidak boleh dibaca.');

            return self::FAILURE;
        }

        $privateModulus = $this->modulusOfPrivateKey($keyPath);

        if ($privateModulus === null) {
            $this->error('Kunci privat gagal dihuraikan — fail rosak atau bukan kunci RSA.');

            return self::FAILURE;
        }

        $this->line('modulus kunci = ' . $this->shorten($privateModulus));
        $this->line('');

        $certs = glob(base_path() . '/fpx/*.{cer,crt,pem}', GLOB_BRACE) ?: [];

        if ($certs === []) {
            $this->error('Tiada fail sijil (.cer/.crt/.pem) dalam fpx/.');

            return self::FAILURE;
        }

        $adaPadanan = false;

        foreach ($certs as $certPath) {
            $this->line('sijil: ' . basename($certPath));

            $raw  = file_get_contents($certPath);
            $info = @openssl_x509_parse($raw);

            if ($info === false) {
                $this->line('  BUKAN sijil X.509 yang sah (mungkin kunci awam mentah atau fail lain)');
                $this->line('');
                continue;
            }

            $this->line('  subjek     : ' . ($info['name'] ?? '?'));
            $this->line('  pengeluar  : ' . ($info['issuer']['CN'] ?? ($info['issuer']['O'] ?? '?')));

            $from = isset($info['validFrom_time_t']) ? date('Y-m-d', $info['validFrom_time_t']) : '?';
            $to   = isset($info['validTo_time_t']) ? date('Y-m-d', $info['validTo_time_t']) : '?';
            $luput = isset($info['validTo_time_t']) && $info['validTo_time_t'] < time();

            $this->line("  sah        : {$from} hingga {$to}" . ($luput ? '  <-- SUDAH LUPUT' : ''));

            $certModulus = $this->modulusOfCertificate($raw);

            if ($certModulus === null) {
                $this->line('  modulus    : gagal dibaca');
                $this->line('');
                continue;
            }

            $padan = hash_equals($privateModulus, $certModulus);
            $this->line('  modulus    : ' . $this->shorten($certModulus));
            $this->line('  SEPADAN dengan kunci privat? ' . ($padan ? 'YA' : 'TIDAK'));
            $this->line('');

            if ($padan && ! $luput) {
                $adaPadanan = true;
            }
        }

        if ($adaPadanan) {
            $this->info('Ada sijil sah yang sepadan dengan kunci privat — puncanya bukan pasangan kunci/sijil.');

            return self::SUCCESS;
        }

        $this->error('TIADA sijil sah yang sepadan dengan kunci privat.');
        $this->line('PayNet mengesahkan tandatangan kita menggunakan sijil yang mereka daftarkan,');
        $this->line("jadi setiap permintaan akan ditolak dengan 'ERROR' selagi ini tidak sepadan.");
        $this->line('Kunci privat yang menjana CSR itulah yang mesti berada di ' . basename($keyPath) . '.');

        return self::FAILURE;
    }

    private function modulusOfPrivateKey(string $path): ?string
    {
        $key = @openssl_pkey_get_private(file_get_contents($path));

        if ($key === false) {
            return null;
        }

        $details = @openssl_pkey_get_details($key);

        return isset($details['rsa']['n']) ? bin2hex($details['rsa']['n']) : null;
    }

    private function modulusOfCertificate(string $raw): ?string
    {
        $pub = @openssl_pkey_get_public($raw);

        if ($pub === false) {
            return null;
        }

        $details = @openssl_pkey_get_details($pub);

        return isset($details['rsa']['n']) ? bin2hex($details['rsa']['n']) : null;
    }

    private function shorten(string $hex): string
    {
        return strlen($hex) <= 32
            ? $hex
            : substr($hex, 0, 16) . '...' . substr($hex, -16) . ' (' . (strlen($hex) * 4) . ' bit)';
    }
}
