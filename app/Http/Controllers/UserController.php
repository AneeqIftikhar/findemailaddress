<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Subscription;
use Userdesk\Subscription\Classes\SubscriptionConsumer;
use Userdesk\Subscription\Classes\SubscriptionProduct;
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
        $Checkout = Subscription::processor('2checkout');
        $consumer=new SubscriptionConsumer('Aneeq','661/L','Lahore','Punjab','54000','Pakistan','kh.aneeq@gmail.com','+923346783142');
        $product=new SubscriptionProduct('Basic','25.00','Basic subscription','http://localhost/email_finder_verifier/public/api/handleIpn','http://localhost/email_finder_verifier/public/login','http://localhost/email_finder_verifier/public/login',1,'month',0);
        $Checkout->complete('1',$product,$consumer);
    }
    public function cartComplete(Request $request, $proc){
        $processor = Subscription::processor('2checkout');
        try{
            $result = $processor->pdt($request->all());
        }catch(TransactionException $exception){
            Log::error($exception->getMessage());   
        }
        
        if(!empty($result)){
            $cartId = $result->getId();
            if(!empty($cartId)){
                $action = $result->getAction();    
                if($action=='signup'){
                    //Handle successful Signup
                }
            }else{
                Log::error('Cart Not Found For PDT', ['proc'=>$proc, 'data'=>$request->all()]); 
            }
        }
    }
    public function handleIpn(Request $request, $proc){
        $processor = Subscription::processor('2checkout');
        try{
            $result = $processor->ipn($request->all());
        }catch(Userdesk\Subscription\Exceptions\TransactionException $exception){
            //Handle Exceptions
            Log::error($exception->getMessage());  
        }

        if(!empty($result)){
            $cartId = $result->getId();
            if(!empty($cartId)){
                $action = $result->getAction();        

                if($action=='signup'){
                  //Handle Signup Code
                }else if($action=='payment'){          
                  $transactionId = $result->getIdent();
                  $amount = $result->getAmount();
                  //Handle successful payments
                }else if($action=='refund'){          
                  $transactionId = $result->getIdent();
                  $amount = $result->getAmount();
                  //Handle refunds
                }else if($action=='cancel'){
                  //Handle cancellations;
                }
            }else{
                Log::error('Cart Not Found For IPN', ['proc'=>$proc, 'data'=>$request->all()]); 
            }
        }   
    }
}
