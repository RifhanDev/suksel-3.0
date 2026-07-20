<?php

namespace App\Interfaces;

interface SmtpMailsRepositoryInterface
{
    public function createSmtpMails(array $new_smtp_mails);
    public function readSmtpMails(int $smtp_mail);
    public function updateSmtpMails(int $smtp_mail, $new_smtp_mails);
    public function deleteSmtpMails(int $smtp_mail);

    public function readAllSmtpMails();
}
