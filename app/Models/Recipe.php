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
}
