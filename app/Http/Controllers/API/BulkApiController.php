<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\UserFiles;
use App\Emails;
use Validator;
class BulkApiController extends Controller
{
    public function fetch_unprocessed_files(Request $request) 
	{
		try
		{

			$file=UserFiles::where('status','Import Completed')->get();
			if($file)
			{
				return json_encode(array('status'=>'success','data'=>$file));
			}
			else
			{
				return json_encode(array('status'=>'fail','message'=>"File Not Found"));
			}
			
			

		}
		catch(Exception $e)
		{
			return json_encode(array('status'=>'fail','message'=>"Unexpected Error"));
		}

	}
	public function fetch_file_emails(Request $request) 
	{
		try
		{

			$validator = Validator::make($request->all(), [
			    'id' => ['required'],
			]);

			if ($validator->fails()) {
			  $errors = $validator->errors();
			    return response()->json(["errors"=>$errors],422);
			}
			$emails=Emails::where('user_file_id',$request->id)->where('status','Unverified')->take(10)->get();
			if($emails)
			{
				return json_encode(array('status'=>'success','data'=>$emails));
			}
			else
			{
				return json_encode(array('status'=>'fail','message'=>"Emails Not Found"));
			}
			
			

		}
		catch(Exception $e)
		{
			return json_encode(array('status'=>'fail','message'=>"Unexpected Error"));
		}

	}
	public function update_emails(Request $request) 
	{
		try
		{

			$validator = Validator::make($request->all(), [
			    'json_response' => ['required'],
			]);

			if ($validator->fails()) {
			    $errors = $validator->errors();
			    return response()->json(["errors"=>$errors],422);
			}
			$json_output_array=json_decode($request->json_response);
			if($json_output_array)
			{
				foreach ($json_output_array as $key => $json_output) 
				{
					$status="";
					$email="";
					$error="";
					$status=$json_output->status;
				    $server_status="Valid";
				    $email_db=Emails::where('id',$json_output->id)->first();
				    if($json_output->status != 'Valid')
				    {
				      
				      if($json_output->mx==null || $json_output->mx=='')
				      {
				         $status="No Mailbox";
				         $server_status="No Mailbox";
				      }
				      if($json_output->status==null || $json_output->status=='')
		              {
		                $status="Not Found";
		              }
				      else if($json_output->status=='Catch All')
				      {

				      	if($email_db->email)
				      	{
				      		$email=$email_db->email;
				      	}
				      	else
				      	{
				      		$email=strtolower($email_db->first_name).'@'.strtolower($email_db->domain);
				      	}
				        
				      }
				      else if($json_output->status=='Risky')
		              {
		                $email=$json_output->email;
		              }

				    }
				    else
				    {
				      $email=$json_output->email;
				    }
				    if($email_db)
				    {
				    	Emails::update_email($email_db,$email,$status,$server_status,json_encode($json_output));
				    }
				    
				}
				   
				return json_encode(array('status'=>'success','data'=>[]));
			}
			else
			{
				return json_encode(array('status'=>'fail','message'=>"Json Response Error"));
			}
			
			

		}
		catch(Exception $e)
		{
			return json_encode(array('status'=>'fail','message'=>"Unexpected Error"));
		}

	}
}
