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
        'admin_id'
        ,'business_id',
        'status',
        'json',
        'product_id',
        'purchase_id',
    ];
    function user()  {
        return $this->belongsTo(AdminUser::class,'admin_id');
    }
    function business()  {
        return $this->belongsTo(Business::class,'business_id');
    }
}
