<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingSubscriptions extends Model
{
    protected $table = 'pending_subscriptions';
   protected $fillable = ['user_id','package_id','status','credits','reason'];
}
