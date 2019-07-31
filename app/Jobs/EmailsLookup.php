<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\UserFiles;
use App\Emails;
use App\Jobs\FindOrVerifyEmail;
class EmailsLookup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $emails=null;
    public $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user,UserFiles $file)
    {
        $this->user = $user;
        $this->file=$file;

        
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $find_verify=null;
        $emails=Emails::where('user_id',$this->user->id)->where('user_file_id',$this->file->id)->where('status','unverified')->get();
        $priority="low";
        if(count($emails)>5)
        {
            $priority="low";
        }
        else
        {
            $priority="high";
        }
        foreach ($emails as $key => $email) {
            $email->status='not_found';
            $email->save();
            $find_verify=(new FindOrVerifyEmail($email,$email->first_name.'@'.$email->domain))->onQueue($priority);
            dispatch($find_verify);
            // //1 firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //2 firstName[0]+lastName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name[0].$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //3 firstName+'.'+lastName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name.'.'.$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //4 firstName+lastName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name.$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //5 firstName+lastName[0]+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name.$email->last_name[0].'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //6 firstName[0]+lastName[0]+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name[0].$email->last_name[0].'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //7 last_name+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //8 lastName[0]+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name[0].$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //9 lastName[0]+'.'+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name[0].'.'.$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //10 firstName+'-'+lastName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name.'-'.$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //11 firstName+'_'+lastName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name.'_'.$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //12 firstName[0]+'-'+lastName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name[0].'-'.$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //13 firstName[0]+'_'+lastName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->first_name[0].'_'.$email->last_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //14 lastName[0]+'-'+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name[0].'-'.$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //15 lastName[0]+'_'+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name[0].'_'.$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //16 lastName+'_'+firstName[0]+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.'_'.$email->first_name[0].'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //17 lastName+'-'+firstName[0]+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.'-'.$email->first_name[0].'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //18 lastName+'.'+firstName[0]+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.'.'.$email->first_name[0].'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //19 lastName+firstName[0]+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.$email->first_name[0].'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //20 lastName+'.'+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.'.'.$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //21 lastName+'-'+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.'-'.$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //22 lastName+'_'+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.'_'.$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);

            // //23 lastName+firstName+domain;
            // $find_verify=(new FindOrVerifyEmail($email,$email->last_name.$email->first_name.'@'.$email->domain))->onQueue($priority);
            // dispatch($find_verify);
        }

    }
}
