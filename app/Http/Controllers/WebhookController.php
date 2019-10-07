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
        $signature = $request->header('X-FS-Signature');
        $json_dump=$request->getContent();
        $s = base64_encode(hash_hmac('sha256', $json_dump, env('FASTSPRING_WEBHOOK_HMAC_SECRET','12345678'), true));
        if($s==$signature)
        {   
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

                            $Webhook=new Webhooks();
                            $Webhook->webhook_dump=$json_dump;
                            $Webhook->user_id=$user->id;
                            $Webhook->save();

                        }
                        else if($event['type']=="subscription.charge.completed")
                        {
                            $user=User::where('payment_user_reference',$event['data']['subscription']['account'])->first();
                            $new_package=Package::where('name',$event['data']['subscription']['product'])->first();

                            if($user->package->amount<$new_package->amount)
                            {
                                $previous_package=Package::find($user->package_id);
                                $user->credits=Package::calculateProratedCredits($previous_package,$new_package,$event['data']['subscription']['nextInSeconds'],$user);
                                $subscription=PendingSubscriptions::where('user_id',$user->id)->first();
                                if($subscription)
                                {
                                    $subscription->delete();
                                }
                            }
                            else
                            {
                                $subscription=PendingSubscriptions::where('user_id',$user->id)->first();
                                if($subscription)
                                {
                                    $subscription->delete();
                                }
                                $user->credits=$user->credits+$new_package->credits;
                            }
                            $user->package_id=$new_package->id;
                            $user->save();

                            $Webhook=new Webhooks();
                            $Webhook->webhook_dump=$json_dump;
                            $Webhook->user_id=$user->id;
                            $Webhook->save();
                        }
                        else if($event['type']=="subscription.canceled")
                        {
                            $user=User::where('payment_user_reference',$event['data']['account'])->first();
                            $package=Package::where('name',$event['data']['product'])->first();

                            $subscription=PendingSubscriptions::where('user_id',$user->id)->first();
                            if(!$subscription)
                            {
                                $subscription=new PendingSubscriptions();
                                $subscription->user_id=$user->id;
                                $subscription->current_package_id=$user->package->id;
                                $subscription->next_package_id=$package->id;
                                $subscription->status="CANCELED";
                                $subscription->is_active=0;
                                $subscription->credits=$user->credits;
                                $subscription->reson="Charge Failed";
                                $subscription->save();

                            }
                            else
                            {
                                if($subscription->is_active==1)
                                {
                                    $subscription->reson="Charge Failed on Update";
                                }
                                else
                                {
                                    $subscription->reson="User Canceled the Subscription";
                                }
                                $subscription->is_active=0;
                                $subscription->credits=$user->credits;
                                $subscription->save();
                            } 
                            
                            $free=Package::where('name','free')->first();
                            $user->package_id=$free->id;
                            $user->save();
                           

                            $Webhook=new Webhooks();
                            $Webhook->webhook_dump=$json_dump;
                            $Webhook->user_id=$user->id;
                            $Webhook->save();

                            
                        }
                        else if($event['type']=="subscription.deactivated")
                        {
                            $user=User::where('payment_user_reference',$event['data']['account'])->first();

                            $Webhook=new Webhooks();
                            $Webhook->webhook_dump=$json_dump;
                            $Webhook->user_id=$user->id;
                            $Webhook->save();

                            $free=Package::where('name','free')->first();
                            $user->package_id=$free->id;
                            $user->subscription_id=null;
                            $user->credits=$free->credits;
                            $user->save();
                        }
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
        if($user->package->amount<$package->amount)
        {
            $prorate=true;
            $status="UPGRADE";
        }
        else
        {
            $prorate=false;
            $status="DOWNGRADE";
        }
        $FastSpringApi = new FastSpringApi();
        $resp=$FastSpringApi->updateSubscription($user->subscription_id,$package_name,$prorate);
        if($resp && isset($resp['subscriptions']) && $resp['subscriptions'][0]['result']=="success")
        {
            $subscription=PendingSubscriptions::where('user_id',$user->id)->first();
            if(!$subscription)
            {
                $subscription=new PendingSubscriptions();
                $subscription->user_id=$user->id;
            }
            $subscription->next_package_id=$package->id;
            $subscription->current_package_id=$user->package->id;
            $subscription->credits=$user->credits;
            $subscription->status=$status;
            $subscription->save();
           
        }
        return $resp;
    }
    public function cancel_subscription(Request $request)
    {
        $user=Auth::user();
        $FastSpringApi = new FastSpringApi();
        $resp=$FastSpringApi->cancelSubscription($user->subscription_id);
        if($resp && isset($resp['subscriptions']) && $resp['subscriptions'][0]['result']=="success")
        {
            $subscription=PendingSubscriptions::where('user_id',$user->id)->first();
            if(!$subscription)
            {
                $subscription=new PendingSubscriptions();
                $subscription->user_id=$user->id;
                $subscription->next_package_id=$user->package->id;
                $subscription->current_package_id=$user->package->id;
                $subscription->status="CALCELED";
            }
            $subscription->credits=$user->credits;
            $subscription->is_active=0;
            $subscription->save();

            $free=Package::where('name','free')->first();
            $user->package_id=$free->id;
            $user->save();
           
        }
        return $resp;
    }
    public function uncancel_subscription(Request $request)
    {
        $user=Auth::user();
        $FastSpringApi = new FastSpringApi();
        $resp=$FastSpringApi->uncancelSubscription($user->subscription_id);
        if($resp && isset($resp['subscriptions']) && $resp['subscriptions'][0]['result']=="success")
        {
            $subscription=PendingSubscriptions::where('user_id',$user->id)->first();
            if($subscription)
            {
                $user->package_id=$subscription->current_package_id;
                $user->save();
                if($subscription->status=="CANCELED")
                {
                    $subscription->delete();
                }
                else
                {
                    $subscription->is_active=1;
                    $subscription->save();
                }
                
            }
           
        }
        return $resp;
    }
}
