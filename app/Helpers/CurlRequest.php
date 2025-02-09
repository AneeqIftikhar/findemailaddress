<?php

namespace App\Helpers;


class CurlRequest{

    public static function find_email($first_name,$last_name,$domain){


    	$endpoint = env('PYTHON_SERVER_IP','http://3.17.231.9:5000/')."find";
        $postdata='data=[{"'.'firstName":"'.$first_name.'", "'.'lastName":"'.$last_name.'", "'.'domainName": "'.$domain.'"}]';
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 240);
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
    	$endpoint = env('PYTHON_SERVER_IP','http://3.17.231.9:5000/')."verify";
        $postdata='data=["'.$email.'"]';
  		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
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
    public static function add_automizy_contact($user)
    {
        $endpoint = "https://gateway.automizy.com/v2/smart-lists/2/contacts";
        $name=explode(" ",$user->name);
        if(count($name)>2)
        {
            $first_name=$name[0];
            $last_name=$name[count($name)-1];
        }
        else if (count($name)>1)
        {
            $first_name=$name[0];
            $last_name=$name[1];
        }
        else
        {
            $first_name=$name[0];
            $last_name=" ";
        }
        if($last_name==" ")
        {
            $postdata='{
                    "email":"'.$user->email.'",
                    "customFields":{
                        "firstname":"'.$first_name.'"
                    }
                }';
        }
        else
        {
            $postdata='{
                    "email":"'.$user->email.'",
                    "customFields":{
                        "firstname":"'.$first_name.'",
                        "lastname":"'.$user->last_name.'"
                    }
                }';
        }

        $authorization = "Authorization: Bearer ".env('AUTOMIZY_TOKEN','');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',$authorization));
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

    public static function companiesCurl($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
