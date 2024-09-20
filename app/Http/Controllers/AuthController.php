<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\EmailOtp;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use PgSql\Lob;

class AuthController extends Controller
{

    protected $emailOtpExpiryInMinutes = 180;

    function adminLogin(Request $request)
    {
        $user = AdminUser::where('username', $request->username)->first();
        if (!$user) {
            return response([
                'message' => "There is no staff registered with username " . $request->username
            ], 401);
        }
        if (Hash::check($request->password, $user->password)) {
            $user->load('roles', 'permissions');
            return  response([
                'token' => $user->createToken('token')->plainTextToken,
                'user' => $user,
                'message' => "success",

            ]);
        }
        return response([
            'message' => "worng password with username " . $request->username,
            'pass' => $user->password,
            'rpass' => $request->password,
        ], 401);
    }

    public function sendResetLink($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response([
                'message' => "There is no user with email " . $email
            ], 401);
        }

        // Send the password reset link
        $otp = $this->generateOtp($user->email);
        Log::channel('callvcal')->info("Error while Sending email otp " . " data:" . json_encode($otp));

        try {

            $data = array('otp' => (string) $otp);

            Mail::send('templates.otp', $data, function ($message) use (&$user) {
                $message->to($user->email, $user->name)->subject($user->name);
            });
            return response(['message' => 'success', 'expiryInMinutes' => $this->emailOtpExpiryInMinutes, 'type' => "email", 'value' => $user->email]);
        } catch (Exception $th) {
            Log::channel('callvcal')->info("Error while Sending email otp " . " ERROR:$th");
            // return response(['message' => json_encode($th), 'type' => "email", 'value' => $user->email], 401);
        }
        return response(['message' => 'success', 'expiryInMinutes' => $this->emailOtpExpiryInMinutes, 'type' => "email", 'value' => $user->email]);
    }
    public function resetPassword(Request $request)
    {
        $otp = $request->otp;
        $email = $request->email ?? null;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response([
                'message' => "There is no user with email " . $email
            ], 401);
        }

        $verified = $this->checkEmailOTP($user->email, $otp);


        if (!$verified) {
            return response([
                'message' => "Invalid OTP " . $otp .  ", please enter valid OTP received in " . $email
            ], 401);
        }
        $user->email_verified_at = Carbon::now();
        $user->password = Hash::make($request->password);
        $user->save();
        return $this->returnUserToken($request, $user);
    }
    public function checkEmailOTP($email, $otp)
    {
        // $dateTime = new DateTime();


        $emaiOtps = EmailOtp::where('email', $email)->get();

        foreach ($emaiOtps as $emaiOtp) {
            if (Hash::check("$otp", $emaiOtp->otp)) {
                EmailOtp::where('email', $email)->delete();

                return true;
            }
        }

        return false;
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'old_password' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:20'],
            // 'user_id' => ['required']
        ]);

        $user = AdminUser::find(auth()->user()->id);

        if (!$user) {
            return response([
                'message' => "User does not exists"
            ], 401);
        }

        // if (Hash::check($request->password, $user->password)) 
        // {
        //     return response([
        //         'message' => "Wrong Password! Please enter correct old password or contact to admin"
        //     ], 401);
        // }

        $user->password = $request->password;
        $user->save();

        return response($user);
    }



    public function deleteEmail()
    {
        $user = User::find(auth()->user()->id);
        $user->email = null;
        $user->email_verified_at = null;
        $user->save();
        return response([
            'message' => "Deleted"
        ]);
    }

    public function deleteMobile()
    {
        $user = User::find(auth()->user()->id);
        $user->mobile = null;
        $user->country_code = null;
        $user->dail_code = null;
        $user->mobile_verified_at = null;
        $user->save();
        return response([
            'message' => "Deleted"
        ]);
    }

    public function deleteAccount()
    {

        $user = User::find(auth()->user()->id);
        if ($user) {


            $user->delete();
        }
        return response([
            'message' => "Deleted"
        ]);
    }

    public function generateOtp($email)
    {
        $otp = rand(100000, 999999);

        $encrypted = Hash::make("$otp");
        $dateTime = new DateTime();
        $dateTime->modify("+" . $this->emailOtpExpiryInMinutes . " minutes");

        EmailOtp::create([
            'email' => $email,
            'otp' => $encrypted,
            "expiry_date" => $dateTime
        ]);

        return $otp;
    }

    public function sendEmailOTP()
    {
        $user = User::find(auth()->user()->id);

        $otp = $this->generateOtp($user->email);
        Log::channel('callvcal')->info("Error while Sending email otp " . " data:" . json_encode($user));

        try {

            $data = array('otp' => (string) $otp);

            Mail::send('templates.otp', $data, function ($message) use (&$user, &$otp) {
                $message->to($user->email, $user->name)->subject("OTP for verification is " . "$otp");
            });
            return response(['message' => 'success', 'expiryInMinutes' => $this->emailOtpExpiryInMinutes, 'type' => "email", 'value' => $user->email]);
        } catch (Exception $th) {
            Log::channel('callvcal')->info("Error while Sending email otp " . " ERROR:$th");
            return response(['message' => json_encode($th), 'type' => "email", 'value' => $user->email], 401);
        }
    }
    public function sendUserEmailOTP(Request $request)
    {


        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user || (isset($request->password))) {
            $otp = $this->generateOtp($request->email);
            Log::channel('callvcal')->info("Error while Sending email otp " . " ERROR:$otp");

            try {

                $data = array('otp' => (string) $otp);

                Mail::send('templates.otp', $data, function ($message) use (&$email, &$otp) {
                    $message->to($email, "User")->subject("OTP for verification is " . "$otp");
                });
                return response(['message' => 'success', 'expiryInMinutes' => $this->emailOtpExpiryInMinutes, 'type' => "email", 'value' => $email]);
            } catch (Exception $th) {
                Log::channel('callvcal')->info("Error while Sending email otp " . " ERROR:$th");
                // return response(['message' => json_encode($th), 'type' => "email", 'value' => $email], 401);
            }
            return response(['message' => 'success', 'expiryInMinutes' => $this->emailOtpExpiryInMinutes, 'type' => "email", 'value' => $email]);
        }
        return response(['message' => 'This email address is already exists in our server', 'expiryInMinutes' => $this->emailOtpExpiryInMinutes, 'type' => "email", 'value' => $email], 203);
    }
    public function sendUserMobileOtp(Request $request)
    {


        $mobile = $request->mobile;
        $id = $request->id;

        if (isset($id)) {
            return response([
                'message' => "success"
            ]);
        }



        return response([
            'message' => "In valid user, mobile otp not allowed, please login with google or apple"
        ], 401);
    }

    ///This is required function to test auth
    public function deleteAccountRequest()
    {
        $user = auth()->user();

        $user = User::find($user->id);

        $user->deleted = 1;
        $user->deleting_date = now()->addDays(7);
        $user->save();

        if (isset($user->mobile)) {

            $mobile = $user->mobile;
        }



        return response([
            'message' => "We've received your account deletion request. Your data will be deleted in 7 days."
        ]);
    }
    public function test()
    {
        $auth = auth()->user();

        if (isset(apache_request_headers()['isbilling']) && (apache_request_headers()['isbilling'] == 'true')) {
            $auth = AdminUser::find($auth->id);
            $auth->load('roles', 'permissions');
        }

        return response([
            'authenticated' => ($auth) != null,
            'user' => $auth
        ]);
    }


    public function user()
    {
        $auth = auth()->user();
        $user = User::find($auth->id);


        return response($user);
    }



    function saveMobile(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->mobile = $request->mobile;
        $user->save();
        return response($user);
    }





    public function updateProviderData(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'gender' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'string', 'max:10'],
        ]);

        $user = User::find(auth()->user()->id);



        $data = $request->all();
        if (isset($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response($user);
    }
    public function saveFCM(Request $request)
    {
        $fcmToken = $request->fcm_token;
        if (isset($fcmToken)) {

            if (isset(apache_request_headers()['isbilling']) && (apache_request_headers()['isbilling'] == 'true')) {
                $user = AdminUser::find(auth()->user()->id);
            } else {
                $user = User::find(auth()->user()->id);
            }


            $user->fcm_token = $fcmToken;
            $user->save();

            return response(['message' => "saved"]);
        }
        return response(['message' => "Invalid or null fcm token"], 401);
    }

    public function deleteDP()
    {
        $user = User::find(auth()->user()->id);

        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }
        $user->image = null;
        $user->save();
        return response()->json([
            'message' => 'Image deleted successfully',
        ], 200);
    }
    public function saveDP(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);

        $user = User::find(auth()->user()->id);
        $image = $request->file('file');

        $ext = $image->getClientOriginalExtension();
        $dist = 'users/photos/dp';
        $name = 'dp-' . $user->id . '-' . time() . '.' . $ext;

        $image->move(public_path($dist), $name);

        // Deleting old image
        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        $user->image =  $dist . '/' . $name;
        $user->save();

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image' => $user->image
        ], 200);
    }

    public function signUpCustom(Request $request)
    {
        $data = $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            // 'gender' => ['required', 'string', 'max:20'],
            // 'date_of_birth' => ['required', 'string', 'max:10'],
            // 'mobile' => ['required', 'string'],
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $data['fcm_token'] = $request->fcm_token ?? null;
        $user = User::create($data);

        return $this->returnUserToken($request, $user);
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






        if (isset(apache_request_headers()['isdriver']) && (apache_request_headers()['isdriver'] == 'true')) {
            $user->is_driver = 1;
            $user->save();
        }

        if (isset(apache_request_headers()['isbilling']) && (apache_request_headers()['isbilling'] == 'true')) {
            $auth = AdminUser::find($user->id);
            $auth->load('roles', 'permissions','business');
        }

        return response([
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user,
            'message' => "success",

        ]);
    }





    public function signInCustom(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $exists =  $this->isEmailExistsBool($request);

        if ($exists) {
            $user = User::where('email', $data['email'])->first();

            if (isset($request->password)) {
                $password = $request->password;

                if (Hash::check($password, $user->password)) {
                    return $this->returnUserToken($request, $user);
                }

                return response([
                    'message' => "Wrong Password",
                ], 401);
            } else {
                return $this->returnUserToken($request, $user);
            }
        }

        return response([
            'message' => "Entered email does not exist",
        ], 401);
    }





    public function isEmailExists(Request $request)
    {
        return response(['exists' => $this->isEmailExistsBool($request)]);
    }
    public function isEmailExistsBool(Request $request)
    {
        return filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) && User::where('email', $request->email)->exists();
    }

    public function isMobileExists(Request $request)
    {
        $mobile = $request->input('complete_mobile');

        return count($mobile) <= 12
            ? response(['exists' => false, 'message' => "Mobile number must include a country code"], 400)
            : response(['exists' => User::where('mobile', $mobile)->exists()]);
    }


    public function verifyOTP(Request $request)
    {
        $request->validate(
            [
                'mobile' => 'required|string',
                'countryCode' => 'required|string',
                'secret1' => 'required',
                'secret2' => 'required',
                'otpType' => 'required',
                'otp' => 'required',
            ]
        );


        $id = $request->id ?? null;
        $email = $request->email ?? null;
        $mobile = $request->mobile ?? null;
        $otp = $request->otp;
        $countryCode = $request->countryCode; {

            if (isset($request->idToken)) {
                $verified = (new FirebaseController())->validateTokenId($request->idToken);
                // $verified = true;
            } else {
                return response([
                    'message' => "There is issue with authentication please restart app and try again",
                    'type' => 'mobile',
                ], 401);
            }




            if ($verified == true) {

                if (isset($id)) {
                    $user = User::find($id);
                }

                if (!isset($user)) {
                    $user = User::where('mobile', $mobile)->first();
                }


                if (!$user) {
                    $user =  User::create([
                        'mobile' => $request->mobile,
                        'fcm_token' => $request->fcm_token ?? null
                    ]);
                }
                $user->mobile = $mobile;
                $user->country_code = $countryCode;
                $user->mobile_verified_at = Carbon::now();
                $user->save();

                return $this->returnUserToken($request, $user);
            }
            return response([
                'message' => "Invalid or expired otp (Firebase)",
                'type' => 'mobile',
            ], 401);
        }
    }

    public function verifyUserEmailOtp(Request $request)
    {
        if ($this->invalid($request->all())) {
            $data = array();
            $data['message'] = 'Invalid secretes';
            return response($data);
        }
        $request->validate(
            [
                'email' => 'required|string',
                'secret1' => 'required',
                'secret2' => 'required',
                'otpType' => 'required',
            ]
        );


        $email = $request->email ?? null;

        if ($request->otpType == 'email') {

            $verified = $this->checkEmailOTP($email, $request->otp);
        }

        if ($verified == true) {

            $user = User::where('email', $email)->first();

            if (!$user) {
                $data = $request->all();
                $user =  User::create([
                    'email' => $email,
                    'fcm_token' => $request->fcm_token ?? null
                ]);
            }
            $user->email_verified_at = Carbon::now();

            if (isset($request->password) && ($request->isReset)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            return $this->returnUserToken($request, $user);
        }
        return response([
            'message' => "Invalid or expired otp (Firebase)",
            'type' => 'mobile',
        ], 401);
    }





   
    
    protected $secret1 =
    'A8@C1#03&56%14^ED@BE(EC)E1-A2+69=5F#1fxdghbvgTD8hBCvA2@8A%33#41';
    protected $secret2  =
    '#1C@D5@48@65@#2#2@B7@7#2@79@6E@bvhjnbvh@C1@ED@59@5D@E7@54@C4@ED@#21@CA@BC@DD@69@D3@A7@9A@C9@60@7#2@#21#';


    public function invalid($fields)
    {
        return ($fields['secret1'] != $this->secret1) || ($fields['secret2'] != $this->secret2);
    }
    public function verifyGAF(Request $request)
    {
        if ($this->invalid($request->all())) {
            $data = array();
            $data['message'] = 'Invalid secretes';
            return response($data);
        }
        $user = $request->user;
        $secrete = $request->provider['secrete'];
        $provider = $request->provider['name'];


        Log::channel('callvcal')->info("validateTokenId: provider:" . json_encode($provider));



        if ($provider == 'apple' || $provider == 'googleWeb') {
            $verified = (new FirebaseController())->validateTokenId($secrete);
            // $verified = true;


            // if (!$verified) {
            //     return response([
            //         'message' => "There is issue with authentication please restart app and try again",
            //         'type' => 'google',
            //     ], 401);
            // }
        }
        if ($provider == 'google') {
            $verified = (new FirebaseController())->validateAccessToken($secrete);

            // $verified = true;

            if (!$verified) {
                return response([
                    'message' => "There is issue with authentication please restart app and try again",
                    'type' => 'google',
                ], 401);
            }
        }


        $userModel = User::where('email', $user['email'])->first();



        if (!$userModel) {
            $userModel = User::create([
                'email' => $user['email'],
                'name' => $user['name'],
                'fcm_token' => $request->fcm_token ?? null
            ]);
        }

        try {
            if (!isset($userModel->image)) {
                if (isset($user['image'])) {
                    $dist = 'namak/images';
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
    ///Verify google apple facebook


}
