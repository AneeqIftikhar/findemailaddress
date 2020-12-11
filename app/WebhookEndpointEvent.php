<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebhookEndpointEvent extends Model
{
    protected $fillable = [
        'event_id',
        'endpoint_id'
    ];
}
