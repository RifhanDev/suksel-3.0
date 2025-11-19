<?php

namespace App\Http\Controllers;

use App\Models\FaqLog;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaqLogController extends Controller
{
    use Helper;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!FaqLog::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

			$faq_log_list = FaqLog::join('faq_categories', 'faq_logs.faq_category_id', '=', 'faq_categories.id')
                        ->join('faqs', 'faq_logs.faq_id', '=', 'faqs.id')
                        ->select(DB::raw("faq_logs.*, faq_categories.name, faqs.question"));

			$start 		= $request->start ?? 0;  						// Get starting point for data to be retrieved
			$length 	= $request->length ?? 10;						// Get how much data to be retrieved
			$draw 		= $request->draw ?? 1;							// Get original draw request
			$keyword 	= $request->get('search')["value"] ?? "";
			$keyword 	= Str::lower($keyword);

            $orderByColumn  = $request->get('order')[0]["column"] ?? "";
            $orderByDir     = $request->get('order')[0]["dir"] ?? "";


            if ($orderByColumn == 1)
            {
                $faq_log_list->orderBy('faq_categories.name', $orderByDir);
            }

            if ($orderByColumn == 2)
            {
                $faq_log_list->orderBy('faqs.question', $orderByDir);
            }

			$recordsTotal = (clone $faq_log_list)->count() ?? 0;


			$faq_log_list->where( function($q) use($keyword){
				$q->whereRaw("lower(faqs.question) like ?", '%'.$keyword.'%')
                ->orWhereRaw("lower(faq_categories.name) like ?", '%'.$keyword.'%');
			});
			
			$recordsFiltered = (clone $faq_log_list)->count() ?? 0;

			$results = $faq_log_list->offset($start)->limit($length)->get();

			$datatable_data = [];

            $i = 0;
			foreach ($results as $rows) {

                $enc_id = $this->encryptString($rows->id);

				$actions    = [];
                $actions[] = FaqLog::canShow() ? "<a class='btn btn-xs btn-primary' href='".route('chatbot-manager.chatlog.show', ['chatlog' => $enc_id])."'>Papar</a>" : '';
                $actions[] = FaqLog::canUpdate() ? "<a class='btn btn-xs btn-warning' href='".route('chatbot-manager.chatlog.edit', ['chatlog' => $enc_id])."'>Kemaskini</a>" : '';
                $actions[] = FaqLog::canDelete() ? "<button class='btn btn-xs btn-danger' onclick='popupDelete(".'"'.$enc_id.'"'.")'>Padam</button>" : '';
				
				$action_column = '<div class ="btn-group">' . implode(' ', $actions) . '</div>';

                $logged_response = $rows->user_response ?? "{}";
                $logged_response = json_decode($logged_response);

                
                $user_response = "";

                if ( isset($logged_response->require_input_attachment) )
                {
                    $user_response = 'Pengguna telah memuatnaik lampiran berikut :- <br/>'.'<a href="'.$logged_response->require_input_attachment.'" target="_blank"><img src="'.$logged_response->require_input_attachment.'" style="width:100px; height:100px;"></a>';
                }

                if ( isset($logged_response->require_input_text) )
                {
                    $user_response = "Pengguna telah melaporkan seperti berikut :- <br/>".$logged_response->require_input_text;
                }

				$datatable_data[] = array(
					"created_at" => $rows->created_at->format('d-m-Y'),
					"faq_category_name" => ($rows->name ?? ""),
					"question" => $rows->question,
					"chat_id" => $logged_response->chat_id,
					"user_response" => $user_response,
                    "id" => ++$i,
					// "actions" => $action_column,
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

        return view('faq-log.index');
    }

}
