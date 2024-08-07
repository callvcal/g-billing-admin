<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['menus','subcategories',
        'name','image','admin_id','business_id'
    ];
}
