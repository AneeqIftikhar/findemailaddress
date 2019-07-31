<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Jobs\ParseExcelFile;
use Excel;
use App\EmailsImport;
use App\Jobs\EmailsLookup;
use App\User;
use App\UserFiles;
use Auth;
use App\Emails;
use App\Exports\EmailsExport;
class EmailController extends Controller
{
   	function find_email_ajax(Request $request)
   	{
   		$endpoint = "http://3.213.137.104:4500/find";
         $postdata='data=[{"'.'firstName":"'.$request->first_name.'", "'.'lastName":"'.$request->last_name.'", "'.'domainName": "'.$request->domain.'"}]';
         $ch = curl_init();

         curl_setopt($ch, CURLOPT_URL,$endpoint);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

         $server_output = curl_exec ($ch);

         curl_close ($ch);

         $json_output=json_decode($server_output);
         $status='Catch All';
         $result=array();
         for($i=0;$i<count($json_output);$i++)
         {
            if($json_output[$i]->status=="fail")
            {
               $status='fail';
            }
         }
         if($status=='fail')
         {
            for($i=0;$i<count($json_output);$i++)
            {
               if($json_output[$i]->status!="fail")
               {
                  $status='success';
                  array_push($result,$json_output[$i]->email);

               }
            }
         }
         // if($json_output[0]->status!="fail" && $json_output[0]->status!="error")
         // {
         //    echo "Found";
         // }
         // else
         // {
         //    echo "Not Found";
         // }
         $user=Auth::user();
         $user->credits=($user->credits)-3;
         $user->save();
         return json_encode(array('status'=>$status,'emails'=>$result));


   	}
   	function verify_email_ajax(Request $request)
   	{
   		
         $endpoint = "http://3.213.137.104:4500/verify";
         $postdata='data=["'.$request->email.'"]';
   		$ch = curl_init();

         curl_setopt($ch, CURLOPT_URL,$endpoint);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

         $server_output = curl_exec ($ch);

         curl_close ($ch);

         $json_output=json_decode($server_output);
         if($json_output[0]->status!="fail" && $json_output[0]->status!="error")
         {
            echo "Found";
         }
         else
         {
            echo "Not Found";
         }
         $user=Auth::user();
         $user->credits=($user->credits)-1;
         $user->save();

         return $server_output;
   	}

      public function import(Request $request) 
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
            $user_file->save();
            if(!$file->move(public_path('excel'),$excel_name))
            {
               return false;
            }
           else
           {
               $emailJob = (new ParseExcelFile($user,$user_file))->onQueue('high');
               dispatch($emailJob);
           }
           return view('batch_find');
         }
         else
         {
            return back()->withInput()
                                  ->with('error_message','Unexpected error occurred while trying to process your request');
         }
         
         
         //$email_import=new EmailsImport;
         //$email_import->setUserId($user->id);

         // $emailJob = new EmailsLookup($user);
         // Excel::import($email_import , request()->file('excel_file'))->chain([
         //    dispatch($emailJob),
         // ]);
         //Excel::import($email_import , request()->file('excel_file'));



      }
      public function getUserFiles(Request $request)
      {
         $files=Auth::user()->userFiles()->get();
         return view('list',compact('files'));
      }
      public function getUserFoundEmails(Request $request)
      {
         $emails=Auth::user()->emails()->get();
         return view('find_history',compact('emails'));
      }
      public function getUserVerifiedEmails(Request $request)
      {
         $emails=Auth::user()->emails()->get();
         return view('verify_history',compact('emails'));
      }

      public function getEmailsFromFile(Request $request,$id)
      {
         $user=Auth::user();
         $user_file=UserFiles::where('id',$id)->where('user_id',$user->id)->first();
         if($user_file)
         {
            $emails=Emails::where('user_file_id',$id)->get();
            return view('emails',compact('emails','id'));
         }
         else
         {
            return back()->with('error_message','Request Not Allowed');
         }
         
      }
      public function downloadExcel(Request $request,$id,$type,$records)
       {
         $user=Auth::user();
         $user_file=UserFiles::where('id',$id)->first();
         if($user_file && $user_file->user_id==$user->id)
         {
            $email_export=new EmailsExport();
            $email_export->set_details($id,$records);
            return Excel::download($email_export, 'emails.'.$type);         
         }
         else
         {
            return back()->with('error_message','Request Not Allowed');
         }
         
       }
       public function downloadFoundRecords(Request $request,$records)
       {
         $user=Auth::user();
         // $user_file=UserFiles::where('id',$id)->first();
         // if($user_file && $user_file->user_id==$user->id)
         // {
         //    $email_export=new EmailsExport();
         //    $email_export->set_details($id,$records);
         //    return Excel::download($email_export, 'emails.'.$type);         
         // }
         // else
         // {
         //    return back()->with('error_message','Request Not Allowed');
         // }
       }
}
