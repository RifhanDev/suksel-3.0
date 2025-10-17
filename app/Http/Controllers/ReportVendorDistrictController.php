<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Excel;
use App\Exports\VendorDistrict;
use App\Vendor;

class ReportVendorDistrictController extends Controller
{
	public function index() {
       
      return view('reports.vendor.district.index');
   }

   public function view(Request $request) {

		$district   = $request->input('district', []);
		$districtOpts = array_keys(array_merge(['all' => 'Semua'] + Vendor::$districts));
		
		$validator = Validator::make([
			'district'  => $district
		],[
			'district'  => 'required|in:' . implode(',', $districtOpts)
		]);
		$validator->setAttributeNames([
			'district'  => 'Daerah'
		]);
		
		if($validator->fails()) {
			$title = 'Senarai Syarikat Mengikut Daerah';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
		
		$vendors = Vendor::orderBy('name', 'asc');
		
		if($district != 'all') {
			if($district > 0) {
				$vendors    = $vendors->where('district_id', $district);
			} else {
				$vendors    = $vendors->whereNull('district_id')->orWhere('district_id', '=', 0);
			}
		}
		
		$vendors = $vendors->paginate('100');
		
		return view('reports.vendor.district.view', compact('vendors', 'district'));
	}

   public function excel(Request $request) {
        	$district = $request->input('district', []);
        	$vendors    = Vendor::orderBy('name', 'asc');

        	if($district != 'all') {
            if($district > 0) {
                	$vendors = $vendors->where('district_id', $district);
            } else {
                	$vendors = $vendors->whereNull('district_id')->orWhere('district_id', '=', 0);
            }
        	}

        	$vendors = $vendors->get();

        	return Excel::download(new VendorDistrict($district, $vendors), 'Senarai Syarikat Mengikut Daerah.xlsx');
   }

}
