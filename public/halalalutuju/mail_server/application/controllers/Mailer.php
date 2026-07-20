<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer extends CI_Controller {

	public function send_mail($enc_mail_queue_id)
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");

		$this->load->helper('custom');
		$mail_queue_id = decryptString($enc_mail_queue_id);

		if ($mail_queue_id === false)
		{
			echo json_encode(["Invalid mail_queue_id provided"]);
		}

		if ($mail_queue_id > 0)
		{
			$this->load->model('MailQueue_model', 'mail_queue');

			$data_mail = $this->mail_queue->getById($mail_queue_id);


			if($data_mail->status == "S")
			{
				echo json_encode(["Email already been send"]);
			}

			$send_date = date('Y-m-d H:i:s');
			$send_status = $this->sendMailWithParams($data_mail->content, $data_mail->config, $data_mail->payload);

			if ($send_status)
			{
				$update_status = $this->mail_queue->update($mail_queue_id, ["status" => "S", "email_send_at" => $send_date]);
				echo json_encode(["Email sent successfully"]);
			}
			else
			{
				$update_status = $this->mail_queue->update($mail_queue_id, ["status" => "T", "email_send_at" => $send_date]);
				echo json_encode(["Email send tried and may failed to be received by receiver"]);
			}


			// echo "<pre>";
			// var_dump($send_status);
			// var_dump($update_status);
		}

		
	}

	public function sendMailWithParams($content, $mail_config, $mail_payload)
	{
		$mail_config	= json_decode($mail_config);
		$mail_payload	= json_decode($mail_payload);


		// decrypt mail pass
		$mail_password = decryptString($mail_config->mail_password);

		if ($mail_password === false)
		{
			echo json_encode(["Invalid mail_password provided"]);
		}

		// var_dump($mail_config);
		// var_dump($mail_payload);
		// var_dump($mail_password);


		$this->load->library('email');

		$config['protocol']		= 'smtp';
		$config['charset']		= 'utf-8';
		$config['mailtype']		= 'html';
		
		// $config['send_multipart']	= false;
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

		return $this->email->send(FALSE);
		// echo "<pre>";
		// var_dump($this->email->send(FALSE));
		// var_dump($this->email->print_debugger());
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
