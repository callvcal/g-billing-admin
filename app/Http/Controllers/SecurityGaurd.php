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
        return '@345602hjhhbb%71GbghMi*8N46w%i^j7Gn^2dsdfghcu^w#p&';
    }

    public function check()
    {
        $allow = false;

        if (isset(apache_request_headers()['secure'])) {

            $pass = apache_request_headers()['secure'];
            $allow = $pass == $this->password();
        }

        
        return $allow;
    }
}
