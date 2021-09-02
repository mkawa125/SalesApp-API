<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;

class ApiRegisterController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'first_name' => 'required',
            'surname' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $data = $request->all();
        $data['name'] = $data['first_name'] ." ". $data['surname'];
        $data['password'] = bcrypt($request->post('password'));
        User::create($data);
        $user = User::first();
        $token = JWTAuth::fromUser($user);

        return Response::json([
            'error' => false,
            "success" => true,
            'message' => 'User created',
            'user' => $user,
            'access_token' => $token,
        ], 201);
    }
}
