<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Exception;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = auth('api')->attempt($credentials)) {
            return response([
                'token'=>$token,
                'userData'=>$this->selectUserDetails(Auth::id())
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    protected function selectUserDetails ($id){

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

        return [
          'user'=>$user,
          'role'=>$userRole,
          'permission'=>$permission
        ];
    }

    public function logout()
    {
        try {
            auth('api')->logout(true);
            return response()->json(['message' => 'Successfully logged out']);
        }catch (Exception $e){
            return response()->json([
               'error'=>$e->getMessage()
            ]);
        }

    }

    /**
     * Refresh a token.
     */
    public function refresh()
    {

        try {
            $newToken = auth('api')->refresh();
            return response()->json([
                "token"=>$newToken
            ]);
        }
        catch (TokenBlacklistedException $e){
            return response()->json(['error' => 'Token has been blacklisted'], 403);
        }

    }

    /**
     * Get the token array structure.
     *
     */

    protected function respondWithToken(string $token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     */
    public function guard()
    {
        return Auth::guard();
    }
}
