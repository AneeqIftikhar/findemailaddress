<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriptions;
class SubscriptionController extends Controller
{
    public function webhook(Request $request)
    {
    	$subscription=new Subscriptions();
    	$subscription->activated=$request->getContent();
    	$subscription->save();
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
