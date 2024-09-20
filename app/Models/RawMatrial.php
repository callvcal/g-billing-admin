<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMatrial extends Model
{
    protected $table = 'raw_materials';
    public function   unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function   material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    protected $fillable = [
        'name',
        'admin_id',
        'material_id',
        'qty',
        'type',
        'datetime',
        'amount',
        'unit_id',
    ];
}
