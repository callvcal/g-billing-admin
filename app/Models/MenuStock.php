<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuStock extends Model
{
    protected $table = 'menu_stocks';
    protected $fillable = [
        'qty',
        'note',
        'type',
        'stock',
        'sell_id',
        'datetime',
        'admin_id',
        'menu_id',
        'business_id',
    ];
    function business()  {
        return $this->belongsTo(Business::class,'business_id');
    }
    function admin()  {
        return $this->belongsTo(AdminUser::class,'admin_id');
    }
    
    
}
