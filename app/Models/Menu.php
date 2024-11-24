<?php

namespace App\Models;

use App\Http\Controllers\BarcodeController;
use App\Jobs\MenuJob;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function   unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function   subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
    public function   category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    protected $fillable = [
        'name', 
        'business_id','allow_delivery','allow_dine_in','allow_take_away',
        'image',
        'subtitle',
        'code',
        'description',
        'price',
        'price_din_in',
        'price_take_away',
        'price_with_delivery',
        'in_stock',
        'discount',
        'food_type',
        'category_id',
        'subcategory_id',
        'unit_id',
        'alert_stocks',
        'stocks',
        'admin_id',
        'ratings',
        'sells',
        'active',
        'stock_status',
        'calories_count',
        'weight_per_serving',
        'proteins_count',
        'qty',
    ];
    protected static function booted()
    {
        static::created(function ($model) {
            (new BarcodeController())->genBarcode($model);
            dispatch(new MenuJob(menu:$model));
        });

        static::deleted(function ($model) {
        });

        static::updated(function ($model) {
        });
    }
}
