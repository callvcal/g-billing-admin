<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
    use HasFactory;

    protected $guarded = ['updated_at', 'created_at', 'driver', 'order', 'category', 'user', 'owner', 'product'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
