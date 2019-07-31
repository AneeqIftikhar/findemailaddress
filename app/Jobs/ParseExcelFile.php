<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Excel;
use App\EmailsImport;
use App\Jobs\EmailsLookup;
use App\User;
use App\UserFiles;
class ParseExcelFile implements ShouldQueue
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
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

         $email_import=new EmailsImport;
         $email_import->setUserId($this->user->id);
         $email_import->setUserFileId($this->file->id);
         //$emailJob = new EmailsLookup($user);
         Excel::import($email_import , public_path('excel/'.$this->file->name))->chain([

            new EmailsLookup($this->user,$this->file),
         ]);
         //Excel::import($email_import ,$this->file);
    }
}
