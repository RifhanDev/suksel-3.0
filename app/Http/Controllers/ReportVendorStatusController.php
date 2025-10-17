<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use App\Exports\VendorStatus;
use App\Vendor;

class ReportVendorStatusController extends Controller
{
	protected $statuses = [
		'daftar_belum_lulus'    => 'Daftar Belum Lulus',
		'lulus_belum_bayar'     => 'Lulus Belum Bayar',
		'aktif'                 => 'Aktif',
		'tidak_aktif'           => 'Tamat Pendaftaran',
		'mof_expired'           => 'Tamat Tempoh MOF',
		'cidb_expired'          => 'Tamat Tempoh CIDB',
	];
	
	protected $date_labels = [
		'daftar_belum_lulus'    => 'Tarikh Daftar',
		'lulus_belum_bayar'     => 'Tarikh Kelulusan',
		'aktif'                 => 'Tarikh Tamat Langganan',
		'tidak_aktif'           => 'Tarikh Tamat Langganan',
		'mof_expired'           => 'Tarikh Tamat Sijil MOF',
		'cidb_expired'          => 'Tarikh Tamat Sijil CIDB',
	];
	
	public function index() {
		// $query      = DB::select('SELECT * FROM `vendor_snapshot`');
		// $data       = $query[0];
		// $statuses   = $this->statuses;

		// return view('reports.vendor.status.index', compact('data', 'statuses'));

		$daftar_belum_lulus = Vendor::where('completed', 1)->whereNull('approval_1_id')->where('vendors.created_at', '>=', '2015-03-01 00:00:00')->count();
		$lulus_belum_bayar  = Vendor::where('completed', 1)->whereNotNull('approval_1_id')->where('registration_paid', 0)->count();
		$aktif              = Vendor::activeSubscriptionCount();
		$tidak_aktif        = Vendor::nonActiveSubscriptionCount();
		$mof_expired        = Vendor::whereNotNull('mof_ref_no')->where('vendors.mof_end_date', '>=', date('Y-m-d'))->count();
		$cidb_expired       = Vendor::whereNotNull('cidb_ref_no')->where('vendors.cidb_end_date', '>=', date('Y-m-d'))->count();

		return view('reports.vendor.status.index', compact('daftar_belum_lulus', 'lulus_belum_bayar', 'aktif', 'tidak_aktif', 'mof_expired', 'cidb_expired'));
	}
	
	public function view($name) {            
		

		if(!array_key_exists($name, $this->statuses)) {
			$title = 'Senarai Syarikat Mengikut Status';
			$error = 'Sila pastikan pilihan status adalah betul.';
			return view('reports.error', compact('title', 'error'));
		}
	
		// $vendors    = DB::table('list_of_vendor_' . $name)->orderBy('date')->orderBy('name')->get();
		// $status     = $this->statuses[$name];
		// $date_label = $this->date_labels[$name];

		$status = $this->statuses[$name];
		$date_label = $this->date_labels[$name];
		switch ($name) {
			case 'daftar_belum_lulus':
				$vendors = Vendor::where('completed', 1)->whereNull('approval_1_id')->where('vendors.created_at', '>=', '2015-03-01 00:00:00')->get();
				break;

			case 'lulus_belum_bayar':
				$vendors = Vendor::where('completed', 1)->whereNotNull('approval_1_id')->where('registration_paid', 0)->get();
				break;

			case 'aktif':
				$vendors = Vendor::whereHas('activeSubscription')->get();
				break;

			case 'tidak_aktif':
				$vendors = Vendor::whereDoesntHave('activeSubscription')->get();
				break;

			case 'mof_expired':
				$vendors = Vendor::whereNotNull('mof_ref_no')->where('vendors.mof_end_date', '>=', date('Y-m-d'))->get();
				break;
			
			default:
				$vendors = Vendor::whereNotNull('cidb_ref_no')->where('vendors.cidb_end_date', '>=', date('Y-m-d'))->get();
				break;
		}
		
		return view('reports.vendor.status.view', compact('vendors', 'status', 'date_label', 'name'));
	}

	public function excel($name) {
		$status = $this->statuses[$name];
		$date_label = $this->date_labels[$name];
		switch ($name) {
			case 'daftar_belum_lulus':
				$vendors = Vendor::where('completed', 1)->whereNull('approval_1_id')->where('vendors.created_at', '>=', '2015-03-01 00:00:00')->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Daftar Belum Lulus.xlsx');
				break;

			case 'lulus_belum_bayar':
				$vendors = Vendor::where('completed', 1)->whereNotNull('approval_1_id')->where('registration_paid', 0)->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Lulus Belum Bayar.xlsx');
				break;

			case 'aktif':
				$vendors = Vendor::whereHas('activeSubscription')->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Aktif.xlsx');
				break;

			case 'tidak_aktif':
				$vendors = Vendor::whereDoesntHave('activeSubscription')->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Tidak Aktif.xlsx');
				break;

			case 'mof_expired':
				$vendors = Vendor::whereNotNull('mof_ref_no')->where('vendors.mof_end_date', '>=', date('Y-m-d'))->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Tamat Tempoh MOF.xlsx');
				break;
			
			default:
				$vendors = Vendor::whereNotNull('cidb_ref_no')->where('vendors.cidb_end_date', '>=', date('Y-m-d'))->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Tamat Tempoh CIDB.xlsx');
				break;
		}
		
	}

   public function csv($name) {
              
        	if(!array_key_exists($name, $this->statuses)) {
            $title = 'Senarai Syarikat Mengikut Status';
            $error = 'Sila pastikan pilihan status adalah betul.';
            return view('reports.error', compact('title', 'error'));
        	}

		$status = $this->statuses[$name];
		$date_label = $this->date_labels[$name];
		switch ($name) {
			case 'daftar_belum_lulus':
				$vendors = Vendor::where('completed', 1)->whereNull('approval_1_id')->where('vendors.created_at', '>=', '2015-03-01 00:00:00')->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Daftar Belum Lulus.csv');
				break;

			case 'lulus_belum_bayar':
				$vendors = Vendor::where('completed', 1)->whereNotNull('approval_1_id')->where('registration_paid', 0)->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Lulus Belum Bayar.csv');
				break;

			case 'aktif':
				$vendors = Vendor::whereHas('activeSubscription')->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Aktif.csv');
				break;

			case 'tidak_aktif':
				$vendors = Vendor::whereDoesntHave('activeSubscription')->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Tidak Aktif.csv');
				break;

			case 'mof_expired':
				$vendors = Vendor::whereNotNull('mof_ref_no')->where('vendors.mof_end_date', '>=', date('Y-m-d'))->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Tamat Tempoh MOF.csv');
				break;
			
			default:
				$vendors = Vendor::whereNotNull('cidb_ref_no')->where('vendors.cidb_end_date', '>=', date('Y-m-d'))->get();
				return Excel::download(new VendorStatus($vendors, $status, $date_label), 'Syarikat Tamat Tempoh CIDB.csv');
				break;
		}

        	// $that       = $this;
        	// $response   = new StreamedResponse(function() use($that, $name) {
         //    $vendors    = DB::table('list_of_vendor_' . $name)->orderBy('date')->orderBy('name')->get();
         //    $handle     = fopen('php://output', 'w');

         //    fputcsv($handle, [
         //        $that->statuses[$name]
         //    ]);

         //    fputcsv($handle, [
         //        'Bil.',
         //        'No. Syarikat',
         //        'Nama Syarikat',
         //        'Alamat',
         //        'No Telefon',
         //        $this->date_labels[$name]
         //    ]);

         //    $count = 1;
         //    foreach($vendors as $vendor) {
         //        fputcsv($handle, [
         //            $count,
         //            $vendor->registration,
         //            $vendor->name,
         //            $vendor->address,
         //            $vendor->tel,
         //            $vendor->date
         //        ]);
         //        $count++;
         //    }

         //    fclose($handle);
        	// }, 200, [
         //    'Content-Type' => 'text/csv',
         //    'Content-Disposition' => 'attachment; filename="' . $name .'.csv"',
        	// ]);

        	// return $response;
   }

}
