<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Socialite;
use Auth;
use Exception;
use Ramsey\Uuid\Uuid;
use App\FastSpring\FastSpringApi;
use App\Package;
use App\UserPackagesLogs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Helpers\CurlRequest;
class SocialAuthLinkedinController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }


    public function callback()
    {
    	DB::beginTransaction();
        try {


            $linkdinUser = Socialite::driver('linkedin')->user();
            $existUser = User::where('email',$linkdinUser->email)->first();
            if($existUser) {
                Auth::loginUsingId($existUser->id);
                return redirect()->to('/find');
            }
            else {
            	$free_package=Package::where('name','free')->first();
                $user = new User;
                $user->user_uuid = Uuid::uuid4();
                $user->package_id = $free_package->id;
                $user->name = $linkdinUser->name;
                $user->email = $linkdinUser->email;
                // $user->linkedin_id = $linkdinUser->id;
                $user->password = Hash::make((rand(10000000,99999999)));
                $user->email_verified_at = Carbon::now();
                $user->first_login = 0;
                $user->save();
                $FastSpringApi=new FastSpringApi();
                $name=explode(" ",$user->name);
	            if(count($name)>2)
	            {
	                $first_name=$name[0];
	                $last_name=$name[count($name)-1];
	            }
	            else if (count($name)>1)
	            {
	                $first_name=$name[0];
	                $last_name=$name[1];
	            }
	            else
	            {
	                $first_name=$name[0];
	                $last_name=" ";
	            }
	            $if_exists=$FastSpringApi->getCustomerUsingEmail($user->email);
	            if($if_exists['result']=="error")
	            {
	                $account=$FastSpringApi->createCustomer($first_name,$last_name,$user->email,$user->user_uuid);
	                $user->payment_user_reference=$account['id'];
	            }
	            else
	            {
	                $user->payment_user_reference=$if_exists['accounts'][0]['id'];
	                
	            }
	            $user->save();
	            $user_package_log = new UserPackagesLogs;
	            $user_package_log->package()->associate($free_package);
	            $user_package_log->user()->associate($user);
	            $user_package_log->save();
            	DB::commit();
                if (config('app.env')=='production') {

                    $server_output=CurlRequest::add_automizy_contact($user);
                }
                Auth::loginUsingId($user->id);
                return redirect()->to('/upgrade_account');
            }
            
        } 
        catch (Exception $e) {
            DB::rollback();
            return $e;
        }
        catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }
}