<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    protected $guarded = ['updated_at', 'created_at', 'creditor', 'debitor', 'reward', 'user', 'owner', 'product'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function creditor()
    {
        return $this->belongsTo(Wallet::class, 'credit_wallet_id');
    }
    public function debitor()
    {
        return $this->belongsTo(Wallet::class, 'debit_wallet_id');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_point_id');
    }
}
