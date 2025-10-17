<?php

namespace App\Repositories;

use App\Interfaces\SmtpMailsRepositoryInterface;
use App\Models\SmtpMails;
use Illuminate\Support\Facades\DB;

class SmtpMailsRepository implements SmtpMailsRepositoryInterface
{
    public function createSmtpMails(array $new_smtp_mails)
    {
        return SmtpMails::create($new_smtp_mails);
    }

    public function readSmtpMails(int $smtp_mail)
    {
        return SmtpMails::findOrFail($smtp_mail);
    }

    public function updateSmtpMails(int $smtp_mail, $new_smtp_mails)
    {
        return SmtpMails::where('id', $smtp_mail)->update($new_smtp_mails);
    }

    public function deleteSmtpMails(int $smtp_mail)
    {
        return SmtpMails::destroy($smtp_mail);
    }

    public function readAllSmtpMails()
    {
        return SmtpMails::all();
    }

    public function getTodayMailQueue()
    {
        $result = DB::table('smtp_mails')
            ->leftJoin('mail_queues', function($join){
                $join->on('smtp_mails.id', '=', 'mail_queues.smtp_mail_id')
                ->on('mail_queues.created_at','<=',DB::raw(" CURRENT_TIMESTAMP "))
                ->on('mail_queues.created_at','>=',DB::raw(" TIMESTAMP(CURRENT_DATE) "));
            })
            ->whereRaw("smtp_mails.deleted_at is null")
            ->whereRaw("mail_queues.deleted_at is null")
            ->selectRaw("smtp_mails.id, count(mail_queues.smtp_mail_id) as today_mail_queue, smtp_mails.mail_message_ratelimit ")
            ->groupByRaw("smtp_mails.id")
            ->orderBy('today_mail_queue','desc')
            ->get();

        return $result;
    }
}
