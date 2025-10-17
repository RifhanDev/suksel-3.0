<?php

namespace App\Http\Conversations;

use App\Models\Complaint;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Http\Conversations\StatusConversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class AduanConversation extends Conversation
{

    public function run()
    {
        $this->askInput();
    }

    public function askInput()
    {
        $this->ask('Sila tuliskan aduan anda dalam satu perenggan.', function (Answer $answer) {
            $aduan_text = $answer->getText();
            $this->say('Aduan anda adalah :');
            $this->say('"' . $aduan_text . '"');

            $confirmation = array(
                "1" => "Ya",
                "0" => "Tidak"
            );

            $buttonArray = [];

            foreach ($confirmation as $id => $value) {
                $button = Button::create($value)->value($id);
                $buttonArray[] = $button;
            }

            $question = Question::create('Hantar aduan?')
                ->callbackId('select_confirmation')
                ->addButtons($buttonArray);

            $this->bot->userStorage()->save([
                'aduan_content' => $aduan_text,
            ]);

            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {

                    if ($answer->getValue() == '1') {
                        $this->submitAduan();
                    } else if ($answer->getValue() == '0') {
                        $this->sendRegisterManual();
                    }
                } else {
                    $this->repeat();
                }
            });
        });
    }

    public function submitAduan()
    {
        $storage = $this->bot->userStorage()->find();
        $content = $storage->get('aduan_content');

        $arr = [
            'subject' => 'Aduan via Chatbot',
            'content' => $content
        ];

        $valid = Complaint::create($arr);

        if ($valid) {
            $this->bot->reply('Aduan hantar telah dihantar. Terima kasih atas maklumbalas anda.');
        } else {
            $this->bot->reply('Maaf, kami menghadapi masalah teknikal. Sila cuba sekali lagi.');
        }
    }
}
