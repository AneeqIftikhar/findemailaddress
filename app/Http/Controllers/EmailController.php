<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Excel;
use App\EmailsImport;
use App\User;
use App\UserFiles;
use Auth;
use App\Emails;
use App\Exports\VerifyEmailsExport;
use App\Exports\FoundEmailsExport;
use App\Exports\FoundFileEmailsExport;
use App\Exports\VerifyFileEmailsExport;
use App\Rules\BlackListDomains;
use App\Rules\IsValidDomain;
use Validator;
use App\ReportedBounce;
use App\Helpers\CurlRequest;
use App\Helpers\Functions;
use Carbon\Carbon;
use App\FastSpring\FastSpringApi;
use App\PersonalVerificationDomain;
use App\File_Failure;
use App\Rules\WithoutSpaces;
class EmailController extends Controller
{


  function test_fastspring(Request $request)
  {
    //3nYk8pT1S0OBzQdTL3QrTA
    //rO_bGfPeTdipo__qUxC5_g
    //X7XqdNmtRPC7RyZj0-H1AQ
    //W_tVJRN2SL2Uv172r7Ho4Q
    $FastSpringApi = new FastSpringApi();
    return $FastSpringApi->getSession('3nYk8pT1S0OBzQdTL3QrTA','basic');
    //return $FastSpringApi->getCustomer('rO_bGfPeTdipo__qUxC5_g');
    //return $FastSpringApi->getAllCustomers();
    //$account=$FastSpringApi->createCustomer('dummy','dummy','dummy4@dummy.com','kaj2shdkuyiu2yiudhsa');
    //return $account['id'];
    //return $FastSpringApi->updateCustomer();
    //return $FastSpringApi->getCustomerUsingEmail('kh.aneeq@gmail.com');
  }

	/*
		Request Type GET
		Return Find Page with the 10 latest emails of the user
	*/
	function find_email_page(Request $request)
	{
		$user=Auth::user();
		$emails=Auth::user()->emails()->where('type','find')->where('status','!=','Unverified')->orderBy('id', 'DESC')->take(10)->get();
		return view('find',compact('emails'));
	}
	/*
		Request Type GET
		Return Verify Page with 10 latest emails of the user
	*/
	function verify_email_page(Request $request)
	{
		$user=Auth::user();
		$emails=Auth::user()->emails()->where('type','verify')->where('status','!=','Unverified')->orderBy('id', 'DESC')->take(10)->get();
		return view('verify',compact('emails'));
	}
	/*
		Request Type POST
		Parameters: first_name, last_name, domain
		Return: Json Response
	*/
	function find_email_ajax(Request $request)
  {
		try
		{

			$this->validate($request, [
			    'first_name' => ['required', 'string', 'max:50'],
			    'last_name' => ['required', 'string', 'max:50', new WithoutSpaces],
			    'domain' => ['required', 'string', 'max:50', new BlackListDomains,new IsValidDomain],
			]);

			$user=Auth::user();
      if($user->credits>0)
      {
        $first_name=Functions::removeAccents($request->first_name);
        $last_name=Functions::removeAccents($request->last_name);
        $domain=strtolower(Functions::get_domain(Functions::removeAccentsDomain($request->domain)));
        $status="";
        $type="find";
        $email="";
        $error="";
        $server_status="";
        $check_server_dump=1;
        $exists_email=Emails::where('first_name',$first_name)->where('last_name',$last_name)->where('domain',$domain)->latest()->first();
        if($exists_email)
        {
          $server_output=$exists_email->server_json_dump;
          $json_output=json_decode($server_output);
          if($json_output && array_key_exists('OVERRIDE',$json_output))
          {
            $status=$exists_email->status;
            $email=$exists_email->email;
            $server_status=$exists_email->server_status;
            $check_server_dump=0;
            $user->decrement('credits');
          }
          else
          {
            if($exists_email->email != null && $exists_email->status != "Catch All" && $exists_email->status != "Risky")
            {
              $email_created_at = new Carbon($exists_email->created_at);
              $now = Carbon::now();
              if($email_created_at->diffInDays($now)>1)
              {
                $server_output=CurlRequest::verify_email($exists_email->email);
              }
              else
              {
                $server_output=$exists_email->server_json_dump;
              }

            }
            else
            {
              $server_output=CurlRequest::find_email($first_name,$last_name,$domain);
            }
          }
        }
        else
        {
          $server_output=CurlRequest::find_email($first_name,$last_name,$domain);
        }
        if($check_server_dump==1)
        {
          $json_output=json_decode($server_output);
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
                  $email=$first_name.'@'.$domain;
                }
                else if($json_output[0]->status=='Risky')
                {
                  $email=$json_output[0]->email;
                }

              }
              else
              {
                $email=$json_output[0]->email;
                $user->decrement('credits');
              }
            }
          }
        }


        Emails::insert_email($first_name,$last_name,$domain,$email,$status,$user->id,$type,$server_output,$server_status);

        return json_encode(array('status'=>$status,'emails'=>$email,'credits_left'=>$user->credits,'error'=>$error));

      }
      else
      {
        return response()->json(['errors'=>['message'=>['Insufficient credits to perform this request']]], 422 );
      }


		}
		catch(Exception $e)
		{

		}
  }
   	/*
		Request Type POST
		Parameters: email
		Return: Json Response
      }
	*/
    function verify_email_ajax(Request $request)
   	{
   		try
      {
          $this->validate($request, [
              'email' => ['required', 'string', 'email', 'max:255'],
          ]);
          $user=Auth::user();
          if($user->credits>0)
          {

            $email=strtolower(Functions::removeAccentsEmail($request->email));
            $domain = explode('@', $email)[1];
            $first_name="";
            $last_name="";
            $email_status="";
            $server_status="";
            $type="verify";
            $error="";
            if ((strpos($domain, 'yahoo.')!== false) || (strpos($domain, 'aol.com')!== false) || (strpos($domain, 'ymail.com')!== false))
            {

              $e_status="Unknown";
              $s_status="-";
              $server_output=array("type"=>"PersonalVerificationDomain",'status'=>"Catch All");
              $server_output=json_encode($server_output);

               Emails::insert_email($first_name,$last_name,$domain,$email,$e_status,$user->id,$type,$server_output,$s_status);

              return json_encode(array('email_status'=>$e_status,'server_status'=>$s_status,'credits_left'=>$user->credits,'error'=>$error));
            }
            else
            {
              $server_output=CurlRequest::verify_email($email);
              $json_output=json_decode($server_output);

              if($json_output && array_key_exists('curl_error',$json_output))
              {
                $error=$json_output->curl_error;
                $email_status="Unknown";
                $server_status="-";
              }
              else
              {
                $user->decrement('credits');
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


              Emails::insert_email($first_name,$last_name,$domain,$email,$email_status,$user->id,$type,$server_output,$server_status);

              return json_encode(array('email_status'=>$email_status,'server_status'=>$server_status,'credits_left'=>$user->credits,'error'=>$error));
            }
          }
          else
          {
            return response()->json(['errors'=>['message'=>['Insufficient credits to perform this request']]], 422 );
          }

         }
         catch(Exception $e)
         {

         }
   	}



      public function getUserFiles(Request $request)
      {
         $files=Auth::user()->userFiles()->orderBy('id', 'DESC')->get();
         return view('list',compact('files'));
      }
      public function getUserFilesFind(Request $request)
      {
         $files=Auth::user()->userFiles()->where('type', 'find')->orderBy('id', 'DESC')->get();
         return view('files_find',compact('files'));
      }
      public function getUserFilesVerify(Request $request)
      {
         $files=Auth::user()->userFiles()->where('type', 'verify')->orderBy('id', 'DESC')->get();
         return view('files_verify',compact('files'));
      }
      public function getUserFilesAjax(Request $request)
      {
         $files=Auth::user()->userFiles()->orderBy('id', 'DESC')->get();
         return json_encode(array('files'=>$files));
      }
      public function getUserFilesFindAjax(Request $request)
      {
         $files=Auth::user()->userFiles()->where('type', 'find')->orderBy('id', 'DESC')->get();
         return json_encode(array('files'=>$files));
      }
      public function getUserFilesVerifyAjax(Request $request)
      {
         $files=Auth::user()->userFiles()->where('type', 'verify')->orderBy('id', 'DESC')->get();
         return json_encode(array('files'=>$files));
      }
      public function getUserFilesErrorsAjax(Request $request,$id)
      {

        $errors=File_Failure::where('user_file_id',$id)->get();
        return json_encode(array('errors'=>$errors));
      }
      public function getUserFilesErrors(Request $request,$id)
      {
        $user=Auth::user();
        $user_file=UserFiles::where('id',$id)->where('user_id',$user->id)->first();
        if($user_file)
        {
          $errors=File_Failure::where('user_file_id',$id)->paginate(100);
          return view('file_errors',compact('errors'));
        }
      }
      public function getUserFoundEmails(Request $request)
      {
         $emails=Auth::user()->emails()->where('type','find')->where('status','!=','Unverified')->whereNull('user_file_id')->orderBy('id', 'DESC')->with('bounce')->paginate(100);
         return view('find_history',compact('emails'));
      }
      public function getUserVerifiedEmails(Request $request)
      {
         $emails=Auth::user()->emails()->where('type','verify')->where('status','!=','Unverified')->whereNull('user_file_id')->orderBy('id', 'DESC')->with('bounce')->paginate(100);
         return view('verify_history',compact('emails'));
      }

      public function getEmailsFromFile(Request $request,$id)
      {
         $user=Auth::user();
         $user_file=UserFiles::where('id',$id)->where('user_id',$user->id)->first();
         if($user_file)
         {
            $emails=Emails::where('user_file_id',$id)->paginate(100);
            $data=['emails'=>$emails,'file'=>$user_file];
            return view('emails',compact('data',$data));
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
         $email_export=new FoundEmailsExport();
         $email_export->set_details($records);
         return Excel::download($email_export, 'emails.'.$type);
       }

       public function downloadFoundRecordsFile(Request $request,$id,$type,$records)
       {
          $file=UserFiles::where('id',$id)->first();
          if($file->type=="find")
          {
            $email_export=new FoundFileEmailsExport();
          }
          else
          {
            $email_export=new VerifyFileEmailsExport();
          }
          $email_export->set_details($id,$records);
          return Excel::download($email_export, $file->title.'-processed.'.$type);
       }

       public function downloadVerifiedRecords(Request $request,$type,$records)
       {
         $email_export=new VerifyEmailsExport();
         $email_export->set_details($records);
         return Excel::download($email_export, 'emails.'.$type);
       }

       public function report_bounce(Request $request)
       {
            $user=Auth::user();
            $bounce = new ReportedBounce;
            $bounce->email_id = $request->bounce_email_id;
            $bounce->user_id = $user->id;
            $bounce->status = "Bounce Reported";
            $bounce->message = $request->bounce_message;
            $bounce->save();

          if($request->bounce_email_type=="find")
          {
            $emails=Auth::user()->emails()->where('type','find')->with('bounce')->get();
            return view('find_history',compact('emails'));
          }
          else
          {
            $emails=Auth::user()->emails()->where('type','verify')->with('bounce')->get();
            return view('verify_history',compact('emails'));
          }



       }



       /*
       Old Commenmted Codes
       */

    /*
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

                  $user->decrement('credits');
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
            $user->decrement('credits');
            if($json_output[0]->mx==null || $json_output[0]->mx=='')
            {
               $server_status="No Mailbox";
               $email_status="-";
            }
            else
            {
              if($json_output[0]->status==null || $json_output[0]->status=='')
              {
                $email_status="Not Found";
              }
              else
              {
                $email_status=$json_output[0]->status;
              }
            }
            $emails_db = new Emails;
            $emails_db->email = $request->email;
            $emails_db->user_id = $user->id;
            $emails_db->status = $email_status;
            $emails_db->server_status = $server_status;
            $emails_db->type = 'verify';
            $emails_db->save();
            //return json_encode($json_output[0]);
            return json_encode(array('email_status'=>$email_status,'server_status'=>$server_status,'credits_left'=>$user->credits,'MX'=>$json_output[0]->mx));
         }
         catch(Exception $e)
         {

         }
   	}*/
}
