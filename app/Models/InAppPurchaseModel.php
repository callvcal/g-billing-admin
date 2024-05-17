<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InAppPurchaseModel extends Model
{
    protected $table = 'in_app_purchases';
    protected $casts = [
        'json' => 'array'
    ];

    protected $fillable=[
        'id',
        'json',
        'user_id',
        'location_id',
        'status',
        'json',
        'product_id',
        'purchase_id',
    ];
}
