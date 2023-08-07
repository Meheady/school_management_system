<?php

namespace App\Http\Controllers\backend\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DB::table('users')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = DB::table('users')
            ->select('id', 'name', 'email')
            ->find($id);

        $userRole = DB::table('roles')
            ->join('user_has_roles', 'roles.id', '=', 'user_has_roles.role_id')
            ->select('roles.role_name', 'user_has_roles.role_id')
            ->where('user_has_roles.user_id', $user->id)
            ->first();

        $permission = DB::table('role_has_permissions')
            ->leftJoin('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('permissions.module_name', 'permissions.option_name')
            ->where('role_id', $userRole->role_id)
            ->get();

        return compact('user', 'userRole', 'permission');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
