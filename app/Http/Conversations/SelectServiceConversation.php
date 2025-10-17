<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use App\Http\Conversations\AduanConversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Http\Conversations\StatusConversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SelectServiceConversation extends Conversation
{

    public function run()
    {
        $this->askService();
    }

    public function askService()
    {
        if (Auth()->check()) {
            $services = array(
                "1" => "Panduan",
                "2" => "Semak Status",
                "3" => "Aduan"
            );
        } else {
            $services = array(
                "1" => "Panduan"
            );
        }

        $buttonArray = [];

        foreach ($services as $id => $value) {
            $button = Button::create($value)->value($id);
            $buttonArray[] = $button;
        }

        $question = Question::create('Berikut adalah senarai perkhidmatan yang ditawarkan.')
            ->callbackId('select_service')
            ->addButtons($buttonArray);

        $this->bot->typesAndWaits(1);
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->bot->userStorage()->save([
                    'service' => $answer->getValue(),
                ]);

                if ($answer->getValue() == '1') {
                    $this->bot->startConversation(new ManualConversation);
                } else if ($answer->getValue() == '2') {
                    $this->bot->startConversation(new StatusConversation);
                } else if ($answer->getValue() == '3') {
                    $this->bot->startConversation(new AduanConversation);
                }
                
            } else {
                $this->repeat();
            }
        });
    }
}
