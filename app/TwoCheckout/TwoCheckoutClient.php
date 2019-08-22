<?php

namespace App\TwoCheckout;

class TwoCheckoutClient extends \GuzzleHttp\Client
{
    public function __construct()
    {
        $date = gmdate('Y-m-d H:i:s');
        $accept = 'application/json';
        $content_type = 'application/json';
        $code =  env('2CHECKOUT_MERCHANT_CODE', '');
        $key = env('2CHECKOUT_SECRET_KEY', '');
        $base_uri=env('2CHECKOUT_BASE_URI','');
        $hash = hash_hmac('md5', strlen($code) . $code . strlen($date) . $date, $key);
        $headers = [
            'X-Avangate-Authentication' => 'code="' . $code . '" date="' . $date . '" hash="' . $hash . '"',
            'Accept' => $accept,
            'Content-Type'=>$content_type
        ];
        // $setup['headers'] = array_key_exists('headers', $setup) ? array_merge($setup['headers'], $headers) : $headers;
        // unset($setup['code'], $setup['key'], $setup['version']);
        parent::__construct(['base_uri' =>$base_uri,'headers'=>$headers]);
    }
       
}
