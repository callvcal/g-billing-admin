<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotToken extends Model
{
    use HasFactory;

    protected $fillable=[
        'date','token','type'
    ];
}
