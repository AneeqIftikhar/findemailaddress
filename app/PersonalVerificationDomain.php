<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalVerificationDomain extends Model
{
    protected $table = 'personal_verification_domains';
   protected $fillable = ['domain','status','created_at', 'updated_at'];
}
