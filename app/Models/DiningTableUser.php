<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiningTableUser extends Model
{
    protected $table = 'dining_table_users';

    protected $fillable = [
        'dining_table_id',
        'date_time',
        'user_name',
        'user_mobile',
        'user_id',
        'dining_table_request_id',
        'amount',
        'invoice_id',
        'staff_user_id',
        'discount',
    ];
}
