<?php

namespace App\Repositories;

use App\Interfaces\MailQueueRepositoryInterface;
use App\Models\MailQueue;
use Illuminate\Support\Facades\DB;

class MailQueueRepository implements MailQueueRepositoryInterface
{
    public function createMailQueue(array $new_mail_queue)
    {
        return MailQueue::create($new_mail_queue);
    }

    public function readMailQueue(int $mail_queue)
    {
        return MailQueue::findOrFail($mail_queue);
    }

    public function updateMailQueue(int $mail_queue, $new_mail_queue)
    {
        return MailQueue::where('id', $mail_queue)->update($new_mail_queue);
    }

    public function deleteMailQueue(int $mail_queue)
    {
        return MailQueue::destroy($mail_queue);
    }

    public function readAllMailQueue()
    {
        return MailQueue::all();
    }
    
    public function getTodayUnsendMailQueue()
    {
        $result = DB::table('mail_queues')
            ->where('mail_queues.created_at','>=',DB::raw(" TIMESTAMP(DATE_SUB(SYSDATE(), INTERVAL 1 DAY)) "))
            ->where('mail_queues.created_at','<=',DB::raw(" CURRENT_TIMESTAMP "))
            ->where('mail_queues.status',"=", "N")
            ->whereRaw("mail_queues.deleted_at is null")
            ->get();

        return $result;
    }
    
    public function getThisWeekUnsendMailQueue()
    {
        $result = DB::table('mail_queues')
            ->where('mail_queues.created_at','>=',DB::raw(" TIMESTAMP(DATE_SUB(SYSDATE(), INTERVAL 7 DAY)) "))
            ->where('mail_queues.created_at','<=',DB::raw(" CURRENT_TIMESTAMP "))
            ->where('mail_queues.status',"=", "N")
            ->whereRaw("mail_queues.deleted_at is null")
            ->get();

        return $result;
    }
    
    public function getThisMonthUnsendMailQueue()
    {
        $result = DB::table('mail_queues')
            ->where('mail_queues.created_at','>=',DB::raw(" TIMESTAMP(DATE_SUB(SYSDATE(), INTERVAL 30 DAY)) "))
            ->where('mail_queues.created_at','<=',DB::raw(" CURRENT_TIMESTAMP "))
            ->where('mail_queues.status',"=", "N")
            ->whereRaw("mail_queues.deleted_at is null")
            ->get();

        return $result;
    }
}
