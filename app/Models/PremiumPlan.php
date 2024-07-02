<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumPlan extends Model
{
    protected $table = 'premium_plans';
    protected $casts = [
        'features' => 'json'
    ];
}
