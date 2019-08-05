<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invalid_Domains extends Model
{
	protected $table = 'invalid_domains';
    protected $fillable = ['id', 'domain', 'created_at', 'updated_at'];
}
