<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            // Generate a unique 4-character alphanumeric string for the uid attribute
            $uid->uid = self::generateUniqueUid();
        });
    }

    public static function generateUniqueUid()
    {
        // Define the character set: digits and lowercase letters
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        
        // Generate a random 4-character string
        $randomUid = substr(str_shuffle($characters), 0, 4);
        
        // Check if UID is unique
        while (self::where('uid', $randomUid)->exists()) {
            $randomUid = substr(str_shuffle($characters), 0, 4);
        }

        return $randomUid;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
