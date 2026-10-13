<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Runs FpxBankListSeeder as part of every deploy, so it's never forgotten
 * manually (including on production — see that seeder's docblock for why
 * that's safe and what its reconcile logic actually does; no bank data is
 * duplicated here, this just calls it).
 */
return new class extends Migration
{
    public function up(): void
    {
        (new \Database\Seeders\FpxBankListSeeder())->run();
    }

    public function down(): void
    {
        // Deliberately not implemented — see FpxBankListSeeder's docblock.
    }
};
