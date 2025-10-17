<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\SmtpMails;
use App\Repositories\MailQueueRepository;
use App\Traits\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MailManagerController extends Controller
{
    use Helper;

    public function index(Request $request)
    {
        if (!SmtpMails::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

			$smtp_mail_list = SmtpMails::select("*");

			$start 		= $request->start ?? 0;  						// Get starting point for data to be retrieved
			$length 	= $request->length ?? 10;						// Get how much data to be retrieved
			$draw 		= $request->draw ?? 1;							// Get original draw request
			$keyword 	= $request->get('search')["value"] ?? "";
			$keyword 	= Str::lower($keyword);

			$recordsTotal = (clone $smtp_mail_list)->count() ?? 0;


			$smtp_mail_list->where( function($q) use($keyword){
				$q->whereRaw("lower(mail_server) like ?", '%'.$keyword.'%')
				->orWhereRaw("mail_port like ?", '%'.$keyword.'%')
				->orWhereRaw("lower(mail_username) like ?", '%'.$keyword.'%');
			});            
			
			$recordsFiltered = (clone $smtp_mail_list)->count() ?? 0;

			$results = $smtp_mail_list->offset($start)->limit($length)->get();

			$datatable_data = [];

			foreach ($results as $rows) {

                $enc_id = $this->encryptString($rows->id);

				$actions    = [];
                $actions[] = SmtpMails::canShow() ? "<a class='btn btn-xs btn-primary' href='".route('mail-manager.show', ['mail_manager' => $enc_id])."'>Papar</a>" : '';
                $actions[] = SmtpMails::canUpdate() ? "<a class='btn btn-xs btn-warning' href='".route('mail-manager.edit', ['mail_manager' => $enc_id])."'>Kemaskini</a>" : '';
                // $actions[] = SmtpMails::canDelete() ? "<button class='btn btn-xs btn-danger' onclick='deleteSmtpMail(".'"'.$enc_id.'"'.")'>Padam</button>" : '';
				
				$action_column = '<div class ="btn-group">' . implode(' ', $actions) . '</div>';

				$datatable_data[] = array(
					"created_at" => $rows->created_at->format('d-m-Y'),
					"mail_server" => $rows->mail_server,
					"mail_port" => $rows->mail_port,
					"mail_username" => $rows->mail_username,
					"mail_message_ratelimit" => $rows->mail_message_ratelimit,
					"actions" => $action_column,
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

        return view('mail-manager.index');
    }

    public function show($id)
    {
        $true_id = $this->decryptString($id);
        $data = SmtpMails::findOrFail($true_id);
        $data->enc_id = $id;

        if (!$data->canShow()) {
            return $this->_access_denied();
        }

        return view('mail-manager.show', compact('data'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return $this->_ajax_denied();
        }
        if (!SmtpMails::canCreate()) {
            return $this->_access_denied();
        }
        return view('mail-manager.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mail_server' => 'required|max:255',
            'mail_port' => 'required|integer|max:65535',
            'mail_username' => 'required|unique:smtp_mails|string',
            'mail_password' => 'required|string',
            'mail_message_ratelimit' => 'required|integer|min:1',
        ]);

        if (!SmtpMails::canCreate()) {
            return $this->_access_denied();
        }

        $new_smtp_mail = array(
            "mail_server" => $request->post('mail_server'),
            "mail_port" => $request->post('mail_port'),
            "mail_username" => $request->post('mail_username'),
            "mail_password" => $this->encryptString($request->post('mail_password')),
            "mail_message_ratelimit" => $request->post('mail_message_ratelimit'),
            "created_by" => Auth::id()
        );

        SmtpMails::create($new_smtp_mail);
        return redirect('mail-manager')->with('success', $this->created_message);
    }

    public function edit($id)
    {
        $true_id = $this->decryptString($id);

        $data = SmtpMails::findOrFail($true_id);
        $data["enc_id"] = $id;

        if (!SmtpMails::canUpdate()) {
            return $this->_access_denied();
        }
        
        return view('mail-manager.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $true_id = $this->decryptString($id);
        $data = SmtpMails::findOrFail($true_id);

        if (!$true_id || !SmtpMails::canUpdate())  {
            return $this->_access_denied();
        }

        $rules = array(
            'mail_port' => 'required|integer|max:65535',
            'mail_username' => 'required|string',
            'mail_message_ratelimit' => 'required|integer|min:1',
        );

        if ($data->mail_server != $request->mail_server)
        {
            $rules["mail_server"] = 'required|unique:smtp_mails|max:255';
        }

        $decrypted_passwd = $this->decryptString($data->mail_password);

        if ($decrypted_passwd != $request->mail_password && $request->mail_password != '********' )
        {
            $rules["mail_password"] = 'required';
        }

        $request->validate($rules);

        $update_smtp_mail = array(
            "mail_server" => $request->post('mail_server'),
            "mail_port" => $request->post('mail_port'),
            "mail_username" => $request->post('mail_username'),
            "mail_message_ratelimit" => $request->post('mail_message_ratelimit'),
            "updated_by" => Auth::id()
        );

        if ($decrypted_passwd != $request->mail_password && $request->mail_password != '********' )
        {
            $update_smtp_mail["mail_password"] = $this->encryptString($request->mail_password);
        }

        SmtpMails::findOrFail($true_id)->update($update_smtp_mail);
        return redirect()->route('mail-manager.edit', ["mail_manager" => $id])->with('success', $this->updated_message);
    }

    public function destroy($id)
    {
        $true_id = $this->decryptString($id);

        if (!$true_id || !SmtpMails::canDelete()) {
            return $this->_access_denied();
        }
        
        $update_del_smtp_mail["deleted_by"] = Auth::id();
        SmtpMails::findOrFail($true_id)->update($update_del_smtp_mail);

        SmtpMails::destroy($true_id);

        return redirect()->route('mail-manager.index')->with('success', $this->deleted_message);
    }

    public function resend_unsend_daily_email()
    {
        $mailQueueRepo = new MailQueueRepository();
        $list_pending_send = $mailQueueRepo->getTodayUnsendMailQueue();


        foreach ($list_pending_send as $rows) {
            $unique_id = $this->encryptString($rows->id);
            dispatch(new SendEmailJob($unique_id));
        }

        return response()->json(["Today unsend email has been added to queue"]);

        // dd($list_pending_send);
    }

    public function resend_unsend_this_week_email()
    {
        $mailQueueRepo = new MailQueueRepository();
        $list_pending_send = $mailQueueRepo->getThisWeekUnsendMailQueue();


        foreach ($list_pending_send as $rows) {
            $unique_id = $this->encryptString($rows->id);
            dispatch(new SendEmailJob($unique_id));
        }

        return response()->json(["This week unsend email has been added to queue"]);

        // dd($list_pending_send);
    }

    public function resend_unsend_this_month_email()
    {
        $mailQueueRepo = new MailQueueRepository();
        $list_pending_send = $mailQueueRepo->getThisMonthUnsendMailQueue();


        foreach ($list_pending_send as $rows) {
            $unique_id = $this->encryptString($rows->id);
            dispatch(new SendEmailJob($unique_id));
        }

        return response()->json(["This month unsend email has been added to queue"]);

        // dd($list_pending_send);
    }

    public function encrypt($id)
    {
        dd( $this->encryptString($id) );
    }

    public function decrypt($string)
    {
        dd( $this->decryptString($string) );
    }

    public function test_email()
    {
        dd( $this->sendMail("raw_text", "tester6089@gmail.com", "test", "Hello World"));
    }
}
