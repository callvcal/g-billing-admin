<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentData extends Model
{
    use HasFactory;
    use HasFactory;
    protected $casts = [
        'json' => 'array'
    ];

    protected $fillable=[
        'id',
        'json',
        'sell_id',
        'user_id',
        'payment_gateway',
        'payment_type',
        'request_type',
        'receipt_id',
        'table_request_id',
        'wallet_transaction_id',
    ];
}
