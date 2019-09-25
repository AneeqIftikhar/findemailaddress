<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Package;
use App\UserPackagesLogs;
use Auth;
use App\Webhooks;
use App\FastSpring\FastSpringApi;
use App\PendingSubscriptions;
class WebhookController extends Controller
{
    public function webhook(Request $request)
    {
    
        $json_dump=$request->getContent();	
        $response=json_decode($json_dump,true);

        if($response)
        {
            if($response['events'])
            {
                foreach ($response['events'] as $key => $event) 
                {
                    if($event['type']=="subscription.activated")
                    {
                        $user=User::where('payment_user_reference',$event['data']['account'])->first();
                        $subscription_id=$event['data']['subscription'];

                        $Webhook=new Webhooks();
                        $Webhook->webhook_dump=$json_dump;
                        $Webhook->user_id=$user->id;
                        $subscription->save();

                        $product_name=$event['data']['product'];
                        $package=Package::where('name',$product_name)->first();

                        $user->package_id=$package->id;
                        $user->subscription_id=$subscription_id;
                        $user->credits=$package->credits;
                        $user->save();

                        $user_package_log = new UserPackagesLogs;
                        $user_package_log->package()->associate($package);
                        $user_package_log->user()->associate($user);
                        $user_package_log->save();
                    }
                    else if($event['type']=="subscription.updated")
                    {
                        $user=User::where('payment_user_reference',$event['data']['account'])->first();
                       
                        $product_name=$event['data']['product'];
                        $package=Package::where('name',$product_name)->first();

                        $subscription=new PendingSubscriptions();
                        $subscription->user_id=$user->id;
                        $subscription->package_id=$package->id;
                        $subscription->credits=$user->credits;
                        $subscription->status="UPDATE_IN_PROGRESS";
                        $subscription->save();

                        $Webhook=new Webhooks();
                        $Webhook->webhook_dump=$json_dump;
                        $Webhook->user_id=$user->id;
                        $subscription->save();

                    }
                }
            }
        }
    }
    public function get_webhook(Request $request)
    {
    	$subscription=Webhooks::all();
    	if($subscription)
    	{
    		return $subscription;
    	}
    	else
    	{
    		return "ops";
    	}
    	
    }
    public function update_subscription(Request $request)
    {
        $user=Auth::user();
        $package_name=$request->input('package_name');
        $package=Package::where('name',$product_name)->first();
        if($user->package_id<$package->id)
        {
            $prorate=true;
        }
        else
        {
            $prorate=false;
        }
        $FastSpringApi = new FastSpringApi();
        return $FastSpringApi->updateSubscription($subscription->subscription_id,$package_name,$prorate);
    }
}
