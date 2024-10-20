<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name','business_id','stock',
        'admin_id','alert_qty','unit_id','total_stock_out','total_stock_in'

    ];

    public function   transactions()
    {
        return $this->hasMany(RawMatrial::class, 'material_id');
    }
   public function total()
    {
        // Sum the quantities for 'stock-in' transactions
        $stockInQty = $this->transactions()
            ->where('type', 'stock-in')
            ->sum('qty');

        // Sum the quantities for 'stock-out' transactions
        $stockOutQty = $this->transactions()
            ->where('type', 'stock-out')
            ->sum('qty');

        // Calculate the total quantity
        return $stockInQty - $stockOutQty;
    }
}
