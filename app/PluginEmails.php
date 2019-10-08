<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PluginEmails extends Model
{

	protected $table = 'plugin_emails';
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';


    /**
     * @var array
     */
    protected $fillable = ['first_name', 'last_name','domain', 'email','status','server_status','server_json_dump', 'created_at', 'updated_at','type'];


    public static function insert_email($first_name,$last_name,$domain,$email,$status,$type,$server_json_dump,$server_status)
    {
       $emails_db = new PluginEmails;
       $emails_db->first_name = $first_name ? $first_name : '';
       $emails_db->last_name = $last_name ? $last_name : '';
       $emails_db->domain = $domain ? $domain : '';
       $emails_db->email = $email ? $email : '';
       $emails_db->status = $status;
       $emails_db->type = $type;
       $emails_db->server_json_dump = $server_json_dump ? $server_json_dump : '';
       $emails_db->server_status = $server_status ? $server_status : '';
       $emails_db->save();
    }
}
