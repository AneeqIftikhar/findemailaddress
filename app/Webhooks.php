<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhooks extends Model
{
   protected $table = 'webhooks';
   protected $fillable = ['webhook_dump','user_id'];
}
