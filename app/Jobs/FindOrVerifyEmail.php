<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Emails;
class FindOrVerifyEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $email_address;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Emails $email,$email_address)
    {
        $this->email = $email;
        $this->email_address=$email_address;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $endpoint = "http://3.213.137.104:4500/verify";
        // $postdata='data=["'.$this->email_address.'"]';
        // $ch = curl_init();

        //  curl_setopt($ch, CURLOPT_URL,$endpoint);
        //  curl_setopt($ch, CURLOPT_POST, 1);
        //  curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
        //  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        //  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //  $server_output = curl_exec ($ch);

        //  curl_close ($ch);

        //  $json_output=json_decode($server_output);
        //  if($json_output[0]->status!="fail" && $json_output[0]->status!="error")
        //  {
        //     $this->email->email=$this->email_address;
        //     $this->email->status='verified';
        //     $this->email->save();
        //  }



        /*
        $endpoint = "http://3.213.137.104:4500/find";
         $postdata='data=[{"'.'firstName":"'.$this->email->first_name.'", "'.'lastName":"'.$this->email->last_name.'", "'.'domainName": "'.$this->email->domain.'"}]';
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

                  $this->email->email=$this->email_address;
                  $this->email->status='verified';
                  $this->email->save();

               }
            }
         }
         else
         {
            $this->email->status='catch_all';
            $this->email->save();
         }*/

    }
}
