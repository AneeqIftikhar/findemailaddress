<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_file_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $industry
 * @property string $status
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $designation
 * @property string $contact
 * @property string $created_at
 * @property string $updated_at
 * @property UserFile $userFile
 * @property User $user
 */
class Emails extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'user_file_id', 'first_name', 'last_name','domain', 'email', 'industry', 'status', 'country', 'state', 'city', 'designation', 'contact', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userFile()
    {
        return $this->belongsTo('App\UserFile');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    
}
