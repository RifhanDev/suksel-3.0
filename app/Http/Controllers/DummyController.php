<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DummyController extends Controller
{
    /**
     * Step 1: Papar halaman Cipta Tender
     */
    public function create()
    {
        return view('tender.cipta_tender');
    }

    /**
     * Step 2: Simpan data awal tender (dummy logic)
     * Decide which page to go next based on rules
     */
    public function store(Request $request)
    {
        $jenis = $request->input('kategori_perolehan');
        // perkhidmatan_bekalan | kerja

        if ($jenis === 'perkhidmatan_bekalan') {
            return redirect()->route('tender.bekalan');
        }

        if ($jenis === 'kerja') {
            return redirect()->route('tender.kerja');
        }

        return back()->with('error', 'Jenis perolehan tidak sah');
    }

    /**
     * Page for Bekalan / Perkhidmatan
     */
    public function bekalan()
    {
        return view('tender.bekalan.index');
    }

    /**
     * Page for Kerja
     */
    public function kerja()
    {
        return view('tender.kerja.index');
    }

    /**
     * Example: decide page by STATUS (future-proof)
     */
    public function viewByStatus($status)
    {
        return match ($status) {
            'draf'         => view('tender.status.draf'),
            'penilaian'    => view('tender.status.penilaian'),
            'pengesyoran'  => view('tender.status.pengesyoran'),
            'laporan'      => view('tender.status.laporan'),
            default        => abort(404),
        };
    }
}
