<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
   protected $table = 'subscriptions';
   protected $fillable = ['activated'];
}
