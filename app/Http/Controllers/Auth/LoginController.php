<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\LoginLog;
use App\Helpers\UserAgent;
use Carbon\Carbon;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/find';
   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     * Called when user logs in
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    function authenticated(Request $request, $user)
    {
        $data=[];
        
        $user_agent=UserAgent::get_user_agent(request()->ip());
        $data['user_agent']=json_encode($user_agent);
        $data['country']=$user_agent['country'];
        $data['ip']=$user_agent['ip'];
        $data['login_at']=Carbon::now();
        $data['user_id']=$user->id;
        LoginLog::create($data);
    }
}
