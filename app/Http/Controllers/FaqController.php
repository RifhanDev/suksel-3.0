<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    use Helper;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Faq::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

			// $faq_list = Faq::with('FaqCategory')->select("*");
			$faq_list = Faq::join('faq_categories', 'faqs.faq_category_id', '=', 'faq_categories.id')->select(DB::raw("faqs.*, faq_categories.name"));

			$start 		= $request->start ?? 0;  						// Get starting point for data to be retrieved
			$length 	= $request->length ?? 10;						// Get how much data to be retrieved
			$draw 		= $request->draw ?? 1;							// Get original draw request
			$keyword 	= $request->get('search')["value"] ?? "";
			$keyword 	= Str::lower($keyword);

            $orderByColumn  = $request->get('order')[0]["column"] ?? "";
            $orderByDir     = $request->get('order')[0]["dir"] ?? "";


            if ($orderByColumn == 1)
            {
                $faq_list->orderBy('question', $orderByDir);
            }

            if ($orderByColumn == 2)
            {
                $faq_list->orderBy('answer', $orderByDir);
            }

            if ($orderByColumn == 3)
            {
                $faq_list->orderBy('faq_categories.name', $orderByDir);
            }

			$recordsTotal = (clone $faq_list)->count() ?? 0;


			$faq_list->where( function($q) use($keyword){
				$q->whereRaw("lower(question) like ?", '%'.$keyword.'%')
                ->orWhereRaw("lower(answer) like ?", '%'.$keyword.'%')
                ->orWhereRaw("lower(faq_categories.name) like ?", '%'.$keyword.'%');
			});
			
			$recordsFiltered = (clone $faq_list)->count() ?? 0;

			$results = $faq_list->offset($start)->limit($length)->get();

			$datatable_data = [];

            $i = 0;
			foreach ($results as $rows) {

                $enc_id = $this->encryptString($rows->id);

				$actions    = [];
                $actions[] = Faq::canShow() ? "<a class='btn btn-xs btn-primary' href='".route('chatbot-manager.question.show', ['question' => $enc_id])."'>Papar</a>" : '';
                $actions[] = Faq::canUpdate() ? "<a class='btn btn-xs btn-warning' href='".route('chatbot-manager.question.edit', ['question' => $enc_id])."'>Kemaskini</a>" : '';
                $actions[] = Faq::canDelete() ? "<button class='btn btn-xs btn-danger' onclick='popupDelete(".'"'.$enc_id.'"'.")'>Padam</button>" : '';
				
				$action_column = '<div class ="btn-group">' . implode(' ', $actions) . '</div>';

				$datatable_data[] = array(
					"created_at" => $rows->created_at->format('d-m-Y'),
					"question" => $rows->question,
					"answer" => $rows->answer,
					"faq_category_name" => ($rows->name ?? ""),
                    "id" => ++$i,
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

        return view('faq.index');
    }

    public function show($id)
    {
        $faq_categories = FaqCategory::all();

        $true_id = $this->decryptString($id);
        $data = Faq::findOrFail($true_id);
        $data->enc_id = $id;

        if (!$data->canShow()) {
            return $this->_access_denied();
        }

        return view('faq.show', compact('data', 'faq_categories'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return $this->_ajax_denied();
        }
        if (!Faq::canCreate()) {
            return $this->_access_denied();
        }

        $faq_categories = FaqCategory::all();
        return view('faq.create', compact('faq_categories'));
    }

    public function store(Request $request)
    {
        if (!Faq::canCreate()) {
            return $this->_access_denied();
        }

        $rules = array(
            'question' => 'required|unique:faqs|string',
            'answer' => 'required|string',
            'faq_category_id' => 'required|integer|min:1',
            'require_input_text' => 'prohibits:require_input_attachment|integer|min:1',
            'require_input_attachment' => 'prohibits:require_input_text|integer|min:1'
        );

        $custom_err_msg = array(
            'require_input_text.required_without' => 'Sila pilih salah satu sahaja',
            'require_input_text.prohibits' => 'Sila pilih salah satu sahaja',
            'require_input_attachment.required_without' => 'Sila pilih salah satu sahaja',
            'require_input_attachment.prohibits' => 'Sila pilih salah satu sahaja'
        );

        $request->validate($rules, $custom_err_msg);

        $new_faq = array(
            "question" => $request->post('question'),
            "answer" => $request->post('answer'),
            "faq_category_id" => $request->post('faq_category_id'),
            "require_input_attachment" => ($request->post('require_input_attachment') ?? 0),
            "require_input_text" => ($request->post('require_input_text') ?? 0),
            "created_by" => Auth::id()
        );

        Faq::create($new_faq);
        return redirect()->route('chatbot-manager.question.index')->with('success', $this->created_message);
    }

    public function edit($id)
    {
        $true_id = $this->decryptString($id);

        $data = Faq::findOrFail($true_id);
        $data["enc_id"] = $id;

        if (!Faq::canUpdate()) {
            return $this->_access_denied();
        }

        $faq_categories = FaqCategory::all();
        return view('faq.edit', compact('data', 'faq_categories'));
    }

    public function update(Request $request, $id)
    {
        $true_id = $this->decryptString($id);

        if (!$true_id || !Faq::canUpdate())  {
            return $this->_access_denied();
        }

        $rules = array(
            'question' => 'required|unique:faqs|string',
            'answer' => 'required|string',
            'faq_category_id' => 'required|integer|min:1',
            'require_input_text' => 'prohibits:require_input_attachment|integer|min:1',
            'require_input_attachment' => 'prohibits:require_input_text|integer|min:1'
        );

        $custom_err_msg = array(
            'require_input_text.required_without' => 'Sila pilih salah satu sahaja',
            'require_input_text.prohibits' => 'Sila pilih salah satu sahaja',
            'require_input_attachment.required_without' => 'Sila pilih salah satu sahaja',
            'require_input_attachment.prohibits' => 'Sila pilih salah satu sahaja'
        );

        $faq = Faq::find($true_id);

        if($faq->question == $request->question)
        {
            unset($rules['question']);
        }

        $request->validate($rules, $custom_err_msg );

        $update_faq_category = array(
            "question" => $request->post('question'),
            "answer" => $request->post('answer'),
            "faq_category_id" => $request->post('faq_category_id'),
            "require_input_attachment" => ($request->post('require_input_attachment') ?? 0),
            "require_input_text" => ($request->post('require_input_text') ?? 0),
            "updated_by" => Auth::id()
        );

        Faq::findOrFail($true_id)->update($update_faq_category);
        return redirect()->route('chatbot-manager.question.edit', ["question" => $id])->with('success', $this->updated_message);
    }

    public function destroy($id)
    {
        $true_id = $this->decryptString($id);

        if (!$true_id || !Faq::canDelete()) {
            return $this->_access_denied();
        }
        
        $del_faq["deleted_by"] = Auth::id();
        Faq::findOrFail($true_id)->update($del_faq);

        Faq::destroy($true_id);

        return redirect()->route('chatbot-manager.question.index')->with('success', $this->deleted_message);
    }
}
