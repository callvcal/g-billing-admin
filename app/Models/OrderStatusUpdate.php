<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusUpdate extends Model
{
    protected $table = 'order_status_updates';

    protected $fillable = [
        'sell_id',
        'user_id',
        'driver_id',
        'status',
        'updated_at',
        'dominant',
        'delivery_status',
        'order_status',
        'created_at',
    ];

    

}
