<?php

namespace App\Interfaces;

interface MailQueueRepositoryInterface
{
    public function createMailQueue(array $new_mail_queue);
    public function readMailQueue(int $mail_queue);
    public function updateMailQueue(int $mail_queue, $new_mail_queue);
    public function deleteMailQueue(int $mail_queue);

    public function readAllMailQueue();
}
