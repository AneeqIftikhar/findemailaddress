<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyEmails extends Model
{
    protected $table = 'company_emails';
    protected $fillable = ['user_id', 'company_slug', 'people_slug', 'created_at', 'updated_at'];
}
