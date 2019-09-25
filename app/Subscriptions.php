<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
   protected $table = 'subscriptions';
   protected $fillable = ['webhook_dump','subscription_id','active','user_id','product_name','price'];
}
