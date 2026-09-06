<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Inventori bahan kripto FPX di cakera, dan padanan silang kunci lawan CSR/sijil.
 *
 * Folder fpx/ mengandungi dua jenis fail yang mudah dikelirukan:
 *
 *   - Sijil PayNet (FPX.cer, CN=FPX SMI) — untuk MENGESAHKAN respons PayNet.
 *     Tiada kaitan dengan kunci privat kita; ia tidak sepatutnya sepadan.
 *   - Bahan merchant KITA (EX000#####.key/.csr/.cer) — PayNet mendaftarkan kunci
 *     awam kita dan mengesahkan setiap permintaan bertandatangan terhadapnya.
 *
 * Sijil dikelaskan mengikut MODULUS, bukan nama: sijil yang kunci awamnya sepadan
 * dengan salah satu kunci privat kita adalah milik kita, selebihnya milik PayNet.
 * Ini tidak boleh tersalah kelas walaupun fail dinamakan semula.
 *
 * Hanya bahan bagi exchange id yang sedang aktif menentukan keputusan — CSR atau
 * sijil milik merchant lain sememangnya tidak sepadan dan bukan bukti apa-apa.
 * Carian merangkumi subfolder, kerana salinan kerap disimpan dalam OLD-Cert atau
 * folder backup.
 */
class FpxCheckCert extends Command
{
    protected $signature = 'fpx:check-cert';

    protected $description = 'Inventori kunci/CSR/sijil FPX dan sahkan kunci aktif sepadan dengan bahan exchange id semasa';

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
        $certs = [];

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
            } elseif (preg_match('/\.(cer|crt|pem)$/i', $path)) {
                $raw  = file_get_contents($path);
                $info = @openssl_x509_parse($raw);

                if ($info !== false) {
                    $certs[$rel] = ['modulus' => $this->modulusOfCert($raw), 'info' => $info];
                }
            }
        }

        $this->line('== Kunci privat (' . count($keys) . ') ==');

        foreach ($keys as $rel => $m) {
            $tag = hash_equals($activeModulus, $m) ? '  <- kunci aktif' : '';
            $this->line('  ' . str_pad($rel, 38) . $this->shortHex($m) . $tag);
        }

        $this->line('');
        $this->line('== CSR (' . count($csrs) . ') ==');

        if ($csrs === []) {
            $this->line('  (tiada)');
        }

        foreach ($csrs as $rel => $m) {
            $this->line('  ' . str_pad($rel, 38) . $this->shortHex($m));
        }

        // Sijil yang kunci awamnya sepadan dengan mana-mana kunci privat kita
        // adalah sijil merchant kita; selebihnya milik PayNet.
        $ourCerts    = [];
        $paynetCerts = [];

        foreach ($certs as $rel => $c) {
            $milikKita = $c['modulus'] !== null
                && ($this->matchesAny($c['modulus'], $keys) || hash_equals($activeModulus, $c['modulus']));

            if ($milikKita) {
                $ourCerts[$rel] = $c;
            } else {
                $paynetCerts[$rel] = $c;
            }
        }

        $this->line('');
        $this->line('== Sijil merchant kita (' . count($ourCerts) . ') ==');

        if ($ourCerts === []) {
            $this->line('  (tiada)');
        }

        foreach ($ourCerts as $rel => $c) {
            $this->line('  ' . str_pad($rel, 38) . $this->shortHex($c['modulus']) . '  ' . $this->validity($c['info']));
        }

        $this->line('');
        $this->line('== Sijil PayNet, untuk mengesahkan respons PayNet (' . count($paynetCerts) . ') ==');

        $adaSijilPayNetSah = false;

        foreach ($paynetCerts as $rel => $c) {
            $adaSijilPayNetSah = $adaSijilPayNetSah || ! $this->expired($c['info']);
            $this->line('  ' . str_pad($rel, 38) . $this->validity($c['info']));
        }

        if ($paynetCerts !== [] && ! $adaSijilPayNetSah) {
            $this->warn('  Semua sijil PayNet luput — tidak menjejaskan permintaan kita (kita');
            $this->warn('  menandatangan dengan kunci privat), tetapi pengesahan tandatangan');
            $this->warn('  respons akan gagal apabila dihidupkan. Minta sijil terkini PayNet.');
        }

        $this->line('');

        // Sijil merchant ialah bukti terkuat: ia kunci awam yang PayNet SENDIRI
        // tandatangani, jadi ia diutamakan berbanding CSR.
        $bukti = $this->materialFor($exchangeId, array_map(fn ($c) => $c['modulus'], $ourCerts))
            ?? $this->materialFor($exchangeId, array_map(fn ($c) => $c['modulus'], $certs))
            ?? $this->materialFor($exchangeId, $csrs);

        if ($bukti !== null) {
            [$rel, $modulus] = $bukti;

            if ($modulus !== null && hash_equals($activeModulus, $modulus)) {
                $this->info("Kunci aktif SEPADAN dengan {$rel} — bahan tempatan konsisten.");

                if (isset($certs[$rel]) && $this->expired($certs[$rel]['info'])) {
                    $this->warn('Namun sijil itu SUDAH LUPUT — minta PayNet keluarkan yang baharu.');

                    return self::FAILURE;
                }

                $this->line("Jika PayNet masih membalas 'ERROR', puncanya bukan bahan kripto:");
                $this->line("semak pengaktifan {$exchangeId} di UAT PayNet dan penyenaraian IP pelayan.");

                return self::SUCCESS;
            }

            $this->error("Kunci aktif TIDAK sepadan dengan {$rel} — kunci privat salah di cakera.");
            $this->line('Kunci yang berpasangan dengan fail itulah yang mesti berada di ' . basename($keyPath) . '.');

            return self::FAILURE;
        }

        foreach ($csrs as $rel => $m) {
            if (hash_equals($activeModulus, $m)) {
                $this->warn("Tiada bahan untuk {$exchangeId}, tetapi kunci aktif sepadan dengan {$rel}.");
                $this->line('Kunci ini milik exchange id lain — semak sama ada merchant_code betul.');

                return self::SUCCESS;
            }
        }

        $this->warn("TIDAK DAPAT DISAHKAN: tiada CSR atau sijil untuk {$exchangeId} di cakera.");
        $this->line('Bahan yang ada milik exchange id lain, jadi ia sememangnya tidak sepadan');
        $this->line('dan bukan bukti kunci ini salah.');
        $this->line('');
        $this->line("Untuk mengesahkan: letakkan {$exchangeId}.cer (sijil daripada PayNet) dalam");
        $this->line('fpx/, atau minta PayNet sahkan modulus kunci awam yang mereka daftarkan.');

        return self::SUCCESS;
    }

    /**
     * Cari bahan yang namanya bermula dengan exchange id semasa.
     *
     * @param  array<string, string|null>  $haystack
     * @return array{0: string, 1: string|null}|null
     */
    private function materialFor(string $exchangeId, array $haystack): ?array
    {
        foreach ($haystack as $rel => $modulus) {
            if (stripos(basename($rel), $exchangeId) === 0) {
                return [$rel, $modulus];
            }
        }

        return null;
    }

    /** @param  array<string, string>  $moduli */
    private function matchesAny(string $modulus, array $moduli): bool
    {
        foreach ($moduli as $m) {
            if (hash_equals($modulus, $m)) {
                return true;
            }
        }

        return false;
    }

    /** @param  array<string, mixed>  $info */
    private function expired(array $info): bool
    {
        $to = $info['validTo_time_t'] ?? 0;

        return $to > 0 && $to < time();
    }

    /** @param  array<string, mixed>  $info */
    private function validity(array $info): string
    {
        $to = $info['validTo_time_t'] ?? 0;

        return 'sah hingga ' . ($to ? date('Y-m-d', $to) : '?') . ($this->expired($info) ? '  <-- LUPUT' : '');
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

    private function modulusOfCert(string $raw): ?string
    {
        $pub = @openssl_pkey_get_public($raw);

        return $pub === false ? null : $this->modulusOf($pub);
    }

    private function modulusOf($key): ?string
    {
        $details = @openssl_pkey_get_details($key);

        return isset($details['rsa']['n']) ? bin2hex($details['rsa']['n']) : null;
    }

    private function shortHex(?string $hex): string
    {
        return $hex === null ? '(bukan RSA)' : substr($hex, 0, 12) . '..' . substr($hex, -8);
    }

    private function shorten(string $hex): string
    {
        return strlen($hex) <= 32
            ? $hex
            : substr($hex, 0, 16) . '...' . substr($hex, -16) . ' (' . (strlen($hex) * 4) . ' bit)';
    }
}
