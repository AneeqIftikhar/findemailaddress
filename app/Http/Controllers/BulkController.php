<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Jobs\NotifyServer;
use App\Imports\FindEmailsImport;
use App\Imports\VerifyEmailsImport;
use App\UserFiles;
use Excel;
class BulkController extends Controller
{
	public function import_find(Request $request)
	{

		$user=Auth::user();
		$filename=''.time() . uniqid(rand());
		$file=request()->file('excel_file');
		if($file)
		{
			$path = $file->getRealPath();
			$data = array_map('str_getcsv', file($path));
	    	$csv_data = array_slice($data, 0, 3);
	    	$csv_data[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $csv_data[0]);
			$excel_name=$filename.'.'.$file->getClientOriginalExtension();
			if(!$file->move(public_path('excel'),$excel_name))
			{
			   return json_encode(array('status'=>"fail",'message','Unexpected Storage Error'));
			}
			$user_file = new UserFiles;
			$user_file->name = $excel_name;
			$user_file->user_id=$user->id;
			$user_file->title=$request->title;
			$user_file->type='find';
			$user_file->status='Mapping Required';
			$user_file->save();

			return json_encode(array('status'=>"success",'data'=>$csv_data,'file_id'=>$user_file->id));
		}
		else
		{
			return json_encode(array('status'=>"fail",'message','Unexpected error occurred while trying to process your request'));
		} 

	}
	public function process_import(Request $request)
	{
	    $user=Auth::user();
	    $user_file = UserFiles::where('id',$request->file_id)->first();
	    if($user_file)
	    {
	    	$user_file->status='Pending Import';
			$user_file->save();
	    	$email_import=new FindEmailsImport;
	        $email_import->setUser($user);
	        $email_import->setUserFile($user_file);
	        $email_import->setHeaderMappings($request->first_name,$request->last_name,$request->domain);
	        Excel::import($email_import , public_path('excel/'.$user_file->name))->chain([

	            new NotifyServer($user,$user_file),
	         ]);
	        return json_encode(array('status'=>"success",'data'=>[]));
	    }
	    else
	    {
	    	return json_encode(array('status'=>"fail",'message','File Not Found'));
	    }
	    
	}
    public function old_import_find(Request $request) 
	{
		$user=Auth::user();
		$filename=''.time() . uniqid(rand());
		$file=request()->file('excel_file');
		if($file)
		{
			$excel_name=$filename.'.'.$file->getClientOriginalExtension();

			$user_file = new UserFiles;
			$user_file->name = $excel_name;
			$user_file->user_id=$user->id;
			$user_file->title=$request->title;
			$user_file->type='find';
			$user_file->status='Pending Import';
			$user_file->save();
			if(!$file->move(public_path('excel'),$excel_name))
			{
			   return false;
			}
			else
			{
			    $email_import=new FindEmailsImport;
		        $email_import->setUser($user);
		        $email_import->setUserFile($user_file);
		        Excel::import($email_import , public_path('excel/'.$excel_name))->chain([

		            new NotifyServer($user,$user_file),
		         ]);
			}
			return json_encode(array('status'=>"success"));
			$files=$user->userFiles()->get();
			return redirect()->route('list', compact('files'));
		}
		else
		{
			return back()->withInput()->with('error_message','Unexpected error occurred while trying to process your request');
		}

	}
	public function import_verify(Request $request) 
	{
		$user=Auth::user();
		$filename=''.time() . uniqid(rand());
		$file=request()->file('excel_file');
		if($file)
		{
			$excel_name=$filename.'.'.$file->getClientOriginalExtension();

			$user_file = new UserFiles;
			$user_file->name = $excel_name;
			$user_file->user_id=$user->id;
			$user_file->title=$request->title;
			$user_file->type='verify';
			$user_file->status='Pending Import';
			$user_file->save();
			if(!$file->move(public_path('excel'),$excel_name))
			{
			   return false;
			}
			else
			{
			    $email_import=new VerifyEmailsImport;
		        $email_import->setUser($user);
		        $email_import->setUserFile($user_file);
		        Excel::import($email_import , public_path('excel/'.$excel_name))->chain([

		            new NotifyServer($user,$user_file),
		         ]);
			}
			$files=$user->userFiles()->get();
			return redirect()->route('list', compact('files'));
		}
		else
		{
			return back()->withInput()->with('error_message','Unexpected error occurred while trying to process your request');
		}

	}
}
