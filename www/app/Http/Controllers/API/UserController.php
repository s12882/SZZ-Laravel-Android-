<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\SectionService;
use App\Services\RoleService;
use Validator;

use Auth;
use View;
use URL;

class UserController extends Controller
{
    public function __construct(UserService $userService, SectionService $sectionService, RoleService $roleService) {
      $this->modelService = $userService;
      $this->roleService = $roleService;
      $this->sectionService = $sectionService;
      $this->messageModel = trans('models.user');
    }

    public function index(Request $request)
    {
        $users = User::all();
        return response()->json(['success' => $users], 200);
    }

    public function create(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'login' =>'required',
        'first_name'=>'required',
        'surname'=>'required',
        'email'=>'required',
        'phoneNumber'=>'required',
        'password'=>'required',
        'section_id'=>'required',
        ]);

      if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400,  ['object' => $this->messageModel]);            
      }  

      if ($this->modelService->store($request)) {
        return response()->json(['success'=>'success'], 201);
      }
      return response()->json(['error'=>'user creating error'], 501);

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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id'=>'required',
        'login' =>'required',
        'first_name'=>'required',
        'surname'=>'required',
        'email'=>'required',
        'phoneNumber'=>'required',
        'section_id'=>'required',
        ]);

      if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }  

      if($this->modelService->update($request))
      {
         return response()->json(['success'=>'success'], 200);
        }
      return response()->json(['error'=>'User editing error'], 501);
    }


     public function setpin(Request $request)
    {

        $validator = Validator::make($request->all(), [
        'id' =>'required',
        'mobile_pin'=>'required',
        ]);

      if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }  

      if($this->modelService->update($request))
      {
         return response()->json(['success'=>'success'], 200);
        }
      return response()->json(['error'=>'User editing error'], 501);
    }

    public function destroy(Request $request)
    {
      $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);            
        }  

        $id = $request['id'];

      if ($this->modelService->destroy($id)) 
      {
        return response()->json(['success'=>'success'], 200);
        }
      return response()->json(['error'=>'user deliting error'], 501); 
    }

    public function activate(User $user)
    {
      if ($this->modelService->activate($user->id)) {
             return response()->json(['success'=>'success'], 200);
        }
       return response()->json(['error'=>'error'], 501);
    }

    public function deactivate(User $user)
    {
      if ($this->modelService->deactivate($user->id)) {
            return response()->json(['success'=>'success'], 200);
        }
      return response()->json(['error'=>'error'], 501);
    }

    public function getUserTasks(Request $request)
    {
      $id = $request['id'];
      $user = User::where('id', $id)->first();
      $tasks = $user->tasks;
      
      if($tasks){
        return response()->json(['success'=>$tasks], 200);  
      }
      return response()->json(['error'=>'error'], 501);
    }

    public function profile(Request $request)
    {
      $id = $request['id'];
      $user = User::where('id', $id)->first();
      $role = $user->roles;
    
      if($user){
         return response()->json(['success'=>$user], 200); 
      }
      return response()->json(['error'=>'Error - no such a user'], 501); 
    }

    public function datatables(Request $request)
    {
      return $this->modelService->datatables($request);
    }
}
