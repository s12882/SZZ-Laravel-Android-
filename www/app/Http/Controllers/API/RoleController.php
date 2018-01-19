<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Services\RoleService;
use App\Services\PermissionService;
use Validator;

use Auth;
use View;
use URL;

class RoleController extends Controller
{
     public function __construct(RoleService $roleService, PermissionService $permissionService){
    $this->modelService = $roleService;
    $this->permissionService = $permissionService;
    $this->messageModel = trans('models.role');
  }

    public function All(Request $request)
    {
        $roles = Role::all();
        return response()->json(['success' => $roles], 200);
    }

    public function get(Request $request)
    {
      $id = $request['id'];
      $role = Role::where('id', $id)->first();
      
      if($role){
        return response()->json(['success'=>$role], 200);  
      }
      return response()->json(['error'=>'error'], 501);
    }

}
