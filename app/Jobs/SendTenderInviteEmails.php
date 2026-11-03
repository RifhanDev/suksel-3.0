<?php

namespace App\Jobs;

use App\Tender;
use App\Traits\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTenderInviteEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Helper;

    public int $timeout = 300;

    public int $tries = 1;

    public function __construct(protected int $tenderId)
    {
    }

    public function handle(): void
    {
        $tender = Tender::with(['invites.vendor.user'])->find($this->tenderId);

        if (!$tender) {
            Log::warning('[SendTenderInviteEmails] Tender not found.', ['tender_id' => $this->tenderId]);

            return;
        }

        foreach ($tender->invites as $invite) {
            $vendor = $invite->vendor;

            if (!$vendor || !$vendor->user) {
                continue;
            }

            $to = trim((string) $vendor->user->email);

            if ($to === '') {
                continue;
            }

            $this->sendMail(
                'html',
                $to,
                'Sistem Tender Online Selangor: Jemputan Tender',
                '',
                'tenders.emails.invite',
                ['tender' => $tender, 'vendor' => $vendor]
            );
        }
    }
}
