<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Permission\Models\Role;
use App\Services\RoleService;
use App\Services\PermissionService;
use Validator;

class LoginController extends Controller
{

    public function __construct(RoleService $roleService, PermissionService $permissionService){
    $this->roleService = $roleService;
    $this->permissionService = $permissionService;
    $this->messageModel = trans('models.role');
  }
        public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        if(Auth::attempt(['login' => request('login'), 'password' => request('password'), 'is_active' => 1])){
            $user = Auth::user();
            $token = $user->accessToken;
            $success = $user->createToken('MyApp')->accessToken;
            return response()->json(['token' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        $role = $user->roles;
        $permissions = $user->getPermissionsViaRoles();
        return response()->json(['success' => $user], $this->successStatus);
    }

	public function verifyToken(){
	
        $user = Auth::user();
        return response()->json(['success' => ' '], $this->successStatus);
	}
}