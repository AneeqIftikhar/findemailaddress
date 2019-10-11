<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\UserFiles;
use App\Emails;
use Validator;
class BulkApiController extends Controller
{
    public function fetch_file(Request $request) 
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
			$file=UserFiles::where('id',$request->id)->first();
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
	// public function update_emails(Request $request) 
	// {
	// 	try
	// 	{

	// 		$validator = Validator::make($request->all(), [
	// 		    'id' => ['required'],
	// 		    'json_response' => ['required'],
	// 		]);

	// 		if ($validator->fails()) {
	// 		  $errors = $validator->errors();
	// 		    return response()->json(["errors"=>$errors],422);
	// 		}
	// 		$email=Emails::where('id',$request->id)->first();
	// 		if($email)
	// 		{
	// 			$first_name=$email->first_name;
	//         	$last_name=$email->last_name;
	//         	$domain=$email->domain;
	//         	$json_output=json_decode($request->json_response);
	// 			$status="";
	// 			$type=$email->type;
	// 			$email="";
	// 			$error="";
	// 			if($json_output && count($json_output)>0)
	// 			{
	// 			   $status=$json_output[0]->status;
	// 			   $server_status="Valid";
	// 			   if($json_output[0]->status != 'Valid')
	// 			   {
				      
	// 			      if($json_output[0]->mx==null || $json_output[0]->mx=='')
	// 			      {
	// 			         $status="No Mailbox";
	// 			         $server_status="No Mailbox";
	// 			      }
	// 			      if($json_output[0]->status==null || $json_output[0]->status=='')
	// 	              {
	// 	                $status="Not Found";
	// 	              }
	// 			      else if($json_output[0]->status=='Catch All')
	// 			      {
	// 			         $email=strtolower($first_name).'@'.strtolower($domain);
	// 			      }
	// 			      else if($json_output[0]->status=='Risky')
	// 	              {
	// 	                $email=$json_output[0]->email;
	// 	              }

	// 			   }
	// 			   else
	// 			   {
	// 			      $email=$json_output[0]->email;
	// 			   }
	// 			return json_encode(array('status'=>'success','data'=>$email));
	// 		}
	// 		else
	// 		{
	// 			return json_encode(array('status'=>'fail','message'=>"Email Not Found"));
	// 		}
			
			

	// 	}
	// 	catch(Exception $e)
	// 	{
	// 		return json_encode(array('status'=>'fail','message'=>"Unexpected Error"));
	// 	}

	// }
}
