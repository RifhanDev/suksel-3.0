<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ManualConversation extends Conversation
{

    public function run()
    {
        $this->askManual();
    }

    public function askManual()
    {
        if (Auth()->check()) {
            $manuals = array(
                "1" => "Pemulangan Semula",
                "2" => "Pembelian Dokumen",
                "3" => "Langganan",
                "4" => "Kebenaran Khas"
            );
        } else {
            $manuals = array(
                "1.1" => "Log Masuk",
                "1.2" => "Daftar Akaun",
                "1.3" => "Lupa Kata Laluan",
            );
        }

        $buttonArray = [];

        foreach ($manuals as $id => $value) {
            $button = Button::create($value)->value($id);
            $buttonArray[] = $button;
        }

        $question = Question::create('Panduan Untuk..')
            ->callbackId('select_manuals')
            ->addButtons($buttonArray);

        $this->bot->typesAndWaits(1);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {

                if ($answer->getValue() == '1.1') {
                    $this->sendLoginManual();
                } else if ($answer->getValue() == '1.2') {
                    $this->sendRegisterManual();
                } else if ($answer->getValue() == '1.3') {
                    $this->sendForgotPasswordManual();
                } else if ($answer->getValue() == '1') {
                    $this->sendRefundManual();
                } else if ($answer->getValue() == '2') {
                    $this->sendPurchaseManual();
                } else if ($answer->getValue() == '3') {
                    $this->sendSubscriptionManual();
                } else if ($answer->getValue() == '4') {
                    $this->sendExceptionManual();
                }
            } else {
                $this->repeat();
            }
        });
    }

    public function sendExceptionManual()
    {
        $this->bot->reply('Exception Instruction Here');
    }

    public function sendSubscriptionManual()
    {
        $this->bot->reply('Subscription Instruction Here');
    }

    public function sendPurchaseManual()
    {
        $this->bot->reply('Purchase Instruction Here');
    }

    public function sendRefundManual()
    {
        $this->bot->reply('Refund Instruction Here');
    }

    public function sendLoginManual()
    {
        $this->bot->reply('Masukkan Alamat E-mel dan Kata Laluan yang telah didaftarkan.</br>Tekan "Daftar Masuk"');
    }

    public function sendRegisterManual()
    {
        $this->bot->reply('Register Instruction Here');
    }

    public function sendForgotPasswordManual()
    {
        $this->bot->reply('Forgot Password Instruction Here');
    }
}
