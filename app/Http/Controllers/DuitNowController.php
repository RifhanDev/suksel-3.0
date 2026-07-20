<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\DuitNow;
use App\Gateway;
use Log;

class DuitNowController extends Controller
{
    /**
     * Connect to DuitNow payment gateway
     * This method prepares the payment request and redirects to DuitNow
     */
    public function connect()
    {
        $transaction = Transaction::with('gateway')->find(session('txn_id'));

        if (
            empty($transaction) || empty($transaction->gateway) ||
            $transaction->gateway->type != 'duitnow' ||
            $transaction->method != $transaction->gateway->type
        ) {
            return $this->_access_denied();
        }

        // Set description based on transaction type
        if ($transaction->type == 'subscription') {
            $description = 'Langganan Tender Selangor';
        } elseif ($transaction->type == 'purchase') {
            $description = 'Beli Dokumen Tender Selangor';
        } else {
            $description = 'Pembayaran Sistem Tender Online Selangor';
        }

        // Get user phone number (DuitNow typically requires this)
        $user_phone = $transaction->user->vendor->phone ??
            $transaction->user->phone ??
            '';

        $duitnow = new DuitNow([
            'amount'        => $transaction->amount,
            'merchant_id'   => $transaction->gateway->merchant_code,
            'prefix'        => $transaction->gateway->transaction_prefix,
            'order_number'  => $transaction->number,
            'user_email'    => $transaction->user->email,
            'user_phone'    => $user_phone,
            'description'   => $description,
            'return_url'    => url()->route('duitnow.respond'),
            'callback_url'  => url()->route('duitnow.callback'),
            'api_key'       => $transaction->gateway->private_key, // Using private_key field for API key
            'endpoint_url'  => $transaction->gateway->endpoint_url
        ]);

        $duitnow->sign();

        // Store gateway message for reference
        $messages = [];
        foreach ($duitnow->request_keys as $key => $value) {
            $messages[] = implode(': ', [$key, $value]);
        }

        $transaction->gateway_message = implode(' | ', $messages);
        $transaction->save();

        return view('payment.duitnow.connect', compact('duitnow', 'transaction'));
    }

    /**
     * Handle DuitNow payment response (user redirect)
     */
    public function respond(Request $request)
    {
        // Log::channel('duitnow-respond')->info($request->all());

        $transaction = Transaction::with('gateway')->find(session('txn_id'));

        if (
            empty($transaction) || empty($transaction->gateway) ||
            $transaction->gateway->type != 'duitnow' ||
            $transaction->method != $transaction->gateway->type
        ) {
            return $this->_access_denied();
        }

        // Verify signature if provided
        $gateway = $transaction->gateway;
        if ($request->has('signature') && $gateway) {
            $duitnow = new DuitNow([
                'merchant_id' => $gateway->merchant_code,
                'api_key'     => $gateway->private_key
            ]);

            if (!$duitnow->verifySignature($request->signature, $request->all())) {
                $transaction->status = 'failed';
                $transaction->response_message = 'Invalid transaction signature';
                $transaction->save();

                return redirect()->back()->with('error', 'Invalid payment signature. Please contact support.');
            }
        }

        // Process response based on status
        // Adjust status codes based on PayNet's actual response format
        $status = $request->get('status', '');
        $response_code = $request->get('response_code', '');

        switch (strtoupper($status)) {
            case 'SUCCESS':
            case '00':
            case 'APPROVED':
                $transaction->status = 'success';
                break;
            case 'PENDING':
            case '99':
                $transaction->status = 'pending_authorization';
                break;
            default:
                $transaction->status = 'failed';
                break;
        }

        // Store response data
        $message = [];
        foreach ($request->all() as $key => $value) {
            $message[] = "{$key}: {$value}";
        }

        $transaction->response_code     = $response_code;
        $transaction->response_message  = $request->get('response_message', $request->get('message', ''));
        $transaction->gateway_reference = $request->get('transaction_id', $request->get('reference_id', ''));
        $transaction->gateway_auth       = $request->get('auth_code', '');
        $transaction->gateway_response  = $status;
        $transaction->gateway_message   = implode(' | ', $message);

        $transaction->save();

        // Redirect based on transaction type
        if ($transaction->type == 'purchase') {
            $redirect = redirect('cart/callback/' . $transaction->id);
        } elseif ($transaction->type == 'subscription' && session('txn_type') == 'registration') {
            $redirect = redirect('register/payment_callback/' . $transaction->id);
        } elseif ($transaction->type == 'subscription' && session('txn_type') == 'renewal') {
            $redirect = redirect('renewal_callback/' . $transaction->id);
        } else {
            $redirect = redirect('/');
        }

        return $redirect;
    }

    /**
     * Handle DuitNow server-to-server callback
     * This is called by PayNet's servers to notify payment status
     */
    public function callback(Request $request)
    {
        // Log::channel('duitnow-callback')->info($request->all());

        $order_id = $request->get('order_id', '');
        $merchant_id = $request->get('merchant_id', '');

        if (empty($order_id) || empty($merchant_id)) {
            return response('Invalid request', 400);
        }

        // Find gateway
        $gateway = Gateway::whereType('duitnow')
            ->whereMerchantCode($merchant_id)
            ->whereActive(1)
            ->first();

        if (empty($gateway)) {
            return response('Gateway not found', 404);
        }

        // Extract transaction number from order_id
        $transaction_number = str_replace($gateway->transaction_prefix ?? '', '', $order_id);
        $transaction = Transaction::with('gateway')->whereNumber($transaction_number)->first();

        if (empty($transaction)) {
            return response('Transaction not found', 404);
        }

        // Verify signature
        if ($request->has('signature')) {
            $duitnow = new DuitNow([
                'merchant_id' => $gateway->merchant_code,
                'api_key'     => $gateway->private_key
            ]);

            if (!$duitnow->verifySignature($request->signature, $request->all())) {
                return response('Invalid signature', 400);
            }
        }

        // Don't update if already successful
        if ($transaction->status == 'success') {
            return response('OK', 200);
        }

        // Process callback
        $status = $request->get('status', '');
        $response_code = $request->get('response_code', '');

        switch (strtoupper($status)) {
            case 'SUCCESS':
            case '00':
            case 'APPROVED':
                $transaction->status = 'success';
                break;
            case 'PENDING':
            case '99':
                $transaction->status = 'pending_authorization';
                break;
            default:
                $transaction->status = 'failed';
                break;
        }

        $message = [];
        foreach ($request->all() as $key => $value) {
            $message[] = "{$key}: {$value}";
        }

        $transaction->response_code     = $response_code;
        $transaction->response_message  = $request->get('response_message', $request->get('message', ''));
        $transaction->gateway_reference = $request->get('transaction_id', $request->get('reference_id', ''));
        $transaction->gateway_auth       = $request->get('auth_code', '');
        $transaction->gateway_response  = $status;
        $transaction->gateway_message   = implode(' | ', $message);

        $transaction->save();

        return response('OK', 200);
    }
}
