<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserApiKey extends Model
{
    protected $fillable = [
        'title',
        'api_key',
        'expires_at',
        'is_enabled',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
