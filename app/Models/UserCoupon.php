<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    protected $table = 'user_coupons';
    protected $fillable = [
        'sell_id',
        'user_id',
        'order_status',
        
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
