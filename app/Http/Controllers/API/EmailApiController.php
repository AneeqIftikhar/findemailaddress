<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PluginEmails;
use App\Emails;
use App\Failed_Logs;
use App\Rules\BlackListDomains;
use App\Rules\IsValidDomain;
use Validator;
use App\Helpers\CurlRequest;
use App\Helpers\Functions;
use Mail;
use App\Invalid_Domains;
class EmailApiController extends Controller
{

	function invalid_domains_api(Request $request)
	{
		$invalid=Invalid_Domains::all();
		return json_encode(array('status'=>'success','data'=>$invalid));
	}
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
			$first_name=Functions::removeAccents($request->first_name);
        	$last_name=Functions::removeAccents($request->last_name);
        	$domain=strtolower(Functions::get_domain(Functions::removeAccentsDomain($request->domain)));

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
				   $server_status="Valid";
				   if($json_output[0]->status != 'Valid')
				   {
				      
				      if($json_output[0]->mx==null || $json_output[0]->mx=='')
				      {
				         $status="No Mailbox";
				         $server_status="No Mailbox";
				      }
				      if($json_output[0]->status==null || $json_output[0]->status=='')
		              {
		                $status="Not Found";
		              }
				      else if($json_output[0]->status=='Catch All')
				      {
				         $email=strtolower($first_name).'@'.strtolower($domain);
				      }
				      else if($json_output[0]->status=='Risky')
		              {
		                $email=$json_output[0]->email;
		              }

				   }
				   else
				   {
				      $email=$json_output[0]->email;
				   }
				   
				} 
			}
			PluginEmails::insert_email($first_name,$last_name,$domain,$email,$status,$type,$server_output,$server_status);
			return json_encode(array('status'=>$status,'emails'=>$email,'error'=>$error));

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
			     'email' => ['required', 'string', 'email', 'max:255'],
			]);

			if ($validator->fails()) {
				$errors = $validator->errors();
			    return response()->json(["errors"=>$errors],422);
			}
			$email=strtolower(Functions::removeAccentsEmail($request->email));
			$domain = explode('@', $email)[1];
            $first_name="";
            $last_name="";
            $email_status="";
            $server_status="";
            $type="verify";
            $error="";
            if ((strpos($domain, 'yahoo.')!== false) || (strpos($domain, 'aol.com')!== false)) 
            {

              $e_status="Unknown";
              $s_status="Valid";
              $server_output=array("type"=>"PersonalVerificationDomain",'status'=>"Catch All");
              $server_output=json_encode($server_output);

              PluginEmails::insert_email($first_name,$last_name,$domain,$email,$e_status,$type,$server_output,$s_status);
              return json_encode(array('email_status'=>$e_status,'server_status'=>$s_status,'error'=>$error));
            }
            else
            {
				$server_output=CurlRequest::verify_email($email);
				$json_output=json_decode($server_output);


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
				PluginEmails::insert_email($first_name,$last_name,$domain,$email,$email_status,$type,$server_output,$server_status);
				return json_encode(array('email_status'=>$email_status,'server_status'=>$server_status));
			}
		}
		catch(Exception $e)
		{

		}
    }
    function get_emails_api(Request $request)
    {
		try
		{

			if($request->key=="hamza_local_key")
			{
				$emails=Emails::orderBy('id', 'DESC')->paginate(100);
				return json_encode(array('emails'=>$emails));
			}
			else
			{
				abort(404);
			}
			
			
			
		}
		catch(Exception $e)
		{

		}
    }
    function add_emails_api(Request $request)
    {
		try
		{
			if($request->key=="hamza_local_key")
			{
				$validator = Validator::make($request->all(), [
				    'email' => ['required', 'string', 'email', 'max:255',new BlackListDomains],
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
		        $domain=Functions::get_domain(strtolower(Functions::removeAccentsDomain($request->domain)));
		        $status="Valid";
		        $type="find";
		        $email=strtolower(Functions::removeAccentsEmail($request->email));
		        $server_status="Valid";
		        $server_output=array("OVERRIDE"=>"1");
				$server_output=json_encode($server_output);
				Emails::insert_email($first_name,$last_name,$domain,$email,$status,1,$type,$server_output,$server_status);
				return json_encode(array('status'=>'success'));
			}
			else
			{
				abort(404);
			}
			
			
			
		}
		catch(Exception $e)
		{
			abort(404);
		}
    }

    function failed_response_notification(Request $request)
    {
    	$data = $request->json_response;
    	$server_output=json_decode($data);
    	$failed_logs=new Failed_Logs;
    	$failed_logs->server_json_dump=$data;
    	if(isset($server_output->proxy))
    	{
    		$failed_logs->proxy=json_encode($server_output->proxy);
    	}
    	$failed_logs->save();
    	if($request->send=="true")
    	{
    		$email_address=env('FAILED_RESPONSE_EMAIL','notifications@findemailaddress.co');
        	Mail::send('emails.failed_response', ['json_response' => $data], function ($m) use ($data,$email_address) {
            $m->to($email_address)->subject('Failed Response');
        });
    	}
    	
        return json_encode(array('status'=>'success'));
    }
    
}
