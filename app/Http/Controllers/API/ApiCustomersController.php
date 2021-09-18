<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use App\Models\Customer;

class ApiCustomersController extends Controller
{
    public function registerCustomer(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:customers',
            'first_name' => 'required',
            'surname' => 'required',
            'password'=> 'required',
            'identification_number' => 'required',
            'gender' => 'required',
        ]); 
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $data = $request->all();
        $data['name'] = $data['first_name'] ." ". $data['surname'];
        $data['password'] = bcrypt($request->post('password'));
        $data['created_by'] = auth()->user()->id;
        $data['lead_sorce'] = auth()->user()->id;
        $customer = Customer::create($data);
        // $user = User::first();
        // $token = JWTAuth::fromUser($user);

        return Response::json([
            'error' => false,
            "success" => true,
            'message' => 'Customer created',
            'customer' => $customer,
        ], 201);
    }
}
