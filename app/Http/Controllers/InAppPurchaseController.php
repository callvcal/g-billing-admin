<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\InAppPurchaseModel;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InAppPurchaseController extends Controller
{
    public function validPurchase(Request $request)
    {

        $purchases = InAppPurchaseModel::where('purchase_id', $request->purchaseDetails['purchaseID'])->get();

        if ($purchases) {
            foreach ($purchases as $model) {
                if ($model->admin_id !=  auth()->user()->id) {
                    return response([
                        'message' => 'Invalid purchase'
                    ],302);
                }
            }
        }


        InAppPurchaseModel::create([
            'admin_id' => auth()->user()->id,
            'business_id' => auth()->user()->business_id,
            'status' => $request->purchaseDetails['status'],
            'json' => $request->purchaseDetails,
            'product_id' => $request->purchaseDetails['productID'],
            'purchase_id' => $request->purchaseDetails['purchaseID'],
        ]);

        $business = Business::find(auth()->user()->business_id);



        $plan = $request->purchaseDetails['productID'];

        if ($business->plan == $plan) {
            return response([
                'message' => 'already purchased', 302
            ]);
        }

        $expiry_date = Carbon::now();
        switch ($plan) {
            case 'monthly':
                $expiry_date = Carbon::now()->addMonth(1);
                break;
            case 'annual':
                $expiry_date = Carbon::now()->addYear(1);
                break;
            case 'lifetime':
                $expiry_date = Carbon::now()->addYear(10);
                break;
        }

        $business->expiry_date = $expiry_date;
        $business->purchase_date = now();
        $business->plan = $plan;
        $business->active = 1;
        $business->save();

        return response([
            'user' => AdminUser::with('business')->find(auth()->user()->id),
            'message' => "Plan upgraded successfully"
        ]);
    }

    public function invalidPurchase(Request $request)
    {
        $purchases = InAppPurchaseModel::where('purchase_id', $request->purchaseDetails['purchaseID'])->get();

        if ($purchases) {
            foreach ($purchases as $model) {
                if ($model->admin_id !=  auth()->user()->id) {
                    return response([
                        'message' => 'Invalid purchase'
                    ],302);
                }
            }
        }

        InAppPurchaseModel::create([
            'admin_id' => auth()->user()->id,
            'business_id' => auth()->user()->business_id,
            'status' => $request->purchaseDetails['status'],
            'json' => $request->purchaseDetails,
            'product_id' => $request->purchaseDetails['productID'],
            'purchase_id' => $request->purchaseDetails['purchaseID'],
        ]);

        $business = Business::find(auth()->user()->business_id);
        if ($business->plan == 'free') {
            return response([
                'message' => 'already cancelled', 302
            ]);
        }
        $expiry_date = Carbon::now();


        $business->expiry_date = $expiry_date;
        $business->purchase_date = now();
        $business->plan = 'free';
        $business->active = 1;
        $business->save();

        return response([
            'user' => AdminUser::with('business')->find(auth()->user()->id),
            'message' => "Plan downgraded successfully"
        ]);
    }
}
