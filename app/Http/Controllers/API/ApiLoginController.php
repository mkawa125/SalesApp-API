<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class ApiLoginController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'userMessage' => 'Form validation error',
                'developerMessage' => 'Some fields  have error',
                'errorFields' => $validator->errors()
                ], 401);
        }
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'userMessage' => 'Invalid username or password',
                    'developerMessage' => 'Either password or username is invalid'
                ], 401);
            }elseif($token = JWTAuth::attempt($credentials)){
                return Response::json([
                    'userMessage' => 'Login success',
                    'developerMessage' => 'User exists on database and access token created',
                    'data' => auth()->user(),
                    'token' => $token,
                ], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

    }

    public function redirect($provider){
        return Socialite::driver($provider)->redirect();
    }
     /**
      * Login with social google account
      */

    public function handleCallback($driver)
    {
        try {

            $user = Socialite::driver($driver)->user();

            $userDetails = $user->user;
            $userDetails = (object) $userDetails;

            $finduser = User::where('email', $user->email)->first();

            if($finduser){

                Auth::login($finduser);

                return redirect('/home');

            }elseif($driver == 'google'){

                return redirect()->back();
            }elseif ($driver == 'github'){

                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect('auth/'.$driver.'');
        }
    }

    public function logout(Request $request){
        // Get JWT Token from the request header key "Authorization"
        $token = $request->header('Authorization');
        // Invalidate the token
        try {
            JWTAuth::unsetToken($token);
            return response()->json([
                'status' => 'success',
                'message'=> "User successfully logged out."
            ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return Response::json([
            'status' => 'error',
                'message' => 'Failed to logout, please try again.',
                'token'=> $token
            ], 500);
        }
    }
}
