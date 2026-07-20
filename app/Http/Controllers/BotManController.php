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
use Illuminate\Support\Facades\Log;

class BotManController extends Controller
{

    /**
     * Place your BotMan logic here.
     */
    public function handle(Request $request)
    {
        $botman = app('botman');

        // Handle file uploads from custom paperclip button
        if ($request->hasFile('file') && $request->get('attachment') == 'image') {
            return $this->handleFileUpload($request, $botman);
        }

        $botman->hears('(hi|hai|hello)', function ($botman) {
            $botman->typesAndWaits(1);

            $botman->ask('Hi, apa yang boleh saya bantu? </br> cth:- </br>senarai perkhidmatan</br>panduan</br>aduan', function (Answer $answer) {

                $answer = $answer->getText();
            });
        });

        $botman->hears('senarai perkhidmatan', function ($botman) {
            $botman->typesAndWaits(1);
            $botman->startConversation(new SelectServiceConversation);
        })->skipsConversation();

        $botman->hears('panduan', function ($botman) {
            $botman->typesAndWaits(1);
            $botman->startConversation(new ManualConversation);
        })->skipsConversation();

        $botman->hears('aduan', function ($botman) {
            $botman->typesAndWaits(1);
            $botman->startConversation(new AduanConversation);
        })->skipsConversation();

        $botman->hears('status', function ($botman) {
            $botman->typesAndWaits(1);
            $botman->startConversation(new StatusConversation);
        })->skipsConversation();

        $botman->hears('(terima kasih|thx|thank you|thanks)', function ($botman) {
            $botman->typesAndWaits(1);

            $botman->say('Sama-sama.');

            $botman->ask('Ada apa-apa lagi yang boleh saya bantu?', function (Answer $answer) {

                $answer = $answer->getText();
                $arrayAnswer = ['ya', 'yes'];
                if (in_array($answer, $arrayAnswer)) {
                    $this->bot->startConversation(new SelectServiceConversation);
                }
            });
        })->stopsConversation();

        $botman->hears('image_attachment', function (BotMan $bot) {
            // Create attachment
            $attachment = new Image('https://botman.io/img/logo.png');

            // Build message object
            $message = OutgoingMessage::create('This is my text')
                ->withAttachment($attachment);

            // Reply message object
            $bot->reply($message);
        });

        // Direct route for "aduan" (complaint) - exact match, case insensitive
        $botman->hears('aduan', function ($botman) {
            $botman->typesAndWaits(1);
            $botman->startConversation(new AduanConversation);
        })->skipsConversation();

        // Also catch variations
        $botman->hears('(complaint|membuat aduan|hantar aduan)', function ($botman) {
            $botman->typesAndWaits(1);
            $botman->startConversation(new AduanConversation);
        })->skipsConversation();

        // Catch-all route for other questions (FAQ) - must be last
        $botman->hears('{question}', function ($botman) use ($request) {
            $botman->typesAndWaits(1);
            $botman->startConversation(new GlobalConversation());
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

    /**
     * Handle file uploads from custom paperclip button
     */
    protected function handleFileUpload(Request $request, BotMan $botman)
    {
        try {
            $file = $request->file('file');
            $userId = $request->get('userId');
            $chat_id = $userId ?? $botman->getUser()->getId();

            if (!$file || !$file->isValid()) {
                return response()->json([
                    'status' => 200,
                    'messages' => [[
                        'text' => 'DataACK',
                        'additionalParameters' => [
                            'type' => 'text_only',
                            'sender' => 'bot',
                            'response' => 'Fail tidak sah. Sila cuba lagi.'
                        ]
                    ]]
                ]);
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $mimeType = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($mimeType, $allowedTypes) && !in_array($extension, ['jpeg', 'jpg', 'png'])) {
                return response()->json([
                    'status' => 200,
                    'messages' => [[
                        'text' => 'DataACK',
                        'additionalParameters' => [
                            'type' => 'text_only',
                            'sender' => 'bot',
                            'response' => 'Sila muat naik fail mengikut format .jpeg, .jpg, .png sahaja'
                        ]
                    ]]
                ]);
            }

            // Save file
            $imageName = \Illuminate\Support\Str::random(10) . '.' . $extension;
            $path = $file->storeAs('botman/' . $chat_id, $imageName, 'public');
            $uploaded_file_url = asset('storage/botman/' . $chat_id . '/' . $imageName);

            // Store attachment in user storage for AduanConversation to pick up
            $botman->userStorage()->save([
                'pending_attachment' => $uploaded_file_url,
                'attachment_uploaded' => true
            ]);

            // Return success response
            return response()->json([
                'status' => 200,
                'messages' => [
                    [
                        'text' => 'DataACK',
                        'additionalParameters' => [
                            'type' => 'image_only',
                            'sender' => 'user_chat',
                            'response' => $uploaded_file_url
                        ]
                    ],
                    [
                        'text' => 'DataACK',
                        'additionalParameters' => [
                            'type' => 'text_only',
                            'sender' => 'bot',
                            'response' => 'Lampiran berjaya dimuat naik. Sila taip "siap" untuk teruskan.'
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'status' => 200,
                'messages' => [[
                    'text' => 'DataACK',
                    'additionalParameters' => [
                        'type' => 'text_only',
                        'sender' => 'bot',
                        'response' => 'Ralat semasa memuat naik fail. Sila cuba lagi.'
                    ]
                ]]
            ]);
        }
    }
}
