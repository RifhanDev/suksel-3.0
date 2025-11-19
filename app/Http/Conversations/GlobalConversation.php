<?php

namespace App\Http\Conversations;

use App\Repositories\CustomerQuestionRepository;
use App\Repositories\FaqCategoryRepository;
use App\Repositories\FaqLogRepository;
use App\Repositories\FaqRepository;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GlobalConversation extends Conversation
{
    public $input_message = "";

    public function __construct()
    {
        // $unJson = json_decode($input);
        // $this->input_message = $unJson->message;
    }

    public function defaultErrorAnswer()
    {
        $this->tell("Harap maaf, kami tidak menjumpai sebarang padanan berkaitan soalan anda di dalam pangkalan data kami. Sila hubungi SUK SELANGOR");
    }

    public function stopsConversing()
    {
        $this->tell("Terima kasih kerana berhubung dengan saya.");
    }

    public function tell($words, $additional_parameter = [])
    {
        $this->say($words, $additional_parameter);
    }

    public function getChatId($botman)
    {
        // Access user
        $user = $botman->getUser();
        // Access ID
        $id = $user->getId();

        return $id ?? "";
    }
    

    public function askQuestionAndSaveLog($question, $faq_id)
    {
        $this->ask($question, function (Answer $response) use($faq_id)
        {
            $this->tell('Anda telah memasukkan: <br><br>' . "<q>".$response->getText()."</q>");
            $this->tell('Input anda telah disimpan ke dalam pangkalan data');

            $faqRepo = new FaqRepository();
            $faq = $faqRepo->readFaq($faq_id);

            $chat_id = $this->getChatId($this->bot);

            $data_response = array(
                "chat_id" => $chat_id,
                "require_input_text" => $response->getText()
            );

            $faq_log_repo = new FaqLogRepository();
            $newFaqLog = array(
                "faq_category_id" => $faq->faq_category_id,
                "faq_id" => $faq->id,
                "user_response" => json_encode($data_response),
                "created_by" => auth()->user()->id ?? 0
            );

            $faq_log_repo->createFaqLog($newFaqLog);
            
            $this->stopsConversing();
        });

    }

    public function askAttachmentAndSaveLog($question, $faq_id)
    {
        $this->askForImages('Please upload an image.', function ($images) use($question, $faq_id) {

            $list_accepted_images = ["jpeg", "jpg", "png", "raw"];
            $image = $images[0]->getUrl();  // your base64 encoded
            $file_type = explode(';', $image)[0];
            $file_type = explode('/', $file_type)[1];

            if (in_array(strtolower($file_type), $list_accepted_images) )
            {
                $imageName = str_random(10).'.'.$file_type;
                $chat_id    = $this->getChatId($this->bot);
    
                Storage::disk('botman_attachment')->put($chat_id.DIRECTORY_SEPARATOR.$imageName, file_get_contents($images[0]->getUrl()));
    
                $uploaded_file_url = asset('storage/botman/'.$chat_id.'/'.$imageName);
    
                $data = array(
                    "type" => "image_only",
                    "sender" => "user_chat",
                    "response" => $uploaded_file_url,
                    "uploaded_file_type" => $file_type
                );

                $data_response = array(
                    "chat_id" => $chat_id,
                    "require_input_attachment" => $uploaded_file_url
                );

                $faqRepo = new FaqRepository();
                $faq = $faqRepo->readFaq($faq_id);

                $faq_log_repo = new FaqLogRepository();
                $newFaqLog = array(
                    "faq_category_id" => $faq->faq_category_id,
                    "faq_id" => $faq->id,
                    "user_response" => json_encode($data_response),
                    "created_by" => auth()->user()->id ?? 0
                );

                $faq_log_repo->createFaqLog($newFaqLog);

                $this->tell("DataACK", $data);
                $this->tell("DataACK", ["type" => "text_only", "sender" => "bot", "response" => "Fail anda berjaya dimuatnaik."]);
                
                $this->stopsConversing();
            }
            else
            {
                $this->tell("DataACK", ["type" => "text_only", "sender" => "bot", "response" => "Sila muatnaik file mengikut format .jpeg .jpg .png sahaja"]);
                $this->askAttachmentAndSaveLog($question, $faq_category_id);
            }


        }, function(Answer $answer) {


            // This method is called when no valid image was provided.
            $this->tell('Lampiran yang dimasukkan tidak sah');
            $this->tell('Sila Masukkan fail gambar berformat .jpeg, .png sahaja');
            $this->repeat();
        });
    }

    public function askCustomQuestion($question, $faq_category_id = 0)
    {
        $this->tell("Maaf kerana tidak dapat menjawab soalan anda. <br><br>");
        $question = "Sila tinggalkan pertanyaan anda berserta no-telefon atau emel untuk kami hubungi";
        
        
        $this->ask($question, function (Answer $response) use($faq_category_id ) 
        {
            $user_response = $response->getText();
            $this->tell('Pertanyaan anda adalah seperti berikut :- <br><br>' . $user_response);
            $this->tell('Saya telah menyimpan pertanyaan anda ke dalam sistem kami untuk proses seterusnya. <br>Terima kasih, pertanyaan anda akan digunakan untuk menambahbaik lagi perkhidmatan saya di masa hadapan.');
            
            $new_enquiry = new CustomerQuestionRepository();
            $data_enquiry = array(
                "question" => $user_response,
                "faq_category_id" => $faq_category_id,
                "created_by" => auth()->user()->id ?? 0
            );
            $new_enquiry->createCustomerQuestion($data_enquiry);
        });
    }

    public function askDataAsQuestion($faq_category_id, array $question_data, $asking_question, string $code, int $show_none_button = 1)
    {

        foreach ($question_data as $rows) {
            $question_button[] = Button::create($rows["question"])->value($code."-".$rows["id"]);
        }

        if ($show_none_button == 1)
        {
            if ( $faq_category_id != 0 )
            {
                $btn_value = $code."-AAA-".$faq_category_id;
            }
            else
            {
                $btn_value = $code."-AAA";
            }

            $question_button[] = Button::create("Bukan disenarai diatas")->value($btn_value);
        }

        $question = Question::create($asking_question)
            ->fallback('Unable to create question')
            ->callbackId('create_data_question')
            ->addButtons($question_button);

        $this->ask($question, function (Answer $answer) 
        {

            // Detect if button was clicked:
            if ($answer->isInteractiveMessageReply()) {

                $selected_choice = $answer->getValue() ?? "";
                $split_user_option = explode("-",$selected_choice);

                $user_option_code   = $split_user_option[0] ?? "X";
                $user_option_id     = $split_user_option[1] ?? "999";

                switch ($user_option_code) {
                    case 'M':

                        // Get faq category detail
                        $faq_category_repo = new FaqCategoryRepository();
                        $faq_category = $faq_category_repo->readFaqCategory($user_option_id);
                        $faq_category_name = $faq_category->name ?? "";
                        $faq_category_show_none_btn = $faq_category->show_none_btn ?? "";


                        // Get list of child question
                        $faq = new FaqRepository();
                        $child_question = $faq->getFaqByCategoryId($user_option_id);

                        $this->tell('Anda telah memilih topik <q>'.$faq_category_name.'</q>.');
                        $this->askDataAsQuestion($user_option_id, $child_question->toArray(), 'Sila klik salah satu dari pilihan di bawah :- ', "F", $faq_category_show_none_btn);
                        break;

                    case 'F':

                        if ($user_option_id != "AAA")
                        {
                            // Get answer of child question
                            $faq = new FaqRepository();
                            $child_question = $faq->readFaq($user_option_id);

                            if ( isset($child_question->id) && $child_question->id > 0)
                            {
                                $this->tell("Anda telah memilih :- <br><br>"."<q>".$child_question->question."</q>");

                                if ($child_question->require_input_text == 1 || $child_question->require_input_attachment == 1)
                                {
                                    $this->tell($child_question->answer);
                                }

                                if ($child_question->require_input_text == 1)
                                {
                                    // $this->tell("Sila masukkan perenggan anda.");
                                    // $this->askQuestionAndSaveLog($child_question->answer, $child_question->id);
                                    $question = "Sila masukkan perenggan anda.";
                                    $this->askQuestionAndSaveLog($question, $child_question->id);
                                }

                                if ($child_question->require_input_attachment == 1)
                                {
                                    // $this->tell("Sila masukkan gambar lampiran anda.");
                                    // $this->askAttachmentAndSaveLog($child_question->answer);
                                    $question = "Sila masukkan gambar lampiran anda.";
                                    $this->askAttachmentAndSaveLog($question, $child_question->id);
                                }

                                if ($child_question->require_input_text == 0 && $child_question->require_input_attachment == 0)
                                {
                                    // $this->tell("Anda telah memilih "."<q>".$child_question->question."</q> sebagai pertanyaan anda.");
                                    $this->tell("Jawapan : ".$child_question->answer);
                                }
                            }
                        }
                        
                        if ($user_option_id == "AAA")
                        {
                            $user_option_faq_category_id = $split_user_option[2] ?? "0";
                            $this->askCustomQuestion("Masukkan pertanyaan berserta no-telefon atau emel", $user_option_faq_category_id);
                        }

                        break;

                    case 'X':
                        // Display error result\\

                        switch ($user_option_id) {
                            case 'AAA':
                                $this->askCustomQuestion("Masukkan pertanyaan berserta no-telefon atau emel");
                                break;
                            
                            default:
                                $this->tell("Harap maaf, kami tidak menjumpai sebarang padanan berkaitan soalan anda di dalam pangkalan data kami. Sila hubungi Admin IT SUK SELANGOR");
                                break;
                        }
                        
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
            else
            {
                $selected_choice = $answer->getValue() ?? "";
                if($selected_choice == "stop" || $selected_choice == "keluar" || $selected_choice == "henti")
                {
                    $this->stopsConversing();
                }
                else
                {
                    $this->repeat();
                }
            }
        });
    }


    public function askFaq()
    {
        $faq_category_repo = new FaqCategoryRepository();

        $main_category = $faq_category_repo->readAllFaqCategory();
        $question_data = [];

        if(count($main_category) > 0)
        {
            foreach ($main_category as $rows) 
            {
                $question_data[] = array(
                    "id" => $rows->id,
                    "question" => $rows->name
                );
            }

            $this->askDataAsQuestion(0, $question_data, "Sila pilih salah satu dari pilihan berikut untuk mula :-", "M", 0);
        }
        else
        {
            $this->defaultErrorAnswer();
        }
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askFaq();
    }
}
