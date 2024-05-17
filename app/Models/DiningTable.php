<?php

namespace App\Models;

use App\Jobs\SendMessage;
use Illuminate\Database\Eloquent\Model;

class DiningTable extends Model
{
    protected $table = 'dining_tables';
    
    protected $fillable=[
        'customer_name',
        'customer_mobile',
        'status',
        'amount',
        'sell_id',
        'invoice_number',
        'name',
        'capacity',
        'number',

    ];
    
    public function sell()
    {
        return $this->belongsTo(Sell::class, 'sell_id');
    }
    
        

}
