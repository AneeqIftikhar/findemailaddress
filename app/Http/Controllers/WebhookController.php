<?php

namespace App\Http\Controllers;

use App\WebhookEndpoint;
use App\WebhookEndpointEvent;
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
    /*
     * Webhooks From Fast Spring
     */
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
                            $user=User::where('payment_user_reference',$event['data']['account']['account'])->first();
                            $subscription_id=$event['data']['subscription'];

                            $Webhook=new Webhooks();
                            $Webhook->webhook_dump=$json_dump;
                            $Webhook->user_id=$user->id;
                            $Webhook->save();

                            $product_name=$event['data']['product']['product'];
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
                            $user=User::where('payment_user_reference',$event['data']['account']['account'])->first();

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
                            $user=User::where('payment_user_reference',$event['data']['account']['account'])->first();
                            $package=Package::where('name',$event['data']['product']['product'])->first();
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
                                $subscription->reason="Charge Failed";
                                $subscription->save();

                            }
                            else
                            {
                                if($subscription->is_active==1)
                                {
                                    $subscription->reason="Charge Failed on Update";
                                }
                                else
                                {
                                    $subscription->reason="User Canceled the Subscription";
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

    /*
     * Our Webhook Management
     */
    public function addWebhookEndpoint(Request $request){

        $validatedData = $request->validate([
            'webhook_url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'webhook_description' => 'string',
            'webhook_secret' => 'required|string',
            'webhook_events' => 'required|array|min:1',
        ]);

        $url = $request->input('webhook_url');
        $secret = $request->input('webhook_secret');
        $desc = $request->input('webhook_description');
        $events = $request->input('webhook_events');
        $webhook_endpoint = new WebhookEndpoints();
        $webhook_endpoint->url = $url;
        $webhook_endpoint->secret = $secret;
        $webhook_endpoint->description = $desc;
        $webhook_endpoint->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $webhook_endpoint->save();


        $endpoint_event_data=[];
        $endpoint_event_array=[];
        foreach ($events as $key => $event)
        {
            $endpoint_event_data['event_id'] = $event;
            $endpoint_event_data['endpoint_id'] = $webhook_endpoint->id;
            $endpoint_event_data['updated_at'] = now();
            $endpoint_event_data['created_at'] = now();
            array_push($endpoint_event_array,$endpoint_event_data);
        }
        WebhookEndpointEvent::insert($endpoint_event_array);

        return Redirect("api")->with('success', 'Endpoint Added Successfully');

    }

    public function updateWebhookEndpoint(Request $request, WebhookEndpoint $endpoint){
        $input = $request->all();
        // dd($input);

        $url = $request->input('webhook_url');
        $secret = $request->input('webhook_secret');
        $desc = $request->input('webhook_description');
        $events = $request->input('webhook_events');

        $endpoint->url = $url;
        $endpoint->secret = $secret;
        $endpoint->description = $desc;
        $endpoint->user_id = Auth::user()->id;
        $endpoint->save();

        $endpoint->events()->sync($events);

        return Redirect("api")->with('success', 'Endpoint Updated Successfully');

    }
    public function deleteWebhookEndpoint(WebhookEndpoint $endpoint){
        $endpoint->delete();
        return Redirect("api")->with('success', 'Endpoint Deleted Successfully');
    }
}
