<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_Failure extends Model
{
    protected $table = 'file_failures';
    protected $fillable = ['id', 'user_file_id','row', 'attribute','errors','values','created_at', 'updated_at'];
}
