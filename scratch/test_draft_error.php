<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\JawatankuasaController;
use App\Tender;

$tenderUuid = 'd65cf044-899f-46e8-a6f1-37e3999a2100';
$tender = Tender::where('uuid', $tenderUuid)->first();
if ($tender) {
    $tender->update(['tender_peringkat' => 1]);
}

$requestData = [
    'tender_uuid' => $tenderUuid,
    'tabs' => [
        'spec' => [
            'catatan' => 'Catatan spec',
            'rows' => [
                ['user_id' => '2', 'p_p' => '1', 'peranan' => '1'],
                ['user_id' => '3', 'p_p' => '1', 'peranan' => '2'],
                ['user_id' => '4', 'p_p' => '1', 'peranan' => '3'],
            ]
        ],
        'open' => [
            'catatan' => 'Catatan open',
            'rows' => [
                ['user_id' => '2', 'p_p' => '1', 'peranan' => '1'],
                ['user_id' => '3', 'p_p' => '1', 'peranan' => '2'],
                ['user_id' => '4', 'p_p' => '1', 'peranan' => '3'],
            ]
        ],
        'eval' => [
            'catatan' => 'Catatan eval',
            'rows' => [
                ['user_id' => '2', 'p_p' => '1', 'peranan' => '1'],
                ['user_id' => '3', 'p_p' => '1', 'peranan' => '2'],
                ['user_id' => '4', 'p_p' => '1', 'peranan' => '3'],
            ]
        ]
    ]
];

$request = Request::create('/jawatankuasa', 'POST', $requestData);
$controller = new JawatankuasaController();

try {
    $response = $controller->store($request);
    echo "Response status code: " . $response->getStatusCode() . "\n";
    echo "Response content: " . $response->getContent() . "\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Exception: \n";
    print_r($e->errors());
} catch (\Exception $e) {
    echo "Generic Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
