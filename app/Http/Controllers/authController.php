<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use App\Mail\visitorConfirmation;
use Twilio\Jwt\AccessToken;

class authController extends Controller
{
    // register user
    public function registerUser(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',

        ]);
        if ($validator->fails()) {

            return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->site_id = $request->site_id;
        $user->phone = $request->phone;
        $user->save();
        $usr = User::where('id', $user->id)->first();
        $usr->assignRole('Visitor');

        if ($user) {

            // Mail::send('templates.email.register_user', ['user' => $user,'password'=>$request->password ], function ($message) use ($user) {
            //     $message->to($user->email);
            //     $message->subject('Your account has been created');
            //     $message->from(env('MAIL_FROM_ADDRESS'),env('APP_NAME'));
            // });



            return response()->json([
                'status' => 'success',
                'msg' => 'User Account Created Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 'fail',
                'msg' => 'Email has been already exist'
            ], 200);
        }
    }
    // guest regster page
    public function GuestCheck($id)
    {

        $visitor_id = Crypt::decryptString($id);

        if ($visitor_id) {
            $app = Appointment::find($visitor_id);
            if ($app) {

                $user = User::where('email', $app->email)->first();
                if ($user) {
                    return redirect()->route('guest/login', ['id' => $id]);
                } else {

                    return redirect()->route('guest/register', ['id' => $id]);
                }
            } else {
                return abort('404');
            }
        } else {
            return abort('404');
        }
    }

    public function guestRegister($id)
    {

        $visitor = Crypt::decryptString($id);
        $app = Appointment::find($visitor);
        if ($app) {
            return view('templates.auth.guest_register', compact('app', 'id'));
        } else {
            return abort('404');
        }
    }

    public function guestLogin($id)
    {

        $visitor = Crypt::decryptString($id);
        $app = Appointment::find($visitor);
        if ($app) {

            return view('templates.auth.guest_login', compact('app', 'id'));
        } else {
            return abort('404');
        }
    }

    //login page view
    public function LoginPageView()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {

            Session::flush();
            $res = [];
            $res["msg"] = "no";
            return view('templates.auth.login', compact('res'));
        }
    }
    // login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
        }
        try {
            // $remember_me = $request->has('remember_me') ? true : false;
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                if (Auth::user()->status == 'active') {
                    $user = Auth::user();
                    $responseArray = $user->createToken('app')->accessToken;
                    $identity = Auth::user()->first_name;
                    $role = '';
                    if (Auth::user()->hasRole('Visitor')) {
                        $role = 'visitor';
                    } elseif (Auth::user()->hasRole('Tenant')) {

                        $role = 'tenant';
                    } elseif (Auth::user()->hasRole('Guard')) {

                        $role = 'guard';
                    } elseif (Auth::user()->hasRole('BuildingAdmin')) {
                        $role = 'BuildingAdmin';
                    } elseif (Auth::user()->hasRole('Integrator')) {
                        $role = 'Integrator';
                    } else {
                        $role = 'admin';
                    }
                    $passwordcheck = $user->password_updated;
                    // $app = $request->app_id;
                    // if (!empty($app)) {

                    //     $ap = Appointment::find($app);
                    //     $client = User::where('id',$ap->tenant_id)->first();
                    //     $site = $client->site->name;

                    //     $data=[];
                    //     $data["visitor_name"] = $ap->name;
                    //     $data["site"] = $site;
                    //     $data["image_url"] = asset($ap->qr_code);



                    //     Mail::to($ap->email)->send(new visitorConfirmation($data));


                    // } 

                    return response()->json(['status' => 'success', 'token' => $responseArray, 'msg' => 'You have successfully login', 'data' => $user, 'role' => $role, 'password_status' => $passwordcheck]);
                } else {
                    Auth::logout();
                    return response()->json(['status' => 'fail', 'msg' => 'Your account is not active Yet']);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'msg' => 'Invalid Email/Password'
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'server',
                'msg' => $exception->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }

    ///---------Forgot Password--------------///


    public function submitForgetPasswordForm(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $user = User::where('email', $request->email)->first();

            Mail::send('templates.email.forgot_password', ['token' => $token, 'user' => $user], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Please reset your account passsword');
                $message->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
            });

            return response()->json(['status' => 'success', 'msg' => 'We have emailed your password reset link, please checkout your mail']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function showResetPasswordForm($token)
    {
        return view('templates.auth.reset_password', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
        }

        $updatePassword = DB::table('password_resets')
            ->where([
                'token' => $request->token
            ])
            ->first();

        if (empty($updatePassword)) {

            return response()->json(['status' => 'fail', 'msg' => 'Invalid token']);
        } else {

            $user = User::where('email', $updatePassword->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();


            return response()->json(['status' => 'success', 'msg' => 'Your password has been changed']);
        }
    }
}
