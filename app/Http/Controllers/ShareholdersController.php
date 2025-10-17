<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shareholder;

class ShareholdersController extends Controller
{
	public function index($parent_id) {
		if(!Shareholder::canList($parent_id)) {
			return $this->_access_denied();
		}
		return Shareholder::where('vendor_id', $parent_id)->get();
   }

	public function __construct() {
		// parent::__construct();
	}
}
