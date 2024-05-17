<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppHome extends Model
{
    protected $table = 'app_home';
    
   
  
    

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }
    
}
