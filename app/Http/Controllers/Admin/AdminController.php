<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
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
        $min=0;
        $max=0;
        if($request->has('min'))
            $min=$request->input('min');
        if($request->has('max'))
            $max=$request->input('max');

        if($min==0 && $max==0)
        {
            $all_users=User::where('ticketit_admin',0)->get();
        }
        else if($min==0 && $max!=0)
        {
            $all_users=User::where('ticketit_admin',0)->where('user_id','<=',$max)->get();
        }
        else if($min!=0 && $max==0)
        {
            $all_users=User::where('ticketit_admin',0)->where('user_id','>=',$min)->get();
        }
        else
        {
            $all_users=User::where('ticketit_admin',0)->where('user_id','>=',$min)->where('user_id','<=',$max)->get();
        }
        return response()->json(['users' => $all_users], 200);

    }
}
