<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name','business_id',
        'admin_id',

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
