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
use App\FastSpring\FastSpringApi;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Helpers\CurlRequest;
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
    protected $redirectTo = '/find';

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
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:155', 'unique:users',new BlackListDomains],
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

        DB::beginTransaction();
        try
        {
            $free_package=Package::where('name','free')->first();
            $data['password']=Hash::make($data['password']);
            $data['credits']=$free_package->credits;
            $data['user_uuid'] = Uuid::uuid4();
            $data['package_id'] = $free_package->id;
            $data['name'] = $data['firstname'].' '.$data['lastname'];
            $user = new User($data);
            $user->save();
            $FastSpringApi=new FastSpringApi();
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
            $if_exists=$FastSpringApi->getCustomerUsingEmail($user->email);
            if($if_exists['result']=="error")
            {
                $account=$FastSpringApi->createCustomer($first_name,$last_name,$user->email,$user->user_uuid);
                $user->payment_user_reference=$account['id'];
            }
            else
            {
                $user->payment_user_reference=$if_exists['accounts'][0]['id'];
                
            }
            $user->save();
            $user_package_log = new UserPackagesLogs;
            $user_package_log->package()->associate($free_package);
            $user_package_log->user()->associate($user);
            $user_package_log->save();
            DB::commit();

            if (config('app.env')=='production') {

                //adding comment to be removed later
                // $server_output=CurlRequest::add_automizy_contact($user);
            }
            



            return $user;

        }
        catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }
        
    }
}
