<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsBanner extends Model
{
    protected $table = 'ads_banner';

    public function   menu(){
        return $this->belongsTo(Menu::class,'menu_id');
    }
    public function   subcategory(){
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }

}
