<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WithoutSpaces implements Rule
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
        $check_spaces=explode(" ",$value);

        $total_names=0;
        for($i=0;$i<count($check_spaces);$i++)
        {
            if($check_spaces[$i]!="")
            {
                $total_names++;
            }
        }
        if($total_names>1)
        {
            return false;
        }
        return true;
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please remove spaces from the last name';
    }
}
