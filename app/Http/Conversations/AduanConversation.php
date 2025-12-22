<?php

namespace App\Http\Conversations;

use App\Models\Complaint;
use App\Models\User;
use App\Traits\Helper;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Http\Conversations\StatusConversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AduanConversation extends Conversation
{
    use Helper;

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

            // Ask if they want to attach files
            $attachmentOptions = array(
                "1" => "Ya, ada lampiran",
                "0" => "Tidak, terus hantar"
            );

            $buttonArray = [];

            foreach ($attachmentOptions as $id => $value) {
                $button = Button::create($value)->value($id);
                $buttonArray[] = $button;
            }

            $question = Question::create('Adakah anda ingin melampirkan fail? (gambar/dokumen)')
                ->callbackId('select_attachment')
                ->addButtons($buttonArray);

            $this->bot->userStorage()->save([
                'aduan_content' => $aduan_text,
            ]);

            $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    if ($answer->getValue() == '1') {
                        $this->askForAttachment();
                    } else if ($answer->getValue() == '0') {
                        $this->askConfirmation();
                    }
                } else {
                    $this->repeat();
                }
            });
        });
    }

    public function askForAttachment()
    {
        // Tell user to upload using the paperclip button
        $this->say('Sila klik butang lampiran (📎) di bahagian bawah untuk memuat naik gambar atau dokumen anda. (Format: .jpeg, .jpg, .png)');

        // Wait for user to upload file or send a message
        $this->ask('Selepas memuat naik fail, sila taip "siap" atau klik butang di bawah untuk teruskan.', function (Answer $answer) {
            $message = strtolower(trim($answer->getText()));

            // Check if there's a pending attachment
            if ($this->checkPendingAttachment()) {
                return; // Attachment processed, confirmation will be asked
            }

            // If user says "siap" or similar, check for attachment again
            if (in_array($message, ['siap', 'done', 'selesai', 'ok', 'ya'])) {
                if ($this->checkPendingAttachment()) {
                    return;
                }
                $this->say('Tiada lampiran dijumpai. Sila muat naik fail terlebih dahulu atau pilih "Tidak, terus hantar" untuk menghantar tanpa lampiran.');
                $this->askConfirmation();
            } else {
                // Check for attachment on any message
                if (!$this->checkPendingAttachment()) {
                    $this->repeat();
                }
            }
        });
    }

    /**
     * Check for pending attachment from custom upload
     */
    protected function checkPendingAttachment()
    {
        $storage = $this->bot->userStorage()->find();
        $pendingAttachment = $storage->get('pending_attachment');
        $attachmentUploaded = $storage->get('attachment_uploaded', false);

        if ($attachmentUploaded && $pendingAttachment) {
            // Process the uploaded attachment
            $attachments = $storage->get('aduan_attachments', []);
            $attachments[] = $pendingAttachment;
            $this->bot->userStorage()->save([
                'aduan_attachments' => $attachments,
                'pending_attachment' => null,
                'attachment_uploaded' => false
            ]);

            $this->say('Lampiran berjaya dimuat naik.');
            $this->askConfirmation();
            return true;
        }
        return false;
    }

    /**
     * Process attachment uploaded via custom paperclip button
     */
    public function processAttachment($attachmentUrl)
    {
        $storage = $this->bot->userStorage()->find();
        $attachments = $storage->get('aduan_attachments', []);
        $attachments[] = $attachmentUrl;
        $this->bot->userStorage()->save([
            'aduan_attachments' => $attachments,
        ]);

        $this->say('Lampiran berjaya dimuat naik.');
        $this->askConfirmation();
    }

    public function askConfirmation()
    {
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
    }

    public function getChatId($botman)
    {
        $user = $botman->getUser();
        $id = $user->getId();
        return $id ?? "";
    }

    public function submitAduan()
    {
        $storage = $this->bot->userStorage()->find();
        $content = $storage->get('aduan_content');
        $attachments = $storage->get('aduan_attachments', []);

        // Get user email if authenticated, otherwise use default
        $user_email = Auth::check() ? Auth::user()->email : 'chatbot@selangor.gov.my';

        // Append attachment URLs to content if any
        if (!empty($attachments)) {
            $content .= "\n\n[Lampiran:]\n";
            foreach ($attachments as $index => $attachment) {
                $content .= ($index + 1) . ". " . $attachment . "\n";
            }
        }

        $arr = [
            'subject' => 'Aduan via Chatbot' . (!empty($attachments) ? ' (Dengan Lampiran)' : ''),
            'content' => $content,
            'email' => $user_email
        ];

        $complaint = Complaint::create($arr);

        if ($complaint) {
            // Send email notification to all admin users
            $this->sendEmailNotificationToAdmins($complaint);

            $this->bot->reply('Aduan hantar telah dihantar. Terima kasih atas maklumbalas anda.');
        } else {
            $this->bot->reply('Maaf, kami menghadapi masalah teknikal. Sila cuba sekali lagi.');
        }
    }

    /**
     * Send email notification to all admin users
     */
    protected function sendEmailNotificationToAdmins($complaint)
    {
        try {
            // Get all users with Admin role
            $adminUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })
                ->where('confirmed', 1)
                ->whereNotNull('email')
                ->where('email', '!=', 'anonymous')
                ->get();

            if ($adminUsers->isEmpty()) {
                Log::warning('No admin users found to send complaint notification');
                return;
            }

            // Send email to each admin
            foreach ($adminUsers as $admin) {
                if (filter_var(trim($admin->email), FILTER_VALIDATE_EMAIL)) {
                    $to = trim($admin->email);
                    $subject = 'Aduan Baru Diterima - ' . $complaint->subject;

                    $this->sendMail(
                        "html",
                        $to,
                        $subject,
                        "",
                        "complaint.emails.new-complaint",
                        ['complaint' => $complaint]
                    );
                }
            }

            Log::info('Complaint notification emails sent to ' . $adminUsers->count() . ' admin(s)');
        } catch (\Exception $e) {
            Log::error('Failed to send complaint notification emails: ' . $e->getMessage());
            // Don't fail the complaint submission if email fails
        }
    }

    public function sendRegisterManual()
    {
        $this->say('Sila hubungi SUK SELANGOR untuk bantuan lanjut.');
        $this->stopsConversing();
    }
}
