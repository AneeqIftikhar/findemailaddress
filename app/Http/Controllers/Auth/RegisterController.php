<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Package;
use App\UserPackagesLogs;
use Illuminate\Validation\Rule;
use App\Rules\BlackListDomains;
use App\TwoCheckout\TwoCheckoutApi;
use Ramsey\Uuid\Uuid;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users',new BlackListDomains],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['string', 'max:100','nullable'],
            'phone' => ['string', 'max:100','nullable']]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        $free_package=Package::where('name','Free')->first();
        $data['password']=Hash::make($data['password']);
        $data['credits']=$free_package->credits;
        $data['user_uuid'] = Uuid::uuid4();
        $user = new User($data);
        $user->save();
        $twocheckoutapi=new TwoCheckoutApi();
        $name=explode(" ",$user->name);
        if(count($name)>2)
        {
            $first_name=$name[0];
            $last_name=$name[count($name)-1];
        }
        else if (count($name)>1)
        {
            $first_name=$name[0];
            $last_name=$name[1];
        }
        else
        {
            $first_name=$name[0];
            $last_name=" ";
        }
        $user->two_checkout_user_reference=$twocheckoutapi->createCustomer($first_name,$last_name,$user->email,$user->uuid);
        $user->save();

        $user_package_log = new UserPackagesLogs;
        $user_package_log->package()->associate($free_package);
        $user_package_log->user()->associate($user);
        $user_package_log->save();
        return $user;
    }
}
