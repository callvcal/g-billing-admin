<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeepLinkCode extends Model
{
    protected $fillable = [
        'user_id', 'shares', 'code','menu_id',
    ];
    use HasFactory;
}
