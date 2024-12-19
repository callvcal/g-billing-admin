<?php

namespace App\Models;

use App\Jobs\MenuJob;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function   kitchen()
    {
        return $this->belongsTo(Kitchen::class, 'kitchen_id');
    }
    protected $fillable = [
        'menus',
        'name', 'image', 'admin_id', 'category_id', 'kitchen_id', 'business_id', 'allow_delivery', 'allow_dine_in', 'allow_take_away',
    ];
    protected static function booted()
    {
        static::created(function ($model) {
            dispatch(new MenuJob(subCategory: $model));
        });

        static::deleted(function ($model) {
        });

        static::updated(function ($model) {
        });
    }
}
