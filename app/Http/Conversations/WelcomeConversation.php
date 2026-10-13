<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class WelcomeConversation extends Conversation
{
    public function run()
    {
        $this->showMainMenu();
    }

    public function showMainMenu()
    {
        $this->bot->typesAndWaits(1);

        if (auth()->check()) {
            $menu = [
                '1' => 'Panduan pengguna',
                '2' => 'Semak status permohonan',
                '3' => 'Hantar aduan',
                '4' => 'Soalan lazim (FAQ)',
            ];
            $prompt = 'Apa yang anda ingin buat? Sila pilih salah satu topik di bawah.';
        } else {
            $menu = [
                '1' => 'Panduan (daftar & log masuk)',
                '4' => 'Soalan lazim (FAQ)',
            ];
            $prompt = 'Apa yang anda ingin buat? Sila pilih topik di bawah.'
                . ' Untuk semak status atau hantar aduan, sila log masuk ke SUKSEL dahulu.';
        }

        $buttonArray = [];
        foreach ($menu as $id => $label) {
            $buttonArray[] = Button::create($label)->value($id);
        }

        $question = Question::create($prompt)
            ->callbackId('welcome_menu')
            ->addButtons($buttonArray);

        $this->ask($question, function (Answer $answer) {
            if (! $answer->isInteractiveMessageReply()) {
                $this->repeat();

                return;
            }

            switch ($answer->getValue()) {
                case '1':
                    $this->bot->startConversation(new ManualConversation);
                    break;
                case '2':
                    $this->bot->startConversation(new StatusConversation);
                    break;
                case '3':
                    $this->bot->startConversation(new AduanConversation);
                    break;
                case '4':
                    $this->bot->startConversation(new GlobalConversation);
                    break;
                default:
                    $this->repeat();
            }
        });
    }
}
