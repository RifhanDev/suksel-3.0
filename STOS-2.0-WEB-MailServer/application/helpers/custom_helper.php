<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function encryptString(string $string)
{
	// Store cipher method - Changed from BF-CBC to AES-256-CBC to match Laravel
	$ciphering = "AES-256-CBC";

	// Use OpenSSl encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;

	// Use 16 bytes IV for AES-256-CBC
	$encryption_iv = substr("lala6699lala6699", 0, $iv_length);

	// Use encryption key - AES-256 needs 32 bytes key
	$encryption_key = substr(md5('suksuk2023'), 0, 32);

	// Encryption of string process starts
	$encryption = openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);

	return base64_encode($encryption);
}

function decryptString(string $hash_string)
{
	// First decode from base64
	$hash_string = base64_decode($hash_string);
	
	// Store cipher method - Changed from BF-CBC to AES-256-CBC to match Laravel
	$ciphering = "AES-256-CBC";

	// Use OpenSSl encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;

	// Use 16 bytes IV for AES-256-CBC
	$encryption_iv = substr("lala6699lala6699", 0, $iv_length);

	// Store the decryption key - AES-256 needs 32 bytes key
	$decryption_key = substr(md5('suksuk2023'), 0, 32);

	// Descrypt the string
	$decryption = openssl_decrypt($hash_string, $ciphering, $decryption_key, $options, $encryption_iv);

	return $decryption;
}

