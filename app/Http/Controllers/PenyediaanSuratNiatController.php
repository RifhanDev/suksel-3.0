<?php

namespace App\Http\Controllers;

use App\Support\TenderProcessStatus;
use App\Tender;

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
    public function __construct()
    {
        $this->menuMiddleware('LetterOfIntent:list');
    }

    public function index()
    {
        $tenders = Tender::query()
            ->where('status_process_id', TenderProcessStatus::penyediaanSuratNiatListStatus())
            ->orderByDesc('id')
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
