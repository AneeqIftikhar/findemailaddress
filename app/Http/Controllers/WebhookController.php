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
                        $Webhook->save();

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
                        $Webhook->save();

                    }
                    else if($event['type']=="subscription.charge.completed")
                    {
                        $user=User::where('payment_user_reference',$event['data']['subscription']['account'])->first();
                        // $Webhook=Webhook::where('user_id',$user->id)->first();
                        $new_package=Package::where('name',$event['data']['subscription']['product'])->first();
                        // if($Webhook && $Webhook->status=="UPDATE_IN_PROGRESS")
                        // {
                        //     $previous_package=Package::find($user->package_id);
                            
                        //     if($previous_package->id<$new_package->id)
                        //     {
                        //         $user->credits=Package::calculateProratedCredits($previous_package,$new_package,$event['data']['subscription']['nextInSeconds'],$user);
                        //     }
                        //     $Webhook->delete();
                        // }
                        if($event['data']['total']<$new_package->amount)
                        {
                            $previous_package=Package::find($user->package_id);
                            
                            if($previous_package->id<$new_package->id)
                            {
                                $user->credits=Package::calculateProratedCredits($previous_package,$new_package,$event['data']['subscription']['nextInSeconds'],$user);
                            }
                            // $Webhook->delete();
                        }
                        else
                        {
                            $user->credits=$user->credits+$new_package->credits;
                        }
                        $user->package_id=$new_package->id;
                        $user->save();
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
        $package=Package::where('name',$package_name)->first();
        if($user->package_id<$package->id)
        {
            $prorate=true;
        }
        else
        {
            $prorate=false;
        }
        $FastSpringApi = new FastSpringApi();
        return $FastSpringApi->updateSubscription($user->subscription_id,$package_name,$prorate);
    }
}
