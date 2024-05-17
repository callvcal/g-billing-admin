<?php

namespace App\Models;

use App\Jobs\SendMessage;
use Illuminate\Database\Eloquent\Model;

class TableRequest extends Model
{
    protected $fillable = [
        'user_id',
        'guests',
        'date',
        'time',
        'charge',
        'payment_status',
        'payment_method',
        'transaction_id',
        'order_id',
        'status',
        'dining_table_id',
    ];

    protected $table = 'table_requests';

    function user()
    {
        return $this->belongsTo(User::class);
    }


    function diningTable()
    {
        return $this->belongsTo(DiningTable::class,'dining_table_id');
    }
    protected static function booted()
    {
        static::created(function ($model) {

           
        });

        static::updated(function ($model) {
            

            // Dispatch job immediately without queuing
            SendMessage::dispatch($model->id, "dining_table_update", 'dining_table')->afterResponse();
        });
    }
}
