<?php

namespace App\Http\Controllers;

use App\Models\SmtpMails;
use App\Repositories\SmtpMailsRepository;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SmtpMailController extends Controller
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
                $actions[] = SmtpMails::canShow() ? "<a class='btn btn-xs btn-primary' href='".route('mail-manager.smtp-setting.show', ['smtp_setting' => $enc_id])."'>Papar</a>" : '';
                $actions[] = SmtpMails::canUpdate() ? "<a class='btn btn-xs btn-warning' href='".route('mail-manager.smtp-setting.edit', ['smtp_setting' => $enc_id])."'>Kemaskini</a>" : '';
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

        return view('smtp-mail.index');
    }

    public function show($id)
    {
        $true_id = $this->decryptString($id);

        $smtpMailRepo = new SmtpMailsRepository();
        $data = $smtpMailRepo->readSmtpMails($true_id);

        $data->enc_id = $id;

        if (!$data->canShow()) {
            return $this->_access_denied();
        }

        return view('smtp-mail.show', compact('data'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return $this->_ajax_denied();
        }
        if (!SmtpMails::canCreate()) {
            return $this->_access_denied();
        }
        return view('smtp-mail.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mail_server' => 'required|max:255',
            'mail_port' => 'required|integer|max:65535',
            'mail_crypto' => 'required|integer',
            'mail_username' => 'required|unique:smtp_mails|string',
            // 'mail_password' => 'required|string',
            'mail_message_ratelimit' => 'required|integer|min:1',
        ]);

        if (!SmtpMails::canCreate()) {
            return $this->_access_denied();
        }

        $new_smtp_mail = array(
            "mail_server" => $request->post('mail_server'),
            "mail_port" => $request->post('mail_port'),
            "mail_crypto" => $request->post('mail_crypto'),
            "mail_username" => $request->post('mail_username'),
            "mail_password" => $this->encryptString($request->post('mail_password') ?? ""),
            "mail_message_ratelimit" => $request->post('mail_message_ratelimit'),
            "created_by" => Auth::id()
        );

        $smtpMailRepo = new SmtpMailsRepository();
        $smtpMailRepo->createSmtpMails($new_smtp_mail);
        
        return redirect()->route('mail-manager.smtp-setting.index')->with('success', $this->created_message);
    }

    public function edit($id)
    {
        $true_id = $this->decryptString($id);

        $smtpMailRepo = new SmtpMailsRepository();
        $data = $smtpMailRepo->readSmtpMails($true_id);
        
        $data["enc_id"] = $id;

        if (!SmtpMails::canUpdate()) {
            return $this->_access_denied();
        }
        
        return view('smtp-mail.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $true_id = $this->decryptString($id);

        $smtpMailRepo = new SmtpMailsRepository();
        $data = $smtpMailRepo->readSmtpMails($true_id);

        if (!$true_id || !SmtpMails::canUpdate())  {
            return $this->_access_denied();
        }

        $rules = array(
            'mail_port' => 'required|integer|max:65535',
            'mail_crypto' => 'required|integer',
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
            // $rules["mail_password"] = 'required';
        }

        $request->validate($rules);

        $update_smtp_mail = array(
            "mail_server" => $request->post('mail_server'),
            "mail_port" => $request->post('mail_port'),
            "mail_crypto" => $request->post('mail_crypto'),
            "mail_username" => $request->post('mail_username'),
            "mail_message_ratelimit" => $request->post('mail_message_ratelimit'),
            "updated_by" => Auth::id()
        );

        if ($decrypted_passwd != $request->mail_password && $request->mail_password != '********' )
        {
            $update_smtp_mail["mail_password"] = $this->encryptString($request->mail_password ?? "");
        }

        $smtpMailRepo->updateSmtpMails($true_id, $update_smtp_mail);
        
        return redirect()->route('mail-manager.smtp-setting.edit', ["smtp_setting" => $id])->with('success', $this->updated_message);
    }

    public function destroy($id)
    {
        $smtpMailRepo = new SmtpMailsRepository();

        $true_id = $this->decryptString($id);

        if (!$true_id || !SmtpMails::canDelete()) {
            return $this->_access_denied();
        }
        
        $update_del_smtp_mail["deleted_by"] = Auth::id();
        
        $smtpMailRepo->updateSmtpMails($true_id, $update_del_smtp_mail);

        $smtpMailRepo->deleteSmtpMails($true_id);

        return redirect()->route('mail-manager.smtp-setting.index')->with('success', $this->deleted_message);
    }
}
