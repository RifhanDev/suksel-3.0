<?php

namespace App\Http\Controllers;

use App\Gateway;
use App\Mail\ConfirmRegistration;
use App\Models\RefState;
use App\Role;
use App\Traits\Helper;
use App\Transaction;
use App\User;
use App\Vendor;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;


class RegistrationController extends Controller
{
    use Helper;
    public function register()
    {
        return view('registration.register');
    }

    public function storeRegister(Request $request)
    {
        $user = new User;

        $ssm_valid = Vendor::whereRaw("(UPPER(REGEXP_REPLACE(registration , '[^[:alnum:]]+', ''))) = '" . $this->removeCharacter($request->company_no) . "'")->count();
        if ($ssm_valid > 0) {
            return redirect('register')->withInput()->with('error', 'No Syarikat "' . $request->company_no . '" telah didaftarkan.');
        }
        
        if (Vendor::hasRegistered($request->company_no)) {
            return redirect('register')->withInput()->with('error', 'No Syarikat "' . $request->company_no . '" telah didaftarkan.');
        }

        $validator = Validator::make($request->all(), User::$_rules['changePassword']);

        if ($validator->fails()) {
            $error_msg = trans('auth.alerts.wrong_password_reset');
            return redirect('register')->withErrors($validator)->withInput()->with('error', $error_msg);
        } else {

            $validation = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
            ]);

            if ($validation->fails()) {
                $error_msg = trans('auth.alerts.duplicated_credentials');
                return redirect('register')->withErrors($validation)->withInput()->with('error', $error_msg);
            } else {
                $name                    = explode(' ', $request->name);
                $user->name              = $request->name;
                $user->email             = $request->email;
                $user->username          = $request->email;
                $user->password          = Hash::make($request->password);
                $user->confirmation_code = md5(uniqid(mt_rand(), true));

                if ($user->save()) {
                    $vendor = new Vendor;
                    $vendor->registration = $request->company_no;
                    $vendor->name = $request->company_name;
                    $vendor->organization_unit_id = config('app.global_cart_ou');
                    $vendor->officer_name = $request->name;
                    $vendor->officer_email = $request->email;

                    if ($vendor->save()) {
                        User::$rules = [];
                        $user->vendor()->associate($vendor);
                        $user->save();
                        $user->roles()->sync([Role::where('name', 'Vendor')->first()->id]);
                        Mail::to($user)->send(new ConfirmRegistration($user));
                        $notice      = 'Akaun anda telah didaftarkan. Sila semak email untuk pengesahan akaun.';
                        return redirect('/')->with('notice', $notice);
                    }

                    $error = $vendor->errors()->all(':message')[0];
                } else {
                    $error = $user->errors()->all(':message')[0];
                }
            }
        }

        return redirect('register')->withInput($request->except('password'))->with('error', $error);
    }

    public function company()
    {
        $user = auth()->user();
        if (!$user || !$user->vendor) {
            return $this->_access_denied();
        }

        $vendor = $user->vendor;
        if ($vendor->completed && is_null($vendor->approval_1_id)) {
            return redirect('dashboard')->with('notice', 'Maklumat syarikat anda dalam proses pengesahan.');
        }

        if (!is_null($vendor->approval_1_id) && !$vendor->registration_paid) {
            return redirect('register/payment');
        }

        if (!is_null($vendor->approval_1_id) && $vendor->registration_paid) {
            return redirect('dashboard')->with('error', 'Proses pendaftaran vendor telah selesai.');
        }

        $validateFiles = true;
        $country_states = RefState::where('display_status', 1)->get();
        $disable_create_flaq = 3; // Allow editing Alamat, Daerah, Negeri Field for first time registration

        return view('registration.company', compact('vendor', 'validateFiles', 'country_states', 'disable_create_flaq'));
    }

    public function storeCompany(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'mof'             => ['nullable', 'mimes:pdf', 'max:5120'],
            'cidb'            => ['nullable', 'mimes:pdf', 'max:5120'],
            'ssm'             => ['nullable', 'mimes:pdf', 'max:5120'],
            'mof_bumiputera'  => ['nullable', 'mimes:pdf', 'max:5120'],
            'cidb_bumiputera' => ['nullable', 'mimes:pdf', 'max:5120'],
        ]);

        if ($validator->fails())
            return redirect()->back()
                ->withErrors($validator, 'pdf_upload')
                ->with('danger', 'Fail Dimuat Naik Tidak Betul.');

        $user = auth()->user();
        if (!$user || !$user->vendor) {
            return $this->_access_denied();
        }

        $vendor = $user->vendor;

        if ($vendor->completed && is_null($vendor->approval_1_id)) {
            return redirect('dashboard')->with('notice', 'Maklumat syarikat anda dalam proses pengesahan.');
        }

        if (!is_null($vendor->approval_1_id) && !$vendor->registration_paid) {
            return redirect('payment_registration');
        }

        if (!is_null($vendor->approval_1_id) && $vendor->registration_paid) {
            return redirect('dashboard')->with('error', 'Proses pendaftaran vendor telah selesai.');
        }

        Vendor::setRules('update');
        $data = $request->all();
        $data['paidup_capital'] = str_replace(',', '', $data['paidup_capital']);
        $data['authorized_capital'] = str_replace(',', '', $data['authorized_capital']);

        $validate_mof = Validator::make($data, Vendor::$_rules['mof']);
        $validate_cidb = Validator::make($data, Vendor::$_rules['cidb']);

        if ($validate_mof->fails() && $validate_cidb->fails()) {
            return redirect()->back()->withInput()->with('danger', 'Sila pastikan anda telah mengisi salah satu maklumat MOF atau CIDB dengan lengkap.');
        }

        if (!$vendor->canUpdate()) {
            return $this->_access_denied();
        }

        if (!$vendor->completed) {
            $vendor->completed = true;
        }

        $vendor->rejection_reason = '';

        $data['submission_date'] = date('Y-m-d');

        // convert ssm_expiry
		$ssm_expiry_input = $data["ssm_expiry"]; // Your string representation of a date
		$ssm_expiry_input_format = 'd/m/Y'; // Your custom date format
		$new_ssm_expiry = Carbon::createFromFormat($ssm_expiry_input_format, $ssm_expiry_input);
		$data["ssm_expiry"] = $new_ssm_expiry->format('Y-m-d');

        if (!$vendor->update($data)) {
            return $this->_validation_error($vendor);
        }

        return redirect('vendor')->with('success', 'Maklumat syarikat anda telah dikemaskini. Anda akan dimaklumkan sekiranya pendaftaran anda diluluskan atau ditolak.');
    }

    public function payment()
    {
        $user = auth()->user();
        if (!$user || !$user->vendor) {
            return $this->_access_denied();
        }

        $vendor = $user->vendor;
        if ($vendor->completed && is_null($vendor->approval_1_id)) {
            return redirect('register/company');
        }

        if (!is_null($vendor->approval_1_id) && $vendor->registration_paid) {
            return redirect('dashboard')->with('error', 'Proses pendaftaran vendor telah selesai.');
        }

        $fpx = Gateway::whereType('fpx')->whereDefault(1)->whereActive(1)->first();
        $ebpg = Gateway::whereType('ebpg')->whereDefault(1)->whereActive(1)->first();

        return view('registration.payment', compact('fpx', 'ebpg'));
    }

    public function storePayment(Request $request)
    {

        $user   = auth()->user();
        $vendor = $user->vendor;

        if (!in_array($request->method, ['fpx-1', 'fpx-2', 'ebpg'])) {
            return redirect()->back()->with('error', 'Sila pilih saluran pembayaran yang sah.');
        }

        $cached_data = [
            'start_date' => date('Y-m-d'),
            'end_date'   => date('Y-m-d', strtotime('+1 year'))
        ];

        $method = $request->method;
        if (in_array($request->method, ['fpx-1', 'fpx-2'])) {
            $method = 'fpx';
        }

        $gateway = Gateway::whereType($method)->whereDefault(1)->whereActive(1)->first();
        $transaction = $vendor->transactions()->save(new Transaction([
            'type' => 'subscription',
            'method' => $method,
            'status' => 'pending',
            'user_id' => $user->id,
            'organization_unit_id' => isset($gateway) ? $gateway->organization_unit_id : Config::get('app.global_cart_ou'),
            'amount' => 100,
            'ip' => request()->ip(),
            'gateway_id' => isset($gateway) ? $gateway->id : null,
            'cached_data' => serialize($cached_data)
        ]));

        session()->put('txn_id', $transaction->id);
        session()->put('txn_type', 'registration');
        if (in_array($request->method, ['fpx-1', 'fpx-2'])) {
            session()->put('fpx_type', $request->method);
        }
        if ($method == 'fpx' && $gateway->version == '7.0') {
            $redirect = redirect('payment/' . $method . '/bank-list');
        } else {
            $redirect = redirect('payment/' . $method . '/connect');
        }
        return $redirect;
    }

    public function callbackPayment($transaction_id)
    {

        // $transaction = Transaction::findOrFail(Session::get('txn_id', $request->transaction_id));
        $transaction = Transaction::findOrFail($transaction_id);
        $vendor = $transaction->vendor;
        $subscription = $transaction->subscription;

        if ($transaction->user_id != auth()->user()->id) {
            return $this->_access_denied();
        }

        session()->forget('txn_id');
        session()->forget('txn_type');
        return view('registration.callback_payment', compact('transaction', 'subscription', 'vendor'));
    }

    protected function upenRegistered($query)
    {

        $status = false;
        $not_registered = 'Syarikat belum berdaftar dengan sistem pkk';
        $params = [
            'nombor_pendaftaran' => $query,
            'pilihan' => 1,
            'submit' => 'Cari'
        ];
        $url = 'https://smk.selangor.gov.my/kk/';

        try {
            $client = new Client();
            $crawler = $client->request('POST', $url, $params);
        } catch (Exception $e) {
            session()->flash(
                'error',
                'Haraf maaf. Pendaftaran tidak dapat dilakukan buat masa ini disebabkan kegagalan pengesahan data dari sistem Unit Perancangan Ekonomi Negeri Selangor.'
            );
            return $status;
        }

        try {
            $response = trim($crawler->filter('.hasil .hurufbesar')->first()->text());
            $response = trim($response);
        } catch (Exception $e) {
            session()->flash(
                'error',
                'Haraf maaf. Pendaftaran tidak dapat dilakukan buat masa ini disebabkan kegagalan pengesahan data dari sistem Unit Perancangan Ekonomi Negeri Selangor.'
            );
            return $status;
        }

        if ($response == $not_registered) {
            session()->flash(
                'error',
                'Haraf Maaf! Syarikat anda belum berdaftar dengan Unit Perancang Ekonomi Negeri Selangor (UPEN).<br>Sila buat pendaftaran di Seksyen Pihak Berkuasa Tempatan, Tingkat 4, Bangunan SSAAS terlebih dahulu. <a href="http://www.selangor.gov.my/index.php/pages/view/119" target="_blank" title="Muat Turun Borang">Maklumat Lanjut</a>.'
            );
        } else {
            $status = true;
        }

        return $status;
    }

    public function registerUser()
    {
        return view('registration.register-user');
    }

    public function storeRegisterUser(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validation->fails()) {
            $error_msg = trans('auth.alerts.duplicated_credentials');
            return redirect('register-user')->withErrors($validation)->withInput()->with('error', $error_msg);
        } else {

            $user                        = new User;
            $user->name                  = $request->name;
            $user->email                 = $request->email;
            $user->username              = $request->email;
            $user->password              = $request->password;
            $user->organization_unit_id  = $request->organization_unit_id;
            $user->role_applied          = $request->role_applied;
            $user->approved              = null;

            User::setRules('storeUser');
            if ($user->save()) {
                $notice = 'Akaun anda telah didaftarkan. Sila semak email untuk pengesahan akaun.';
                return redirect('/')->with('notice', $notice);
            } else {
                $error = $user->errors()->all(':message')[0];
            }

            return redirect('register-user')->withInput($request->except('password'))->with('error', $error);
        }
    }
}
