<?php

namespace App\Models;

use App\Jobs\SendMessage;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Database\Eloquent\Model;
use OrderEvent;

class Sell extends Model
{
    protected $fillable = [
        'delivery_pick_up_otp',
        'order_complete_otp',
        'date_time',
        'order_id',
        'transaction_id',
        'payment_method',
        'total_amt',
        'paid_amt',
        'gst_amt',
        'gst_type',
        'discount_amt',
        'special_discount_id',
        'due_amt',
        'full_address',
        'user_type',
        'dining_table_id',
        'items_count',
        'sell_type',
        'invoice_id',
        'coupon_id',
        'user_id',
        'customer_mobile',
        'customer_name',
        'address_id',
        'order_status',
        'payment_status',
        'delivery_status',
        'delivery_charge',
        'delivery_tip',
        'delivery_instruction',
        'cooking_notes',
        'serve_type',
        'remark',
    ];
    function diningTable()
    {
        return $this->belongsTo(DiningTable::class, 'dining_table_id');
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function billPrint()
    {
        return $this->id;
    }
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function items()
    {
        return $this->hasMany(SellItem::class);
    }

    // Define an event to create a user_coupon record when an order is created
    // protected static function booted()
    // {
    //     static::created(function ($model) {

    //         if (isset($model->coupon_id)) {
    //             UserCoupon::create([
    //                 'sell_id'=>$model->id,
    //                 'user_id'=>$model->user_id,
    //                 'order_status'=>$model->order_status,
    //                 'status'=>$model->coupon_id,
    //             ]);
    //         }

    //         dispatch(new SendMessage($model->id, "order_create",'order'))->afterResponse();
    //     });
    //     static::updated(function ($model) {
    //         if (isset($model->coupon_id)) {
    //             $coupon=UserCoupon::where('sell_id',$model->id)->first();
    //             if($coupon){
    //                 $coupon->order_status=$model->order_status;
    //                 $coupon->save();
    //             }
    //         }

    //         dispatch(new SendMessage($model->id, "order_update",'order'))->afterResponse();
    //     });
    // }

    protected static function booted()
    {
        static::created(function ($model) {

            if (isset($model->coupon_id)) {
                UserCoupon::create([
                    'sell_id' => $model->id,
                    'user_id' => $model->user_id,
                    'order_status' => $model->order_status,
                    'status' => $model->coupon_id,
                ]);
            }
            if (isset($model->special_discount_id)) {
                SpecialDiscount::find($model->special_discount_id)->update([
                    'is_used' => 1,
                    'used_date' => Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'sell_id' => $model->id,
                ]);
            }



            // Dispatch job immediately without queuing
            SendMessage::dispatch($model->id, "order_create", 'order')->afterResponse();
        });

        static::deleted(function ($model) {


            if (isset($model->dining_table_id)) {
                $table = DiningTable::find($model->dining_table_id);
                if ($table) {
                    $table->customer_name = null;
                    $table->customer_mobile = null;
                    $table->amount = null;
                    $table->sell_id = null;
                    $table->status = 'blank';
                    $table->save();
                }
            }
        });

        static::updated(function ($model) {
            if (isset($model->coupon_id)) {
                $coupon = UserCoupon::where('sell_id', $model->id)->first();
                if ($coupon) {
                    $coupon->order_status = $model->order_status;
                    $coupon->save();
                }
            }
            if (isset($model->special_discount_id)) {

                if (($model->order_status == 'g_cancelled') || ($model->order_status == 'f_rejected')) {
                    SpecialDiscount::find($model->special_discount_id)->update([
                        'is_used' => 0,
                        'used_date' => Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s'),
                        'sell_id' => null,
                    ]);
                }
            }

            if (!isset($model->dining_table_id)) {
                SendMessage::dispatch($model->id, "order_update", 'order')->afterResponse();
            }

            // Dispatch job immediately without queuing
        });
    }
}
