<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('cors', ['except' => ['login']]);
    }
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user=auth('api')->user();
        if($user->ticketit_admin==0)
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
    public function get_users(Request $request)
    {

        if(!auth('api')->user() || auth('api')->user()->ticketit_admin==0)
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $min=$request->input('min');
        $max=$request->input('max');
        if($min==0 && $max==0)
        {
            $all_users=User::where('ticketit_admin',0)->get();
        }
        else if($min==0 && $max!=0)
        {
            $all_users=User::where('ticketit_admin',0)->where('id','<=',$max)->get();
        }
        else if($min!=0 && $max==0)
        {
            $all_users=User::where('ticketit_admin',0)->where('id','>=',$min)->get();
        }
        else
        {
            $all_users=User::where('ticketit_admin',0)->where('id','>=',$min)->where('id','<=',$max)->get();
        }
        foreach ($all_users as $key=>$value)
        {
            $all_users[$key]->user_agent=($all_users[$key]->user_agent) ? json_decode($all_users[$key]->user_agent):'';
        }
        return response()->json(['users' => $all_users], 200);

    }
}
