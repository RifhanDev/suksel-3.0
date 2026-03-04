<?php

namespace App\Http\Controllers;

use App\Models\Ref\RefKategoriJenisPerolehan;
use App\Models\Ref\RefTypeOfPerolehan;
use Illuminate\Http\Request;

class RefKategoriJenisPerolehanController extends Controller
{
    public function getTypeOfPerolehanByKategori(Request $request)
    {
        $kategoriId = $request->get('kategori_id');
        
        if (!$kategoriId) {
            return response()->json([]);
        }

        $typePerolehans = RefTypeOfPerolehan::where('ref_kategori_jenis_perolehan_id', $kategoriId)
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($typePerolehans);
    }
}
