<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Invalid_Domains;
class BlackListDomains implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $email_domain=preg_replace('/.+@/', '', $value);
        $invalid=Invalid_Domains::select('domain')->where('domain',$email_domain)->first();
        if($invalid)
        {
            return false;
        }
        else
        {
            return true;
        }
        
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be of comapny domain.';
    }
}
