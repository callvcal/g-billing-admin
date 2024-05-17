<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    function summary()  {
        $sum=Sell::where('order_status','e_completed')->groupBy('sell_type')->selectRaw('SUM(total_amt) as total');
        $PayMethodCounter=Sell::where('order_status','e_completed')->where('sell_type','counter')->groupBy('payment_method')->selectRaw('SUM(total_amt) as total');
        $PayMethodApp=Sell::where('order_status','e_completed')->where('sell_type','app')->groupBy('payment_method')->selectRaw('SUM(total_amt) as total');
        
    }
}
