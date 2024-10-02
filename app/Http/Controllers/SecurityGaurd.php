<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Nullable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SecurityGaurd extends Controller
{
    //

    public function live()
    {
        return false;
    }

    public function password()
    {
    }

    public function check()
    {
        $allow = false;

       
        

        
        return true;
    }
}
