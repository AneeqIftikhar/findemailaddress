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
    	$user=Auth::user();
    	$data=[];
    	if($request['full_name'] && $request['full_name']!='' && $request['full_name']!=null)
    	{
    		$data['name']=$request['full_name'];
    	}
    	if($request['company_name'] && $request['company_name']!='' && $request['company_name']!=null)
    	{
    		$data['company_name']=$request['company_name'];
    	}
    	if($request['phone'] && $request['phone']!='' && $request['phone']!=null)
    	{
    		$data['phone']=$request['phone'];
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
    	try
    	{
    		$user=Auth::user();
	      	$password=Hash::make($request['password']);
	      	$user->password=$password;
	      	$user->save();
	      	return json_encode(['status'=>'success','message'=>'Password Update Successfully']);
    	}
    	catch(\Exception $e)
    	{
    		return json_encode(['status'=>'fail','message'=>$e->getMessage()]);
    	}
    	
    }
}
