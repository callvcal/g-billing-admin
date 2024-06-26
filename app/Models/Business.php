<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'name',
        'on_board_way',
        'active',
        'plan',
        'on_board_date',
        'purchase_date',
        'last_subscription_date',
        'expiry_date',
        'admin_id',
        'deleting_date',
        'deleted'
    ];


    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'admin_id');
    }

}
