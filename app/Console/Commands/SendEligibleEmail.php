<?php

namespace App\Console\Commands;

use App\TenderEligible;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEligibleEmail extends Command
{
    use Helper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:eligible-email
                            {--tender= : Only send for this tender id}
                            {--limit= : Max rows to process (default 100; with --tender default is all pending)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send tender eligible email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenderId = $this->option('tender');
        $limitOption = $this->option('limit');

        $query = TenderEligible::with(['tender', 'vendor.user'])
            ->whereNull('sent_at')
            ->where('email', 1)
            ->whereHas('vendor', function ($q) {
                $q->whereRaw('blacklisted_until < current_date');
            })
            ->where('created_at', '>', '2023-04-1');

        if ($tenderId !== null && $tenderId !== '') {
            $query->where('tender_id', (int) $tenderId);
        }

        if ($limitOption !== null && $limitOption !== '') {
            $limit = max(1, (int) $limitOption);
            $query->limit($limit);
        } elseif ($tenderId === null || $tenderId === '') {
            // Cron / default run: keep batch small.
            $query->limit(100);
        }
        // With --tender and no --limit: process all pending for that tender.

        $eligibles = $query->get()->sortBy([
            ['tender_id', 'desc'],
            ['id', 'asc'],
        ]);

        if ($eligibles->isEmpty()) {
            $this->info('No pending eligible emails to send.');

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'Processing %d pending eligible email(s)%s...',
            $eligibles->count(),
            $tenderId ? " for tender {$tenderId}" : ''
        ));

        $sent = 0;
        $skipped = 0;
        $disabled = 0;

        foreach ($eligibles as $eligible) {
            $vendor = $eligible->vendor;
            $tender = $eligible->tender;

            if ($tender && $vendor && $vendor->user && ! $vendor->user->isEmailBlacklist() && $vendor->canParticipateInTenders()) {
                if (filter_var(trim($vendor->user->email), FILTER_VALIDATE_EMAIL)) {
                    $to = trim($vendor->user->email);
                    $subject = 'Sistem Tender Online Selangor: Layak Sertai Tender / Sebut Harga - ' . $tender->name;
                    $this->sendMail('html', $to, $subject, '', 'tenders.emails.eligible', [
                        'tender_id' => $tender->id,
                        'vendor_id' => $vendor->id,
                    ]);

                    $eligible->update([
                        'sent_at' => Carbon::now(),
                    ]);
                    $sent++;
                } else {
                    $skipped++;
                }
            } else {
                $eligible->update([
                    'email' => false,
                ]);
                $disabled++;
            }
        }

        $this->info("Done. sent={$sent} skipped_invalid_email={$skipped} disabled={$disabled}");

        return self::SUCCESS;
    }
}
