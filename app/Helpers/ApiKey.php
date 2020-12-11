<?php

namespace App\Helpers;

class ApiKey
{
    public static function generate_api_key($user)
    {
        $secret = getenv('API_KEY_SECRET');
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);
        $payload = json_encode([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'time' => now()
        ]);
        $base64UrlHeader = base64_encode($header);
        $base64UrlPayload = base64_encode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = base64_encode($signature);
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $jwt;
    }

    public static function search_object_in_array($object,$array)
    {
        foreach ($array as $arr)
        {
            if($object->id==$arr->id)
            {
                return true;
            }
        }
        return false;
    }
}
