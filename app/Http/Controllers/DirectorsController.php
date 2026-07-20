<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Director;

class DirectorsController extends Controller
{
	public function index($parent_id) {
		if(!Director::canList($parent_id)) {
			return $this->_access_denied();
		}
		return Director::where('vendor_id', $parent_id)->get();
   }

	public function __construct() {
		// parent::__construct();
	}
}
