<?php 
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Session;
use App\UserPackagesLogs;
use App\Package;
class AddDataToUserSession
{
  public function handle(Login $loginEvent)
  {

  	$package=$loginEvent->user->userPackagesLogs()->orderBy('id', 'DESC')->first();
  	$package_details=$package->package()->first();
  	//
    Session::put('package_name', $package_details->name);
  }
}