<?php

namespace App\Http\Conversations\Status;

use App\Models\Refund;
use App\Traits\Helper;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class RefundStatusConversation extends Conversation
{
    use Helper;

    public function run()
    {
        $this->askRefundNumber();
    }

    public function askRefundNumber()
    {
        $this->ask('Sila masukkan No. Pemulangan Semula anda.</br>Cth:- RFXXXXXXXX', function (Answer $answer) {
            $this->say('Sebentar..');
            $this->bot->typesAndWaits(3);
            $refund_num = $this->reverseRefundNum($answer->getText());

            if (auth()->user()->ability(['Admin'], [])) {
                $validate = Refund::where('number', $refund_num)->exists();
            } else {
                $validate = Refund::where('vendor_id', auth()->user()->vendor_id)->where('number', $refund_num)->exists();
            }

            if ($validate) {
                $this->bot->userStorage()->save([
                    'ori_num' => $refund_num,
                    'refund_num' => $answer->getText(),
                ]);

                $this->answerRefundStatus();
            } else {
                $this->say($answer->getText() . ' tidak dijumpai atau tidak sah.');
                $this->repeat();
            }
        });
    }

    public function answerRefundStatus()
    {
        $storage = $this->bot->userStorage()->find();
        $ori_num = $storage->get('ori_num');
        $refund_num = $storage->get('refund_num');
        $status = Refund::where('number', $ori_num)->first();

        $message = '-------------------------------------- <br>';
        $message .= 'No. Pemulangan Semula : ' . $refund_num . ' <br>';
        $message .= 'Status : ' . $status->refundStatus() . ' <br>';
        $message .= '-------------------------------------- <br>';
        $this->bot->reply($message);
    }
}
