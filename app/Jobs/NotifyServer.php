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
class NotifyServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
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


        $this->file->status="Import Completed";
        $this->file->save();
        $endpoint = env('PYTHON_SERVER_IP','http://3.17.231.9:5000/');
        if($this->file->type=='find')
        {
            $endpoint = $endpoint.'bulk_find';
        }
        else
        {
            $endpoint = $endpoint.'bulk_verify';
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

    }
}
