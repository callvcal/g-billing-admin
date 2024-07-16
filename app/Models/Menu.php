<?php

namespace App\Models;

use App\Http\Controllers\BarcodeController;
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
        'name', 'business_id',
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
        });

        static::deleted(function ($model) {
        });

        static::updated(function ($model) {
        });
    }
}
