<?php

namespace App\Models;

use App\Jobs\PushNotificationJob;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $table = 'push_notifications';
    protected static function booted()
    {
        static::created(function ($model) {
            dispatch(new PushNotificationJob($model->id))->afterResponse();
        });

        static::updated(function ($model) {
            dispatch(new PushNotificationJob($model->id))->afterResponse();
            
        });
    }
}
