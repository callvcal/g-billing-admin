<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyBarcode extends Model
{
    protected $table = 'barcodes';

    use HasFactory;
    protected $fillable=[
        'barcode_id','menu_id','barcode'
    ];}
