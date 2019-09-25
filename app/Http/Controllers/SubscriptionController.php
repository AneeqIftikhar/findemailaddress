<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriptions;
use App\User;
class SubscriptionController extends Controller
{
    public function webhook(Request $request)
    {
    
        $json_dump=$request->getContent();	
        $response=json_decode($json_dump,true);

        if($response)
        {
            if($response['events'])
            {

                $user=User::where('payment_user_reference',$response['events'][0]['data']['account'])->first();
                $subscription_id=$response['events'][0]['data']['subscription'];
                $active=$response['events'][0]['data']['active'];
                $product_name=$response['events'][0]['data']['product'];
                $price=$response['events'][0]['data']['price'];
                $subscription=new Subscriptions();
                $subscription->webhook_dump=$json_dump;
                $subscription->subscription_id=$subscription_id;
                $subscription->active=$active;
                $subscription->product_name=$product_name;
                $subscription->price=$price;
                $subscription->user_id=$user->id;
                $subscription->save();

                $package=Package::where('name',$product_name)->first();
                $credits=$package->credits;
                $user->credits=$credits;
                $user->save();

                $user_package_log = new UserPackagesLogs;
                $user_package_log->package()->associate($package);
                $user_package_log->user()->associate($user);
                $user_package_log->save();

                Session::put('package_name', $package->name); 
            }
            
        }
    	
        
    }
    public function get_webhook(Request $request)
    {
    	$subscription=Subscriptions::all();
    	if($subscription)
    	{
    		return $subscription;
    	}
    	else
    	{
    		return "ops";
    	}
    	
    }
}
