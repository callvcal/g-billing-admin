<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialDiscount extends Model
{
    protected $table = 'special_discounts';
    protected $fillable = [
        'date_of_allocation',
        'refering_user_id',
        'user_id',
        'is_used',
        'is_valid',
        'discount',
        'used_date',
        'sell_id',
        'mobile',
        'discount_index',
    ];
}
