<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CutOffController extends Controller
{
    /**
     * Display the Cut Off (SENARAI TENDER) page.
     * No database interaction; view only.
     */
    public function index()
    {
        return view('newModule.cut_off.index');
    }

    /**
     * Display the Cut Off details page (PENENTUAN HARGA CUT OFF).
     * No database interaction; view only.
     */
    public function show(string $tender_no)
    {
        return view('newModule.cut_off.show', ['tender_no' => $tender_no]);
    }
}
