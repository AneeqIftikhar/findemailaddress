<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebhookEndpoint extends Model
{
    protected $fillable = [
        'url',
        'description',
        'secret'
    ];

    public function events()
    {
        return $this->belongsToMany('App\WebhookEvent','webhook_endpoint_events', 'endpoint_id', 'event_id');
    }
}
