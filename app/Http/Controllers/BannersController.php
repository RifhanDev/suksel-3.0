<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Datatables;
use App\Banner;

class BannersController extends Controller
{
	public function index(Request $request)
	{

		if (!Banner::canList())
			return $this->_access_denied();

		if ($request->ajax()) {
			$banners = Banner::select([
				'id',
				'title',
				'published',
				'created_at'
			]);

			return Datatables::of($banners)
				->editColumn('published', function ($banner) {
					return boolean_icon($banner->published);
				})
				->editColumn('created_at', function ($banner) {
					return Carbon::parse($banner->created_at)->format('j M Y');
				})
				->addColumn('actions', function ($banner) {

					$actions   = [];
					$actions[] = '<div class="btn-group btn-group-modern" role="group">';

					// Edit button - Blue/Primary
					$editIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>';
					$actions[] = link_to_route('banners.edit', $editIcon, $banner->id, ['class' => 'btn btn-sm btn-action btn-action-primary', 'title' => 'Kemaskini', 'data-bs-toggle' => 'tooltip']);

					// View button (if file exists) - Teal/Success
					if ($banner->file) {
						$viewIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>';
						$actions[] = '<a href="' . $banner->file->url . '/' . $banner->file->name . '" class="btn btn-sm btn-action btn-action-success" target="_blank" title="Lihat Banner" data-bs-toggle="tooltip">' . $viewIcon . '</a>';
					}

					// Publish/Unpublish button
					if ($banner->published) {
						$publishIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3l18 18" /><path d="M10.584 10.587a2 2 0 0 0 2.828 2.83" /><path d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c4 0 7.333 2.333 10 7c-.778 1.361 -1.612 2.524 -2.503 3.488m-2.14 1.861c-1.631 1.1 -3.415 1.651 -5.357 1.651c-4 0 -7.333 -2.333 -10 -7c1.369 -2.395 2.913 -4.175 4.632 -5.341" /></svg>';
						$actions[] = link_to_route('banners.publish', $publishIcon, $banner->id, ['class' => 'btn btn-sm btn-action btn-action-warning', 'title' => 'Batal Siar', 'data-bs-toggle' => 'tooltip']);
					} else {
						$publishIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>';
						$actions[] = link_to_route('banners.publish', $publishIcon, $banner->id, ['class' => 'btn btn-sm btn-action btn-action-info', 'title' => 'Siar', 'data-bs-toggle' => 'tooltip']);
					}

					$actions[] = '</div>';

					return implode(' ', $actions);
				})
				->removeColumn('id')
				->rawColumns(['title', 'published', 'created_at', 'actions'])
				->make();
		}

		return view('banners.index');
	}

	public function show($id)
	{

		$banner = Banner::findOrFail($id);

		if (!$banner->canShow())
			return $this->_access_denied();

		return view('banners.show', compact('banner'));
		// kiv no blade for banners.show
	}

	public function create()
	{

		if (!Banner::canCreate())
			return $this->_access_denied();
		$banner = new Banner;
		return view('banners.create', compact('banner'));
	}

	public function store(Request $request)
	{

		if (!Banner::canCreate())
			return $this->_access_denied();

		$data = $request->all();
		$banner = new Banner;
		$banner->fill($data);

		if (!$banner->save())
			return $this->_validation_error($banner);

		return redirect('banners')->with('success', $this->created_message);
	}

	public function edit($id)
	{

		$banner = Banner::findOrFail($id);
		if (!$banner->canUpdate())
			return _access_denied();
		return view('banners.edit', compact('banner'));
	}

	/**
	 * Update the specified notification in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{

		$banner = Banner::findOrFail($id);

		if (!$banner->canUpdate())
			return $this->_access_denied();

		$data = $request->all();

		if (!isset($data['published'])) $data['published'] = 0;

		if (!$banner->update($data))
			return $this->_validation_error($banner);

		return redirect('banners')->with('success', $this->updated_message);
	}

	public function publish($id)
	{

		$banner = Banner::findOrFail($id);
		if (!$banner->canUpdate())
			return $this->_access_denied();

		if ($banner->published) {
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

	public function __construct()
	{
		// parent::__construct();
		// View::share('controller', 'Banner');
	}
}
