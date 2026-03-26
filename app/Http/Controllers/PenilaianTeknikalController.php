<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenilaianTeknikalController extends Controller
{
    /**
     * Display SENARAI TENDER (first page - Peringkat Penilaian Teknikal).
     */
    public function index()
    {
        return view('newModule.penilaian.teknikal_index');
    }

    /**
     * Display penilaian teknikal form for a tender (second page).
     */
    public function show(string $tender_no)
    {
        return view('newModule.penilaian.teknikal', compact('tender_no'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
