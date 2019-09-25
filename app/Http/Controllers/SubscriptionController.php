<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriptions;
use App\User;
use App\Package;
use App\UserPackagesLogs;
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

                if($response['events'][0]['type']=="subscription.activated")
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

                    if($product_name=="small" || $product_name=="Small" || $product_name=="basic"|| $product_name=="Basic")
                    {
                        $p_name="Basic";
                    }
                    else if($product_name=="medium" || $product_name=="Medium" || $product_name=="Pro"|| $product_name=="pro")
                    {
                        $p_name="Medium";
                    }
                    else if($product_name=="large" || $product_name=="Large" || $product_name=="Enterprise"|| $product_name=="enterprise")
                    {
                        $p_name="Large";
                    }
                    else
                    {
                        $p_name="Free";
                    }
                    $package=Package::where('name',$p_name)->first();
                    $credits=$package->credits;
                    $user->credits=$credits;
                    $user->current_plan=$p_name;
                    $user->save();

                    $user_package_log = new UserPackagesLogs;
                    $user_package_log->package()->associate($package);
                    $user_package_log->user()->associate($user);
                    $user_package_log->save();
                }
                else if($response['events'][0]['type']=="subscription.updated")
                {
                    $user=User::where('payment_user_reference',$response['events'][0]['data']['account'])->first();
                    $subscription_id=$response['events'][0]['data']['subscription'];
                    $active=$response['events'][0]['data']['active'];
                    $product_name=$response['events'][0]['data']['product'];
                    $price=$response['events'][0]['data']['price'];
                    $subscription=Subscriptions::where('subscription_id',$subscription_id)->first();
                    if($subscription->product_name=='small' || $subscription->product_name=="Small" || $subscription->product_name=="basic"|| $subscription->product_name=="Basic")
                    {
   
                        if($product_name=="medium" || $product_name=="Medium" || $product_name=="Pro"|| $product_name=="pro")
                        {
                            $p_name="Medium";
                            $medium=Package::where('name',$p_name)->first();
                            $small=Package::where('name','Basic')->first();
                            $difference_in_days=($response['events'][0]['data']['nextInSeconds']-time())/(24*3600);
                            if($difference_in_days>0)
                            {
                                $credits=($difference_in_days*$medium->credits/30)-($difference_in_days*$small->credits/30);
                            }
                            else
                            {
                                $credits=0;
                            }
                            $user->credits=$user->credits+$credits;
                        }
                        else if($product_name=="large" || $product_name=="Large" || $product_name=="Enterprise"|| $product_name=="enterprise")
                        {
                            $p_name="Large";
                            $large=Package::where('name',$p_name)->first();
                            $small=Package::where('name','Basic')->first();
                            $difference_in_days=($response['events'][0]['data']['nextInSeconds']-time())/(24*3600);
                            if($difference_in_days>0)
                            {
                                $credits=($difference_in_days*$large->credits/30)-($difference_in_days*$small->credits/30);
                            }
                            else
                            {
                                $credits=0;
                            }
                            $user->credits=$user->credits+$credits;
                        }

                    }
                    else if($subscription->product_name=='medium' || $subscription->product_name=="Medium" || $subscription->product_name=="Pro"|| $subscription->product_name=="pro")
                    {
                        if($product_name=="large" || $product_name=="Large" || $product_name=="Enterprise"|| $product_name=="enterprise")
                        {
                            $p_name="Large";
                            $large=Package::where('name',$p_name)->first();
                            $medium=Package::where('name','Medium')->first();
                            $difference_in_days=($response['events'][0]['data']['nextInSeconds']-time())/(24*3600);
                            if($difference_in_days>0)
                            {
                                $credits=($difference_in_days*$large->credits/30)-($difference_in_days*$medium->credits/30);
                            }
                            else
                            {
                                $credits=0;
                            }
                            $user->credits=$credits;
                            
                        }
                    }
                    if($product_name=="small" || $product_name=="Small" || $product_name=="basic"|| $product_name=="Basic")
                    {
                        $p_name="Basic";
                    }
                    else if($product_name=="medium" || $product_name=="Medium" || $product_name=="Pro"|| $product_name=="pro")
                    {
                        $p_name="Medium";
                    }
                    else if($product_name=="large" || $product_name=="Large" || $product_name=="Enterprise"|| $product_name=="enterprise")
                    {
                        $p_name="Large";
                    }
                    else
                    {
                        $p_name="Free";
                    }
                    $user->current_plan=$p_name;
                    $user->save();
                    $package=Package::where('name',$p_name)->first();
                    $user_package_log = new UserPackagesLogs;
                    $user_package_log->package()->associate($package);
                    $user_package_log->user()->associate($user);
                    $user_package_log->save();
                    if($subscription)
                    {
                        $subscription->webhook_dump=$json_dump;
                        $subscription->subscription_id=$subscription_id;
                        $subscription->active=$active;
                        $subscription->product_name=$product_name;
                        $subscription->price=$price;
                        $subscription->user_id=$user->id;
                        $subscription->save();                   
                    }
                    
                }

                 
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
