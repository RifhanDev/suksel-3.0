<?php

namespace App;

/**
 * DuitNow Payment Gateway Integration
 * 
 * This class handles DuitNow payment gateway integration following PayNet's API specifications.
 * DuitNow supports QR code payments and can be integrated via API or redirect flow.
 */
class DuitNow
{
    public $merchant_id;
    public $prefix;
    public $order_number;
    public $user_email;
    public $user_phone; // DuitNow typically requires phone number
    public $description;
    public $amount;
    public $signature;
    public $prefix_number;
    public $request_type = 'AR'; // Authorization Request
    public $version = '1.0';
    public $source_string = '';

    public $private_key;
    public $api_key; // DuitNow may use API key instead of private key
    public $endpoint_url;

    /**
     * Request parameters for DuitNow API
     * These will vary based on PayNet's actual API specification
     */
    public $request_keys = [
        'merchant_id'      => '',
        'order_id'         => '',
        'amount'           => '',
        'currency'          => 'MYR',
        'description'      => '',
        'customer_email'   => '',
        'customer_phone'   => '',
        'return_url'       => '',
        'callback_url'     => '',
        'timestamp'        => '',
        'signature'        => ''
    ];

    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        $this->prefix_number = $this->prefix . $this->order_number;

        // Set request parameters
        $this->request_keys['merchant_id']    = $this->merchant_id;
        $this->request_keys['order_id']       = $this->prefix_number;
        $this->request_keys['amount']          = $this->amount;
        $this->request_keys['description']    = $this->description;
        $this->request_keys['customer_email']  = $this->user_email ?? '';
        $this->request_keys['customer_phone']  = $this->user_phone ?? '';
        $this->request_keys['return_url']      = $this->return_url ?? '';
        $this->request_keys['callback_url']    = $this->callback_url ?? '';
        $this->request_keys['timestamp']       = date('Y-m-d H:i:s');
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if ($property == 'amount') {
            $this->amount = sprintf('%.2f', $value);
        }
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    /**
     * Generate signature for DuitNow API request
     * Signature method may vary - check PayNet documentation
     */
    public function sign()
    {
        $this->trimRequestKeys();
        ksort($this->request_keys);

        // Remove signature from signing string
        $signing_data = $this->request_keys;
        unset($signing_data['signature']);

        // Create signature string
        // Format: merchant_id|order_id|amount|currency|timestamp|api_key
        $this->source_string = implode('|', [
            $this->merchant_id,
            $this->prefix_number,
            $this->amount,
            'MYR',
            $this->request_keys['timestamp'],
            $this->api_key ?? $this->private_key
        ]);

        // Generate HMAC SHA256 signature (common for DuitNow)
        // Adjust based on PayNet's actual specification
        $this->signature = hash_hmac('sha256', $this->source_string, $this->api_key ?? $this->private_key);
        $this->request_keys['signature'] = $this->signature;

        ksort($this->request_keys);
    }

    /**
     * Verify signature from callback
     */
    public function verifySignature($received_signature, $data)
    {
        // Remove signature from data
        unset($data['signature']);
        ksort($data);

        $signing_string = implode('|', [
            $data['merchant_id'] ?? '',
            $data['order_id'] ?? '',
            $data['amount'] ?? '',
            $data['currency'] ?? 'MYR',
            $data['timestamp'] ?? '',
            $this->api_key ?? $this->private_key
        ]);

        $calculated_signature = hash_hmac('sha256', $signing_string, $this->api_key ?? $this->private_key);

        return hash_equals($calculated_signature, $received_signature);
    }

    /**
     * Trim all request key values
     */
    public function trimRequestKeys()
    {
        foreach ($this->request_keys as $key => $value) {
            $this->request_keys[$key] = trim($value);
        }
    }

    /**
     * Generate QR code data (if using QR payment flow)
     * This would typically call PayNet's QR generation API
     */
    public function generateQR()
    {
        // This would make an API call to PayNet to generate QR code
        // Implementation depends on PayNet's API specification
        // For now, return placeholder
        return [
            'qr_data' => '',
            'qr_url'  => ''
        ];
    }
}
