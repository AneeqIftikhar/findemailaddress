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
}
