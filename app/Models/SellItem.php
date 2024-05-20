<?php

namespace App\Models;

use App\Events\ReloadEvent;
use Illuminate\Database\Eloquent\Model;

class SellItem extends Model
{
    protected $table = 'sell_items';
    protected $fillable = [
        'date_time',
        'user_id',
        'address_id',
        'admin_id',
        'menu_id',
        'sell_id',
        'uuid',
        'token_number',
        'qty',
        'discount_amt',
        'business_id',
        'total_amt',
        'gst_amt',
        'order_status',
        'order_status_preparing',
        'order_status_prepared',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
    
    public function sell()
    {
        return $this->belongsTo(Sell::class, 'sell_id');
    }
    protected static function booted()
    {
        static::created(function ($model) {
        });
        static::updated(function ($model) {
            // event(new ReloadEvent($model->toArray()));

            if($model->order_status_prepared==1){
                if(SellItem::where('sell_id',$model->sell_id)->where('order_status_prepared',0)->count()==0){
                    Sell::where('uuid',$model->sell_id)->update(['order_status'=>'d_readyToPickup']);
                }
            }else if($model->order_status_preparing==1){
                if(SellItem::where('sell_id',$model->sell_id)->where('order_status_preparing',1)->count()==1){
                    Sell::where('uuid',$model->sell_id)->update(['order_status'=>'c_preparing']);
                }
            }

        });
    }
}
