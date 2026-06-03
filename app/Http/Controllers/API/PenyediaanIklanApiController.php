<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PenyediaanIklanService;
use App\Tender;
use Illuminate\Http\Request;

class PenyediaanIklanApiController extends Controller
{
    public function __construct(protected PenyediaanIklanService $service) {}

    public function show(Tender $tender)
    {
        if ((int) $tender->status_process_id !== 4) {
            return response()->json([
                'success' => false,
                'message' => 'Tender tidak dalam peringkat Penyediaan Iklan.',
            ], 422);
        }

        $data = $this->service->getForTender($tender);

        return response()->json(['data' => $data]);
    }

    public function store(Request $request, Tender $tender)
    {
        return $this->persist($request, $tender, false);
    }

    public function submit(Request $request, Tender $tender)
    {
        return $this->persist($request, $tender, true);
    }

    protected function persist(Request $request, Tender $tender, bool $submit)
    {
        if ((int) $tender->status_process_id !== 4) {
            return response()->json([
                'success' => false,
                'message' => 'Tender tidak dalam peringkat Penyediaan Iklan.',
            ], 422);
        }

        $payload = $request->all();
        $this->service->save($tender, $payload, $submit);

        return response()->json([
            'success' => true,
            'message' => $submit
                ? 'Penyediaan iklan berjaya dihantar.'
                : 'Penyediaan iklan berjaya disimpan.',
        ]);
    }
}
