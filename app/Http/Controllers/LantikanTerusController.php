<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LantikanTerusController extends Controller
{
    /**
     * Senarai projek untuk pembelian terus.
     */
    public function index()
    {
        $projects = $this->dummyProjects();

        return view('newModule.lantikanTerus.cipta_projek_list', compact('projects'));
    }

    /**
     * Borang cipta projek baharu.
     */
    public function create()
    {
        $project = null;

        return view('newModule.lantikanTerus.cipta_projek', compact('project'));
    }

    /**
     * Borang kemaskini projek sedia ada (draf).
     */
    public function edit($id)
    {
        $project = $this->dummyProjects()->firstWhere('id', (int) $id);

        abort_if(!$project, 404);

        return view('newModule.lantikanTerus.cipta_projek', compact('project'));
    }

    /**
     * ───────────────────────────────────────────────────────────────────
     * SEBUT HARGA PEMBELIAN TERUS (view-only flow, lain peranan)
     * Hanya projek yang "telah dihantar" dipaparkan.
     * ───────────────────────────────────────────────────────────────────
     */
    public function sebutHargaIndex()
    {
        $projects = $this->dummyProjects()->where('status', 'submitted')->values();

        return view('newModule.lantikanTerus.sebut_harga_list', compact('projects'));
    }

    public function sebutHargaShow($id)
    {
        $project = $this->dummyProjects()->firstWhere('id', (int) $id);

        abort_if(!$project, 404);

        return view('newModule.lantikanTerus.sebut_harga', compact('project'));
    }

    /**
     * ───────────────────────────────────────────────────────────────────
     * CUT OFF PROJEK — penentuan cut-off (shortlist pembekal)
     * ───────────────────────────────────────────────────────────────────
     */
    public function cutOffIndex()
    {
        $projects = $this->dummyProjects()->where('status', 'submitted')->values();

        return view('newModule.lantikanTerus.cut_off_list', compact('projects'));
    }

    public function cutOffShow($id)
    {
        $project = $this->dummyProjects()->firstWhere('id', (int) $id);

        abort_if(!$project, 404);

        $suppliers = $this->dummyCutOffSuppliers();

        return view('newModule.lantikanTerus.cut_off', compact('project', 'suppliers'));
    }

    /**
     * ───────────────────────────────────────────────────────────────────
     * PEMILIHAN SYARIKAT — tanda pembekal yang disenarai pendek
     * ───────────────────────────────────────────────────────────────────
     */
    public function pemilihanIndex()
    {
        $projects = $this->dummyProjects()->where('status', 'submitted')->values();

        return view('newModule.lantikanTerus.pemilihan_syarikat_list', compact('projects'));
    }

    public function pemilihanShow($id)
    {
        $project = $this->dummyProjects()->firstWhere('id', (int) $id);

        abort_if(!$project, 404);

        // Only the shortlisted suppliers carried over from the cut-off step
        $suppliers = $this->dummyCutOffSuppliers()->take(3)->values();
        $documents = $this->dummySupportingDocuments();

        return view('newModule.lantikanTerus.pemilihan_syarikat', compact('project', 'suppliers', 'documents'));
    }

    /**
     * ───────────────────────────────────────────────────────────────────
     * KEPUTUSAN SYARIKAT — terima / tolak syarikat yang dipilih
     * ───────────────────────────────────────────────────────────────────
     */
    public function keputusanIndex()
    {
        $projects = $this->dummyProjects()->where('status', 'submitted')->values();

        return view('newModule.lantikanTerus.keputusan_syarikat_list', compact('projects'));
    }

    public function keputusanShow($id)
    {
        $project = $this->dummyProjects()->firstWhere('id', (int) $id);

        abort_if(!$project, 404);

        $decision = $this->dummyKeputusan();

        return view('newModule.lantikanTerus.keputusan_syarikat', compact('project', 'decision'));
    }

    /**
     * DUMMY — syarikat yang dipilih untuk keputusan akhir.
     */
    private function dummyKeputusan()
    {
        return (object) [
            'company'   => 'Z & Z PROJECT MANAGEMENT SDN BHD',
            'harga_sst' => 2944.08,
            'status'    => 'pending', // pending | accepted | rejected
        ];
    }

    /**
     * DUMMY — dokumen sokongan yang dimuat naik pada langkah cut-off sebelum ini.
     */
    private function dummySupportingDocuments()
    {
        return (object) [
            'jpict'       => 'JPICT.pdf',
            'minit_bebas' => 'MinitBebas.pdf',
        ];
    }

    /**
     * DUMMY — senarai pembekal & tawaran untuk penentuan cut-off.
     * Replace with the real query when the data source is ready.
     */
    private function dummyCutOffSuppliers(): Collection
    {
        return collect([
            (object) ['name' => 'Z & Z PROJECT MANAGEMENT SDN BHD', 'harga_tawaran' => 2922.00, 'bq_filename' => 'Dokumen BQ.pdf'],
            (object) ['name' => 'ADNA JAYA ENTERPRISE',            'harga_tawaran' => 2932.00, 'bq_filename' => 'Dokumen BQ.pdf'],
            (object) ['name' => 'WILAYAH OFFICE TRADING',          'harga_tawaran' => 2726.00, 'bq_filename' => 'Dokumen BQ.pdf'],
            (object) ['name' => 'A2ZOFFICE',                       'harga_tawaran' => 3932.00, 'bq_filename' => 'Dokumen BQ.pdf'],
            (object) ['name' => 'SAIDINA GROUP',                   'harga_tawaran' => 3412.00, 'bq_filename' => 'Dokumen BQ.pdf'],
            (object) ['name' => 'XYM FURNITURE',                   'harga_tawaran' => 3022.00, 'bq_filename' => 'Dokumen BQ.pdf'],
        ]);
    }

    /**
     * ───────────────────────────────────────────────────────────────────
     * DUMMY DATA — placeholder until the real database/source is ready.
     * Replace dummyProjects() with the actual query (e.g. a model/API call)
     * and the rest of the controller will work as-is.
     * ───────────────────────────────────────────────────────────────────
     */
    private function dummyProjects(): Collection
    {
        // Pull a few real reference IDs so the edit form actually shows selections.
        $ptjId      = \App\OrganizationUnit::value('id');
        $lokalitiId = \App\Models\Ref\RefLokaliti::where('active', true)->value('id');
        $mofCodes   = \App\Code::where('type', 'mof')->orderBy('code')->limit(2)->pluck('id')->all();
        $cidbGrades = \App\Code::where('type', 'cidb-g')->orderBy('code')->limit(1)->pluck('id')->all();
        $cidbSpecs  = \App\Code::where('type', 'cidb-c')->orderBy('code')->limit(2)->pluck('id')->all();

        $dummyKodBidang = [
            'mof'  => [['logic_mid' => 'OR', 'code' => $mofCodes]],
            'cidb' => [['logic_mid' => 'AND', 'grade' => $cidbGrades, 'spec' => $cidbSpecs]],
        ];

        return collect([
            (object) [
                'id'                 => 1,
                'no_tender'          => 'QT210000000023741',
                'name'               => 'Pembekalan Peralatan Komputer Untuk Pejabat SUK Selangor',
                'tarikh'             => '15/06/2026',
                'status'             => 'draft',
                // extra fields for the edit form prefill
                'ref_number'         => 'SH/DF/TRG',
                'ptj_id'             => $ptjId,
                'harga_indikatif'    => 15000,
                'tarikh_buka'        => '15/06/2026',
                'tarikh_tutup'       => '15/07/2026',
                'zon_lokasi'         => '0',
                'lokaliti_id'        => $lokalitiId,
                'kategori_perolehan' => 'ict',
                'sumber_peruntukan'  => 'pembangunan',
                'terbuka_kepada'     => 'semua',
                'bq_filename'        => 'Dokumen BQ.pdf',
                'mof'                => $dummyKodBidang['mof'],
                'cidb'               => $dummyKodBidang['cidb'],
            ],
            (object) [
                'id'                 => 2,
                'no_tender'          => 'QT210000000023742',
                'name'               => 'Perkhidmatan Penyelenggaraan Sistem Penghawa Dingin',
                'tarikh'             => '18/06/2026',
                'status'             => 'submitted',
                'ref_number'         => 'SH/DF/PHD',
                'ptj_id'             => $ptjId,
                'harga_indikatif'    => 32000,
                'tarikh_buka'        => '18/06/2026',
                'tarikh_tutup'       => '18/07/2026',
                'zon_lokasi'         => '0',
                'lokaliti_id'        => $lokalitiId,
                'kategori_perolehan' => 'perkhidmatan',
                'sumber_peruntukan'  => 'mengurus',
                'terbuka_kepada'     => 'semua',
                'bq_filename'        => 'Dokumen BQ.pdf',
                'mof'                => $dummyKodBidang['mof'],
                'cidb'               => $dummyKodBidang['cidb'],
            ],
            (object) [
                'id'                 => 3,
                'no_tender'          => 'QT210000000023743',
                'name'               => 'Pembekalan Alat Tulis Dan Keperluan Pejabat',
                'tarikh'             => '20/06/2026',
                'status'             => 'draft',
                'ref_number'         => 'SH/DF/ATK',
                'ptj_id'             => $ptjId,
                'harga_indikatif'    => 8000,
                'tarikh_buka'        => '20/06/2026',
                'tarikh_tutup'       => '20/07/2026',
                'zon_lokasi'         => '0',
                'lokaliti_id'        => $lokalitiId,
                'kategori_perolehan' => 'bekalan',
                'sumber_peruntukan'  => 'pembangunan',
                'terbuka_kepada'     => 'bumiputera',
                'bq_filename'        => 'Dokumen BQ.pdf',
                'mof'                => $dummyKodBidang['mof'],
                'cidb'               => $dummyKodBidang['cidb'],
            ],
        ]);
    }
}
