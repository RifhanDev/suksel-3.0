<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembelianTerusController extends Controller
{
    public function createProject()
    {
        return view('newModule.pembelian_terus.create_project');
    }

    public function quoteProject()
    {
        return view('newModule.pembelian_terus.quote_tender');
    }
}
