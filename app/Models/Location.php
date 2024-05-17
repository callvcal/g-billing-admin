<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'place_id',
        'address',
        'city',
        'district',
        'state',
        'country',
        'pincode',
        'latitude',
        'colony',
        'uses',
        'longitude',
    ];
    // protected $primaryKey="place_id";
    // public $incrementing=false;
    // protected $keyType="string";
    protected $guarded = ['updated_at', 'created_at', 'distance', 'order', 'category', 'user', 'owner', 'product'];

}
