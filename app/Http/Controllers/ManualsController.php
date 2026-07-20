<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ManualsController extends Controller
{

   public function index() {
        	
     	$user = auth()->user();
     	$content = '';
     	return view('documentation.default', compact('user', 'content'));
   }

	public function show($file) {

     	$user = auth()->user();
     	// $content = file_get_contents('app/views/documentation/'. $file . '.md');
     	$content = file_get_contents('../resources/views/documentation/'. $file . '.md');

     	return view('documentation.default', compact('user', 'content'));
   }

    // public function __construct()
    // {
    //     parent::__construct();
    //     View::share('controller', 'manuals');
    // }
}
