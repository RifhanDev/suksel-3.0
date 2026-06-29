<?php

namespace App\Console\Commands;

use App\Services\KodPembekalService;
use Illuminate\Console\Command;

class SyncKodPembekalCommand extends Command
{
    protected $signature = 'kod-pembekal:sync {tender_id? : Optional tender ID to sync}';

    protected $description = 'Generate kod_pembekal (e.g. 1/20) for tender purchasers';

    public function handle(KodPembekalService $service): int
    {
        $tenderId = $this->argument('tender_id');

        if ($tenderId) {
            $service->syncForTender((int) $tenderId);
            $this->info("Kod pembekal synced for tender {$tenderId}.");

            return self::SUCCESS;
        }

        $count = $service->syncAll();
        $this->info("Kod pembekal synced for {$count} tender(s).");

        return self::SUCCESS;
    }
}
