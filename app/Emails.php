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
    protected $fillable = ['user_id', 'user_file_id', 'first_name', 'last_name','domain', 'email', 'industry', 'status','server_status', 'country', 'state', 'city', 'designation', 'contact','server_json_dump','type', 'created_at', 'updated_at'];

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

    public function bounce()
    {
        return $this->hasMany('App\ReportedBounce', 'email_id', 'id');
    }

    public static function insert_email($first_name,$last_name,$domain,$email,$status,$user_id,$type,$server_json_dump,$server_status)
    {
       $emails_db = new Emails;
       $emails_db->first_name = $first_name ? $first_name : '';
       $emails_db->last_name = $last_name ? $last_name : '';
       $emails_db->domain = $domain ? $domain : '';
       $emails_db->email = $email ? $email : '';
       $emails_db->status = $status;
       $emails_db->user_id = $user_id;
       $emails_db->type = $type;
       $emails_db->server_json_dump = $server_json_dump ? $server_json_dump : '';
       $emails_db->server_status = $server_status ? $server_status : '';
       $emails_db->save();
    }
    public static function update_email($email_db,$email,$status,$server_status,$server_json_dump)
    {
       $email_db->email = $email ? $email : '';
       $email_db->status = $status;
       $email_db->server_status = $server_status ? $server_status : '';
       $email_db->server_json_dump = $server_json_dump ? $server_json_dump : '';
       $email_db->save();
    }
    public static function update_updated_at($email_id)
    {
        $emails_db = Emails::find($email_id);
        $emails_db->touch();
    }
    
}
