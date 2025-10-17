<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductsController extends Controller
{
	public function index($parent_id) {
		if(!Product::canList($parent_id)) {
			return $this->_access_denied();
		}
		return Product::where('vendor_id', $parent_id)->get();
    }

	public function __construct() {
		// parent::__construct();
	}
}
