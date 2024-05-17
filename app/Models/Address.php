<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

   
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'city',
        'plot_no',
        'pincode',
        'district',
        'state',
        'country',
        'address',
        'name',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
