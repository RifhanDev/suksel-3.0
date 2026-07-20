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

    // cut off
    public function cutOffProject()
    {
        return view('newModule.pembelian_terus.cut_off_project');
    }

    public function cutOffDetails($tender_no)
    {
        $suppliers = [
            [
                'name' => 'Z & Z PROJECT MANAGEMENT SDN BHD',
                'price' => '410,400.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '410,400.00'
            ],
            [
                'name' => 'ADNA JAYA ENTERPRISE',
                'price' => '510,024.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '510,024.00'
            ],
            [
                'name' => 'WILAYAH OFFICE TRADING',
                'price' => '510,124.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '510,124.00'
            ],
            [
                'name' => 'A2ZOFFICE',
                'price' => '510,134.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '510,134.00'
            ],
            [
                'name' => 'SAIDINA GROUP',
                'price' => '510,200.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '510,200.00'
            ],
            [
                'name' => 'XYM FURNITURE',
                'price' => '510,220.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '510,220.00'
            ]
        ];

        return view('newModule.pembelian_terus.cut_off_details', [
            'tender_no' => $tender_no,
            'suppliers' => $suppliers
        ]);
    }

    public function pemilihanSyarikat()
    {
        return view('newModule.pembelian_terus.pemilihan_syarikat');
    }

    public function pemilihanSyarikatDetails($tender_no)
    {
        $suppliers = [
            [
                'name' => 'Z & Z PROJECT MANAGEMENT SDN BHD',
                'price' => '410,400.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '410,400.00'
            ],
            [
                'name' => 'ADNA JAYA ENTERPRISE',
                'price' => '510,024.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '510,024.00'
            ],
            [
                'name' => 'WILAYAH OFFICE TRADING',
                'price' => '510,124.00',
                'items' => [
                    ['item' => 'MONITOR', 'kuantiti' => '10', 'harga' => '100,000.00'],
                    ['item' => 'PRINTER', 'kuantiti' => '10', 'harga' => '190,000.00'],
                    ['item' => 'PROJECTOR', 'kuantiti' => '10', 'harga' => '90,000.00']
                ],
                'totalPrice' => '380,000.00',
                'totalPriceSST' => '510,124.00'
            ]
        ];

        return view('newModule.pembelian_terus.pemilihan_syarikat_details', [
            'tender_no' => $tender_no,
            'suppliers' => $suppliers
        ]);
    }

    public function keputusanSyarikat()
    {
        return view('newModule.pembelian_terus.keputusan_syarikat');
    }

    public function keputusanSyarikatDetails($tender_no)
    {
        $results = [
            [
                'tender_no' => 'QT210000000023741',
                'tajuk_perolehan' => 'TENDER PERKHIDMATAN DIGITAL FORENSIK KE ATAS ALIRAN PROSES SISTEM XXXX',
                'nama_syarikat' => 'Z & Z PROJECT MANAGEMENT SDN BHD',
                'harga_sst' => '410,400.00'
            ]
        ];

        return view('newModule.pembelian_terus.keputusan_syarikat_details', [
            'tender_no' => $tender_no,
            'results' => $results
        ]);
    }

    public function downloadSuratSetujuTerima($tender_no)
    {
        // Download sample PDF from external URL
        $pdfUrl = 'https://ontheline.trincoll.edu/images/bookdown/sample-local-pdf.pdf';
        
        try {
            $pdfContent = file_get_contents($pdfUrl);
            
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="Surat_Setuju_Terima_' . $tender_no . '.pdf"');
        } catch (\Exception $e) {
            abort(500, 'Failed to download PDF');
        }
    }
}
