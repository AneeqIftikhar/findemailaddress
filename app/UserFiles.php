<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Email[] $emails
 */
class UserFiles extends Model
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
    protected $fillable = ['user_id', 'name','total_rows','title','type','status', 'created_at', 'updated_at'];

    protected $appends = ['processed_emails_count','file_failure_count'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emails()
    {
        return $this->hasMany('App\Email');
    }

    public function failures()
    {
        return $this->hasMany('App\File_Failure','user_file_id');
    }
    public function failuresDistinctRows()
    {
        return $this->hasMany('App\File_Failure','user_file_id')->distinct('row');
    }

    public function processedEmailsCountRelation()
    {
        return $this->hasOne('App\Emails','user_file_id')->selectRaw('user_file_id, count(*) as count')->where('status','!=','Unverified')->groupBy('user_file_id');
    }

    public function getProcessedEmailsCountAttribute()
    {
        if($this->processedEmailsCountRelation)
        {
            $count=$this->processedEmailsCountRelation->count;
            return $count;
        }
        else
        {
            return 0;
        }
        
    }
    public function getFileFailureCountAttribute()
    {
        return $this->failures()->count();
    }

}
