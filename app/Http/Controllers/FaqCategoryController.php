<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;
use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FaqCategoryController extends Controller
{
    use Helper;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!FaqCategory::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

			$faq_category_list = FaqCategory::select("*");

			$start 		= $request->start ?? 0;  						// Get starting point for data to be retrieved
			$length 	= $request->length ?? 10;						// Get how much data to be retrieved
			$draw 		= $request->draw ?? 1;							// Get original draw request
			$keyword 	= $request->get('search')["value"] ?? "";
			$keyword 	= Str::lower($keyword);

            $orderByColumn  = $request->get('order')[0]["column"] ?? "";
            $orderByDir     = $request->get('order')[0]["dir"] ?? "";


            if ($orderByColumn == 1)
            {
                $faq_category_list->orderBy('name', $orderByDir);
            }

			$recordsTotal = (clone $faq_category_list)->count() ?? 0;


			$faq_category_list->where( function($q) use($keyword){
				$q->whereRaw("lower(name) like ?", '%'.$keyword.'%');
			});
			
			$recordsFiltered = (clone $faq_category_list)->count() ?? 0;

			$results = $faq_category_list->offset($start)->limit($length)->get();

			$datatable_data = [];

            $i = 0;
			foreach ($results as $rows) {

                $enc_id = $this->encryptString($rows->id);

				$actions    = [];
                $actions[] = FaqCategory::canShow() ? "<a class='btn btn-xs btn-primary' href='".route('chatbot-manager.category.show', ['category' => $enc_id])."'>Papar</a>" : '';
                $actions[] = FaqCategory::canUpdate() ? "<a class='btn btn-xs btn-warning' href='".route('chatbot-manager.category.edit', ['category' => $enc_id])."'>Kemaskini</a>" : '';
                $actions[] = FaqCategory::canDelete() ? "<button class='btn btn-xs btn-danger' onclick='popupDelete(".'"'.$enc_id.'"'.")'>Padam</button>" : '';
				
				$action_column = '<div class ="btn-group">' . implode(' ', $actions) . '</div>';

				$datatable_data[] = array(
					"created_at" => $rows->created_at->format('d-m-Y'),
					"name" => $rows->name,
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

        return view('faq-category.index');
    }

    public function show($id)
    {
        $true_id = $this->decryptString($id);
        $data = FaqCategory::findOrFail($true_id);
        $data->enc_id = $id;

        if (!$data->canShow()) {
            return $this->_access_denied();
        }

        return view('faq-category.show', compact('data'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return $this->_ajax_denied();
        }
        if (!FaqCategory::canCreate()) {
            return $this->_access_denied();
        }
        return view('faq-category.create');
    }

    public function store(Request $request)
    {
        if (!FaqCategory::canCreate()) {
            return $this->_access_denied();
        }

        $rules = array(
            'name' => 'required|unique:faq_categories|string',
            // 'show_none_btn' => 'required|integer|min:1',
        );

        $request->validate($rules);

        $new_faq_category = array(
            "name" => $request->post('name'),
            "show_none_btn" => ($request->post('show_none_btn') ?? 0),
            "created_by" => Auth::id()
        );

        FaqCategory::create($new_faq_category);
        return redirect()->route('chatbot-manager.category.index')->with('success', $this->created_message);
    }

    public function edit($id)
    {
        $true_id = $this->decryptString($id);

        $data = FaqCategory::findOrFail($true_id);
        $data["enc_id"] = $id;

        if (!FaqCategory::canUpdate()) {
            return $this->_access_denied();
        }
        
        return view('faq-category.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $true_id = $this->decryptString($id);

        if (!$true_id || !FaqCategory::canUpdate())  {
            return $this->_access_denied();
        }

        $rules = array(
            'name' => 'required|unique:faq_categories|string',
            // 'show_none_btn' => 'required|integer|min:1',
        );

        $faq_category = FaqCategory::find($true_id);

        if($faq_category->name == $request->name)
        {
            unset($rules['name']);
        }

        $request->validate($rules);

        $update_faq_category = array(
            "name" => $request->post('name'),
            "show_none_btn" => $request->post('show_none_btn'),
            "updated_by" => Auth::id()
        );

        FaqCategory::findOrFail($true_id)->update($update_faq_category);
        return redirect()->route('chatbot-manager.category.edit', ["category" => $id])->with('success', $this->updated_message);
    }

    public function destroy($id)
    {
        $true_id = $this->decryptString($id);

        if (!$true_id || !FaqCategory::canDelete()) {
            return $this->_access_denied();
        }
        
        $del_faq_category["deleted_by"] = Auth::id();
        FaqCategory::findOrFail($true_id)->update($del_faq_category);

        FaqCategory::destroy($true_id);

        return redirect()->route('chatbot-manager.category.index')->with('success', $this->deleted_message);
    }
}
