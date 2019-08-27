<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
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
    protected $fillable = ['amount', 'order_reference', 'user_id', 'order_date', 'recurring_enabled','status','created_at', 'updated_at','package_name'];

}
