<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMailQueueRequest;
use App\Http\Requests\UpdateMailQueueRequest;
use App\Models\MailQueue;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MailQueueController extends Controller
{
	use Helper;
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!MailQueue::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

			$mail_queue_list = MailQueue::select("*");

			$start 		= $request->start ?? 0;  						// Get starting point for data to be retrieved
			$length 	= $request->length ?? 10;						// Get how much data to be retrieved
			$draw 		= $request->draw ?? 1;							// Get original draw request
			$keyword 	= $request->get('search')["value"] ?? "";
			$keyword 	= Str::lower($keyword);

			$orderByColumn  = $request->get('order')[0]["column"] ?? "";
            $orderByDir     = $request->get('order')[0]["dir"] ?? "";

			if ($orderByColumn == 0)
            {
                $mail_queue_list->orderByRaw("lower(payload->'$.subject') ".$orderByDir);
            }

            if ($orderByColumn == 2)
            {
                $mail_queue_list->orderBy('created_at', $orderByDir);
            }

            if ($orderByColumn == 3)
            {
                $mail_queue_list->orderBy('email_send_at', $orderByDir);
            }

            if ($orderByColumn == 4)
            {
                $mail_queue_list->orderBy('status', $orderByDir);
            }


			$recordsTotal = (clone $mail_queue_list)->count() ?? 0;


			$mail_queue_list->where( function($q) use($keyword){
				$q->whereRaw("lower(status) like ?", '%'.$keyword.'%');
				$q->orWhereRaw("lower(payload->'$.subject') like ?", '%'.$keyword.'%');
			});
			
			$recordsFiltered = (clone $mail_queue_list)->count() ?? 0;

			$results = $mail_queue_list->offset($start)->limit($length)->get();

			$datatable_data = [];

			foreach ($results as $rows) {

                // $enc_id = $this->encryptString($rows->id);
                $enc_smtp_mail_id = $this->encryptString($rows->smtp_mail_id);
				$payload = json_decode($rows->payload) ?? [];

				// $actions    = [];
                // $actions[] = MailQueue::canShow() ? "<a class='btn btn-xs btn-primary' href='".route('mail-manager.smtp-setting.show', ['smtp_setting' => $enc_id])."'>Papar</a>" : '';
                // $actions[] = MailQueue::canUpdate() ? "<a class='btn btn-xs btn-warning' href='".route('mail-manager.smtp-setting.edit', ['smtp_setting' => $enc_id])."'>Kemaskini</a>" : '';
                // $actions[] = MailQueue::canDelete() ? "<button class='btn btn-xs btn-danger' onclick='deleteSmtpMail(".'"'.$enc_id.'"'.")'>Padam</button>" : '';
				// $action_column = '<div class ="btn-group">' . implode(' ', $actions) . '</div>';

				$created_at = "";
				$email_send_at = "";
				$status = "";

				if($rows->created_at != "")
				{
					$created_at = $rows->created_at->format('d-m-Y H:i:s');
				}

				if($rows->email_send_at != "")
				{
					$email_send_at = $rows->email_send_at->format('d-m-Y H:i:s');
				}

				switch ($rows->status) {
					case 'S':
						$status = "<span class='badge badge-success'>Telah dihantar</span>";
						break;

					case 'N':
						$status = "<span class='badge badge-secondary'>Belum dihantar</span>";
						break;
					
					default:
						# code...
						break;
				}

				$datatable_data[] = array(
					"created_at" => $created_at,
					"subject" => $payload->subject ?? "",
					"smtp_mail_id" => "<a class='btn btn-xs btn-primary' href='".route('mail-manager.smtp-setting.show', ['smtp_setting' => $enc_smtp_mail_id])."'> Papar Tetapan Email SMTP </a>",
					"status" => $status,
					"email_send_at" => $email_send_at,
				);
			}

			$datatable_response = array(
				"draw" => $draw,
				"recordsTotal" => $recordsTotal,
  				"recordsFiltered" => $recordsFiltered,
				"data" => $datatable_data
			);

			return response()->json($datatable_response);
		}

        return view('mail-queue.index');
    }

}
