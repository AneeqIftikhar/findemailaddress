<?php

namespace App\FastSpring;

class FastSpringClient extends \GuzzleHttp\Client
{
    public function __construct()
    {
        $accept = 'application/json';
        $content_type = 'application/json';
        $base_uri=env('FASTSPRING_BASE_URI','');
        $username =  env('FASTSPRING_USERNAME', '');
        $password = env('FASTSPRING_PASSWORD', '');
        $headers = [
            'Accept' => $accept,
            'Content-Type'=>$content_type,
        ];
        parent::__construct(['base_uri' =>$base_uri,'headers'=>$headers,'auth' => [$username, $password]]);
    }
       
}
