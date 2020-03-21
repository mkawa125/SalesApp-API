<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiUserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'asc')->paginate(20);
        return response()->json([
            'userMessage' => 'success',
            'developerMessage' => 'Users retrieved successfully',
            'data' => UserResource::collection($users),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','id')->all();
        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'Roles successfully retrieved',
            'data' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'first_name' => 'required',
            'phone_number' => 'required|min:9|unique:users',
            'surname' => 'required|string',
            'password' => 'min:6|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'User successfully created',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::find($id);
            return response()->json([
                'userMessage' => 'Success',
                'developerMessage' => 'User found to database',
                'data' => new UserResource($user),
            ], 200);                                                                
        } catch (\Throwable $e) {
            return response()->json([
                'userMessage' => 'Sorry error occured',
                'developerMessage' => 'User Not Found',
            ], 404);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $user = User::find($id);
            $roles = Role::pluck('name','name')->all();
            $userRole = $user->roles->pluck('name','name')->all();
            return response()->json([
                'userMessage' => 'Success',
                'developerMessage' => 'User found to database',
                'data' => new UserResource($user),
                'roles' => $role,
                'userRole' => $userRole,
            ], 200);                                                                
        } catch (\Throwable $e) {
            return response()->json([
                'userMessage' => 'Sorry error occured',
                'developerMessage' => 'User Not Found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request()->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'first_name' => 'required',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        /** get all request data */
        $input = $request->all();

        /** check if request has password */
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input , array('password'));
        }

        $user = User::find($id);
        $user->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'User successfully updated',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'User deleted successfully',
        ], 200);
    }
}
