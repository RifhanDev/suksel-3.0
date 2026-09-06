<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Semak bahan kripto FPX di cakera.
 *
 * Folder fpx/ mengandungi DUA jenis fail yang mudah dikelirukan:
 *
 *   - FPX.cer — sijil PayNet SENDIRI (CN=FPX SMI, O=Payments Network Malaysia).
 *     Ia digunakan untuk MENGESAHKAN respons PayNet. Ia tidak ada kaitan dengan
 *     kunci privat kita dan tidak sepatutnya sepadan dengannya.
 *   - EX000#####.key + .csr — identiti merchant KITA (CN=STOS, O=PEJABAT SUK
 *     SELANGOR). PayNet mendaftarkan kunci awam daripada CSR ini dan mengesahkan
 *     setiap permintaan bertandatangan kita terhadapnya.
 *
 * Oleh itu satu-satunya pemeriksaan pasangan yang bermakna secara tempatan ialah
 * kunci privat lawan CSR — bukan lawan FPX.cer. Membandingkannya dengan FPX.cer
 * sentiasa melaporkan "tidak sepadan" dan menuding kepada punca yang salah.
 */
class FpxCheckCert extends Command
{
    protected $signature = 'fpx:check-cert';

    protected $description = 'Sahkan kunci privat FPX sepadan dengan CSR merchant, dan laporkan status sijil PayNet';

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

        $keyModulus = $this->modulusOfPrivateKey($keyPath);

        if ($keyModulus === null) {
            $this->error('Kunci privat gagal dihuraikan — fail rosak atau bukan kunci RSA.');

            return self::FAILURE;
        }

        $this->line('modulus      = ' . $this->shorten($keyModulus));
        $this->line('');

        // --- Pasangan merchant: kunci privat lawan CSR ------------------------
        $this->line('== Identiti merchant kita (kunci privat lawan CSR) ==');

        $csrs   = glob(base_path() . '/fpx/*.csr') ?: [];
        $padan  = false;
        $adaCsr = false;

        foreach ($csrs as $csrPath) {
            $raw     = file_get_contents($csrPath);
            $modulus = $this->modulusOfCsr($raw);

            if ($modulus === null) {
                continue;
            }

            $adaCsr = true;
            $sama   = hash_equals($keyModulus, $modulus);
            $padan  = $padan || $sama;

            $this->line('  ' . basename($csrPath) . ' -> ' . ($sama ? 'SEPADAN' : 'tidak sepadan'));
            $this->line('    subjek: ' . $this->subjectOfCsr($raw));
        }

        if (! $adaCsr) {
            $this->line('  Tiada CSR dalam fpx/ untuk dibandingkan.');
        }

        $this->line('');

        // --- Sijil PayNet: untuk mengesahkan respons mereka -------------------
        $this->line('== Sijil PayNet (untuk mengesahkan respons PayNet) ==');

        $adaSijilSah = false;

        foreach (glob(base_path() . '/fpx/*.{cer,crt,pem}', GLOB_BRACE) ?: [] as $certPath) {
            $info = @openssl_x509_parse(file_get_contents($certPath));

            if ($info === false) {
                $this->line('  ' . basename($certPath) . ' -> bukan sijil X.509 yang sah');
                continue;
            }

            $to    = $info['validTo_time_t'] ?? 0;
            $luput = $to > 0 && $to < time();
            $adaSijilSah = $adaSijilSah || ! $luput;

            $this->line('  ' . basename($certPath) . ' -> sah hingga ' . ($to ? date('Y-m-d', $to) : '?')
                . ($luput ? '  <-- SUDAH LUPUT' : ''));
        }

        if (! $adaSijilSah) {
            $this->line('');
            $this->warn('Semua sijil PayNet sudah luput. Ini TIDAK menyebabkan permintaan kita ditolak');
            $this->warn('(kita menandatangan dengan kunci privat), tetapi pengesahan tandatangan');
            $this->warn('respons PayNet akan gagal apabila ia dihidupkan. Minta sijil terkini PayNet.');
        }

        $this->line('');

        // --- Kesimpulan -------------------------------------------------------
        if ($padan) {
            $this->info('Kunci privat sepadan dengan CSR merchant — bahan kripto tempatan konsisten.');
            $this->line("Jika PayNet masih membalas 'ERROR', puncanya di pihak PayNet: kunci awam");
            $this->line("daripada CSR ini belum didaftarkan/diaktifkan untuk {$exchangeId} di UAT.");

            return self::SUCCESS;
        }

        if ($adaCsr) {
            $this->error('Kunci privat TIDAK sepadan dengan mana-mana CSR dalam fpx/.');
            $this->line('Kunci yang menjana CSR itulah yang mesti berada di ' . basename($keyPath) . '.');

            return self::FAILURE;
        }

        $this->warn("TIDAK DAPAT DISAHKAN: tiada CSR untuk {$exchangeId} di cakera.");
        $this->line('PayNet menyimpan kunci awam kita; kita hanya menyimpan kunci privat, jadi');
        $this->line('pasangan itu tidak dapat dibuktikan secara tempatan tanpa CSR asal.');
        $this->line("Cari EX*.csr yang dijana bersama {$exchangeId}.key, atau minta PayNet sahkan");
        $this->line('cap jari kunci awam yang mereka daftarkan untuk exchange id ini.');

        return self::SUCCESS;
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

    private function subjectOfCsr(string $raw): string
    {
        $subject = @openssl_csr_get_subject($raw);

        if (! is_array($subject)) {
            return '?';
        }

        $parts = [];

        foreach (['CN', 'O', 'OU'] as $field) {
            if (! empty($subject[$field])) {
                $parts[] = $field . '=' . (is_array($subject[$field]) ? reset($subject[$field]) : $subject[$field]);
            }
        }

        return $parts === [] ? '?' : implode(', ', $parts);
    }

    private function modulusOf($key): ?string
    {
        $details = @openssl_pkey_get_details($key);

        return isset($details['rsa']['n']) ? bin2hex($details['rsa']['n']) : null;
    }

    private function shorten(string $hex): string
    {
        return strlen($hex) <= 32
            ? $hex
            : substr($hex, 0, 16) . '...' . substr($hex, -16) . ' (' . (strlen($hex) * 4) . ' bit)';
    }
}
