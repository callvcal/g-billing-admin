<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{

    protected $fillable = [
        'admin_id',
        'business_id',
        'menu_id',
        'subcategory_id',
    ];

    public function materials()
    {
        return $this->hasMany(RecipeMaterial::class, 'recipe_id');
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id','id');
    }
}
