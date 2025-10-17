<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;

class ContactsController extends Controller
{
	public function index($parent_id) {
		if(!Contact::canList($parent_id)) {
			return $this->_access_denied();
		}
		return Contact::where('vendor_id', $parent_id)->get();
    }

	public function __construct() {
		// parent::__construct();
	}
}
