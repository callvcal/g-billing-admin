<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKotTokenRequest;
use App\Http\Requests\UpdateKotTokenRequest;
use App\Models\KotToken;
use Carbon\Carbon;

class KotTokenController extends Controller
{
    public function generateToken($type='token')
    {
        // Get the current date
        $today = Carbon::today();

        // Check if a token entry exists for today's date
        $existingToken = KotToken::where('type',$type)->where('date', $today)->first();

        if ($existingToken) {
            // If a token entry exists, increment the token by 1
            $existingToken->token++;
            $existingToken->save();
        } else {
            // If no token entry exists for today, create a new entry with token starting from 1
            $existingToken=  KotToken::create([
                'date' => $today,
                'token' => 1,
                'type'=>$type
            ]);
        }

        return $existingToken->token;
    }
}
