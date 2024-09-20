<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public function   menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    protected $fillable = [
        'date_time',
        'qty',
        'menu_id',
        'total_amt',
        'dining_table_id',
        'user_id',
        
    ];

    protected $guarded = ['menu'];
}
