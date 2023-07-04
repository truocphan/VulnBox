<?php
/**
 * @package    miniOrange
 * @author	   miniOrange Security Software Pvt. Ltd.
 * @license    GNU/GPLv3
 * @copyright  Copyright 2015 miniOrange. All Rights Reserved.
 *
 *
 * This file is part of miniOrange SAML plugin.
 */

class MOAESEncryption {
    /**
     * @param string $data - the key=value pairs separated with &
     * @return string
     */
    public static function encrypt_data($data, $key) {
        $key    = openssl_digest($key, 'sha256');
        $method = 'AES-128-CBC';
        $ivSize = openssl_cipher_iv_length($method);
        $iv     = openssl_random_pseudo_bytes($ivSize);
        $strCrypt = openssl_encrypt ($data, $method, $key,OPENSSL_RAW_DATA||OPENSSL_ZERO_PADDING, $iv);
        return base64_encode($iv.$strCrypt);
    }


    /**
     * @param string $data - crypt response from Sagepay
     * @return string
     */
    public static function decrypt_data($data, $key) {
        $strIn = base64_decode($data);
        $key    = openssl_digest($key, 'sha256');
        $method = 'AES-128-CBC';
        $ivSize = openssl_cipher_iv_length($method);
        $iv     = substr($strIn,0,$ivSize);
        $data   = substr($strIn,$ivSize);
        $clear  = openssl_decrypt ($data, $method, $key, OPENSSL_RAW_DATA||OPENSSL_ZERO_PADDING, $iv);

        return $clear;
    }

    private static function pkcs5_pad($text) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $size - (strlen($text) % $size);
        return $text . str_repeat(chr($pad), $pad);
    }

    private static function pkcs5_unpad($text) {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) return false;
        if (strspn($text, $text[strlen($text) - 1], strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
}