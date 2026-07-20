<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Http\Conversations\Status\RefundStatusConversation;
use BotMan\BotMan\Messages\Conversations\Conversation;

class StatusConversation extends Conversation
{

    public function run()
    {
        $this->askStatus();
    }

    public function askStatus()
    {
        if (Auth()->check()) {
            $manuals = array(
                "1" => "Pemulangan Semula",
            );
        }

        $buttonArray = [];

        foreach ($manuals as $id => $value) {
            $button = Button::create($value)->value($id);
            $buttonArray[] = $button;
        }

        $question = Question::create('Semak Status..')
            ->callbackId('select_status')
            ->addButtons($buttonArray);
            
        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {

                if ($answer->getValue() == '1') {
                    $this->bot->startConversation(new RefundStatusConversation);
                }
            } else {
                $this->repeat();
            }
        });
    }

}
