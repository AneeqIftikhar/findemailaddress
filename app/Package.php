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
        $new_package_per_day=$new_package->credits/30;
        $previous_package_per_day=$previous_package->credits/30;
        $difference_in_days=($next_charge_seconds-time())/(24*3600);
        $days_used=30-$difference_in_days;
        $allowed=$days_used*$previous_package_per_day;
        $used_credits=($previous_package->credits)-($user->credits);
        $addable=$difference_in_days*$new_package_per_day;
        $prorated_credits=$allowed-$used_credits+$addable;
        return $prorated_credits;
    }
}
