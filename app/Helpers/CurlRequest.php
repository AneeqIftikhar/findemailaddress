<?php

namespace App\Helpers;


class CurlRequest{

    public static function find_email($first_name,$last_name,$domain){
    	$endpoint = "http://3.17.231.9:5000/find";
        $postdata='data=[{"'.'firstName":"'.$first_name.'", "'.'lastName":"'.$last_name.'", "'.'domainName": "'.$domain.'"}]';
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);


        if (curl_errno($ch)) 
        {
            $error_message = curl_error($ch);
        }
        curl_close ($ch);

        if (isset($error_message)) 
        {
		    return json_encode(['curl_error'=>$error_message]);
		}
		else
		{
			return $server_output;
		}
       	
    }
    public static function verify_email($email)
    {
    	$endpoint = "http://3.17.231.9:5000/verify";
        $postdata='data=["'.$email.'"]';
  		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        if (curl_errno($ch)) 
        {
            $error_message = curl_error($ch);
        }
        curl_close ($ch);
        if (isset($error_message)) 
        {
		    return json_encode(['curl_error'=>$error_message]);
		}
		else
		{
			return $server_output;
		}
    }
}