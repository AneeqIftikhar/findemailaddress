<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
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
}
