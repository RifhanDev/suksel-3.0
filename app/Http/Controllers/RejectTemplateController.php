<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RejectTemplate;
use Illuminate\Http\Request;
use Datatables;
use Former;

class RejectTemplateController extends Controller
{
    public function index(Request $request)
    {
        if (!RejectTemplate::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {

            $templates = RejectTemplate::select([
                'id',
                'title',
                'content',
                'applicable_0',
                'applicable_1',
                'applicable_2',
            ]);

            // dd($templates);
            return Datatables::of($templates)
                ->editColumn('content', function ($template) {
                    return (strlen($template->content) > 70) ? substr($template->content, 0, 70) . '...' : $template->content;
                })
                ->addColumn('applicable', function ($template) {
                    $applicable = null;
                    $columns = ['applicable_0', 'applicable_1', 'applicable_2'];
                    $loop = 0;
                    foreach ($columns as $column) {
                        if ($template->{$column}) {
                            $col = RejectTemplate::applicableDescription($loop);
                            (!isset($applicable)) ? $applicable .= $col : $applicable .= ', ' . $col;
                        }
                        $loop++;
                    }

                    return $applicable;
                })
                ->orderColumn('applicable', function ($query, $order) {
                    $query->orderBy('applicable_0', $order)->orderBy('applicable_1', $order)->orderBy('applicable_2', $order);
                })
                ->addColumn('actions', function ($template) {
                    $actions   = [];
                    $actions[] = '<div class="btn-group">';
                    $actions[] = $template->canUpdate() ? link_to_action('RejectTemplateController@edit', 'Kemaskini', $template->id, ['class' => 'btn btn-xs btn-primary']) : '';
                    $actions[] = $template->canDelete() ? Former::open(action('RejectTemplateController@destroy', $template->id))->class('form-inline')
                        . Former::hidden('_method', 'DELETE')
                        . '<button type="button" class="btn btn-xs btn-danger confirm-delete">Delete</button>'
                        . Former::close() : '';
                    $actions[] = $template->canShow() ? link_to_action('RejectTemplateController@show', 'Lihat', $template->id, ['class' => 'btn btn-xs btn-success']) : '';
                    $actions[] = '</div>';
                    return implode(' ', $actions);
                })
                ->removeColumn('id')
                ->rawColumns(['title', 'content', 'applicable', 'actions'])
                ->make();
        }

        return view('reject-template.index');
    }

    public function show(Request $request, $id)
    {

        $template = RejectTemplate::findOrFail($id);
        if (!$template->canShow()) {
            return $this->_access_denied();
        }
        if ($request->ajax()) {
            return response()->json($template);
        }
        return view('reject-template.show', compact('template'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return $this->_ajax_denied();
        }
        if (!RejectTemplate::canCreate()) {
            return $this->_access_denied();
        }
        return view('reject-template.create');
    }

    public function store(Request $request)
    {

        $data = $request->all();
        RejectTemplate::setRules('store');
        if (!RejectTemplate::canCreate()) {
            return $this->_access_denied();
        }
        $template = new RejectTemplate;
        $template->fill($data);
        if (!$template->save()) {
            return $this->_validation_error($template);
        }
        if ($request->ajax()) {
            return response()->json($template, 201);
        }
        return redirect('reject-template')->with('success', $this->created_message);
    }

    public function edit(Request $request, $id)
    {
        $template = RejectTemplate::findOrFail($id);
        if ($request->ajax()) {
            return $this->_ajax_denied();
        }
        if (!$template->canUpdate()) {
            return _access_denied();
        }
        return view('reject-template.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = RejectTemplate::findOrFail($id);
        RejectTemplate::setRules('update');
        
        $columns = ['applicable_0', 'applicable_1', 'applicable_2'];
        foreach ($columns as $column) {
            if (!isset($request->{$column})) {
                $request->request->add([$column => '0']);
            }
        }

        $data = $request->all();
        
        if (!$template->canUpdate()) {
            return $this->_access_denied();
        }
        if (!$template->update($data)) {
            return $this->_validation_error($template);
        }
        if ($request->ajax()) {
            return $template;
        }
        session()->forget('_old_input');
        return redirect('reject-template/' . $id)->with('success', $this->updated_message);
    }

    public function destroy(Request $request, $id)
    {
        $template = RejectTemplate::findOrFail($id);
        if (!$template->canDelete()) {
            return $this->_access_denied();
        }
        $template->delete();
        if ($request->ajax()) {
            return response()->json($this->deleted_message);
        }
        return redirect('reject-template')->with('success', $this->deleted_message);
    }
}
