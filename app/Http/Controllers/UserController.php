<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\TwoCheckout\TwoCheckoutApi;
use App\FastSpring\FastSpringApi;
use App\Orders;
use App\Package;
use App\UserPackagesLogs;
use Session;
class UserController extends Controller
{
    public function update_personal_info(Request $request)
    {
        $this->validate($request, [
             'full_name' => ['required', 'string', 'max:50'],
             'company_name' => ['string', 'max:50'],
             'phone' => ['string', 'max:20'],
         ]);
    	$user=Auth::user();
    	$data=[];
    	$data['name']=$request['full_name'];
    	if($request['company_name'] && $request['company_name']!='' && $request['company_name']!=null)
    	{
    		$data['company_name']=$request['company_name'];
    	}
        else
        {
            $data['company_name']=null;
        }
    	if($request['phone'] && $request['phone']!='' && $request['phone']!=null)
    	{
    		$data['phone']=$request['phone'];
    	}
        else
        {
            $data['phone']=null;
        }
    	if($user->update($data))
    	{
    		return json_encode(['status'=>'success','message'=>'Profile Update Successfully']);
    	}
    	else
    	{
    		return json_encode(['status'=>'fail','message'=>'Server Error']);
    	}

    }
    public function update_password(Request $request)
    {
         $this->validate($request, [
             'password' => ['required', 'string','min:8' ,'max:20']
         ]);
    	try
    	{
    		$user=Auth::user();
	      	$password=Hash::make($request['password']);
	      	$user->password=$password;
	      	$user->save();

            Auth::logout();
	      	return json_encode(['status'=>'success','message'=>'Password Update Successfully']);
    	}
    	catch(\Exception $e)
    	{
    		return json_encode(['status'=>'fail','message'=>$e->getMessage()]);
    	}
    	
    }
    public function test_2checkout(Request $request)
    {
        
        $twocheckoutapi=new TwoCheckoutApi();
        //return $twocheckoutapi->createCustomer("fname","lname","kh.aneeq@gmail.com","id2");

        return $twocheckoutapi->getCustomerSubscriptions('676306262');
        
    }
    public function get_fastspring_session(Request $request)
    {
        $user=Auth::user();
        $FastSpringApi = new FastSpringApi();
        return $FastSpringApi->getSession($user->payment_user_reference,$request->input('package_name'));
    }
    public function return_url(Request $request)
    {
        $orderRef=$request['refno'];
        $exists=Orders::where('order_reference',$orderRef)->first();
        if(!$exists)
        {
            $total=$request['total'];
            $total_currency=$request['total-currency'];
            $signature=$request['signature'];

            $twocheckoutapi=new TwoCheckoutApi();
            $order=$twocheckoutapi->getOrderDetails($orderRef);
            $return_user=User::where('user_uuid',$order['CustomerDetails']['ExternalCustomerReference'])->first();
            $data=[];
            $data['order_reference']=$order['RefNo'];
            $data['amount']=$order['NetPrice'];
            $data['user_id']=$return_user->id;
            $data['order_date']=$order['OrderDate'];
            $data['recurring_enabled']=$order['RecurringEnabled'];
            $data['status']=$order['Status'];
            $data['package_name']=$order['Items'][0]['ProductDetails']['Name'];
            $new_orders=new Orders($data);
            $new_orders->save();
            $credits=0;

            $package=Package::where('name',$order['Items'][0]['ProductDetails']['Name'])->first();
            $credits=$package->credits;

            $user=Auth::user();
            $user->credits=$user->credits+$credits;
            $user->save();

            $user_package_log = new UserPackagesLogs;
            $user_package_log->package()->associate($package);
            $user_package_log->user()->associate($user);
            $user_package_log->save();

            Session::put('package_name', $package->name);
            return view('upgrade_account')->with('message', 'Package Upgraded Successfully');
        }
        return view('upgrade_account')->with('message', 'View Your Subscriptions in Subscription Tab');
        
        
        
    }
    public function getUserSubscriptions(Request $request)
    {
        $user=Auth::user();
        $user->visited_subscription_page=$user->visited_subscription_page+1;
        $twocheckoutapi=new TwoCheckoutApi();
        $data=$twocheckoutapi->getCustomerSubscriptions($user->two_checkout_user_reference);
        return view('subscriptions', ['data'=>$data]);
    }
    public function getUpgradeAccount(Request $request)
    {
        $user=Auth::user();
        $user->visited_pricing_page=$user->visited_pricing_page+1;
        return view('upgrade_account');
    }
        
    public function disableRecurringBilling(Request $request)
    {
        $user=Auth::user();
        $twocheckoutapi=new TwoCheckoutApi();
        $data=$twocheckoutapi->disableRecurringBilling($request['subscription_ref']);
        $data=$twocheckoutapi->getCustomerSubscriptions($user->two_checkout_user_reference);
        return redirect()->back()->with(['data'=>$data]);
    }
    public function enableRecurringBilling(Request $request)
    {
        $user=Auth::user();
        $twocheckoutapi=new TwoCheckoutApi();
        $data=$twocheckoutapi->enableRecurringBilling($request['subscription_ref']);
        $data=$twocheckoutapi->getCustomerSubscriptions($user->two_checkout_user_reference);
        return redirect()->back()->with(['data'=>$data]);
    }

    
}
