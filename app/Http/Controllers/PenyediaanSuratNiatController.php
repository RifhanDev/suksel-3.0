<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RestrictsTenderByRole;
use App\Support\TenderListingFilters;
use App\Support\TenderProcessStatus;
use App\Tender;
use Illuminate\Http\Request;

/**
 * Senarai tender pada peringkat Penyediaan Surat Niat.
 *
 * Halaman butiran dan semua tindakan surat (pembekal, jana, kemas kini, muat
 * turun, hantar) berada dalam SuratNiatController — lihat routes/web.php.
 * Kaedah show()/hantar() yang dahulu ada di sini telah dibuang kerana kedua-dua
 * laluan itu kini menuju ke sana; logik "naikkan status proses" dalam hantar()
 * lama sudah dipindahkan ke SuratNiatController::hantar().
 */
class PenyediaanSuratNiatController extends Controller
{
    use RestrictsTenderByRole;

    public function __construct()
    {
        $this->menuMiddleware('LetterOfIntent:list');
    }

    public function index(Request $request)
    {
        $query = $this->applyOwnAgencyScope(Tender::query())
            ->where('status_process_id', TenderProcessStatus::penyediaanSuratNiatListStatus());

        TenderListingFilters::apply($query, $request);

        if ($request->filled('status')) {
            if ($request->input('status') === 'penyediaan_surat_niat') {
                $query->where('tenders.status_process_id', TenderProcessStatus::penyediaanSuratNiatListStatus());
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $tenders = $query->orderByDesc('tenders.id')
            ->get()
            ->map(fn (Tender $tender) => $this->mapTenderAdvertRow($tender, 'penyediaanSuratNiat'))
            ->values()
            ->all();

        return view('newModule.penyediaanSuratNiat.index', compact('tenders'));
    }

    private function mapTenderAdvertRow(Tender $tender, string $routeName): array
    {
        return [
            'uuid' => $tender->uuid,
            'name' => $tender->name ?: '-',
            'tarikh_jual' => $tender->advertise_start_date
                ? \Carbon\Carbon::parse($tender->advertise_start_date)->format('d/m/Y')
                : '-',
            'tarikh_tutup' => $tender->advertise_stop_date
                ? \Carbon\Carbon::parse($tender->advertise_stop_date)->format('d/m/Y')
                : '-',
            'harga' => number_format((float) ($tender->price ?? 0), 2),
            // id, bukan uuid: SuratNiatController::show() menerima int dan
            // memanggil Tender::find() terus pada primary key.
            'show_url' => route($routeName, ['tender' => $tender->id]),
        ];
    }
}
