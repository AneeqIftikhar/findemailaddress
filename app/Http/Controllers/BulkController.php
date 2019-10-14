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
			$files=$user->userFiles()->get();
			return redirect()->route('list', compact('files'));
		}
		else
		{
			return back()->withInput()->with('error_message','Unexpected error occurred while trying to process your request');
		}

	}
	public function import_find(Request $request) 
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
