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
use Illuminate\Support\Facades\Log;
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

        /////////////////////Later, can remove BELOW, DEV ONLY////////////////////////
        // DEV ONLY — bypass transactions (created via [DEV] Bypass Bayaran) may not follow
        // the expected YYYY-XXXXXXXXX format and won't have a '-' delimiter.
        // Remove this guard once real payment gateway is integrated and all transactions
        // are guaranteed to have proper number format.
        if (count($arr) < 2) {
            return $transaction_num;
        }
        /////////////////////Later, can remove ABOVE, DEV ONLY////////////////////////

        $year = substr($arr[0], -2);
        $running_num = $arr[1] ?? 0;
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
     * Add watermark to PDF Document ("Dokumen Meja Terkawal" + optional ID box).
     *
     * @param  string  $file  Absolute path to the PDF (overwritten in place)
     * @param  string  $watermark_file  Absolute path to PNG watermark
     * @param  string|null  $idLabel  Optional stamp e.g. "ID 8361"
     */
    public function setWatermark($file, $watermark_file, ?string $idLabel = null)
    {
        $original_name = $file;
        $tmp_name = Str::replaceLast('.pdf', '_ori.pdf', $file);
        if ($tmp_name === $file) {
            $tmp_name = $file . '_ori.pdf';
        }

        if (! is_file($file)) {
            throw new \RuntimeException('PDF source missing for watermark.');
        }

        if (! @rename($file, $tmp_name)) {
            throw new \RuntimeException('Gagal menyediakan PDF untuk watermark.');
        }

        $gsOut = $original_name . '.gs.pdf';

        try {
            // Prefer Ghostscript 1.4 downgrade when it works (FPDI compatibility).
            // Write to a side file first — never overwrite the original with an empty gs output.
            @unlink($gsOut);
            shell_exec(
                'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH '
                . '-sOutputFile=' . escapeshellarg($gsOut) . ' '
                . escapeshellarg($tmp_name) . ' 2>/dev/null'
            );

            $fpdiSource = (is_file($gsOut) && filesize($gsOut) > 0) ? $gsOut : $tmp_name;
            if (! is_file($fpdiSource) || filesize($fpdiSource) <= 0) {
                throw new \RuntimeException('Source PDF not found for watermark.');
            }

            $pdf = new Fpdi();
            $pagecount = $pdf->setSourceFile($fpdiSource);
            $hasPng = is_file($watermark_file);

            for ($i = 1; $i <= $pagecount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl, 0, 0, $size['width'], $size['height'], false);

                // Top-right mark (~55mm), matching production samples.
                $markW = 55;
                $markX = max(2, $size['width'] - $markW - 8);
                $markY = 4;

                if ($hasPng) {
                    $pdf->Image($watermark_file, $markX, $markY, $markW, 0, 'PNG');
                } else {
                    $pdf->SetTextColor(139, 69, 46);
                    $pdf->SetFont('Helvetica', 'B', 11);
                    $pdf->SetXY($markX, $markY);
                    $pdf->Cell($markW, 6, 'Dokumen Meja Terkawal', 0, 0, 'R');
                    $pdf->SetTextColor(0, 0, 0);
                }

                // Boxed ID stamp over the mark (e.g. "ID 8361").
                if ($idLabel !== null && $idLabel !== '') {
                    $pdf->SetFont('Helvetica', 'B', 8);
                    $boxW = max(18, $pdf->GetStringWidth($idLabel) + 4);
                    $boxH = 6;
                    $boxX = $markX + ($markW / 2) - ($boxW / 2);
                    $boxY = $markY + 3;
                    $pdf->SetDrawColor(0, 0, 0);
                    $pdf->SetLineWidth(0.3);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Rect($boxX, $boxY, $boxW, $boxH, 'DF');
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY($boxX, $boxY + 1);
                    $pdf->Cell($boxW, 4, $idLabel, 0, 0, 'C');
                }
            }

            $pdf->Output('F', $file);
            @unlink($tmp_name);
            @unlink($gsOut);

            if (! is_file($file) || filesize($file) <= 0) {
                throw new \RuntimeException('Watermark PDF output was empty.');
            }
        } catch (\Throwable $e) {
            // Always put the original bytes back — never leave a 0-byte .pdf behind.
            @unlink($gsOut);
            if (is_file($tmp_name)) {
                @unlink($file);
                @rename($tmp_name, $original_name);
            }
            throw $e;
        }
    }

    public function encryptString(string $string)
    {
        $ciphering = "AES-256-CBC";

        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        $encryption_iv = substr("lala6699lala6699", 0, $iv_length);

        $encryption_key = substr(md5('suksuk2023'), 0, 32);

        $encryption = openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);

        if ($encryption === false) {
            \Log::error('Encryption failed for string: ' . $string . ' Error: ' . openssl_error_string());
            return false;
        }

        return base64_encode($encryption);
    }

    public function decryptString(string $hash_string)
    {
        $hash_string = base64_decode($hash_string);
        $ciphering = "AES-256-CBC";

        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        $encryption_iv = substr("lala6699lala6699", 0, $iv_length);

        $decryption_key = substr(md5('suksuk2023'), 0, 32);

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
    public function sendMail($type = "raw_text", $to = "", $subject = "", $raw_text = "", $view_name = "", $view_params = [], $attachments = [], array $tracking = [])
    {
        if ($to == "") {
            Log::error('[sendMail] Missing recipient address.');
            return "Missing parameter to. to can't be empty";
        }

        if ($subject == "") {
            Log::error('[sendMail] Missing email subject.');
            return "Missing parameter subject. subject can't be empty";
        }

        if ($type == "html") {
            if ($view_name == "") {
                Log::error('[sendMail] Missing view_name for html type email.');
                return "Missing parameter view_name. view_name can't be empty";
            }

            $content = view($view_name, $view_params)->render();

            $result = $this->createEmailQueue($content, $to, $subject, $attachments, $tracking);
            if (! $this->emailSendSucceeded($result)) {
                Log::error('[sendMail] Failed to queue email.');
            }
            return $result;
        } else if ($type == "raw_text") {
            if ($raw_text == "") {
                Log::error('[sendMail] Missing raw_text for raw_text type email.', ['to' => $to, 'subject' => $subject]);
                return "Missing parameter raw_text. raw_text can't be empty";
            }

            $result = $this->createEmailQueue($raw_text, $to, $subject, $attachments, $tracking);
            if (! $this->emailSendSucceeded($result)) {
                Log::error('[sendMail] Failed to queue email.');
            }
            return $result;
        } else {
            Log::error('[sendMail] Invalid type given.', ['type' => $type]);
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

        if ($count_available_mail_config === 0) {
            Log::error('[getAvailableEmailConfig] No SMTP mail configurations found in the database (smtp_mails table is empty).');
            return $available_config;
        }

        $mail_available_limit   = 0;

        for ($i = 0; $i < $count_available_mail_config; $i++) {

            if ($list_available_mail_config[$i]->today_mail_queue < $list_available_mail_config[$i]->mail_message_ratelimit) {
                // $available_config = $list_available_mail_config[$i];
                $available_mail_id      = $list_available_mail_config[$i]->id;
                $mail_available_limit   = (int)$list_available_mail_config[$i]->mail_message_ratelimit - (int)$list_available_mail_config[$i]->today_mail_queue;
            }
        }

        if ($available_mail_id > 0) {
            $smtp_mail_detail = $smtpMailRepo->readSmtpMails($available_mail_id);
            $available_config = $smtp_mail_detail->toArray();
            $available_config["mail_available_limit"] = $mail_available_limit;
            $available_config["mail_crypto"] = $smtp_mail_detail->getMailCryptoDesc();
        } else {
            Log::error('[getAvailableEmailConfig] All SMTP configs have reached their daily rate limit.', [
                'configs_checked' => $count_available_mail_config,
            ]);
        }

        return $available_config;
    }

    public function setEmailConfig($smtp_mail)
    {
        if (empty($smtp_mail)) {
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

    public function createEmailQueue($content = "", $to = [], $subject = [], $attachments = [], array $tracking = [])
    {
        // Retrieve available config based on today date
        $available_config = $this->getAvailableEmailConfig();

        if (count($available_config) == 0) {
            Log::warning('[createEmailQueue] No SMTP queue config — falling back to Laravel mailer.', [
                'fallback' => 'laravel_mailer',
            ]);

            return $this->sendViaLaravelMailer($content, $to, $subject, $attachments);
        }

        $config = $this->setEmailConfig($available_config);

        $payload = array(
            "from" => $config["mail_username"],
            "alias" => $config["mail_alias"],
            "to" => $to,
            "subject" => $subject,
        );

        if (!empty($tracking)) {
            // The mail server ignores additional payload metadata; the Laravel
            // queue job uses it to update the originating workflow after SMTP.
            $payload['tracking'] = $tracking;
        }

        $mailQueueRepo = new MailQueueRepository();

        $new_mail_queue = array(
            "smtp_mail_id" => $available_config["id"],
            "content" => $content,
            "config" => json_encode($config),
            "payload" => json_encode($payload),
            "attachments" => ! empty($attachments) ? json_encode($attachments) : null,
            "status" => 'N',
            // Automated sends have no signed-in user. Zero is the system actor
            // sentinel for the legacy non-null created_by column.
            "created_by" => auth()->id() ?: 0,
        );

        $new_queue = $mailQueueRepo->createMailQueue($new_mail_queue);

        $unique_id = $this->encryptString($new_queue->id);

        Log::info('[createEmailQueue] Email queued successfully.', [
            'mail_queue_id' => $new_queue->id,
            'smtp_mail_id' => $available_config["id"],
        ]);

        dispatch(new SendEmailJob($unique_id, (int) $new_queue->id))->delay(5);

        return "Email send to queue";
    }

    public function trigger_mail_server($unique_id)
    {
        $mail_server_url = config('services.mail_server.url') . "/" . $unique_id;

        try {
            $response = Http::withoutVerifying()
                ->connectTimeout(5)
                ->timeout(15)
                ->get($mail_server_url);

            if ($response->ok()) {
                $status = $response->body();
                Log::info('[trigger_mail_server] Mail server responded successfully.', [
                    'mail_server_host' => parse_url($mail_server_url, PHP_URL_HOST),
                    'response_status' => $status,
                ]);
            } else {
                $status = "Mail server returned error status: " . $response->status();
                Log::error('[trigger_mail_server] Mail server returned non-200 response.', [
                    'mail_server_host' => parse_url($mail_server_url, PHP_URL_HOST),
                    'http_status' => $response->status(),
                ]);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $status = "Failed to connect with mail server";
            Log::error('[trigger_mail_server] Could not connect to mail server.', [
                'mail_server_host' => parse_url($mail_server_url, PHP_URL_HOST),
                'exception' => get_class($e),
            ]);
        } catch (\Exception $e) {
            $status = "Unexpected error contacting mail server";
            Log::error('[trigger_mail_server] Unexpected exception.', [
                'mail_server_host' => parse_url($mail_server_url, PHP_URL_HOST),
                'exception' => get_class($e),
            ]);
        }

        echo $status;
    }

    protected function emailSendSucceeded(string $result): bool
    {
        return in_array($result, ['Email send to queue', 'Email sent via Laravel mailer'], true);
    }

    protected function sendViaLaravelMailer(string $content, string $to, string $subject, array $attachments = []): string
    {
        try {
            Mail::html($content, function ($message) use ($to, $subject, $attachments) {
                $message->to($to)->subject($subject);

                foreach ($attachments as $attachment) {
                    if (empty($attachment['data'])) {
                        continue;
                    }

                    $message->attachData(
                        base64_decode($attachment['data']),
                        $attachment['filename'] ?? 'attachment',
                        ['mime' => $attachment['mime'] ?? 'application/octet-stream']
                    );
                }
            });

            Log::info('[sendViaLaravelMailer] Email accepted by Laravel mailer.');

            return 'Email sent via Laravel mailer';
        } catch (\Throwable $e) {
            Log::error('[sendViaLaravelMailer] Failed to send email.', [
                'exception' => get_class($e),
            ]);

            return 'Failed to send email: ' . $e->getMessage();
        }
    }

    public function generateEligible($tender_id, int $email = 1)
    {
        $tender = Tender::find($tender_id);

        if (!$tender) {
            return;
        }

        $hasMofCriteria = $tender->mof_codes->isNotEmpty();
        $hasCidbGradeCriteria = $tender->cidb_grades->isNotEmpty();
        $hasCidbCodeCriteria = $tender->cidb_codes->isNotEmpty();
        $hasCidbCriteria = $hasCidbGradeCriteria || $hasCidbCodeCriteria;

        $mof_vendor_ids = $hasMofCriteria ? $tender->getCodes('mof') : [];
        $mof_vendor_ids = is_array($mof_vendor_ids) ? $mof_vendor_ids : $mof_vendor_ids->all();
        if ($hasMofCriteria && !empty($mof_vendor_ids)) {
            $mof_vendor_ids = $this->filterVendorsByCodeValidity($mof_vendor_ids, 'mof');
        }
        if ($hasMofCriteria && $tender->only_bumiputera && !empty($mof_vendor_ids)) {
            $mof_vendor_ids = Vendor::whereIn('id', $mof_vendor_ids)->where('mof_bumi', 1)->pluck('id');
        }

        $cidbMatchSets = [];
        if ($hasCidbGradeCriteria) {
            $code_ids = $tender->codes()->where('code_type', 'cidb-g')->pluck('code_id');
            $ids = VendorCode::whereIn('code_id', $code_ids)->groupBy('vendor_id')->pluck('vendor_id');
            $cidbMatchSets[] = $ids->all();
        }

        if ($hasCidbCodeCriteria) {
            $ids_cidb = $tender->getCodes('cidb');
            $cidbMatchSets[] = is_array($ids_cidb) ? $ids_cidb : $ids_cidb->all();
        }

        if (count($cidbMatchSets) === 1) {
            $cidb_vendor_ids = $cidbMatchSets[0];
        } elseif (count($cidbMatchSets) > 1) {
            $cidb_vendor_ids = array_values(array_intersect(...$cidbMatchSets));
        } else {
            $cidb_vendor_ids = [];
        }

        if ($hasCidbCriteria && !empty($cidb_vendor_ids)) {
            $cidb_vendor_ids = $this->filterVendorsByCodeValidity($cidb_vendor_ids, 'cidb');
        }

        // Bumi filters apply whenever that code category is selected, including
        // CIDB-code-only tenders that do not specify a CIDB grade.
        if ($hasCidbCriteria && $tender->only_bumiputera && !empty($cidb_vendor_ids)) {
            $cidb_vendor_ids = Vendor::whereIn('id', $cidb_vendor_ids)->where('cidb_bumi', 1)->pluck('id')->all();
        }

        $mof_vendor_ids = is_array($mof_vendor_ids) ? $mof_vendor_ids : $mof_vendor_ids->all();
        $vendor_ids = $this->combineEligibilityCodeMatches(
            $mof_vendor_ids,
            $cidb_vendor_ids,
            $hasMofCriteria,
            $hasCidbCriteria,
            (string) $tender->mof_cidb_rule
        );

        if (count($vendor_ids) > 0) {
            $vendor_ids = array_values(array_unique(array_filter($vendor_ids)));
        }

        if ($tender->only_selangor != 3 && count($vendor_ids) > 0) {
            if ($tender->only_selangor == 1) {
                $vendor_ids = Vendor::whereIn('id', $vendor_ids)->whereNotNull('district_id')->whereNull('state_id')->pluck('id')->all();
            }

            if (!empty($tender->district_id)) {
                $vendor_ids = Vendor::whereIn('id', $vendor_ids)->where('district_id', $tender->district_id)->pluck('id')->all();
            }

            $district_list_rules = json_decode($tender->district_list_rule ?? '[]');
            if (count($district_list_rules) > 0) {
                $by_district = [];
                $by_state = [];
                foreach ($district_list_rules as $rows) {
                    $district_id = $rows->district_id ?? '-999';
                    $state_id = $rows->state_id ?? '-999';

                    if ($district_id > 0) {
                        $by_district[] = $district_id;
                    }
                    if ($district_id == 0 && $state_id > 0) {
                        $by_state[] = $state_id;
                    }
                }

                $vendor_ids = Vendor::whereIn('id', $vendor_ids)->where(function ($q) use ($by_state, $by_district) {
                    $q->whereIn('state_id', $by_state)->orWhereIn('district_id', $by_district);
                })->pluck('id')->all();
            }
        }

        $vendor_ids = array_values(array_unique(array_filter(
            is_array($vendor_ids) ? $vendor_ids : $vendor_ids->all()
        )));

        // Apply the same registration, approval, active subscription, and
        // blacklist rules used by Vendor::canParticipateInTenders().
        if (!empty($vendor_ids)) {
            $vendor_ids = Vendor::query()->whereIn('id', $vendor_ids)
                ->canParticipate()
                ->where('registration_paid', 1)
                ->whereDate('expiry_date', '>=', date('Y-m-d'))
                ->pluck('id')->all();
        }

        // Reconcile in bounded batches so regeneration does not hydrate every
        // historical eligibility row for a tender into PHP memory.
        $eligibleLookup = array_flip($vendor_ids);
        TenderEligible::query()->where('tender_id', $tender->id)
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($eligibleLookup) {
                $staleIds = $rows->filter(fn ($row) => !isset($eligibleLookup[$row->vendor_id]))->pluck('id')->all();
                if (!empty($staleIds)) {
                    TenderEligible::whereIn('id', $staleIds)->update(['email' => 0, 'updated_at' => now()]);
                }
            });

        foreach (array_chunk($vendor_ids, 500) as $chunk) {
            $existingByVendor = TenderEligible::query()
                ->where('tender_id', $tender->id)
                ->whereIn('vendor_id', $chunk)
                ->get(['id', 'vendor_id', 'email', 'sent_at'])
                ->keyBy('vendor_id');

            $rows = [];
            foreach ($chunk as $vendorId) {
                $existing = $existingByVendor->get($vendorId);
                if ($existing) {
                    if (is_null($existing->sent_at) && (int) $existing->email === 0) {
                        TenderEligible::whereKey($existing->id)->update([
                            'email' => $email,
                            'updated_at' => now(),
                        ]);
                    }
                    continue;
                }

                $rows[] = [
                    'tender_id' => $tender->id,
                    'vendor_id' => $vendorId,
                    'email' => $email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($rows)) {
                TenderEligible::insert($rows);
            }
        }
    }

    /**
     * Combine MOF/CIDB match sets while distinguishing a missing criterion
     * from a selected criterion that happens to match no vendors.
     */
    protected function combineEligibilityCodeMatches(
        array $mofVendorIds,
        array $cidbVendorIds,
        bool $hasMofCriteria,
        bool $hasCidbCriteria,
        string $rule
    ): array {
        if ($hasMofCriteria && $hasCidbCriteria) {
            return $rule === 'and'
                ? array_values(array_intersect($mofVendorIds, $cidbVendorIds))
                : array_values(array_unique(array_merge($mofVendorIds, $cidbVendorIds)));
        }

        if ($hasMofCriteria) {
            return $mofVendorIds;
        }

        return $hasCidbCriteria ? $cidbVendorIds : [];
    }

    /**
     * Match Tender::matchVendor()'s MOF/CIDB validity checks while retrieving
     * only small vendor batches from the database.
     */
    protected function filterVendorsByCodeValidity(array $vendorIds, string $type): array
    {
        $validVendorIds = [];
        $validityMethod = $type === 'mof' ? 'mofValid' : 'cidbValid';
        $validityColumn = $type === 'mof' ? 'mof_end_date' : 'cidb_end_date';

        foreach (array_chunk(array_values(array_unique($vendorIds)), 500) as $vendorIdChunk) {
            $vendors = Vendor::query()->whereIn('id', $vendorIdChunk)->get(['id', $validityColumn]);
            foreach ($vendors as $vendor) {
                if ($vendor->{$validityMethod}()) {
                    $validVendorIds[] = $vendor->id;
                }
            }
        }

        return $validVendorIds;
    }
}
