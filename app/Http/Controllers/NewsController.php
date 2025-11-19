<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use DB;
use Str;
use Carbon\Carbon;
use App\News;
use App\Tender;
use App\Traits\Helper;
use App\Vendor;

class NewsController extends Controller
{
	use Helper;

	public function index(Request $request) {

		$news = News::whereNotNull('created_at');

		if($request->ajax()) {
			$news = News::with('agency')->orderBy('created_at', 'desc');

			if(!auth()->check() || !auth()->user()->ability(['Admin', 'Agency Admin', 'Agency User'], []))
				$news = $news->wherePublish(1);

			$news = $news->select([
				'id',
				'created_at',
				'organization_unit_id',
				'title',
				'notification',
			]);

			return Datatables::of($news)
			//$datatable = Datatables::of($news).

				->editColumn('created_at', function($news){
					return Carbon::parse($news->created_at)->format('j M Y');
				})
				->editColumn('organization_unit_id', function($news){
					return $news->agency->name;
				})
				->editColumn('title', function($news){
					$string = '';
					$string .= '<h4>' . $news->title . '</h4>';
					$string .= '<p>' . Str::words($news->notification, 20) . '</p>';
					return $string;
				})
				->addColumn('actions', function($news){
					return link_to_action('NewsController@show', 'Selanjutnya', $news->id, ['class' => 'btn btn-xs btn-primary']);
				})
				->removeColumn('id')
				//->removeColumn('notification')
				->rawColumns(['created_at', 'organization_unit_id', 'title', 'actions'])
				->make();

			//return $datatable;
		}
		
		return view('news.index', compact('news'));
	}

	public function show($id) {

		$news = News::findOrFail($id);

		if(!$news->canShow())
			return $this->_access_denied();

		return view('news.show', compact('news'));
	}

	public function create() {

		if(!News::canCreate())
			return $this->_access_denied();
		return view('news.create');
	}

	public function store(Request $request) {
		if(!News::canCreate())
			return $this->_access_denied();

		$data = $request->all();
		News::setRules('store');
		

		if(!auth()->user()->hasRole('Admin')) $data['organization_unit_id'] = auth()->user()->organization_unit_id;
		if(isset($data['tender_id']) && !Tender::find($data['tender_id'])) $data['tender_id'] = null;

		$data['publish'] = 1;
		$data['published_at'] = Carbon::now()->format('Y-m-d');

		$news = new News;
		$news->fill($data);

		if(!$news->save())
			return $this->_validation_error($news);

		if($news->tender)
		{
			$purchases = $news->tender->participants()->where('participate', 1)->pluck('vendor_id');
			$vendors = Vendor::whereIn('id', $purchases)->get();

			foreach($vendors as $vendor)
			{
				// Enable this if want to notify eligible vendor to get ralat news through email
				// Mail::send('tenders.emails.news', ['tender' => $news->tender, 'vendor' => $vendor, 'news' => $news], function($message) use($vendor) {
                // 	$message->to(trim($vendor->user->email));
                // 	$message->subject('Sistem Tender Online Selangor: Makluman / Ralat');
            	// });

				// dd($vendor->user->email);
				
				$to			= trim($vendor->user->email);
				$subject 	= 'Sistem Tender Online Selangor: Makluman / Ralat';
				$send_status = $this->sendMail("html", $to, $subject, "", "tenders.emails.news", ['tender' => $news->tender, 'vendor' => $vendor, 'news' => $news]);
				// dd($send_status);
         	}


		}

		if ($request->fromTenderRequest ?? 0 == "999")
		{
			$tender_id = $data['tender_id'] ?? 0;
			return redirect('tenders/'.$tender_id)->with('success', $this->created_message);
		}

		return redirect('news/'.$news->id)->with('success', $this->created_message);
	}

	public function edit($id) {
		$news = News::findOrFail($id);
		if(!$news->canUpdate())
			return _access_denied();
		return view('news.edit', compact('news'));
	}

	/**
	 * Update the specified notification in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id) {

		$news = News::findOrFail($id);
	
		if(!$news->canUpdate())
			return $this->_access_denied();

		$data = $request->all();
		News::setRules('update');

		if(!auth()->user()->hasRole('Admin')) $data['organization_unit_id'] = auth()->user()->organization_unit_id;
		if(isset($data['tender_id']) && !Tender::find($data['tender_id'])) $data['tender_id'] = null;

		if(!$news->update($data))
			return $this->_validation_error($news);

		return redirect('news/'.$id)->with('success', $this->updated_message);
	}

	public function destroy($id) {

		$news = News::findOrFail($id);
		if(!$news->canDelete())
			return $this->_access_denied();

		$news->delete();
		return redirect('news')->with('success', $this->deleted_message);
	}

	public function publish($id) {

		$news = News::findOrFail($id);
		if(!$news->canUpdate())
			return $this->_access_denied();

		if($news->publish) {
			$news->publish = 0;
			$news->published_at = null;
		} else {
			$news->publish = 1;
			$news->published_at = Carbon::now()->format('Y-m-d');
		}

		$news->save();
		return redirect('news/'.$news->id)->with('success', $this->updated_message);
	}
	

	/**
	 * Constructor
	 */

	// public function __construct()
	// {
	// 	parent::__construct();
	// 	View::share('controller', 'News');
	// }
}
