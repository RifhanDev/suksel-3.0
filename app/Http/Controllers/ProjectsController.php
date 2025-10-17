<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
	public function index($parent_id) {
		if(!Project::canList($parent_id)) {
			return $this->_access_denied();
		}
		return Project::where('vendor_id', $parent_id)->get();
    }

	public function __construct() {
		// parent::__construct();
	}
}
