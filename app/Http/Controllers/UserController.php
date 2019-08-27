<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\TwoCheckout\TwoCheckoutApi;
use App\Orders;
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
        return $twocheckoutapi->getCustomerSubscriptions("4");
        
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

            $data=[];
            $data['order_reference']=$order['RefNo'];
            $data['amount']=$order['NetPrice'];
            $data['user_id']=$order['CustomerDetails']['ExternalCustomerReference'];
            $data['order_date']=$order['OrderDate'];
            $data['recurring_enabled']=$order['RecurringEnabled'];
            $data['status']=$order['Status'];
            $data['package_name']=$order['Items'][0]['ProductDetails']['Name'];
            $new_orders=new Orders();
            $credits=100;
            if($order['Items'][0]['ProductDetails']['Name']=="Basic")
            {
                $credits=1000;
            }
            $user=Auth::user();
            $user->credits=$user->credits+$credits;
            $user->save();
        }
        
        
        return view('upgrade_account');
        
    }
    
}
