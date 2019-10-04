<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Emails;
use App\Rules\BlackListDomains;
use App\Rules\IsValidDomain;
use Validator;
use App\Helpers\CurlRequest;
use App\Helpers\Functions;
class EmailApiController extends Controller
{


	function find_email_api(Request $request)
	{
		try
		{

			$validator = Validator::make($request->all(), [
			    'first_name' => ['required', 'string', 'max:50'],
			    'last_name' => ['required', 'string', 'max:50'],
			    'domain' => ['required', 'string', 'max:50', new BlackListDomains,new IsValidDomain],
			]);

			if ($validator->fails()) {
			  $errors = $validator->errors();
			    return response()->json(["errors"=>$errors],422);
			}
			$first_name=strtolower(Functions::removeAccents($request->first_name));
        	$last_name=strtolower(Functions::removeAccents($request->last_name));
        	$domain=strtolower(Functions::removeAccentsDomain($request->domain));

			$server_output = $server_output=CurlRequest::find_email($first_name,$last_name,$domain);

			$json_output=json_decode($server_output);

			
			$status="";
			$type="find";
			$email="";
			$error="";
			if($json_output && array_key_exists('curl_error',$json_output))
			{
				$error=$json_output->curl_error;
				$status="Not Found";
			}
			else
			{
				if($json_output && count($json_output)>0)
				{
				   $status=$json_output[0]->status;
				   if($json_output[0]->status != 'Valid')
				   {
				      
				      if($json_output[0]->mx==null || $json_output[0]->mx=='')
				      {
				         $status="No Mailbox";
				      }
				      if($json_output[0]->status=='Catch All')
				      {
				         $email=strtolower($first_name).'@'.strtolower($domain);
				      }

				   }
				   else
				   {
				      $email=$json_output[0]->email;
				   }
				   
				} 
			}
			return json_encode(array('status'=>$status,'emails'=>$email,'logs'=>$json_output[0],'error'=>$error));

		}
		catch(Exception $e)
		{

		}
	}
    function verify_email_api(Request $request)
    {
		try
		{
			$validator = Validator::make($request->all(), [
			     'email' => ['required', 'string', 'email', 'max:255',new BlackListDomains],
			]);

			if ($validator->fails()) {
				$errors = $validator->errors();
			    return response()->json(["errors"=>$errors],422);
			}
			$email=strtolower(Functions::removeAccentsEmail($request->email));
			$server_output=CurlRequest::verify_email($email);
			$json_output=json_decode($server_output);

			$first_name="";
			$last_name="";
			$domain="";
			$email_status="";
			$server_status="";
			$type="verify";
			$error="";

			if($json_output && array_key_exists('curl_error',$json_output))
			{
				$error=$json_output->curl_error;
				$email_status="Not Found";
				$server_status="-";
			}
			else
			{
				$email_status="Valid";
				$server_status="Valid";
	            if($json_output[0]->mx==null || $json_output[0]->mx=='')
	            {
	               $server_status="No Mailbox";
	               $email_status="-";
	            }
	            else
	            {
					if($json_output[0]->status==null || $json_output[0]->status==''|| $json_output[0]->status=='Not Found')
					{
						$email_status="Invalid";
					}
					else
					{
						$email_status=$json_output[0]->status;
					}
	            }
			}

			return json_encode(array('email_status'=>$email_status,'server_status'=>$server_status));
		}
		catch(Exception $e)
		{

		}
    }
    
}
