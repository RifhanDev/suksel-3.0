<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Tender;
use App\User;
use Illuminate\Support\Facades\Auth;

$user = User::first();
Auth::login($user);

$tender = Tender::first();
$tenderKey = !empty($tender->uuid) ? $tender->uuid : $tender->id;
$vendorId = 1;

$routes = [
    'imbangan'           => route('lembaranImbangan', [$tenderKey, 'vendor_id' => $vendorId, 'mode' => 'view', 'modal' => 1]),
    'penyata_bank'       => route('penyataBank', [$tenderKey, 'vendor_id' => $vendorId, 'mode' => 'view', 'modal' => 1]),
    'bon_saham'          => route('bonAtauSaham', [$tenderKey, 'vendor_id' => $vendorId, 'mode' => 'view', 'modal' => 1]),
    'prestasi'           => route('prestasiKerjaSemasa', [$tenderKey, 'vendor_id' => $vendorId, 'mode' => 'view', 'modal' => 1]),
    'pengalaman_kerja'   => route('senaraiTeknikal.pengalamanKerja.tender', [$tenderKey, 'vendor_id' => $vendorId, 'mode' => 'view', 'modal' => 1]),
    'kakitangan_teknikal'=> route('kakitanganTeknikal', [$tenderKey, 'vendor_id' => $vendorId, 'mode' => 'view', 'modal' => 1]),
];

echo "Testing AJAX HTML fetch for Borang Routes\n";
echo "----------------------------------------------------------------------\n";

foreach ($routes as $key => $url) {
    $path = parse_url($url, PHP_URL_PATH);
    $query = parse_url($url, PHP_URL_QUERY);
    parse_str($query, $queryParams);

    $request = Illuminate\Http\Request::create($path, 'GET', $queryParams);
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    $request->setUserResolver(fn () => $user);

    $response = $app->handle($request);
    $html = $response->getContent();

    echo "[{$key}] Status: " . $response->getStatusCode() . " | Length: " . strlen($html) . " bytes\n";
    if (str_contains($html, 'form') || str_contains($html, 'table') || str_contains($html, 'card')) {
        echo " -> Contains form/table/card elements successfully.\n";
    }
}
