<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{

	protected $table = 'login_logs';
    protected $fillable = ['id', 'user_id','ip','country','user_agent','login_at','logout_at','created_at', 'updated_at'];


}
