<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Inventori bahan kripto FPX di cakera, dan padanan silang kunci lawan CSR.
 *
 * Folder fpx/ mengandungi dua jenis fail yang mudah dikelirukan:
 *
 *   - FPX.cer — sijil PayNet SENDIRI (CN=FPX SMI). Untuk mengesahkan respons
 *     PayNet. Tiada kaitan dengan kunci privat kita; ia tidak sepatutnya sepadan.
 *   - EX000#####.key + .csr — identiti merchant KITA (CN=STOS). PayNet
 *     mendaftarkan kunci awam daripada CSR dan mengesahkan permintaan kita
 *     terhadapnya, jadi kunci lawan CSR ialah satu-satunya pasangan yang boleh
 *     dibuktikan secara tempatan.
 *
 * Padanan dibuat mengikut MODULUS, bukan nama fail, dan hanya CSR bagi exchange
 * id yang sedang aktif yang menentukan keputusan — CSR milik merchant lain
 * sememangnya tidak sepadan dan bukan bukti apa-apa. Carian merangkumi subfolder,
 * kerana salinan kerap disimpan dalam OLD-Cert atau folder backup.
 */
class FpxCheckCert extends Command
{
    protected $signature = 'fpx:check-cert';

    protected $description = 'Inventori kunci/CSR FPX dan sahkan kunci aktif sepadan dengan CSR exchange id semasa';

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
        $fpxDir     = base_path() . '/fpx';
        $keyPath    = $fpxDir . '/' . $exchangeId . '.key';

        $this->line("exchange_id = {$exchangeId}");
        $this->line("kunci aktif = {$keyPath}");

        if (! is_readable($keyPath)) {
            $this->error('Kunci privat tidak boleh dibaca.');

            return self::FAILURE;
        }

        $activeModulus = $this->modulusOfPrivateKey($keyPath);

        if ($activeModulus === null) {
            $this->error('Kunci privat gagal dihuraikan.');

            return self::FAILURE;
        }

        $this->line('modulus     = ' . $this->shorten($activeModulus));
        $this->line('');

        $files = $this->scan($fpxDir);
        $keys  = [];
        $csrs  = [];

        foreach ($files as $path) {
            $rel = ltrim(str_replace($fpxDir, '', $path), '/\\');

            if (preg_match('/\.key$/i', $path)) {
                $m = $this->modulusOfPrivateKey($path);

                if ($m !== null) {
                    $keys[$rel] = $m;
                }
            } elseif (preg_match('/\.csr$/i', $path)) {
                $m = $this->modulusOfCsr(file_get_contents($path));

                if ($m !== null) {
                    $csrs[$rel] = $m;
                }
            }
        }

        $this->line('== Kunci privat dijumpai (' . count($keys) . ') ==');

        foreach ($keys as $rel => $m) {
            $tag = hash_equals($activeModulus, $m) ? '  <- kunci aktif' : '';
            $this->line('  ' . str_pad($rel, 38) . $this->shortHex($m) . $tag);
        }

        $this->line('');
        $this->line('== CSR dijumpai (' . count($csrs) . ') ==');

        if ($csrs === []) {
            $this->line('  (tiada)');
        }

        foreach ($csrs as $rel => $m) {
            $this->line('  ' . str_pad($rel, 38) . $this->shortHex($m));
        }

        $this->line('');
        $this->line('== Sijil PayNet (mengesahkan respons PayNet) ==');

        $adaSijilSah = false;

        foreach ($files as $path) {
            if (! preg_match('/\.(cer|crt|pem)$/i', $path)) {
                continue;
            }

            $info = @openssl_x509_parse(file_get_contents($path));

            if ($info === false) {
                continue;
            }

            $to    = $info['validTo_time_t'] ?? 0;
            $luput = $to > 0 && $to < time();

            $adaSijilSah = $adaSijilSah || ! $luput;

            $rel = ltrim(str_replace($fpxDir, '', $path), '/\\');
            $this->line('  ' . str_pad($rel, 38) . 'sah hingga ' . ($to ? date('Y-m-d', $to) : '?')
                . ($luput ? '  <-- LUPUT' : ''));
        }

        if (! $adaSijilSah) {
            $this->warn('  Semua sijil PayNet luput — tidak menjejaskan permintaan kita (kita');
            $this->warn('  menandatangan dengan kunci privat), tetapi pengesahan tandatangan');
            $this->warn('  respons akan gagal apabila dihidupkan. Minta sijil terkini PayNet.');
        }

        $this->line('');

        // Hanya CSR bagi exchange id semasa yang boleh menentukan keputusan.
        $csrSemasa = null;

        foreach ($csrs as $rel => $m) {
            if (stripos(basename($rel), $exchangeId) === 0) {
                $csrSemasa = [$rel, $m];
                break;
            }
        }

        if ($csrSemasa !== null) {
            [$rel, $m] = $csrSemasa;

            if (hash_equals($activeModulus, $m)) {
                $this->info("Kunci aktif SEPADAN dengan {$rel} — bahan tempatan konsisten.");
                $this->line("Jika PayNet masih membalas 'ERROR', kunci awam ini belum");
                $this->line("didaftarkan/diaktifkan untuk {$exchangeId} di UAT PayNet.");

                return self::SUCCESS;
            }

            $this->error("Kunci aktif TIDAK sepadan dengan {$rel} — kunci privat salah di cakera.");
            $this->line('Kunci yang menjana CSR itulah yang mesti berada di ' . basename($keyPath) . '.');

            return self::FAILURE;
        }

        // Kunci aktif mungkin milik CSR merchant lain — itu petunjuk berguna.
        foreach ($csrs as $rel => $m) {
            if (hash_equals($activeModulus, $m)) {
                $this->warn("Tiada CSR untuk {$exchangeId}, tetapi kunci aktif sepadan dengan {$rel}.");
                $this->line('Kunci ini milik exchange id lain — semak sama ada merchant_code betul.');

                return self::SUCCESS;
            }
        }

        $this->warn("TIDAK DAPAT DISAHKAN: tiada CSR untuk {$exchangeId} di cakera.");
        $this->line('CSR yang ada milik exchange id lain, jadi ia sememangnya tidak sepadan');
        $this->line('dan bukan bukti kunci ini salah.');
        $this->line('');
        $this->line("Untuk mengesahkan: cari {$exchangeId}.csr yang dijana bersama kunci ini,");
        $this->line('atau minta PayNet sahkan modulus kunci awam yang didaftarkan untuk');
        $this->line("{$exchangeId}, dan bandingkan dengan modulus di atas.");

        return self::SUCCESS;
    }

    /** @return array<int, string> */
    private function scan(string $dir): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        $out = [];

        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($it as $file) {
            if ($file->isFile()) {
                $out[] = $file->getPathname();
            }
        }

        sort($out);

        return $out;
    }

    private function modulusOfPrivateKey(string $path): ?string
    {
        $key = @openssl_pkey_get_private(file_get_contents($path));

        return $key === false ? null : $this->modulusOf($key);
    }

    private function modulusOfCsr(string $raw): ?string
    {
        $pub = @openssl_csr_get_public_key($raw);

        return $pub === false ? null : $this->modulusOf($pub);
    }

    private function modulusOf($key): ?string
    {
        $details = @openssl_pkey_get_details($key);

        return isset($details['rsa']['n']) ? bin2hex($details['rsa']['n']) : null;
    }

    private function shortHex(string $hex): string
    {
        return substr($hex, 0, 12) . '..' . substr($hex, -8);
    }

    private function shorten(string $hex): string
    {
        return strlen($hex) <= 32
            ? $hex
            : substr($hex, 0, 16) . '...' . substr($hex, -16) . ' (' . (strlen($hex) * 4) . ' bit)';
    }
}
