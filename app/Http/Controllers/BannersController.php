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
				'created_at',
				'start',
				'end',
				'start_time',
				'end_time',
			]);

			return Datatables::of($banners)
				->editColumn('start', function ($banner) {
					return '<div class="text-center">' . $this->formatBannerSchedule($banner->start, $banner->start_time) . '</div>';
				})
				->editColumn('end', function ($banner) {
					return '<div class="text-center">' . $this->formatBannerSchedule($banner->end, $banner->end_time) . '</div>';
				})
				->editColumn('published', function ($banner) {
					return '<div class="text-center">' . boolean_icon($banner->published) . '</div>';
				})
				->editColumn('created_at', function ($banner) {
					return '<div class="text-center">' . Carbon::parse($banner->created_at)->format('j M Y') . '</div>';
				})
				->addColumn('actions', function ($banner) {

					$actions   = [];

					$actions[] = link_to_route('banners.edit', 'Kemaskini', $banner->id, ['class' => 'btn btn-sm btn-warning rounded-8 px-3']);

					if ($banner->file) {
						$actions[] = '<a href="' . $banner->file->url . '/' . $banner->file->name . '" class="btn btn-sm btn-info rounded-8 px-3" target="_blank">Lihat</a>';
					}

					if ($banner->published) {
						$actions[] = link_to_route('banners.publish', 'Batal Siar', $banner->id, ['class' => 'btn btn-sm btn-danger rounded-8 px-3']);
					} else {
						$actions[] = link_to_route('banners.publish', 'Siar', $banner->id, ['class' => 'btn btn-sm btn-success rounded-8 px-3']);
					}

					return '<div class="d-flex gap-2 flex-wrap justify-content-center">' . implode('', $actions) . '</div>';
				})
				->removeColumn('id')
				->rawColumns(['title', 'start', 'end', 'published', 'created_at', 'actions'])
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

		$data = $this->normalizeBannerSchedule($data);

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

		$data = $this->normalizeBannerSchedule($data);

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

	protected function normalizeBannerSchedule(array $data): array
	{
		if (! empty($data['start'])) {
			$data['start'] = date('Y-m-d', strtotime($data['start']));
		} else {
			$data['start'] = null;
		}

		if (! empty($data['end'])) {
			$data['end'] = date('Y-m-d', strtotime($data['end']));
		} else {
			$data['end'] = null;
		}

		$data['start_time'] = $this->normalizeTime($data['start_time'] ?? null);
		$data['end_time'] = $this->normalizeTime($data['end_time'] ?? null);

		return $data;
	}

	protected function normalizeTime(?string $value): ?string
	{
		$value = trim((string) $value);
		if ($value === '') {
			return null;
		}

		try {
			return Carbon::parse($value)->format('H:i:s');
		} catch (\Throwable) {
			return null;
		}
	}

	protected function formatBannerSchedule($date, $time): string
	{
		if (! $date) {
			return '-';
		}

		$label = Carbon::parse($date)->format('j M Y');
		if ($time) {
			$label .= ' ' . Carbon::parse($time)->format('H:i');
		}

		return $label;
	}
}
