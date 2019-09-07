<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PluginRequest extends Model
{
    protected $table = 'plugin_request';
    protected $fillable = ['ip', 'count', 'created_at', 'updated_at'];
}
