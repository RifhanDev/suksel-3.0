<?php

namespace App\Models;

use Illuminate\Support\Arr;

class Fpx
{
	public $merchant_id;
	public $prefix;
	public $order_number;
	public $user_email;
	public $description;
	public $amount;
	public $exchange_id;
	public $seller_id;
	public $signature;
	public $prefix_number;
	public $bank_code;
	public $request_type  = 'AR';
	public $msg_token     = '01';
	public $version       = '5.0';
	public $source_string = '';

	// Optional: a gateway URL (endpoint_url or daemon_url) from the same row this
	// merchant_id came from. bankList() derives its request host from this — same
	// host the admin already set in the gateways screen, just a different path —
	// instead of a separate config value that could drift out of sync with it.
	public $reference_url;

	public $private_key;
	
	public $request_keys = [
	'fpx_buyerAccNo'      => '',
	'fpx_buyerBankBranch' => '',
	'fpx_buyerBankId'     => '',
	'fpx_buyerEmail'      => '',
	'fpx_buyerIban'       => '',
	'fpx_buyerId'         => '',
	'fpx_buyerName'       => '',
	'fpx_makerName'       => '',
	'fpx_msgToken'        => '',
	'fpx_msgType'         => 'AR',
	'fpx_productDesc'     => '',
	'fpx_sellerBankCode'  => '01',
	'fpx_sellerExId'      => '',
	'fpx_sellerExOrderNo' => '',
	'fpx_sellerId'        => '',
	'fpx_sellerOrderNo'   => '',
	'fpx_sellerTxnTime'   => '',
	'fpx_txnAmount'       => '',
	'fpx_txnCurrency'     => 'MYR',
	'fpx_version'         => '5.0'
	];
	
	public function __construct($attributes=[]) {
		foreach($attributes as $key => $value) { 
			$this->{$key} = $value;
		}
	
		$merchant_id = explode('|', $attributes['merchant_id']);
		$this->exchange_id    = trim($merchant_id[0]);
		$this->seller_id      = trim($merchant_id[1]);
		$this->prefix_number  = $this->prefix . $this->order_number;
		
		$this->request_keys['fpx_sellerExId']    = $this->exchange_id;
		$this->request_keys['fpx_sellerOrderNo'] = $this->request_keys['fpx_sellerExOrderNo'] = $this->prefix_number;
		$this->request_keys['fpx_sellerTxnTime'] = date('YmdHis');
		$this->request_keys['fpx_sellerId']      = $this->seller_id;
		$this->request_keys['fpx_txnAmount']     = $this->amount;
		$this->request_keys['fpx_buyerEmail']    = $this->user_email;
		$this->request_keys['fpx_productDesc']   = $this->description;
		$this->request_keys['fpx_msgType']       = $this->request_type;
		$this->request_keys['fpx_msgToken']      = $this->msg_token;
		$this->request_keys['fpx_buyerBankId']   = $this->bank_code;
		$this->request_keys['fpx_version']       = $this->version;
		
		$this->private_key  = base_path() . '/fpx/' . $this->exchange_id . '.key';
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	public function __set($property, $value) {
		if($property == 'amount') {
			$this->amount = sprintf('%2.f', $value);
		}
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
	
		return $this;
	}
	
	public function prefill($data=[]) {
		foreach($data as $key => $value) {
			if($key == 'fpx_checkSum') continue;
				$this->request_keys[$key] = $value;
		}
		unset($this->request_keys['fpx_checkSum']);
	}
	
	public function sign() {
		$this->trimRequestKeys();
		ksort($this->request_keys);
		$this->source_string = implode('|', array_values($this->request_keys));
		$file = fopen($this->private_key, 'r');
		$private_key = fread($file, 8192);
		fclose($file);
		$key = openssl_get_privatekey($private_key);
		openssl_sign($this->source_string, $signature, $key, OPENSSL_ALGO_SHA1);
		$this->signature = $this->request_keys['fpx_checkSum'] = strtoupper(bin2hex($signature));
		ksort($this->request_keys);
	}
	
	public function trimRequestKeys() {
		foreach($this->request_keys as $key => $value) {
			$this->request_keys[$key] = trim($value);
		}
	}

	/**
	 * Verify that a response really came from PayNet, by checking its fpx_checkSum
	 * against PayNet's public certificate.
	 *
	 * This is the exact inverse of sign() above: same field ordering (ksort by key,
	 * values joined with '|') and same algorithm (RSA-SHA1), except we verify with
	 * PayNet's public key instead of signing with our private one.
	 *
	 * Returns the raw source string alongside the result so callers can log what was
	 * actually hashed — essential while rolling this out, because a mismatch is
	 * usually caused by an unexpected extra field in the payload rather than a
	 * genuinely forged message.
	 *
	 * @return array{valid: bool, source_string: string, reason: string|null}
	 */
	public static function verifyResponseSignature(array $data, ?string $certificatePath = null): array {
		$fail = function ($reason, $source = '') {
			return ['valid' => false, 'source_string' => $source, 'reason' => $reason];
		};

		if (empty($data['fpx_checkSum'])) {
			return $fail('fpx_checkSum missing from payload');
		}

		$checksum = $data['fpx_checkSum'];
		unset($data['fpx_checkSum']);

		$data = array_map(function ($value) {
			return is_scalar($value) ? trim((string) $value) : '';
		}, $data);

		ksort($data);
		$source_string = implode('|', array_values($data));

		$certificatePath = $certificatePath ?: base_path() . '/fpx/FPX.cer';

		if (!is_readable($certificatePath)) {
			return $fail("certificate not readable at {$certificatePath}", $source_string);
		}

		$publicKey = openssl_pkey_get_public(file_get_contents($certificatePath));

		if ($publicKey === false) {
			return $fail('could not parse certificate: ' . openssl_error_string(), $source_string);
		}

		$signature = @hex2bin($checksum);

		if ($signature === false) {
			return $fail('fpx_checkSum is not valid hex', $source_string);
		}

		// openssl_verify() returns 1 (valid), 0 (invalid) or -1 (error) — only 1 is a pass.
		$result = openssl_verify($source_string, $signature, $publicKey, OPENSSL_ALGO_SHA1);

		return [
			'valid'         => $result === 1,
			'source_string' => $source_string,
			'reason'        => $result === 1 ? null : ($result === -1 ? 'openssl error: ' . openssl_error_string() : 'signature mismatch'),
		];
	}
	
	public function bankList() {
		// $params = array_only($this->request_keys, ['fpx_msgType', 'fpx_msgToken', 'fpx_version', 'fpx_sellerExId']);
		$params = Arr::only($this->request_keys, ['fpx_msgType', 'fpx_msgToken', 'fpx_version', 'fpx_sellerExId']);
		
		ksort($params);
		$source_string = implode('|', $params);
		
		// Use the key path already resolved in the constructor from this gateway's
		// exchange_id, rather than re-hardcoding one merchant's filename here.
		$file = fopen($this->private_key, 'r');
		$private_key = fread($file, 8192);
		fclose($file);

		$key = openssl_get_privatekey($private_key);
		openssl_sign($source_string, $signature, $key, OPENSSL_ALGO_SHA1);

		$signature = $params['fpx_checkSum'] = strtoupper(bin2hex($signature));
		ksort($params);

		// Derive the bank-list host from reference_url (the gateway's own endpoint_url
		// or daemon_url, whichever the caller passed in) — same host an admin already
		// set for this gateway row in the /gateways screen, just swapped to the
		// RetrieveBankList path. Falls back to the production URL 2.0 always hardcoded
		// here, so behaviour is unchanged when no reference_url is given.
		$parts       = $this->reference_url ? parse_url($this->reference_url) : null;
		$bankListUrl = !empty($parts['scheme']) && !empty($parts['host'])
			? "{$parts['scheme']}://{$parts['host']}/FPXMain/RetrieveBankList"
			: 'https://www.mepsfpx.com.my/FPXMain/RetrieveBankList';

		$client = new \GuzzleHttp\Client();
		$response = $client->post($bankListUrl, ['form_params' => $params, 'verify' => config('services.fpx.verify_tls', true)]);
		
		$bodyRaw = $response->getBody();
		$bodyArr = explode('&', $bodyRaw);
		
		if (is_array($bodyArr)) {
			foreach ($bodyArr as $body) {
				$body = explode('=', $body);
				$data["$body[0]"] = $body[1];
			}
		
			$data = explode(',', urldecode($data['fpx_bankList']));
			// return urldecode($data['fpx_bankList']);
			
			foreach ($data as $bankRaw) {
				$bankRaw = explode('~', $bankRaw);
				$bank["$bankRaw[0]"] = $bankRaw[1];
			}
		
			return $bank;
		}
		
		return false;
	}
}
