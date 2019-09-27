<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property float $amount
 * @property string $name
 * @property string $description
 * @property string $annual_saving_percent
 * @property int $credits
 * @property string $created_at
 * @property string $updated_at
 * @property UserPackagesLog[] $userPackagesLogs
 */
class Package extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['amount', 'name', 'description', 'annual_saving_percent', 'credits', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userPackagesLogs()
    {
        return $this->hasMany('App\UserPackagesLog');
    }

    public static function calculateProratedCredits($previous_package,$new_package,$next_charge_seconds,$user)
    {
        //Previous Package Calculation
        $used_days=($next_charge_seconds-time())/(24*3600);
        $remaining_days=round(30-$used_days);
        $previous_package_per_day=($previous_package->credits/30);
        $allowed_credits=($previous_package->credits)-($remaining_days*$previous_package_per_day);
        $rollover_credits=($user->credits)-$allowed_credits;

        //New Package Calculation
        $new_package_per_day=$new_package->credits/30;
        $new_credits_tba=($new_package->credits)-($remaining_days*$new_package_per_day);

        $prorated_credits=$rollover_credits+$new_credits_tba;

        return round($prorated_credits);
    }
}
