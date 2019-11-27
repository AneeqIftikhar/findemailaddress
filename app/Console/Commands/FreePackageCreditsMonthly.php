<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Package;
use DateTime;
class FreePackageCreditsMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'free:credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollover 50 credits for free package subscribers';

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
        $package=Package::where('name','free')->first();
        $users = User::where('package_id',$package->id)->where('credits','<',$package->credits)->get();
        foreach ($users as $user) {
            $datetime1 = new DateTime($user->created_at);
            $datetime2 = new DateTime();
            $interval = $datetime2->diff($datetime1);
            $days = $interval->format('%a');
            if($days!=0 && $days%30==0)
            {
                $user->credits=50;
                $user->save();
            }
            
        }
        if(count($users)==0)
        {
            $this->info('No User found');
        }
        else
        {
            $this->info('Credits Rollover successfull');
        }
    }
}
