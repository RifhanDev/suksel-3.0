<?php

namespace App\Traits;

use App\Jobs\SendEmailJob;
use App\Models\Tender;
use App\Models\TenderEligible;
use App\Models\Vendor;
use App\Models\VendorCode;
use App\Repositories\MailQueueRepository;
use App\Repositories\SmtpMailsRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

trait Helper
{

    function removeCharacter($string)
    {
        $string = preg_replace('/[^[:alnum:]]+/s', '', $string);
        return strtoupper($string);
    }

    public function receiptNumGenerator($transaction_num, $created_year)
    {
        if (strtotime($created_year) < strtotime(Config::get('receipt.date'))) {
            return 'old';
        }
        $app_name = 'STOS';
        $arr = explode('-', $transaction_num);
        $year = substr($arr[0], -2);
        $running_num = $arr[1];
        $new_running_num = $this->receiptAlphabet($running_num);

        return $year . $app_name . $new_running_num;
    }

    public function reverseRefundNum($refund_num)
    {
        $alphabet_arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $defaulter = 99999;

        $str = substr($refund_num, 2);
        $year = '20' . substr($str, 0, 2);
        $alphabet = substr($str, 3, 3);
        $num = substr($str, 4);

        $key = array_search($alphabet, $alphabet_arr);
        $new_num = sprintf("%09d", ($num + ($key * $defaulter)));

        return $year . '-' . $new_num;
    }

    public function refundNumGenerator($refund_num)
    {
        $app_name = 'RF';
        $arr = explode('-', $refund_num);
        $year = substr($arr[0], -2);
        $running_num = $arr[1];
        $new_running_num = $this->receiptAlphabet($running_num);

        return $app_name . $year . $new_running_num;
    }

    public function receiptAlphabet($running_num)
    {
        $defaulter = 99999;
        switch ($running_num) {
            case $running_num <= $defaulter:
                $new_num = $running_num - 0;
                return 'A' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 2 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'B' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 3 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'C' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 4 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'D' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 5 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'E' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 6 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'F' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 7 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'G' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 8 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'H' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 9 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'I' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 10 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'J' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 11 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'K' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 12 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'L' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 13 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'M' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 14 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'N' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 15 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'O' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 16 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'P' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 17 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'Q' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 18 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'R' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 19 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'S' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 20 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'T' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 21 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'U' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 22 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'V' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 23 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'W' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 24 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'X' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 25 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'Y' . sprintf("%05d", $new_num);
                break;

            case $running_num <= 26 * $defaulter:
                $new_num = $running_num - $defaulter;
                return 'Z' . sprintf("%05d", $new_num);
                break;

            default:
                return $running_num;
                break;
        }
    }

    /**
     * Add watermark to PDF Document
     */
    public function setWatermark($file, $watermark_file)
    {
        // echo "<pre>";
        // var_dump( $watermark_file ); 
        // var_dump(getimagesize( $watermark_file )); 
        // die;


        $original_name = $file;
        $tmp_name = Str::replace('.pdf', '_ori.pdf', $file);
        rename($file, $tmp_name);
        shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile="' . $original_name . '" "' . $tmp_name . '"');  //replace version PDF to 1.4 (hack)

        // Set source PDF file 
        $pdf = new Fpdi();
        if (file_exists($tmp_name)) {
            $pagecount = $pdf->setSourceFile($tmp_name);
        } else {
            die('Source PDF not found!');
        }

        // Add watermark to PDF pages 
        for ($i = 1; $i <= $pagecount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->addPage();
            $pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE);

            // var_dump($size); die;

            //Put the watermark 
            $watermark_x_pos = ($size['width'] - 80);
            $watermark_y_pos = 3; // 3 inches from above
            $pdf->Image($watermark_file, $watermark_x_pos, $watermark_y_pos, 0, 0, 'png');
        }
        // @unlink($watermark_file);
        @unlink($tmp_name);
        $pdf->Output('F', $file);
    }

    public function encryptString(string $string)
    {
        // Store cipher method
        $ciphering = "BF-CBC";

        // Use OpenSSl encryption method
        // $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Use random_bytes() function which gives
        // randomly 16 digit values
        $encryption_iv = "lala6699";

        // Alternatively, we can use any 16 digit
        // characters or numeric for iv
        $encryption_key = md5('suksuk2023');

        // Encryption of string process starts
        $encryption = openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);

        return base64_encode($encryption);
    }

    public function decryptString(string $hash_string)
    {
        $hash_string = base64_decode($hash_string);
        // Store cipher method
        $ciphering = "BF-CBC";

        // Use OpenSSl encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Use random_bytes() function which gives
        // randomly 16 digit values
        $encryption_iv = "lala6699";

        // Store the decryption key
        $decryption_key = md5('suksuk2023');

        // Descrypt the string
        $decryption = openssl_decrypt($hash_string, $ciphering, $decryption_key, $options, $encryption_iv);

        return $decryption;
    }

    /**
     * sendMail function summary
     *
     * Queuing all email into single management and assign based on available resource for that day
     * Available Resource is configure in "Senarai Email SMTP"
     *
     * @param string $type,         required :- available option = "html", "raw_text"(default)
     * @param string $to,           required
     * @param string $subject,      required
     * @param string $raw_text,     required only $type="raw_text"
     * @param string $view_name,    required only $type="html"
     * @param string $view_params,  required only $type="html"
     * @return string
     * @throws conditon
     **/
    public function sendMail($type="raw_text", $to = "", $subject = "", $raw_text = "", $view_name = "", $view_params = [])
    {
        if($to == "")
        {
            return "Missing parameter to. to can't be empty"; 
        }


        if($subject == "")
        {
            return "Missing parameter subject. subject can't be empty"; 
        }
        

        if ($type == "html")
        {
            if($view_name == "")
            {
                return "Missing parameter view_name. view_name can't be empty";
            }

            $content = view($view_name, $view_params)->render();

            return $this->createEmailQueue($content, $to, $subject);
        }
        else if ($type == "raw_text")
        {
            if($raw_text == "")
            {
                return "Missing parameter raw_text. raw_text can't be empty";
            }

            return $this->createEmailQueue($raw_text, $to, $subject);
        }
        else
        {
            return "Invalid type given";
        }
    }

    public function getAvailableEmailConfig()
    {
        $available_config = [];
        $available_mail_id = 0;

        $smtpMailRepo = new SmtpMailsRepository();
        $list_available_mail_config = $smtpMailRepo->getTodayMailQueue();

        $count_available_mail_config = count($list_available_mail_config);

        $mail_available_limit   = 0;

        for ($i=0; $i < $count_available_mail_config; $i++) { 
            
            if ($list_available_mail_config[$i]->today_mail_queue < $list_available_mail_config[$i]->mail_message_ratelimit)
            {
                // $available_config = $list_available_mail_config[$i];
                $available_mail_id      = $list_available_mail_config[$i]->id;
                $mail_available_limit   = (int)$list_available_mail_config[$i]->mail_message_ratelimit - (int)$list_available_mail_config[$i]->today_mail_queue;
            }
        }

        if ($available_mail_id > 0)
        {
            $smtp_mail_detail = $smtpMailRepo->readSmtpMails($available_mail_id);
            $available_config = $smtp_mail_detail->toArray();
            $available_config["mail_available_limit"] = $mail_available_limit;
            $available_config["mail_crypto"] = $smtp_mail_detail->getMailCryptoDesc();
        }

        return $available_config;
    }

    public function setEmailConfig($smtp_mail)
    {
        if( empty($smtp_mail) )
        {
            return "Missing smtp_mail config";
        }

        return array(
            "mail_host" => $smtp_mail["mail_server"],
            "mail_port" => $smtp_mail["mail_port"],
            "mail_alias" => "no-reply",
            "mail_username" => $smtp_mail["mail_username"],
            "mail_password" => $smtp_mail["mail_password"],
            "mail_encryption" => $smtp_mail["mail_crypto"],
            "mail_available_limit" =>  (int)$smtp_mail["mail_available_limit"]
        );
    }

    public function createEmailQueue($content = "", $to = [], $subject = [])
    {
        // Retrieve available config based on today date
        $available_config = $this->getAvailableEmailConfig();

        if (count($available_config) == 0)
        {
            return "No Available Email Config";
        }


        $config = $this->setEmailConfig($available_config);

        $payload = array(
            "from" => $config["mail_username"],
            "alias" => $config["mail_alias"],
            "to" => $to,
            "subject" => $subject,
        );

        $mailQueueRepo = new MailQueueRepository();

        $new_mail_queue = array(
            "smtp_mail_id" => $available_config["id"],
            "content" => $content,
            "config" => json_encode($config),
            "payload" => json_encode($payload),
            "status" => 'N',
        );

        $new_queue = $mailQueueRepo->createMailQueue($new_mail_queue);
        
        $unique_id = $this->encryptString($new_queue->id);

        dispatch(new SendEmailJob($unique_id))->delay(5);
        
        return "Email send to queue";
    }

    public function trigger_mail_server($unique_id)
    {
        $response = Http::withoutVerifying()->get(env('MAIL_SERVER')."/".$unique_id);

        $status = "Failed to connect with mail server";

        if( $response->ok() )
        {
            $status = $response->body();
        }

        echo $status;
    }

    public function generateEligible($tender_id)
    {
        $tender = Tender::find($tender_id);

        $vendor_ids = [];

        $mof_vendor_ids = [];

        if(count($tender->mof_codes) > 0) {
            $mof_vendor_ids = $tender->getCodes('mof');

            if($tender->only_bumiputera) {
                $mof_vendor_ids = Vendor::whereIn('id', $mof_vendor_ids)->where('mof_bumi', 1)->pluck('id');
            }
        }

        // dd($mof_vendor_ids);
        
        $cidb_vendor_ids = [];
        if(count($tender->cidb_grades) > 0 ) {
            $code_ids = $tender->codes()->where('code_type', 'cidb-g')->pluck('code_id');
            $ids = VendorCode::whereIn('code_id', $code_ids)->groupBy('vendor_id')->pluck('vendor_id');

            if(count($cidb_vendor_ids) == 0)
            {
                $cidb_vendor_ids = $ids;
            }
            else
            {
                if(!is_array($cidb_vendor_ids)){
                    $cidb_vendor_ids = $cidb_vendor_ids->toArray();
                }
                if(!is_array($ids)){
                    $ids = $ids->toArray();
                }

                $cidb_vendor_ids = array_intersect($cidb_vendor_ids, $ids);
            }

            if($tender->only_bumiputera) {
                $cidb_vendor_ids = Vendor::whereIn('id', $cidb_vendor_ids)->where('cidb_bumi', 1)->pluck('id');
            }
        }

        // dd($cidb_vendor_ids);

        if(count($tender->cidb_codes) > 0)
        {
            $ids_cidb = $tender->getCodes('cidb');
            if(count($cidb_vendor_ids) == 0)
            {
                $cidb_vendor_ids = $ids_cidb;
            }
            else
            {
                if(!is_array($cidb_vendor_ids)){
                    $cidb_vendor_ids = $cidb_vendor_ids->toArray();
                }
                if(!is_array($ids_cidb)){
                    $ids_cidb = $ids_cidb->toArray();
                }

                $cidb_vendor_ids = array_intersect($cidb_vendor_ids, $ids_cidb);
            }
        }

        if($tender->mof_cidb_rule == 'and')
        {
            if(count($mof_vendor_ids) > 0 && count($cidb_vendor_ids) == 0 ) {
                $vendor_ids = $mof_vendor_ids;
            }
            elseif(count($cidb_vendor_ids) > 0 && count($mof_vendor_ids) == 0 ) {
                $vendor_ids = $cidb_vendor_ids;
            }
            else {

                if(!is_array($mof_vendor_ids)){
                    $mof_vendor_ids = $mof_vendor_ids->toArray();
                }
                if(!is_array($cidb_vendor_ids)){
                    $cidb_vendor_ids = $cidb_vendor_ids->toArray();
                }

                $vendor_ids = array_intersect($mof_vendor_ids, $cidb_vendor_ids);
            }
        }
        else
        {
            if(!is_array($mof_vendor_ids)){
                $mof_vendor_ids = $mof_vendor_ids->toArray();
            }

            if(!is_array($cidb_vendor_ids)){
                $cidb_vendor_ids = $cidb_vendor_ids->toArray();
            }
                
            $vendor_ids = array_merge($mof_vendor_ids, $cidb_vendor_ids);
        }

        if($tender->only_selangor != 3)
        {
            if($tender->only_selangor == 1) {
                $vendor_ids = Vendor::whereIn('id', $vendor_ids)->whereNotNull('district_id')->whereNull('state_id')->pluck('id');
            }
    
            if(!empty($tender->district_id)) {
                $vendor_ids = Vendor::whereIn('id', $vendor_ids)->where('district_id', $tender->district_id)->pluck('id');
            }
    
            $district_list_rules = json_decode($tender->district_list_rule ?? "[]");
            
            $vendor_ids_tmp = [];
            if (count($district_list_rules) > 0 )
            {
                $by_district = [];
                $by_state    = [];
                
                foreach ($district_list_rules as $rows) {
                    $district_id = $rows->district_id ?? '-999';
                    $state_id = $rows->state_id ?? '-999';
    
    
                    if($district_id > 0 && $district_id != 0)
                    {
                        $by_district[] = $district_id;
                    }
    
                    if($district_id == 0 && $state_id > 0)
                    {
                        $by_state[] = $state_id;
                    }
    
                }
    
                $vendor_ids = Vendor::whereIn('id', $vendor_ids)->where( function($q) use ($by_state, $by_district){
                    $q->whereIn('state_id', $by_state)
                    ->orWhereIn('district_id', $by_district);
                })->pluck('id');
            }
        }

        // dd($vendor_ids);


        if(count($vendor_ids) > 0) {
            foreach($vendor_ids as $id) {
                $vendor = Vendor::find($id);

                if(empty($vendor)) {
                    continue;
                }

                $eligible = TenderEligible::where('vendor_id', $id)->where('tender_id', $tender->id)->first();
                
                if(!$eligible) {
                    $eligible = new TenderEligible([
                        'tender_id' => $tender->id,
                        'vendor_id' => $id,
                        'email' => 1
                    ]);
                    $eligible->save();
                }
            }
        }

    }
}
