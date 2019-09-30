<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingSubscriptions extends Model
{
    protected $table = 'pending_subscriptions';
   protected $fillable = ['user_id','current_package_id','status','next_package_id','is_active','credits','reason'];
}
