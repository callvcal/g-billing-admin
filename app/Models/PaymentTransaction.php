<?php

namespace App\Models;

use App\Jobs\SendMessage;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';
    protected $fillable = [
        'order_id',
        'transaction_id',
        'user_id',
        'json',
        
        'date_time',
        'transaction_status_local',
        'transaction_status_callback',
        'callback_json',
        'plan',
        'amount',
        'expiry_date',
        'gst',
        'service_charge',
    ];
    protected $casts = [
        'json' => 'array',
        'callback_json' => 'array',
    ];

    function user()  {
        return $this->belongsTo(AdminUser::class,'user_id');
    }
    
    protected static function booted()
    {
        static::created(function ($model) {
            
        });


        static::updated(function ($model) {
            
            SendMessage::dispatch($model->id, "plan", 'PaymentTransaction')->afterResponse();
        });
    }
}
