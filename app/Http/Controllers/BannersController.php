<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Carbon\Carbon;
use Datatables;
use App\Banner;

class BannersController extends Controller
{
	public function index(Request $request) {

		if(!Banner::canList())
			return $this->_access_denied();

		if($request->ajax()) {
			$banners = Banner::select([
				'id',
				'title',
				'published',
				'created_at'
			]);

			return Datatables::of($banners)
				->editColumn('published', function($banner){
					return boolean_icon($banner->published);
				})
				->editColumn('created_at', function($banner){
					return Carbon::parse($banner->created_at)->format('j M Y');
				})
				->addColumn('actions', function($banner){

						$actions   = [];
						$actions[] = '<div class="btn-group">';
						$actions[] = link_to_route('banners.edit', 'Kemaskini', $banner->id, ['class' => 'btn btn-xs btn-primary']);
						$actions[] = link_to_route('banners.publish', $banner->published ? 'Batal Siar' : 'Siar', $banner->id, ['class' => 'btn btn-xs btn-danger']);
						$actions[] = '<a href="' . $banner->file->url . '/' . $banner->file->name . '" class="btn btn-xs btn-success btn-show-banner" target="_blank">Lihat Banner</a>';
						$actions[] = '</div>';
						return implode(' ', $actions);
				})
				->removeColumn('id')
				->rawColumns(['title', 'published', 'created_at', 'actions'])
				->make();
		}

		return view('banners.index');
	}

	public function show($id) {

		$banner = Banner::findOrFail($id);

		if(!$banner->canShow())
			return $this->_access_denied();

		return view('banners.show', compact('banner'));
		// kiv no blade for banners.show
	}

	public function create() {

		if(!Banner::canCreate())
			return $this->_access_denied();
		$banner = new Banner;
		return view('banners.create', compact('banner'));
	}

	public function store(Request $request) {

		if(!Banner::canCreate())
			return $this->_access_denied();

		$data = $request->all();
		$banner = new Banner;
		$banner->fill($data);

		if(!$banner->save())
			return $this->_validation_error($banner);

		return redirect('banners')->with('success', $this->created_message);
	}

	public function edit($id) {

		$banner = Banner::findOrFail($id);
		if(!$banner->canUpdate())
			return _access_denied();
		return view('banners.edit', compact('banner'));
	}

	/**
	 * Update the specified notification in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id) {

		$banner = Banner::findOrFail($id);
	
		if(!$banner->canUpdate())
			return $this->_access_denied();

		$data = $request->all();

		if(!isset($data['published'])) $data['published'] = 0;

		if(!$banner->update($data))
			return $this->_validation_error($banner);

		return redirect('banners')->with('success', $this->updated_message);
	}

	public function publish($id) {

		$banner = Banner::findOrFail($id);
		if(!$banner->canUpdate())
			return $this->_access_denied();

		if($banner->published) {
			$banner->published = 0;
		} else {
			$banner->published = 1;
		}

		$banner->save();
		return redirect('banners')->with('success', $this->updated_message);
	}
	

	/**
	 * Constructor
	 */

	public function __construct() {
		// parent::__construct();
		// View::share('controller', 'Banner');
	}
}
