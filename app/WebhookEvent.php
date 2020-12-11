<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];

    public function endpoints()
    {
        return $this->belongsToMany('App\WebhookEndpoint','webhook_endpoint_events', 'event_id', 'endpoint_id');
    }
}
