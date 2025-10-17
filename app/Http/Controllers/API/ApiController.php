<?php

namespace App\Http\Controllers\API;

use App\Tender;
use App\Vendor;
use App\OrganizationUnit;
use App\Models\ApiContractVendorDetail;
use App\Models\ApiDetailVendor;
use App\Models\ApiTransactionContract;
use App\Traits\Helper;
use App\Traits\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use ApiHelper, Helper;

    public function vendorApi(Request $request)
    {
        $message = null;
        $data = [];
        $status = null;
        $response = [];
        $token = $request->bearerToken();
        $registration = $request->registration;

        $rules = array(
            "token" => "required",
            "registration" => "required"
        );

        $validator = Validator::make([
            "token" => $token,
            "registration" => $registration
        ], $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (!$this->tokenValidator($token)) {
            $status = 'failed';
            $message = 'token is not valid';
        } else {
            $this->recordApiRequest($token, "vendor", ['registration' => $registration]);
            $vendor = Vendor::where('registration', $registration)->with(['user', 'uploads', 'vendorCodes'])->first();

            if (!$this->isExists($vendor)) {

                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            $status = 'success';

            $data[] = [
                'company_information' => [
                    'email' => $vendor->user->email,
                    'registration_no' => $vendor->registration,
                    'name' => $vendor->name,
                    'address' => $vendor->address,
                    'district' => Vendor::$districts[$vendor->district_id],
                    'tel' => $vendor->tel,
                    'fax' => $vendor->fax,
                    'code_cert' => $vendor->token
                ],
                'mof' => [
                    'reference' => $vendor->mof_ref_no,
                    'active_start' => Carbon::parse($vendor->mof_start_date)->format('d-m-Y'),
                    'active_end' => Carbon::parse($vendor->mof_end_date)->format('d-m-Y'),
                    'bumi' => $vendor->mof_bumi,
                    'mof_code' => $vendor->mofCodes->map->only(['code'])
                ],
                'shareholder' => [
                    'holder' => $vendor->shareholders,
                    'summary' => [
                        'bumi' => $vendor->bumi_percentage . '%',
                        'nonbumi' => $vendor->nonbumi_percentage . '%',
                        'foreigner' => $vendor->foreigner_percentage . '%',
                        'total' => sprintf('%.2f', $vendor->bumi_percentage + $vendor->nonbumi_percentage + $vendor->foreigner_percentage) . '%'
                    ]
                ],
                'director' => $vendor->directors,
                'project' => $vendor->projects,
                'file' => $vendor->uploads->map->only(['name', 'type', 'size', 'created_at', 'url'])

            ];
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response);
    }

    public function tenderApi(Request $request)
    {
        $message = null;
        $data = [];
        $status = null;
        $response = [];
        $token = $request->bearerToken();
        $ref_number = $request->ref_number;

        $rules = array(
            "token" => "required",
            "ref_number" => "required"
        );

        $validator = Validator::make([
            "token" => $token,
            "ref_number" => $ref_number
        ], $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (!$this->tokenValidator($token)) {
            $status = 'failed';
            $message = 'token is not valid';
        } else {
            $this->recordApiRequest($token, "tender", ['ref_number' => $ref_number]);
            $tender = Tender::where('ref_number', $ref_number)->first();

            if (!$this->isExists($tender)) {

                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            $participants = $tender->participants()->has('vendor')->with('vendor')->get();
            $histories = $tender->histories()->orderBy('created_at', 'desc')->get();
            $news = $tender->news()->wherePublish(1)->orderBy('published_at', 'asc')->get();

            $cidb_grade = [];
            $history_arr = [];
            $news_arr = [];
            $participant_arr = [];

            foreach ($participants as $participant) {
                $participant_arr[] = [
                    'name' => $participant->vendor->name,
                    'purchase' => $participant->participate,
                    'ref_number' => $participant->ref_number
                ];
            }

            foreach ($news as $post) {
                $news_arr[] = [
                    'published_at' => $post->publish_at,
                    'title' => $post->title
                ];
            }

            foreach ($histories as $history) {
                $history_arr[] = [
                    'action' => $history->action,
                    'changed_date' => $history->changed_date,
                    'by' => $history->user->name
                ];
            }

            foreach ($tender->cidb_grades as $grade) {
                $cidb_grade[] = [
                    'code' => $grade->code->code,
                    'name' => $grade->code->name,
                    'type' => $grade->code->type
                ];
            }

            $data[] = [
                'information' => [
                    'owner' => $tender->tenderer->name,
                    'ref_number' => $tender->ref_number,
                    'start_ad' => $tender->advertise_start_date,
                    'end_ad' => $tender->advertise_stop_date,
                    'start_doc' => $tender->document_start_date,
                    'end_doc' => $tender->document_stopdate,
                    'submission_datetime' => $tender->submission_datetime,
                    'submission_at' => $tender->submission_location_address,
                    'price' => $tender->price,
                ],
                'rule' => $tender->tender_rules,
                'code' => [
                    'mof_code' => $tender->mof_codes,
                    'cidb_grade' => $cidb_grade,
                    'cidb_code' => $tender->cidb_codes->map->only(['join_rule', 'code']),
                    'mof_cidb_rule' => $tender->mof_cidb_rule
                ],
                'file' => $tender->tender_files->map->only(['name', 'type', 'size', 'created_at', 'url']),
                'history' => $history_arr,
                'news' => $news_arr,
                'participant' => $participant_arr
            ];

            $status = 'success';
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response);
    }

    public function transactionApi(Request $request)
    {
        $message = null;
        $data = [];
        $status = null;
        $response = [];
        $token = $request->bearerToken();
        $tender_ref_number = $request->tender_ref_number;

        $rules = array(
            "token" => "required",
            "tender_ref_number" => "required"
        );

        $validator = Validator::make([
            "token" => $token,
            "tender_ref_number" => $tender_ref_number
        ], $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (!$this->tokenValidator($token)) {
            $status = 'failed';
            $message = 'token is not valid';
        } else {
            $this->recordApiRequest($token, "tender", ['tender_ref_number' => $tender_ref_number]);
            $tender = Tender::where('ref_number', $tender_ref_number)->first();

            if (!$this->isExists($tender)) {

                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            $participants = $tender->participants;

            $transaction_arr = [];

            foreach ($participants as $participant) {
                $receipt = $this->receiptNumGenerator($participant->transaction->number, date('d-m-Y', strtotime($participant->transaction->created_at)));
                $transaction_arr[] = [
                    'transaction_time' => $participant->transaction->created_at,
                    'vendor' => $participant->vendor->name,
                    'transaction_number' => $participant->transaction->number,
                    'gateway_reference' => $participant->transaction->gateway_reference,
                    'receipt_number' => ($receipt != 'old') ? $receipt : $participant->transaction->vendor_id . '-' . $participant->transaction->gateway_reference,
                    'type' => $participant->transaction->type,
                    'method' => $participant->transaction->method,
                    'amount' => $participant->transaction->amount,
                    'status' => $participant->transaction->status
                ];
            }

            $status = 'success';

            $data[] = [
                'information' => [
                    'ref_number' => $tender->ref_number
                ],
                'transaction' => $transaction_arr
            ];
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response);
    }

    // function API tender agency
    public function tenderAgency(Request $request){
        $message = null;
        $data = [];
        $status = null;
        $response = [];
        $token = $request->token;
        $ref_number = $request->ref_number;

        $rules = array(
            "token" => "required",
            "ref_number" => "required"
        );

        $validator = Validator::make([
            "token" => $token,
            "ref_number" => $ref_number
        ], $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (!$this->tokenValidator($token)) {
            $status = 'failed';
            $message = 'token is not valid';
        } else {
            $this->recordApiRequest($token, "tender agency", ['ref_number' => $ref_number]);
            $tender = ApiContractVendorDetail::where('ref_number', $ref_number)->where('token', $token)->first();

            if (!$this->isExists($tender)) {
                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            $data['details'] = [
                'contract_name' => $tender->contract_name,
                'ref_number' => $tender->ref_number,
                'contract_type' => $tender->contract_type,
                'agency_name' => $tender->agency_name,
                'advertise_start_date' => $tender->advertise_start_date,
                'advertise_stop_date' => $tender->advertise_stop_date,
                'document_start_date' => $tender->document_start_date,
                'document_stop_date' => $tender->document_stop_date,
                'submission_datetime' => $tender->submission_datetime,
                'price' => $tender->price,
                'tender_rules' => $tender->tender_rules
            ];

            $status = 'success';
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response);
    }

    // function API details vendor
    public function detailVendor(Request $request){
        /* $message = null;
        $data = [];
        $status = null;
        $response = [];
        $token = $request->token;
        $registration = $request->registration;

        $rules = array(
            "token" => "required",
            "registration" => "required"
        );

        $validator = Validator::make([
            "token" => $token,
            "registration" => $registration
        ], $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (!$this->tokenValidator($token)) {
            $status = 'failed';
            $message = 'token is not valid';
        } else {
            $this->recordApiRequest($token, "detail vendor", ['registration' => $registration]);
            $org_vendor = ApiDetailVendor::when(true, function ($query) use ($registration) {
                $query->where('SSM', $registration);   //SSM2 trimed, SSM original format
            })->where('token', $token)->first();

            if (!$this->isExists($org_vendor)) {

                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            $vendor = Vendor::where('id', $org_vendor->vendor_id)->with(['user', 'uploads', 'vendorCodes'])->first();

            if (!$this->isExists($vendor)) {

                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            $status = 'success';

            $data[] = [
                'company_information' => [
                    'email' => $vendor->user->email,
                    'registration_no' => $vendor->registration,
                    'name' => $vendor->name,
                    'address' => $vendor->address,
                    'district' => Vendor::$districts[$vendor->district_id] ?? "",
                    'tel' => $vendor->tel,
                    'fax' => $vendor->fax,
                    'code_cert' => $vendor->token
                ],
                'mof' => [
                    'reference' => $vendor->mof_ref_no,
                    'active_start' => Carbon::parse($vendor->mof_start_date)->format('d-m-Y'),
                    'active_end' => Carbon::parse($vendor->mof_end_date)->format('d-m-Y'),
                    'bumi' => $vendor->mof_bumi,
                    'mof_code' => $vendor->mofCodes->map->only(['code'])
                ],
                'shareholder' => [
                    'holder' => $vendor->shareholders,
                    'summary' => [
                        'bumi' => $vendor->bumi_percentage . '%',
                        'nonbumi' => $vendor->nonbumi_percentage . '%',
                        'foreigner' => $vendor->foreigner_percentage . '%',
                        'total' => sprintf('%.2f', $vendor->bumi_percentage + $vendor->nonbumi_percentage + $vendor->foreigner_percentage) . '%'
                    ]
                ],
                'director' => $vendor->directors,
                'project' => $vendor->projects,
                'file' => $vendor->uploads->map->only(['name', 'type', 'size', 'created_at', 'url'])

            ];
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response); */

        $message = null;
        $data = [];
        $status = null;
        $response = [];
        $token = $request->token;
        $registration = $request->registration;

        $rules = array(
            "token" => "required",
            "registration" => "required"
        );

        $validator = Validator::make([
            "token" => $token,
            "registration" => $registration
        ], $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (!$this->tokenValidator($token)) {
            $status = 'failed';
            $message = 'token is not valid';
        } else {
            $this->recordApiRequest($token, "detail vendor", ['registration' => $registration]);
            $vendor = Vendor::where('registration', $registration)->with(['user', 'uploads', 'vendorCodes'])->first();

            if (!$this->isExists($vendor)) {

                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            $status = 'success';

            $data[] = [
                'company_information' => [
                    'email' => $vendor->user->email,
                    'registration_no' => $vendor->registration,
                    'name' => $vendor->name,
                    'address' => $vendor->address,
                    'district' => Vendor::$districts[$vendor->district_id] ?? "",
                    'tel' => $vendor->tel,
                    'fax' => $vendor->fax,
                    'code_cert' => $vendor->token
                ],
                'mof' => [
                    'reference' => $vendor->mof_ref_no,
                    'active_start' => Carbon::parse($vendor->mof_start_date)->format('d-m-Y'),
                    'active_end' => Carbon::parse($vendor->mof_end_date)->format('d-m-Y'),
                    'bumi' => $vendor->mof_bumi,
                    'mof_code' => $vendor->mofCodes->map->only(['code'])
                ],
                'shareholder' => [
                    'holder' => $vendor->shareholders,
                    'summary' => [
                        'bumi' => $vendor->bumi_percentage . '%',
                        'nonbumi' => $vendor->nonbumi_percentage . '%',
                        'foreigner' => $vendor->foreigner_percentage . '%',
                        'total' => sprintf('%.2f', $vendor->bumi_percentage + $vendor->nonbumi_percentage + $vendor->foreigner_percentage) . '%'
                    ]
                ],
                'director' => $vendor->directors,
                'project' => $vendor->projects,
                'file' => $vendor->uploads->map->only(['name', 'type', 'size', 'created_at', 'url'])

            ];
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response);
    }

    // function API transaksi contract
    public function transactionContract(Request $request){
        $message = null;
        $data = [];
        $status = null;
        $response = [];
        $token = $request->token;
        $ref_number = $request->ref_number;

        $rules = array(
            "token" => "required",
            "ref_number" => "required"
        );

        $validator = Validator::make([
            "token" => $token,
            "ref_number" => $ref_number
        ], $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (!$this->tokenValidator($token)) {
            $status = 'failed';
            $message = 'token is not valid';
        } else {
            $this->recordApiRequest($token, "transaction contract", ['ref_number' => $ref_number]);
            $vendor = ApiTransactionContract::when(true, function ($query) use ($ref_number) {
                $query->where('ref_number', $ref_number);
            })->where('token', $token)->get();

            if($vendor->isEmpty()){
                $status = 'failed';
                $message = 'data not found';

                $response = [
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
                ];

                return response()->json($response);
            }

            foreach($vendor as $item){
                $tran = $item->transaction;
                $receipt = $this->receiptNumGenerator($tran->number, date('d-m-Y', strtotime($tran->created_at)));
                $data[] = [
                    'ssm' => $item->SSM,
                    'company_name' => $item->nama_syarikat,
                    'number' => $item->number,
                    'type' => $item->type,
                    'receipt_number' => $receipt != "old" ? $receipt : $item->vendor_id.'-'.$item->gateway_reference,
                    'method' => $item->method,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'gateway_reference' => $item->gateway_reference,
                    'created_at' => $item->created_at,
                    'winner' => $item->winner == 1 ? true : false
                ];
            }

            $status = 'success';
        }

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response);
    }
}
