<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\SpecialDiscount;
use App\Models\User;
use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;

class CouponApi extends Controller
{
    public function index()
    {
        return response(Coupon::where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->latest()
            ->get());

           
    }
    public function validateCoupon()
    {
        $code = request()->code;

        $coupon = Coupon::whereRaw("LOWER(code) = ?", [strtolower($code)])->latest()->first();

        if ($coupon) {
            // Check if there is a limit on coupon uses
            if ($coupon->max_uses != -1) {
                // Count the number of times the user has used the coupon
                $uses = UserCoupon::where('order_status', 'rejected')
                    ->orWhere('order_status', 'cancelled')
                    ->where('user_id', auth()->user()->id)
                    ->where('coupon_id', $coupon->id)
                    ->count();

                // Compare the number of uses with the max uses allowed
                if ($uses >= $coupon->max_uses) {
                    return response([
                        'message' => "You have already used this coupon for " . $coupon->max_uses . " times.",
                        'count' => $coupon->max_uses,
                    ], 401);
                }
            }

            return response($coupon);
        }

        return response([
            'message' => "Coupon does not exist"
        ], 404);
    }
    public function specialDiscount()
    {
        $sd = SpecialDiscount::where('user_id',auth()->user()->id)->where('is_used',0)->orderBy('discount_index',"ASC")->get();
        return response($sd);
    }
}
