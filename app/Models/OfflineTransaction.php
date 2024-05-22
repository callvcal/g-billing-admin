<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineTransaction extends Model
{
    protected $table = 'offline_transactions';
    
    
    protected $fillable = [
        'id',
        'amount',
        'type',
        'cause',
        'business_id',
        'admin_id',

    ];
}
