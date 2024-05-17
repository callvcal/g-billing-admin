<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferalTransaction extends Model
{
    use HasFactory;
    protected $guarded = ['updated_at', 'created_at', 'referer', 'refered', 'category', 'user', 'owner', 'product'];

    
    public function referer()
    {
        return $this->belongsTo(Referal::class, 'referring_user_id');
    }
    public function refered()
    {
        return $this->belongsTo(Referal::class, 'referred_user_id');
    }

    
}
