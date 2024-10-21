<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeMaterial extends Model
{
    protected $table = 'recipe_materials';
    protected $fillable = [
        'admin_id',
        'business_id',
        'recipe_id','allow_dine_in','allow_parcel_delivery','qty',
        'material_id',
    ];
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id','id');
    }
}
