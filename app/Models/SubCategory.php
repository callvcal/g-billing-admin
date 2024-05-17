<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    public function   category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    protected $fillable=[
        'name','image','admin_id','category_id','kitchen_id'
    ];
}
