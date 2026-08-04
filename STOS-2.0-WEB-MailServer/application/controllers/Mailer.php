<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer extends CI_Controller {

	public function __construct()                                                                                                                 
{                                                                                                                                             
		parent::__construct();                                                                                                                   
		$this->load->database();                                                                                                                  
	} 

	public function send_mail($enc_mail_queue_id)
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");

		$this->load->helper('custom');
		$mail_queue_id = decryptString($enc_mail_queue_id);

		if ($mail_queue_id === false)
		{
			log_message('error', '[send_mail] Invalid or undecryptable mail_queue_id. enc_id: ' . $enc_mail_queue_id);
			echo json_encode(["status" => "error", "message" => "Invalid mail_queue_id provided"]);
			return;
		}

		if ($mail_queue_id > 0)
		{
			$this->load->model('MailQueue_model', 'mail_queue');

			$data_mail = $this->mail_queue->getById($mail_queue_id);

			if (!$data_mail)
			{
				log_message('error', '[send_mail] Mail queue record not found. mail_queue_id: ' . $mail_queue_id);
				echo json_encode(["status" => "error", "message" => "Mail queue record not found"]);
				return;
			}

			if ($data_mail->status == "S")
			{
				log_message('info', '[send_mail] Email already sent, skipping. mail_queue_id: ' . $mail_queue_id);
				echo json_encode(["status" => "skipped", "message" => "Email already been sent"]);
				return;
			}

			$send_date = date('Y-m-d H:i:s');
			$send_status = $this->sendMailWithParams($data_mail->content, $data_mail->config, $data_mail->payload, isset($data_mail->attachments) ? $data_mail->attachments : null);

			if ($send_status)
			{
				$this->mail_queue->update($mail_queue_id, ["status" => "S", "email_send_at" => $send_date]);
				log_message('info', '[send_mail] Email sent successfully. mail_queue_id: ' . $mail_queue_id);
				echo json_encode(["status" => "success", "message" => "Email sent successfully"]);
			}
			else
			{
				$this->mail_queue->update($mail_queue_id, ["status" => "T", "email_send_at" => $send_date]);
				log_message('error', '[send_mail] SMTP send failed. mail_queue_id: ' . $mail_queue_id);
				echo json_encode(["status" => "failed", "message" => "Email send tried and may have failed to be received by receiver"]);
			}
		}
	}

	public function sendMailWithParams($content, $mail_config, $mail_payload, $attachments = null)
	{
		$mail_config	= json_decode($mail_config);
		$mail_payload	= json_decode($mail_payload);

		// decrypt mail pass
		$mail_password = decryptString($mail_config->mail_password);

		if ($mail_password === false)
		{
			log_message('error', '[sendMailWithParams] Failed to decrypt mail password. Check smtp_mails.mail_password encryption.');
			return false;
		}

		$this->load->library('email');

		$config['protocol']		= 'smtp';
		$config['charset']		= 'utf-8';
		$config['mailtype']		= 'html';
		/////////////optional////////////////
		$config['newline']		= "\r\n";
		$config['crlf']			= "\r\n";
		$config['smtp_timeout']	= 30;
		////////////////////////////////////
		$config['smtp_host']	= $mail_config->mail_host;
		$config['smtp_port']	= $mail_config->mail_port;
		$config['smtp_crypto']	= $mail_config->mail_encryption;
		$config['smtp_user']	= $mail_config->mail_username;
		$config['smtp_pass']	= $mail_password;

		$this->email->initialize($config);

		$this->email->from($mail_config->mail_username, $mail_payload->alias);
		$this->email->to($mail_payload->to);
		$this->email->subject($mail_payload->subject);
		$this->email->message($content);

		// Attach any files carried in the queue row. Each item is a base64-encoded
		// buffer: { filename, mime, data }. Using CI buffer-mode attach (4th arg set).
		if (! empty($attachments))
		{
			$decoded_attachments = json_decode($attachments, true);

			if (is_array($decoded_attachments))
			{
				foreach ($decoded_attachments as $attachment)
				{
					if (empty($attachment['data']))
					{
						continue;
					}

					$this->email->attach(
						base64_decode($attachment['data']),
						'attachment',
						! empty($attachment['filename']) ? $attachment['filename'] : 'attachment',
						! empty($attachment['mime']) ? $attachment['mime'] : 'application/octet-stream'
					);
				}
			}
		}

		log_message('info', '[sendMailWithParams] Attempting SMTP send to: ' . $mail_payload->to . ' | subject: ' . $mail_payload->subject . ' | host: ' . $mail_config->mail_host . ':' . $mail_config->mail_port);

		$send_result = $this->email->send(FALSE);

		if (!$send_result) {
			log_message('error', '[sendMailWithParams] SMTP send failed to: ' . $mail_payload->to . ' | debugger: ' . $this->email->print_debugger(['headers', 'subject', 'body']));
		}

		return $send_result;
	}

	public function testMailUsingSukSetup0()
	{
		$this->load->library('email');

		$config['protocol']		= 'smtp';
		$config['smtp_host']	= 'relay.selangor.gov.my';
		$config['smtp_port']	= '25';
		$config['smtp_crypto']	= '';
		$config['smtp_user']	= 'tenderadmin@relay.selangor.gov.my';
		$config['smtp_pass']	= "";
		$config['mailtype']		= 'html';

		$this->email->initialize($config);

		$this->email->from('tenderadmin@relay.selangor.gov.my', 'no-reply');
		$this->email->to("tester6089@gmail.com");
		$this->email->subject("test by suk setting 0");
		$this->email->message("<b>Test Hello World From SUK</b>");

		// return $this->email->send(FALSE);

		echo "<pre>";
		var_dump($this->email->send(FALSE));
		var_dump($this->email->print_debugger());
	}

	public function testMailUsingSukSetup1()
	{
		$this->load->library('email');

		$config['protocol']		= 'smtp';
		$config['smtp_host']	= 'relay.selangor.gov.my';
		$config['smtp_port']	= '25';
		$config['smtp_crypto']	= '';
		$config['smtp_user']	= 'tenderadmin@selangor.gov.my';
		$config['smtp_pass']	= "Suksel2019";
		$config['mailtype']		= 'html';

		$this->email->initialize($config);

		$this->email->from('tenderadmin@selangor.gov.my', 'no-reply');
		$this->email->to("tester6089@gmail.com");
		$this->email->subject("test by suk setting 1");
		$this->email->message("<b>Test Hello World From SUK</b>");

		// return $this->email->send(FALSE);

		echo "<pre>";
		var_dump($this->email->send(FALSE));
		var_dump($this->email->print_debugger());
	}

	public function testMailUsingSukSetup2()
	{
		$this->load->library('email');

		$config['protocol']		= 'smtp';
		$config['smtp_host']	= 'relay.selangor.gov.my';
		$config['smtp_port']	= '25';
		$config['smtp_crypto']	= '';
		$config['smtp_user']	= 'tenderadmin2@selangor.gov.my';
		$config['smtp_pass']	= "Suksel2020";
		$config['mailtype']		= 'html';

		$this->email->initialize($config);

		$this->email->from('tenderadmin2@selangor.gov.my', 'no-reply');
		$this->email->to("tester6089@gmail.com");
		$this->email->subject("test by suk setting 1");
		$this->email->message("<b>Test Hello World From SUK</b>");

		// return $this->email->send(FALSE);

		echo "<pre>";
		var_dump($this->email->send(FALSE));
		var_dump($this->email->print_debugger());
	}

	public function sampler()
	{
		$this->load->library('email');

		$mail_host 			= $this->input->post('mail_host') ?? FALSE;
		$mail_port 			= $this->input->post('mail_port') ?? FALSE;
		$mail_crypto		= $this->input->post('mail_crypto') ?? FALSE;
		$mail_username 		= $this->input->post('mail_username') ?? FALSE;
		$mail_password		= $this->input->post('mail_password') ?? FALSE;
		$mail_target_email	= $this->input->post('target_email') ?? FALSE;

		$config['protocol']		= 'smtp';
		$config['smtp_host']	= $mail_host;
		$config['smtp_port']	= $mail_port;
		$config['smtp_crypto']	= trim(strtolower($mail_crypto));
		$config['smtp_user']	= $mail_username;
		$config['smtp_pass']	= $mail_password;
		$config['mailtype']		= 'html';

		$this->email->initialize($config);

		$this->email->from($mail_username, 'no-reply');
		$this->email->to($mail_target_email);
		$this->email->subject("Sampling SMTP Mail Config");
		$this->email->message("<b>Hi, this is test email send from SMTP configuration</b>");


		echo '<html><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"></head>';

		echo "<body>";
		echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>';

		
		echo "<div class='container-fluid'>";

		echo "<div class='container border '>";
		echo "Received Input Configuration </br>";
		foreach($_POST as $input => $value) echo $input." : ".$value."</br>";

		if (!$mail_host) die;
		
		echo  ( $this->email->send(FALSE) == TRUE ? "<br/><br/><span class='badge bg-success' >Test email was sent successfully </span>" : "<br/><br/><span class='badge bg-danger' >Unable to sent test email</span>" );
		echo "</br></br></br></br></br></br>Email process logs </br>";
		echo $this->email->print_debugger();

		
		echo "</div>";
		echo "</body>";
		echo "</html>";

	}
}
