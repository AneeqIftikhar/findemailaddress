<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Jobs\NotifyServer;
use App\Imports\FindEmailsImport;
use App\Imports\EmailsImportArray;
use App\Imports\VerifyEmailsImport;
use App\UserFiles;
use Excel;
use App\Package;
use Validator;
//use PhpOffice\PhpSpreadsheet\IOFactory;
class BulkController extends Controller
{
	public function import_find(Request $request)
	{
		$validator = Validator::make($request->all(), [
		    'excel_file' => ['required', 'mimes:csv,txt'],
		]);

		if ($validator->fails()) {
		  $errors = $validator->errors();
		    return response()->json(["errors"=>$errors],422);
		}
		$user=Auth::user();
		$filename=''.time() . uniqid(rand());
		$file=request()->file('excel_file');
		if($file)
		{
			$path = $file->getRealPath();
			$data = array_map('str_getcsv', file($path));
			// $request_file_type=IOFactory::identify($file);
			// $objReader = IOFactory::createReader($request_file_type);
			// $objReader->setReadDataOnly(true);
			// $objReader->setLoadSheetsOnly(0);
			// $objPHPExcel = $objReader->load($file);
			// $sheet = $objPHPExcel->getSheet(0);  

			// $total_rows = $sheet->getHighestDataRow();
			
			$total_rows=count($data);
			$unprocessed_files=UserFiles::where('user_id',$user->id)->where('status','=','Import Completed')->get();
			$blocked_credits=0;
			foreach ($unprocessed_files as $key => $unprocessed_file) {
				$blocked_credits=$blocked_credits+$unprocessed_file->total_rows;
			}
			if((($user->credits)-$blocked_credits)<=0)
			{
				return json_encode(array('status'=>"fail",'message'=>'Please wait for previous files to comeplete'));
			}
			$csv_data = Excel::toArray(new EmailsImportArray, $file);
			$excel_name=$filename.'.'.$file->getClientOriginalExtension();
			if(!$file->move(public_path('excel'),$excel_name))
			{
			   return json_encode(array('status'=>"fail",'message'=>'Unexpected Storage Error'));
			}
			
			$user_file = new UserFiles;
			$user_file->name = $excel_name;
			$user_file->user_id=$user->id;
			$user_file->title=$request->title;
			$user_file->type='find';
			$user_file->status='Mapping Required';
			$user_file->total_rows=$total_rows;
			$user_file->save();
			$package=Package::where('id',$user->package_id)->first();
	        if((($user->credits)-$blocked_credits) >= ($package->credits))
	        {
	            $limit=(int) ($package->credits);
	        }
	        else
	        {
	            $limit=(int) (($user->credits)-$blocked_credits);
	        }

	        if($total_rows>=$limit)
	        {
	        	$will_process=$limit;
	        }
	        else
	        {
				$will_process=$total_rows;
	        }

			return json_encode(array('status'=>"success",'data'=>$csv_data[0],'file_id'=>$user_file->id,'limit'=>$will_process));
		}
		else
		{
			return json_encode(array('status'=>"fail",'message'=>'Unexpected error occurred while trying to process your request'));
		} 

	}
	public function import_find_with_file_id(Request $request)
	{

		$user=Auth::user();
		$user_file=UserFiles::where('id',$request->file_id)->first();
		if($user_file)
		{
			$path = public_path('excel')."/".$user_file->name;
			$data = array_map('str_getcsv', file($path));
	    	// $csv_data = array_slice($data, 0, 3);
	    	// $csv_data[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $csv_data[0]);

	    	$csv_data = Excel::toArray(new EmailsImportArray, $path);
			$total_rows=count($data);
	    	$package=Package::where('id',$user->package_id)->first();
	        if($user->credits >= ($package->credits))
	        {
	            $limit=(int) ($package->credits);
	        }
	        else
	        {
	            $limit=(int) ($user->credits);
	        }

	        if(count($data)>=$limit)
	        {
	        	$will_process=$limit;
	        }
	        else
	        {
				$will_process=$total_rows;
	        }
			
			return json_encode(array('status'=>"success",'data'=>$csv_data[0],'file_id'=>$user_file->id,'file_type'=>$user_file->type,'limit'=>$will_process));
		}
		else
		{
			return json_encode(array('status'=>"fail",'message'=>'Unexpected error occurred while trying to process your request'));
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
			$package=Package::where('id',$user->package_id)->first();
	        if($user->credits >= ($package->credits))
	        {
	            $limit=(int) ($package->credits);
	        }
	        else
	        {
	            $limit=(int) ($user->credits);
	        }

	        $exclude_header=false;
	        if($request->exclude_header=="1")
	        {
	        	$exclude_header=true;
	        }
	        $total_rows=$user_file->total_rows;
	        if($exclude_header)
	        {
	            $total_rows=$total_rows-1;
	        }
	        if($limit>$total_rows)
	        {
	        	$limit=$total_rows;
	        }
	        $chunk_size=1;	        
	        if($total_rows>=$limit)
	        {
	            if($limit>1000)
	            {
	                if($limit%10==0)
	                {
	                    $chunk_size = (int) ($limit/10);
	                }
	                else if($limit%2==0)
	                {
	                    $chunk_size = (int) ($limit/2);
	                }
	                else
	                {
	                    $chunk_size = (int) (($limit+1)/2);
	                }
	            }
	            else
	            {
	                $chunk_size = $limit;
	            }
	            
	            
	        }
	        else
	        {
	            if($total_rows>1000)
	            {
	                if($total_rows%10==0)
	                {
	                    $chunk_size = (int) ($total_rows/10);
	                }
	                else if($total_rows%2==0)
	                {
	                    $chunk_size = (int) ($total_rows/2);
	                }
	                else
	                {
	                    $chunk_size = (int) (($total_rows-1)/2);
	                }
	            }
	            else
	            {
	                $chunk_size = $total_rows;
	            }
	        }
	        if($user_file->type=="find")
	        {
	        	$email_import=new FindEmailsImport;
		        $email_import->setUser($user);
		        $email_import->setLimit($limit);
		        $email_import->setChunkSize($chunk_size);
		        $email_import->setUserFile($user_file);
		        $email_import->setExcludeHeader($exclude_header);
		        $email_import->setHeaderMappings($request->first_name,$request->last_name,$request->domain);
		        Excel::import($email_import , public_path('excel/'.$user_file->name))->chain([

		            new NotifyServer($user,$user_file),
		         ]);
	        }
	        else
	        {
	        	$email_import=new VerifyEmailsImport;
		        $email_import->setUser($user);
		        $email_import->setLimit($limit);
		        $email_import->setChunkSize($chunk_size);
		        $email_import->setUserFile($user_file);
		        $email_import->setHeaderMappings($request->email);
		        $email_import->setExcludeHeader($exclude_header);
		        Excel::import($email_import , public_path('excel/'.$user_file->name))->chain([

		            new NotifyServer($user,$user_file),
		         ]);
	        }
	    	
	        return json_encode(array('status'=>"success",'data'=>['limit'=>$limit,'chunk_size'=>$chunk_size]));
	    }
	    else
	    {
	    	return json_encode(array('status'=>"fail",'message'=>'File Not Found'));
	    }
	    
	}
    
	public function import_verify(Request $request) 
	{
		$validator = Validator::make($request->all(), [
		    'excel_file' => ['required', 'mimes:csv,txt'],
		]);

		if ($validator->fails()) {
		  $errors = $validator->errors();
		    return response()->json(["errors"=>$errors],422);
		}

		$user=Auth::user();
		$filename=''.time() . uniqid(rand());
		$file=request()->file('excel_file');
		if($file)
		{
			$path = $file->getRealPath();
			$data = array_map('str_getcsv', file($path));
	    	// $csv_data = array_slice($data, 0, 3);
	    	// $csv_data[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $csv_data[0]);
	    	$unprocessed_files=UserFiles::where('user_id',$user->id)->where('status','=','Import Completed')->get();
	    	$total_rows=count($data);
			$blocked_credits=0;
			foreach ($unprocessed_files as $key => $unprocessed_file) {
				$blocked_credits=$blocked_credits+$unprocessed_file->total_rows;
			}
			if(($user->credits)-$blocked_credits<=0)
			{
				return json_encode(array('status'=>"fail",'message'=>'Please wait for previous files to complete'));
			}
	    	$csv_data = Excel::toArray(new EmailsImportArray, $file);
			$excel_name=$filename.'.'.$file->getClientOriginalExtension();
			if(!$file->move(public_path('excel'),$excel_name))
			{
			   return json_encode(array('status'=>"fail",'message'=>'Unexpected storage error. Please contact support or generate a ticket.'));
			}
			$user_file = new UserFiles;
			$user_file->name = $excel_name;
			$user_file->user_id=$user->id;
			$user_file->title=$request->title;
			$user_file->type='verify';
			$user_file->status='Mapping Required';
			$user_file->total_rows=$total_rows;
			$user_file->save();

			$package=Package::where('id',$user->package_id)->first();
	        if((($user->credits)-$blocked_credits) >= ($package->credits))
	        {
	            $limit=(int) ($package->credits);
	        }
	        else
	        {
	            $limit=(int) (($user->credits)-$blocked_credits);
	        }

	        if(count($data)>=$limit)
	        {
	        	$will_process=$limit;
	        }
	        else
	        {
				$will_process=count($data);
	        }

			return json_encode(array('status'=>"success",'data'=>$csv_data[0],'file_id'=>$user_file->id,'limit'=>$will_process));
		}
		else
		{
			return json_encode(array('status'=>"fail",'message'=>'Unexpected error occurred while trying to process your request'));
		}

	}
}
