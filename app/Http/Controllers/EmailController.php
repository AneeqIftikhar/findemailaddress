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
use App\Rules\BlackListDomains;
use App\Rules\IsValidDomain;
use Validator;
class EmailController extends Controller
{



      function find_email_page(Request $request)
      {
         $user=Auth::user();
         $emails=Auth::user()->emails()->where('type','find')->orderBy('id', 'DESC')->take(10)->get();
         return view('find',compact('emails'));
      }
      function verify_email_page(Request $request)
      {
         $user=Auth::user();
         $emails=Auth::user()->emails()->where('type','verify')->orderBy('id', 'DESC')->take(10)->get();
         return view('verify',compact('emails'));
      }
   	function find_email_ajax(Request $request)
   	{
         try
         {
            $this->validate($request, [
                'first_name' => ['required', 'string', 'max:50'],
                'last_name' => ['required', 'string', 'max:50'],
                'domain' => ['required', 'string', 'max:50', new BlackListDomains,new IsValidDomain],
            ]);
            
            $user=Auth::user();
            // return json_encode(array('status'=>"Catch All",'emails'=>'aneeq@dev-rec.com','logs'=>[],'proxy'=>'Proxy','credits_left'=>$user->credits));

            $endpoint = "http://3.17.231.9:5000/find";
            $postdata='data=[{"'.'firstName":"'.$request->first_name.'", "'.'lastName":"'.$request->last_name.'", "'.'domainName": "'.$request->domain.'"}]';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec ($ch);


            if ($error_number = curl_errno($ch)) {
               $emails_db = new Emails;
               $emails_db->first_name = strtolower($request->first_name);
               $emails_db->last_name = strtolower($request->last_name);
               $emails_db->domain = strtolower($request->domain);
               $emails_db->status = "Not Found";
               $emails_db->user_id = $user->id;
               $emails_db->type = 'find';
               $emails_db->save();
                if (in_array($error_number, array(CURLE_OPERATION_TIMEDOUT, CURLE_OPERATION_TIMEOUTED))) {
                  

                  return json_encode(array('status'=>"Not Found",'emails'=>"",'logs'=>[],'proxy'=>[],'credits_left'=>$user->credits,'commands'=>[],'MX'=>"",'Catch All Test'=>"","error"=>"curl timed out")); 
                }
                else
                {
                   return json_encode(array('status'=>"Not Found",'emails'=>"",'logs'=>[],'proxy'=>[],'credits_left'=>$user->credits,'commands'=>[],'MX'=>"",'Catch All Test'=>"","error"=>curl_error($ch))); 
                }
            }
            

            curl_close ($ch);

            // return json_encode($server_output);

            $json_output=json_decode($server_output);
            $status="-";
            if($json_output && count($json_output)>0)
            {
               $status=$json_output[0]->status;
               if($json_output[0]->status != 'Valid')
               {
                  
                  if($json_output[0]->mx==null || $json_output[0]->mx=='')
                  {
                     $status="No Mailbox";
                  }
                  $emails_db = new Emails;
                  $emails_db->first_name = strtolower($request->first_name);
                  $emails_db->last_name = strtolower($request->last_name);
                  $emails_db->domain = strtolower($request->domain);
                  $emails_db->status = $status;
                  $emails_db->user_id = $user->id;
                  $emails_db->type = 'find';
                  $emails_db->save(); 
                  if($json_output[0]->status=='Catch All')
                  {
                     $json_output[0]->email=strtolower($request->first_name).'@'.strtolower($request->domain);
                  }

               }
               else
               {
                  
                  $user->credits=($user->credits)-1;
                  $user->save();
                  $emails_db = new Emails;
                  $emails_db->first_name = strtolower($request->first_name);
                  $emails_db->last_name = strtolower($request->last_name);
                  $emails_db->domain = strtolower($request->domain);
                  $emails_db->status = $json_output[0]->status;
                  $emails_db->email = $json_output[0]->email;
                  $emails_db->user_id = $user->id;
                  $emails_db->type = 'find';
                  $emails_db->save(); 
               }
               
            } 
            

            return json_encode(array('status'=>$status,'emails'=>$json_output[0]->email,'logs'=>$json_output[0]->logs,'proxy'=>$json_output[0]->proxy,'credits_left'=>$user->credits,'commands'=>$json_output[0]->commands,'MX'=>$json_output[0]->mx,'Catch All Test'=>$json_output[0]->catch_all_test));
         }
         catch(Exception $e)
         {

         }
         


   	}
      function find_email_api(Request $request)
      {
         try
         {

            // $this->validate($request, [
            //     'first_name' => ['required', 'string', 'max:50'],
            //     'last_name' => ['required', 'string', 'max:50'],
            //     'domain' => ['required', 'string', 'max:50', new BlackListDomains,new IsValidDomain],
            // ]);
              $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:50'],
                'last_name' => ['required', 'string', 'max:50'],
                'domain' => ['required', 'string', 'max:50', new BlackListDomains,new IsValidDomain],
            ]);

            if ($validator->fails()) {
              $errors = $validator->errors();
                return response()->json(["errors"=>$errors],422);
            }
            $endpoint = "http://3.17.231.9:5000/find";
            $postdata='data=[{"'.'firstName":"'.$request->first_name.'", "'.'lastName":"'.$request->last_name.'", "'.'domainName": "'.$request->domain.'"}]';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec ($ch);


            if ($error_number = curl_errno($ch)) {

                if (in_array($error_number, array(CURLE_OPERATION_TIMEDOUT, CURLE_OPERATION_TIMEOUTED))) {
                  

                  return json_encode(array('status'=>"Not Found",'emails'=>"",'MX'=>"","error"=>"curl timed out")); 
                }
                else
                {
                   return json_encode(array('status'=>"Not Found",'emails'=>"",'MX'=>"","error"=>curl_error($ch))); 
                }
            }
            

            curl_close ($ch);

            $json_output=json_decode($server_output);
            $status="-";
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
                     $json_output[0]->email=strtolower($request->first_name).'@'.strtolower($request->domain);
                  }

               }

               
            } 
            

            return json_encode(array('status'=>$status,'emails'=>$json_output[0]->email,'MX'=>$json_output[0]->mx));
         }
         catch(Exception $e)
         {

         }
         


      }
    function verify_email_api(Request $request)
    {
      try
         {
            // $this->validate($request, [
            //     'email' => ['required', 'string', 'email', 'max:255',new BlackListDomains],
            // ]);
             $validator = Validator::make($request->all(), [
                 'email' => ['required', 'string', 'email', 'max:255',new BlackListDomains],
            ]);

            if ($validator->fails()) {
              $errors = $validator->errors();
                return response()->json(["errors"=>$errors],422);
            }
            $endpoint = "http://3.17.231.9:5000/verify";
            $postdata='data=["'.$request->email.'"]';
          $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec ($ch);

            curl_close ($ch);
            
            $email_status="Valid";
            $server_status="Valid";
            $json_output=json_decode($server_output);
            if($json_output[0]->mx==null || $json_output[0]->mx=='')
            {
               $server_status=$json_output[0]->status;
               $email_status="-";
            }
            else
            {
               $email_status=$json_output[0]->status;
            }

            return json_encode(array('email_status'=>$email_status,'server_status'=>$server_status));
         }
         catch(Exception $e)
         {

         }
    }
   	function verify_email_ajax(Request $request)
   	{
   		try
         {
            $this->validate($request, [
                'email' => ['required', 'string', 'email', 'max:255',new BlackListDomains],
            ]);
            $endpoint = "http://3.17.231.9:5000/verify";
            $postdata='data=["'.$request->email.'"]';
      		$ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec ($ch);

            curl_close ($ch);
            
            $email_status="Valid";
            $server_status="Valid";
            $json_output=json_decode($server_output);
            $user=Auth::user();
            $user->credits=($user->credits)-1;
            $user->save();
            if($json_output[0]->mx==null || $json_output[0]->mx=='')
            {
               $server_status=$json_output[0]->status;
               $email_status="-";
            }
            else
            {
               $email_status=$json_output[0]->status;
            }
            $emails_db = new Emails;
            $emails_db->email = $request->email;
            $emails_db->user_id = $user->id;
            $emails_db->status = $email_status;
            $emails_db->server_status = $server_status;
            $emails_db->type = 'verify';
            $emails_db->save(); 

            return json_encode(array('email_status'=>$email_status,'server_status'=>$server_status,'credits_left'=>$user->credits));
         }
         catch(Exception $e)
         {

         }
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
         $emails=Auth::user()->emails()->where('type','find')->get();
         return view('find_history',compact('emails'));
      }
      public function getUserVerifiedEmails(Request $request)
      {
         $emails=Auth::user()->emails()->where('type','verify')->get();
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
            $email_export->set_details($id,$records,'file',$user_file->type);
            return Excel::download($email_export, 'emails.'.$type);         
         }
         else
         {
            return back()->with('error_message','Request Not Allowed');
         }
         
       }
       public function downloadFoundRecords(Request $request,$type,$records)
       {
         $email_export=new EmailsExport();
         $email_export->set_details(0,$records,'all_db','find');
         return Excel::download($email_export, 'emails.'.$type);
       }
       public function downloadVerifiedRecords(Request $request,$type,$records)
       {
         $email_export=new EmailsExport();
         $email_export->set_details(0,$records,'all_db','verify');
         return Excel::download($email_export, 'emails.'.$type);
       }
}
