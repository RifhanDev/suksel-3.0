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

	// Algoritma hash untuk menandatangan dan mengesahkan. FPX versi lama
	// menggunakan SHA-1; versi terkini PayNet menggunakan SHA-256. Dijadikan
	// property supaya boleh diuji tanpa mengubah kelakuan lalai — nilai lalai
	// kekal SHA-1, sama seperti sebelum ini.
	public $signature_algo = OPENSSL_ALGO_SHA1;

	// Badan respons mentah PayNet daripada panggilan bankList() terakhir.
	// Tanpa ini bankList() hanya memulangkan false dan balasan sebenar PayNet
	// — selalunya badan ralat yang menerangkan PUNCA penolakan — hilang terus.
	// Fasa log-only dalam pelan go-live juga perlukan badan mentah ini.
	public $last_response_body;

	// Status HTTP dan pengepala respons PayNet daripada panggilan terakhir.
	// PayNet membalas badan 'ERROR' generik untuk apa-apa permintaan yang
	// ditolak — POST kosong pun sama — jadi badan itu tiada nilai diagnostik.
	// Pengepala x-oracle-dms-ecid pula ialah ID korelasi log Oracle mereka:
	// ia membolehkan PayNet mencari permintaan tepat ini dalam log sendiri
	// dan menyatakan sebab penolakan yang badan respons sembunyikan.
	public $last_response_status;

	/** @var array<string, array<int, string>> */
	public $last_response_headers = [];
	
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
		openssl_sign($this->source_string, $signature, $key, $this->signature_algo);
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
		$result = openssl_verify($source_string, $signature, $publicKey, $this->signature_algo);

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
		$source_string = $this->source_string = implode('|', $params);
		
		// Use the key path already resolved in the constructor from this gateway's
		// exchange_id, rather than re-hardcoding one merchant's filename here.
		$file = fopen($this->private_key, 'r');
		$private_key = fread($file, 8192);
		fclose($file);

		$key = openssl_get_privatekey($private_key);
		openssl_sign($source_string, $signature, $key, $this->signature_algo);

		$signature = $params['fpx_checkSum'] = strtoupper(bin2hex($signature));
		ksort($params);

		// Derive the bank-list host from reference_url (the gateway's own endpoint_url
		// or daemon_url, whichever the caller passed in) — same host an admin already
		// set for this gateway row in the /gateways screen, just swapped to the
		// RetrieveBankList path. Falls back to the production URL 2.0 always hardcoded
		// here, so behaviour is unchanged when no reference_url is given.
		$bankListUrl = self::bankListUrl($this->reference_url);

		$client = new \GuzzleHttp\Client();
		$response = $client->post($bankListUrl, ['form_params' => $params, 'verify' => config('services.fpx.verify_tls', true)]);

		$this->last_response_status  = $response->getStatusCode();
		$this->last_response_headers = $response->getHeaders();

		$bodyRaw = $this->last_response_body = (string) $response->getBody();
		$params  = self::explodeToPairs($bodyRaw, '&');

		// PayNet's response may be an error body with no fpx_bankList key at
		// all (wrong exchange id, bad checksum, upstream error) — a hard
		// index lookup on $data['fpx_bankList'] fatals with "Undefined array
		// key" instead of surfacing that as a normal failure.
		if (! isset($params['fpx_bankList'])) {
			return false;
		}

		return self::explodeToPairs(urldecode($params['fpx_bankList']), ',', '~');
	}

	/**
	 * Endpoint RetrieveBankList bagi satu gateway.
	 *
	 * PayNet menyajikan UAT dan produksi sebagai dua hos berasingan dengan
	 * laluan yang sama, jadi hos diambil daripada endpoint_url gateway itu
	 * sendiri — hos yang admin sudah tetapkan dalam skrin /gateways — dan
	 * hanya laluannya ditukar. Diasingkan daripada bankList() supaya alat
	 * diagnostik boleh melaporkan URL yang BENAR-BENAR dipanggil tanpa
	 * menyalin semula logik ini dan berisiko ia terpesong.
	 */
	public static function bankListUrl(?string $referenceUrl): string
	{
		$parts = $referenceUrl ? parse_url($referenceUrl) : null;

		if (!empty($parts['scheme']) && !empty($parts['host'])) {
			return "{$parts['scheme']}://{$parts['host']}/FPXMain/RetrieveBankList";
		}

		return 'https://www.mepsfpx.com.my/FPXMain/RetrieveBankList';
	}

	/**
	 * Splits a delimited string into key => value pairs.
	 *
	 * Used for both layers of PayNet's RetrieveBankList response: the outer
	 * query-string-like body ('&'-separated, '=' inner delimiter) and the
	 * embedded bank list (','-separated, '~' inner delimiter — "TEST0021~A").
	 *
	 * The original code did `explode($delim, $body)` with no limit and no
	 * length check, then indexed `[0]` and `[1]` directly. Two failure modes
	 * followed from that:
	 *
	 *   - An empty segment (a body ending in the outer delimiter, or two
	 *     delimiters in a row) produces a one-element array, and `[1]`
	 *     throws "Undefined array key 1" — this is what crashed
	 *     fpx:debug-banklist on the first real signed request that ever
	 *     reached this code path, once OPENSSL_CONF was fixed for CLI use.
	 *   - A value that itself contains the inner delimiter (e.g. a
	 *     base64-padded checksum containing '=') got silently truncated at
	 *     the first occurrence instead of keeping the rest of the value.
	 *
	 * Malformed segments (no inner delimiter at all) are skipped rather than
	 * inserted with a missing value, since a partial key with no value is
	 * not usable data either way.
	 *
	 * @return array<string, string>
	 */
	private static function explodeToPairs(string $raw, string $outerDelimiter, string $innerDelimiter = '='): array
	{
		$pairs = [];

		foreach (explode($outerDelimiter, $raw) as $segment) {
			if ($segment === '') {
				continue;
			}

			$parts = explode($innerDelimiter, $segment, 2);

			if (count($parts) !== 2) {
				continue;
			}

			$pairs[$parts[0]] = $parts[1];
		}

		return $pairs;
	}
}
