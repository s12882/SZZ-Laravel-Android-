<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Permission\Models\Role;
use App\Services\UserService;
use Validator;

class NotificationsController extends Controller
{
        use NotificationTrait;

    public function __construct(UserService $userService){
    $this->modelService = $userService;
  }

  public function saveDevice(Request $request){
        $validator = Validator::make($request->all(), [
        'id' =>'required',
        'device_id'=>'required',
        ]);

         if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }  

         if($this->modelService->update($request))
        {
            return response()->json(['success'=>'success'], 200);
        }

        return response()->json(['error'=>'Server error'], 501);
  }
}

