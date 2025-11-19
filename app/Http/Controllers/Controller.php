<?php

namespace App\Http\Controllers;

use App\Traits\Helper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helper;

		protected $validation_error_message = 'Pengesahan data gagal';
		protected $access_denied_message    = 'Akses dinafikan';
		protected $created_message          = 'Rekod dicipta';
		protected $create_error_message     = 'Gagal mencipta rekod';
		protected $updated_message          = 'Rekod dikemaskini';
		protected $update_error_message     = 'Gagal mengemaskini rekod';
		protected $deleted_message          = 'Rekod dipadam';
		protected $delete_error_message     = 'Gagal memadam rekod';
	
		/**
		* Setup the layout used by the controller.
		*
		* @return void
		*/
	   protected function setupLayout() {
			if (!is_null($this->layout)) {
				$this->layout = View::make($this->layout);
			}
		}
		
		protected function _ajax_denied() {
			return response()->json("Bad request", 400);
		}
		
		/**
		* Response Shorthands
		*/
		
		protected function _access_denied() {
			if (request()->ajax()) {
				return response()->json($this->access_denied_message, 403);
			}
			return $this->_redirect()->with('danger', $this->access_denied_message);
		}
		
		protected function _validation_error($obj) {
			$validationErrors = (is_subclass_of($obj, 'LaravelBook\Ardent\Ardent'))
			?$obj->validationErrors
			:$obj;
			if (request()->ajax()) {
				return response()->json($validationErrors, 400);
			}
			// Session::remove('_old_input');
			return redirect()->back()
				->withInput()
				->withErrors($validationErrors)
				->with('danger', $this->validation_error_message);
		}
		
		protected function _create_error() {
			if (request()->ajax()) {
				return response()->json($this->create_error_message, 400);
			}
			return redirect()->back()->with('danger', $this->create_error_message);
		}
		
		protected function _update_error() {
			if (request()->ajax()) {
				return response()->json($this->update_error_message, 400);
			}
			return redirect()->back()->with('danger', $this->update_error_message);
		}
		
		protected function _delete_error() {
			if (request()->ajax()) {
				return response()->json($this->delete_error_message, 400);
			}
			return redirect()->back()->with('danger', $this->delete_error_message);
		}
		
		protected function _redirect()
	{/* 
		$referrer = url()->previous();
		if (!$referrer) { */
		$referrer = '/';
		return redirect($referrer);
	}
	
	// protected function __construct()
	// {
	// View::share('controller', '');
	// }

}
