<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\AdminUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{



    function setBusiness(Request $request)
    {

        $user = AdminUser::find(auth()->user()->id);

        $request->validate([
            'password' => ['required', 'string', 'max:20'],
            'mobile' => ['required'],
            'name' => ['required'],
            'username' => ['required'],
            'business_key' => ['required'],
        ]);
        $business = $request->business;

        $businesss = Business::where('name', $business)->get();
        if (count($businesss) != 0) {
            $business = $business .  count($businesss);
        }
        $user->business_key = $business;
        $user->username = 'admin@' . $business;
        $business = Business::create(
            [
                'name' => $business,
                'on_board_way' => 'app',
                'active' => 1,
                'plan' => 'free',
                'on_board_date' => Carbon::now(),
                'purchase_date' => null,
                'last_subscription_date' => null,
                'expiry_date' => null,
                'admin_id' => $user->id
            ]
        );
        $user->business_id = $business->id;


        $mobile = $request->mobile;
        $name = $request->name;
        $username = $request->username;


        $user->mobile = $mobile;
        $user->password = Hash::make($request->password);
        $user->name = $name;
        $user->username = $username;
        $user->save();

        $user->load('business');

        $posRoleId = DB::table('admin_roles')->where('slug', 'Partner-Admin')->value('id');
        if (!$posRoleId) {
            $posRoleId = DB::table('admin_roles')->insertGetId([
                'name' => 'Partner-Admin',
                'slug' => 'Partner-Admin'
            ]);
        }

        // Assign the role to the user
        DB::table('admin_role_users')->insert([
            'role_id' => $posRoleId,
            'user_id' => $user->id
        ]);

        $user->load('business', 'roles', 'permissions');

        return response($user);
    }

    public function status($id)
    {
        $user = AdminUser::find($id);

        if (!$user) {
            return response([
                'message' => "staff does not exist"
            ], 401);
        }

        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return response($user);
    }

    public function delete($id)
    {
        $user = AdminUser::find($id);

        if (!$user) {
            return response([
                'message' => "staff does not exist"
            ], 401);
        }

        $user->delete();

        return response([
            'message' => "staff deleted successfully"
        ]);
    }
    public function fetch()
    {
        $users = AdminUser::with('business')->where('business_id', auth()->user()->business_id)->get();


        return response($users);
    }
    function verifyBusiness()
    {
        $user = AdminUser::find(auth()->user()->id);
        $business = request('business_key');
        Log::channel('callvcal')->info(json_encode( request()->all()));

        if (strpos($business, ' ') !== false) {
            return response([
                'message' => "Please don't use space, remove space and try again."
            ], 401);
        }

        if (strlen($business) < 3) {
            return response([
                'message' => "Please enter business at least 3 charecter and try again."
            ], 401);
        }
        if (strlen($business) > 10) {
            return response([
                'message' => "Please enter business at max 10 charecter and try again."
            ], 401);
        }
        $businesss = Business::where('name', $business)->get();
        if (count($businesss) != 0) {
            $business = $business .  count($businesss);
        }
        return response([
            'business_key' => $business,

        ]);
    }

    public function verifyGAF(Request $request)
    {
        if ((new AuthController())->invalid($request->all())) {
            $data = array();
            $data['message'] = 'Invalid secretes';
            return response($data);
        }
        $user = $request->user;
        $secrete = $request->provider['secrete'];
        $provider = $request->provider['name'];

        if ($provider == 'apple' || $provider == 'googleWeb') {
            $verified = (new FirebaseController())->validateTokenId($secrete);
        }
        if ($provider == 'google') {
            $verified = (new FirebaseController())->validateAccessToken($secrete);

            if (!$verified) {
                return response([
                    'message' => "There is issue with authentication please restart app and try again",
                    'type' => 'google',
                ], 401);
            }
        }


        $userModel = AdminUser::where('email', $user['email'])->first();

        if (!$userModel) {
            $userModel = AdminUser::create([
                'email' => $user['email'],
                'username' => $user['email'],
                'password' => Hash::make("admin@Somesh"),
                'name' => $user['name'],
                'fcm_token' => $request->fcm_token ?? null
            ]);
        }

        try {
            if (!isset($userModel->image)) {
                if (isset($user['image'])) {
                    $dist = 'eatinsta/images';
                    $name = time() . '_' . 'avatar.png';
                    $path = $dist . '/' . $name;
                    Storage::disk('s3')->put("$dist/$name", file_get_contents($user['image']));
                    $userModel->image = $path;
                    $userModel->save();
                }
            }
        } catch (Exception $e) {
            Log::channel('callvcal')->info("auth-file-upload error: e:" . json_encode($e) . " ,request:" . json_encode($request) . ", model:" . json_encode($userModel));
        }

        return $this->returnUserToken($request, $userModel);
    }
    public function returnUserToken($request, $user)
    {
        $referRequest = [
            'refer' => $request->refer,
            'isNewUser' => false
        ];
        $createdAt = Carbon::parse($user->created_at);
        $now = Carbon::now();
        if ($createdAt->equalTo($now)) {
            $referRequest['isNewUser'] = true;
        }
        if (isset($request->refer)) {
            $referRequest['referedUserId'] = $user->id;
            (new ReferalTransactionController())->create($referRequest);
        }
        return response([
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user,
            'message' => "success",

        ]);
    }
    public function saveFilePath($request, $dir, $model, $key)
    {

        $image = $request->file('file');

        if ($image !== null) {
            try {
                $this->deleteFilePath($model, $key);
            } catch (Exception $e) {
            }
            $dist = "eatinsta/$dir";
            $name = time() . '_' . $image->getClientOriginalName();


            Storage::disk('s3')->put("$dist/$name", file_get_contents($image));

            if (isset($model)) {
                if ($model->$key && Storage::disk('s3')->exists($model->$key)) {
                    Storage::disk('s3')->delete($model->$key);
                }

                $model->$key =  $dist . '/' . $name;
                $model->save();
            }
            return response()->json([
                'message' => 'File uploaded successfully',
                $key => $dist . '/' . $name,

            ]);
        }

        return response()->json([
            'message' => 'Please select file to upload',
        ], 401);
    }


    public function deleteFilePath($model, $key)
    {


        if ($model->$key && Storage::disk('s3')->exists($model->$key)) {
            Storage::disk('s3')->delete($model->$key);
        }
        $model->$key = null;
        $model->save();

        return response()->json([
            'message' => 'File deleted successfully',
        ], 201);
    }
}
