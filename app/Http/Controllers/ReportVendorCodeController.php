<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Exports\VendorCodes;
use App\Vendor;
use App\Code;
use App\VendorCode;

class ReportVendorCodeController extends Controller
{
	public function index() {

      return view('reports.vendor.code.index');
   }

   public function excel(Request $request) {

     	$mof_codes  = $request->input('mof_codes', []);
     	$mof_empty  = false;
     	if(count($mof_codes) == 1 && empty($mof_codes[0]['codes'])) $mof_empty = true;

     	$cidb_codes = $request->input('cidb_codes');
     	$cidb_empty = false;
     	if(count($cidb_codes) == 1 && empty($cidb_codes[0]['codes'])) $cidb_empty = true;

     	$cidb_grades    = $request->input('cidb_grades', []);
     	$grade_empty    = false;
     	if(count($cidb_grades) == 0) $grade_empty;

     	$cidb_grades = Code::whereIn('id', $cidb_grades)->get();

     	$vendor_ids = [];

     	if(count($mof_codes) > 0 && !$mof_empty) {
         $count = 1;
         foreach($mof_codes as $code) {
          	if(!isset($code['codes'])) continue;
            if ($code['inner_rule'] == 'or')
                $codes_m = Vendor::withCodes($code['codes'], 'mof', $code['inner_rule'])->toArray();
            else
                $codes_m = Vendor::withCodes($code['codes'], 'mof', $code['inner_rule']);

          	if(count($vendor_ids) == 0 ) {
              	$vendor_ids = $codes_m;
              	continue;
          	} else {
              	if($count > 1) {
                  if($cidb_codes[$count-1]['join_rule'] == 'and') {
                     $vendor_ids = array_intersect($codes_m, $vendor_ids);
                  }

                  if($cidb_codes[$count-1]['join_rule'] == 'or') {
                     $vendor_ids = array_merge($vendor_ids, $codes_m);
                  }
              	}
              	else {
                  $vendor_ids = array_intersect($codes_m, $vendor_ids);
              	}
          	}
          	$count++;
         }
     	}

     	if(count($cidb_grades) > 0 && !$grade_empty) {
         $cidb_grade_vendor_ids = VendorCode::whereIn('code_id', $cidb_grades->pluck('id'))->groupBy('vendor_id')->pluck('vendor_id')->toArray();

         if(count($cidb_grade_vendor_ids) > 0) {
          	if(count($vendor_ids) == 0 ) {
              	$vendor_ids = $cidb_grade_vendor_ids;
          	} else {
              	$vendor_ids = array_intersect($cidb_grade_vendor_ids, $vendor_ids);
          	}
         }
     	}

     	if(count($cidb_codes) > 0 && !$cidb_empty) {
            $count = 1;
            foreach($cidb_codes as $code) {
                if(!isset($code['codes'])) continue;
                if ($code['inner_rule'] == 'or')
                    $codes_c = Vendor::withCodes($code['codes'], 'cidb', $code['inner_rule'])->toArray();
                else
                    $codes_c = Vendor::withCodes($code['codes'], 'cidb', $code['inner_rule']);

                if(count($vendor_ids) == 0 ) {
                    $vendor_ids = $codes_c;
                    continue;
                } else {
                    if($count > 1) {
                        if($cidb_codes[$count-1]['join_rule'] == 'and') {
                            $vendor_ids = array_intersect($codes_c, $vendor_ids);
                        }

                        if($cidb_codes[$count-1]['join_rule'] == 'or') {
                            $vendor_ids = array_merge($vendor_ids, $codes_c);
                        }
                    }
                    else {
                        $vendor_ids = array_intersect($codes_c, $vendor_ids);
                    }
                }

                $count++;
            }
        }

      $vendors = Vendor::whereIn('id', $vendor_ids)->orderBy('name');

      $district_id  = $request->input('district_id', null);

      if($district_id > 0) {
         $district_id = (int) $district_id;

         if($district_id === 0) {
				$vendors  = $vendors->whereNull('district_id');
				$district = Vendor::$districts['0'];
         }
         else if($district_id > 0) {
				$vendors  = $vendors->where('district_id', $district_id);
				$district = Vendor::$districts[$district_id];
         }
      }
      else {
         $district = null;
      }

      $vendors = $vendors->get();

     	return Excel::download(new VendorCodes($vendors), 'Senarai Syarikat Mengikut Kod Bidang.xlsx');
   }

   public function view(Request $request) {
     
		$mof_codes  = $request->input('mof_codes', []);
		$mof_empty  = false;
		if(count($mof_codes) == 1 && empty($mof_codes[0]['codes'])) $mof_empty = true;
		
		$cidb_codes = $request->input('cidb_codes');
		$cidb_empty = false;
		if(count($cidb_codes) == 1 && empty($cidb_codes[0]['codes'])) $cidb_empty = true;
		
		$cidb_grades    = $request->input('cidb_grades', []);
		$grade_empty    = false;
		if(count($cidb_grades) == 0) $grade_empty;
		
		if($mof_empty && $cidb_empty && $grade_empty) {
			$title = 'Senarai Syarikat Mengikut Kod Bidang';
			$error = 'Sila pastikan pilihan medan adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}

		$cidb_grades = Code::whereIn('id', $cidb_grades)->get();
		
		$vendor_ids = [];

		if(count($mof_codes) > 0 && !$mof_empty) {

			$count = 1;
			foreach($mof_codes as $code) {


				if(!isset($code['codes'])) continue;

                if ($code['inner_rule'] == 'or')
                    $codes_m = Vendor::withCodes($code['codes'], 'mof', $code['inner_rule'])->toArray();
                else
                    $codes_m = Vendor::withCodes($code['codes'], 'mof', $code['inner_rule']);
					
			
				if(count($vendor_ids) == 0 ) {
					$vendor_ids = $codes_m;
					continue;
				} else {
					if($count > 1) {
						if($cidb_codes[$count-1]['join_rule'] == 'and') {
							$vendor_ids = array_intersect($codes_m, $vendor_ids);
						}
			
						if($cidb_codes[$count-1]['join_rule'] == 'or') {
							$vendor_ids = array_merge($vendor_ids, $codes_m);
						}
					}
					else {
						$vendor_ids = array_intersect($codes_m, $vendor_ids);

                        
					}
				}
			
				$count++;
			}
		}

        if(count($cidb_grades) > 0 && !$grade_empty) {
            $cidb_grade_vendor_ids = VendorCode::whereIn('code_id', $cidb_grades->pluck('id'))->groupBy('vendor_id')->pluck('vendor_id')->toArray();

            if(count($cidb_grade_vendor_ids) > 0) {
                if(count($vendor_ids) == 0 ) {
                    $vendor_ids = $cidb_grade_vendor_ids;
                } else {
                        $vendor_ids = array_intersect($cidb_grade_vendor_ids, $vendor_ids);
                }
            }
        }

     	if(count($cidb_codes) > 0 && !$cidb_empty) {
         $count = 1;
         foreach($cidb_codes as $code) {
            if(!isset($code['codes'])) continue;

            if ($code['inner_rule'] == 'or')
                $codes_c = Vendor::withCodes($code['codes'], 'cidb', $code['inner_rule'])->toArray();
            else
                $codes_c = Vendor::withCodes($code['codes'], 'cidb', $code['inner_rule']);


            if(count($vendor_ids) == 0 ) {
              	$vendor_ids = $codes_c;
              	continue;
            } else {
              	if($count > 1) {
                  if($cidb_codes[$count-1]['join_rule'] == 'and') {
                     $vendor_ids = array_intersect($codes_c, $vendor_ids);
                  }

                  if($cidb_codes[$count-1]['join_rule'] == 'or') {
                     $vendor_ids = array_merge($vendor_ids, $codes_c);
                  }
              	}
              	else {
                  $vendor_ids = array_intersect($codes_c, $vendor_ids);
              	}
            }

            $count++;
         }
     	}

     	$vendors = Vendor::whereIn('id', $vendor_ids)->orderBy('name');

     	$district_id  = $request->input('district_id', null);

        if($district_id > 0) {
            $district_id = (int) $district_id;

            if($district_id === 0) {
                $vendors    = $vendors->whereNull('district_id');
                $district   = Vendor::$districts['0'];
            }
            else if($district_id > 0) {
                $vendors    = $vendors->where('district_id', $district_id);
                $district   = Vendor::$districts[$district_id];
            }
     	}
     	else {
            $district = null;
     	}

        $vendors = $vendors->get();

        return view('reports.vendor.code.view', compact('mof_empty', 'cidb_empty', 'grade_empty', 'mof_codes', 'cidb_codes', 'cidb_grades', 'vendors', 'district', 'district_id'));
   }

}
