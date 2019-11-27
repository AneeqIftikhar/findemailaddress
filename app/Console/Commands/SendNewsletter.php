<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Mail;
class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a newsletter email to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {

            Mail::raw("Test", function ($mail) use ($user) {
                $mail->from('info@findemailaddress.co');
                $mail->to($user->email)
                    ->subject('Email Daily');
            });
        }
         
        $this->info('Test Mail sent to All Users');
    }
}
