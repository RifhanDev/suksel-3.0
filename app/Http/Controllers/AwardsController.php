<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Award;

class AwardsController extends Controller
{
	/**
	 * Display a listing of shareholders
	 *
	 * @return Response
	 */
	public function index($parent_id) {
		if(!Award::canList($parent_id)) {
			return $this->_access_denied();
		}
		return Award::where('vendor_id', $parent_id)->get();
   }

	public function __construct() {
		// parent::__construct();
	}
}
