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
        'name','admin_id',
        'capacity',
        'uuid',
        'number','business_id',

    ];
    
    public function sell()
    {
        return $this->belongsTo(Sell::class, 'sell_id','uuid');
    }
    
        

}
