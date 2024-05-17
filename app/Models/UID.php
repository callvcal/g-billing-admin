<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UID extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uid',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($uid) {
            // Generate UUID for the uid attribute
            $uid->uid = Uuid::uuid4()->toString();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
