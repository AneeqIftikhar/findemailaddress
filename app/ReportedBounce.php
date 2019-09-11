<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportedBounce extends Model
{
    protected $table = 'reported_bounce';
    protected $fillable = ['ip','user_id','email_id', 'message', 'created_at', 'updated_at'];
}
