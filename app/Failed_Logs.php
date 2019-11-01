<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Failed_Logs extends Model
{
    protected $table = 'failed_logs';
    protected $fillable = ['id', 'server_json_dump','proxy', 'created_at', 'updated_at'];
}
