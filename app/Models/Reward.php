<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;
    protected $guarded = ['updated_at', 'created_at', 'creditor', 'debitor', 'reward', 'user', 'owner', 'product'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
   
    
}
