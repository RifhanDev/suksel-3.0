<?php

namespace App\Http\Controllers;

use App\Http\Conversations\AduanConversation;
use App\Http\Conversations\GlobalConversation;
use App\Http\Conversations\ManualConversation;
use App\Http\Conversations\SelectServiceConversation;
use App\Http\Conversations\StatusConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Http\Request;

class BotManController extends Controller
{

    /**
     * Place your BotMan logic here.
     */
    public function handle(Request $request)
    {
        $botman = app('botman');

        // $botman->hears('(hi|hai|hello)', function ($botman) {
        //     $botman->typesAndWaits(1);

        //     $botman->ask('Hi, apa yang boleh saya bantu? </br> cth:- </br>senarai perkhidmatan</br>panduan</br>aduan', function (Answer $answer) {

        //         $answer = $answer->getText();
        //     });
        // });

        // $botman->hears('senarai perkhidmatan', function ($botman) {
        //     $botman->typesAndWaits(1);
        //     $botman->startConversation(new SelectServiceConversation);
        // })->skipsConversation();

        // $botman->hears('panduan', function ($botman) {
        //     $botman->typesAndWaits(1);
        //     $botman->startConversation(new ManualConversation);
        // })->skipsConversation();

        // $botman->hears('aduan', function ($botman) {
        //     $botman->typesAndWaits(1);
        //     $botman->startConversation(new AduanConversation);
        // })->skipsConversation();

        // $botman->hears('status', function ($botman) {
        //     $botman->typesAndWaits(1);
        //     $botman->startConversation(new StatusConversation);
        // })->skipsConversation();

        // $botman->hears('(terima kasih|thx|thank you|thanks)', function ($botman) {
        //     $botman->typesAndWaits(1);

        //     $botman->say('Sama-sama.');

        //     $botman->ask('Ada apa-apa lagi yang boleh saya bantu?', function (Answer $answer) {

        //         $answer = $answer->getText();
        //         $arrayAnswer = ['ya','yes'];
        //         if (in_array($answer,$arrayAnswer)) {
        //             $this->bot->startConversation(new SelectServiceConversation);
        //         }
        //     });
        // })->stopsConversation();

        // $botman->hears('image_attachment', function (BotMan $bot) {
        //     // Create attachment
        //     $attachment = new Image('https://botman.io/img/logo.png');
        
        //     // Build message object
        //     $message = OutgoingMessage::create('This is my text')
        //                 ->withAttachment($attachment);
        
        //     // Reply message object
        //     $bot->reply($message);
        // });

        $botman->hears('{question}', function ($botman) use($request) {            
            $botman->typesAndWaits(1);
            $botman->startConversation( new GlobalConversation() );
        });

        // $botman->hears('stop', function ($botman) {
        //     $botman->say('Stopped');
        // })->stopsConversation();

        $botman->listen();
    }

    public function chatWidget($chat_id)
    {
        $data["chat_id"] = $chat_id;
        // var_dump($chat_id);
        return view('layouts.chatbot', $data);
    }
}
