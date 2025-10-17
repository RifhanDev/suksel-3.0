<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Help;
use App\HelpCategory;

class HelpsController extends Controller
{
	public function index() {
		$categories = HelpCategory::all();
		return view('helps.index', compact('categories'));
	}

	public function show($category_id) {
		$category = HelpCategory::findOrFail($category_id);
		$helps    = $category->helps;
		return view('helps.show', compact('category', 'helps'));
	}

	public function search(Request $request) {
		$query = strip_tags($request->q);

		if( strlen($query) == 0 )
			return redirect('helps');

		$helps = Help::where('question', 'LIKE', "%{$query}%")->orWhere('answer', 'LIKE', "%{$query}%")->get();

		return view('helps.search', compact('query', 'helps'));
	}

	public function create() {
		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		return view('helps.create');
	}

	public function store(Request $request) {
		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		Help::setRules('store');
		$data = $request->all();
		$help = new Help($data);
		$help->user_id = auth()->user()->id;
		
		if(!$help->save()) {
			return $this->_validation_error($help);
		}
		return redirect('helps/'.$help->category_id)->with('success', $this->created_message);
	}

	public function edit($id) {
		$help = Help::findOrFail($id);

		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		return view('helps.edit', compact('help'));
	}

	public function update(Request $request, $id) {
		$help = Help::findOrFail($id);

		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		Help::setRules('update');
		$data = $request->all();
		if(!$help->update($data)) {
			return $this->_validation_error($help);
		}
		session()->forget('_old_input');
		return redirect('helps/'.$help->category_id)->with('success', $this->updated_message);
	}

	public function destroy($id) {
		$help = Help::findOrFail($id);

		if(!auth()->user()->hasRole('Admin'))
			return $this->_access_denied();

		$category_id = $help->category_id;
		$help->delete();
		return redirect('helps/'.$category_id)->with('success', $this->deleted_message);
	}

	// public function __construct() {
	// 	parent::__construct();
	// 	$this->beforeFilter('auth', array('except' => ['index', 'show']));
	// 	Asset::push('css', 'form');
		
	// 	View::share('controller', 'Help');
	// }
}
