<?php

namespace App\Http\Controllers;

use App\Admin\Forms\Setting;
use App\Models\DeepLinkCode;
use App\Models\Menu;
use App\Models\Setting as ModelsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InviteController extends Controller
{
    public function share(Request $request)
    {
        $menuItemId = $request->query('menu_id');
        $userId =  auth()->user()->id;
        $domainUrl = config('app.url'); // Your application's domain URL




        $dCode = DeepLinkCode::where('user_id', $userId)->where('menu_id', $menuItemId)->first();

        if (!$dCode) {
            $code = $this->generateCode();
            $dCode = DeepLinkCode::create([
                'user_id' => $userId,
                'menu_id' => $menuItemId,
                'code' => $code
            ]);
        } else {
            $code = $dCode->code;
        }
        $url = "{$domainUrl}/refer/{$code}";

        return response(['data' => [
            'url' => $url,
            'code' => $code
        ], 'message' => 'Success']);
    }

    public function view($code)
    {

        $dLink = DeepLinkCode::where('code', $code)->first();

        if (!$dLink) {
            return redirect('/');
        }
        $menuItemId = $dLink->menu_id;

        $imagePath = null;

        if (isset($menuItemId)) {
            $menu = Menu::find($menuItemId);
            $imagePath = $menu->image;
        }

        if (!$imagePath) {
            $imagePath = ModelsSetting::find(1)->json['refer_earn_image'];
        }

        $userId = $dLink->user_id;

        $url=null;
        $domainUrl = config('app.url'); // Your application's domain URL
        $appName = config('app.name'); // Your application's domain URL
        $s3BucketUrl = null; // Optional, S3 bucket URL

        $image = $s3BucketUrl ? "{$s3BucketUrl}/{$imagePath}" :asset($imagePath);

        $title = "Enjoy Delicious Meals with $appName!";
        $description = "Get ready to enjoy delicious meals delivered to your doorstep with $appName! Use my referral code to get a special discount on your first order.";




        return view('invite', compact('title', 'description', 'image', 'url'));
    }




    function generateCode(int $length = 6): string
    {
        $timestamp = dechex(time()); // Convert current time to a hexadecimal string
        $randomPartLength = $length - strlen($timestamp);

        // Ensure the length accommodates the timestamp



        do {
            // Combine the timestamp and a random string for uniqueness
            $randomPart = $randomPartLength > 0 ? Str::lower(Str::random($randomPartLength)) : '';
            $code = substr($timestamp . $randomPart, 0, $length); // Trim or pad to desired length
        } while (DB::table('deep_link_codes')->where('code', $code)->exists()); // Ensure code is unique

        return $code;
    }
}
