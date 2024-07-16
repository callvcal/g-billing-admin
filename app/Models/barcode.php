<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barcode extends Model
{
    use HasFactory;
    protected $fillable=[
        'barcode_id','menu_id'
    ];
}
