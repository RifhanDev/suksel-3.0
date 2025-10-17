<?php

namespace App;


class Ebpg 
{
public $hash_key;
  public $merchant_id;
  public $prefix;
  public $order_number;
  public $amount;
  public $signature;
  public $prefix_number;
  public $description;
  public $return_url;
  private $signature_string;

  public $request_keys = [
    'MERCHANT_ACC_NO'   => '',
    'MERCHANT_TRANID'   => '',
    'AMOUNT'            => '',
    'TRANSACTION_TYPE'  => '3',
    'TXN_SIGNATURE'     => '',
    'RESPONSE_TYPE'     => 'HTTP',
    'RETURN_URL'        => '',
    'TXN_DESC'          => ''
  ];

  public function __construct($attributes)
  {
    foreach($attributes as $key => $value)
    {
      $this->{$key} = $value;
    }

    $this->prefix_number = $this->prefix . $this->order_number;

    $this->request_keys['AMOUNT'] = $this->amount;
    $this->request_keys['MERCHANT_ACC_NO'] = $this->merchant_id;
    $this->request_keys['MERCHANT_TRANID'] = $this->prefix_number;
    $this->request_keys['RETURN_URL'] = $this->return_url;
    $this->request_keys['TXN_DESC'] = $this->description;
  }

  public function sign()
  {
    $this->signature_string = "{$this->hash_key}{$this->merchant_id}{$this->prefix_number}{$this->amount}";
    $this->signature        = hash('sha512', $this->signature_string);
    $this->request_keys['TXN_SIGNATURE'] = $this->signature;
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
}
