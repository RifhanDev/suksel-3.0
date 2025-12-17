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

    public function detailProject($tender_no, Request $request)
    {
        // TODO: Fetch project data based on $tender_no from database
        // For now, returning view with sample data structure
        $project = (object) [
            'nama_proyek' => 'PEMBANGUNAN GEDUNG PERKANTORAN',
            'lokasi' => 'JAKARTA',
            'tahun_anggaran' => '2023',
            'kategori_perusahaan' => 'Kategori Dipilih',
            'jenis_pekerjaan' => 'Jasa',
            'nilai_proyek' => 1000000000
        ];

        $contracts = [
            ['posisi' => 'Direktur Utama', 'nama' => 'M. FAUZI'],
            ['posisi' => 'Manajer Proyek', 'nama' => 'M. FAUZI'],
            ['posisi' => 'Pelaksana', 'nama' => 'M. FAUZI']
        ];

        return view('newModule.pembelian_terus.details', [
            'tender_no' => $tender_no,
            'project' => $project,
            'contracts' => $contracts
        ]);
    }
}
