<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $casts = [
        'json' => 'array'
    ];

    protected $fillable=[
        'id',
        'json'
    ];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
