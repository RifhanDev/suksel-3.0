<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenilaianKewanganController extends Controller
{
    public function index()
    {
        $tender = [
            [
                'no' => 'QT210000000023741',
                'ptj' => 'BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN',
                'tajuk' => 'TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX',
                'tamat' => '17/01/2022',
            ],
            [
                'no' => 'QT210000000023740',
                'ptj' => 'BAHAGIAN PENTADBIRAN - CAWANGAN KEWANGAN - KEMENTERIAN KEWANGAN',
                'tajuk' => 'TAJUK PEROLEHAN 1',
                'tamat' => '17/01/2022',
            ],
        ];

        return view('newModule.penilaian_kewangan.index', compact('tender'));
    }

    /**
     * Display penilaian kewangan form for a tender (second page).
     */
    public function show(string $tender_no)
    {
        return view('newModule.penilaian_kewangan.show', compact('tender_no'));
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
