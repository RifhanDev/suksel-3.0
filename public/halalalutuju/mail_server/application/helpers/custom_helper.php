<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function encryptString(string $string)
{
	// Store cipher method
	$ciphering = "BF-CBC";

	// Use OpenSSl encryption method
	// $iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;

	// Use random_bytes() function which gives
	// randomly 16 digit values
	$encryption_iv = "lala6699";

	// Alternatively, we can use any 16 digit
	// characters or numeric for iv
	$encryption_key = md5('suksuk2023');

	// Encryption of string process starts
	$encryption = openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);

	return base64_encode($encryption);
}

function decryptString(string $hash_string)
{
	$hash_string = base64_decode($hash_string);
	// Store cipher method
	$ciphering = "BF-CBC";

	// Use OpenSSl encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;

	// Use random_bytes() function which gives
	// randomly 16 digit values
	$encryption_iv = "lala6699";

	// Store the decryption key
	$decryption_key = md5('suksuk2023');

	// Descrypt the string
	$decryption = openssl_decrypt($hash_string, $ciphering, $decryption_key, $options, $encryption_iv);

	return $decryption;
}

