<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Former;
use App\HelpCategory;

class HelpCategoriesController extends Controller
{
	public function index(Request $request)
	{
		if($request->ajax()) {
			$categories = HelpCategory::with('helps');
			$categories	= $categories->select([
				'help_categories.id',
				'help_categories.name',
				'help_categories.description'
			]);
			return Datatables::of($categories)
				->editColumn('name', function($cat){
					return '<strong>' . $cat->name . '</strong><br><small>' . $cat->description . '</small>';
				})
				->addColumn('count', function($cat){
					return $cat->helps->count();
				})
				->addColumn('actions', function($cat){
                    $actions   = [];
                    $actions[] = link_to_route('helpcategories.edit', 'Kemaskini', $cat->id, ['class' => 'btn btn-xs btn-primary']);
                    $actions[] = Former::open(url('helpcategories/'.$cat->id))->class('form-inline') 
                        . Former::hidden('_method', 'DELETE')
                        . '<button type="button" class="btn btn-xs btn-danger confirm-delete">Padam</button>'
                        . Former::close();
                    return implode(' ', $actions);
                })
				->removeColumn('id')
				->removeColumn('description')
				->rawColumns(['name', 'count', 'actions'])
				->make();
		}

		return view('helpcategories.index');
	}

	public function show($id) {
		$category 	= HelpCategory::findOrFail($category_id);
		$helps		= $category->helps;
	}

	public function create() {
		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		return view('helpcategories.create');
	}

	public function store(Request $request) {
		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		HelpCategory::setRules('store');
		$data = $request->all();
		$help = new HelpCategory($data);
		
		if(!$help->save()) {
			return $this->_validation_error($help);
		}
		return redirect('helpcategories')->with('success', $this->created_message);
	}

	public function edit($id) {
		$category = HelpCategory::findOrFail($id);

		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		return view('helpcategories.edit', compact('category'));
	}

	public function update(Request $request, $id) {
		$category = HelpCategory::findOrFail($id);

		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		HelpCategory::setRules('update');
		$data = $request->all();
		if(!$category->update($data)) {
			return $this->_validation_error($category);
		}
		session()->remove('_old_input');
		return redirect('helpcategories')->with('success', $this->updated_message);
	}

	public function destroy($id) {
		$category = HelpCategory::findOrFail($id);

		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		$category->delete();
		return redirect('helpcategories')->with('success', $this->deleted_message);
	}

	public function __construct() {
		// parent::__construct();
		// Asset::push('css', 'form');
		
		// view()->share('controller', 'HelpCategory');
	}
}
