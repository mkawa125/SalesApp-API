<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use DB;

class ApiRoleController extends Controller
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
    public function index(Request $request)
    {
        $roles = Role::all();
        return response()->json([
                'userMessage' => 'Success',
                'developerMessage' => 'Roles retrieved successfully',
                'data' => $roles,
            ], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return response()->json([
                'userMessage' => 'Success',
                'developerMessage' => 'Permisions retrieved successfully',
                'data' => $permissions,
            ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         /**  Validate all input fields */

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'userMessage' => 'Form validation error',
                'developerMessage' => 'Some fields  have error',
                'errorFields' => $validator->errors()
                ], 401
            );
        }

        /** Create role  and sync role permissions */
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'Role created successfully',
            'data' => $role,
        ], 201);
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'Role created successfully',
            'role' => $role,
            'permissions' =>$permissions,
        ], 201);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'Role and permissions retrieved successfully',
            'role' => $role,
            'permissions' =>$permissions,
            'rolePermissions' => $rolePermissions,
        ], 201);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'userMessage' => 'Form validation error',
                'developerMessage' => 'Some fields  have error',
                'errorFields' => $validator->errors()
                ], 401
            );
        }

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();


        $role->syncPermissions($request->input('permission'));


        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'Role and permissions updated successfully',
            'role' => $role,
            'permissions' =>$permissions,
            'rolePermissions' => $rolePermissions,
        ], 201);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return response()->json([
            'userMessage' => 'Success',
            'developerMessage' => 'Role deleted successfully',
        ], 200);
    }
}
