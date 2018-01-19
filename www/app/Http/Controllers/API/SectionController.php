<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\LoginController;
use App\Services\SectionService;
use App\Services\UserService;
use Validator;

class SectionController extends Controller
{

  public function __construct(SectionService $sectionService, UserService $userService, LoginController $login) {
    $this->modelService = $sectionService;
    $this->userService = $userService;
    $this->login = $login;
    $this->messageModel = trans('models.section');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {      
        $sections = Section::all();
        return response()->json(['success' => $sections], 200);
    }

     public function getStaff(Request $request)
    {
        $SectionId = $request['section_id'];
        $users = User::where('section_id', $SectionId)->get();

        return response()->json(['success' => $users], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

      $validator = Validator::make($request->all(), [
            'name' => 'required',
            'manager_id' => 'required',
        ]);

      if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }  

        $saved = $this->modelService->store($request);

      if ($saved) {
          $this->userService->setSection($request['manager_id'], $saved->id);
           return response()->json(['success'=>'success'], 201);
      }
          return response()->json(['error'=>'section creating error'], 501);                
    }

    /**
     * API for get the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $id = $request['id'];
        $section = Section::where('id', $id)->first();;

        if($section){
            return response()->json(['success'=>$section], 200); 
        }
        return response()->json(['error'=>'Error - no such a section'], 404); 
    }
 
    /**
     *  API for Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'manager_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }  

      if ($this->modelService->update($request)) {
            return response()->json(['success'=>'success'], 200);
      }

      return response()->json(['error'=>'section editing error'], 401);     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

       $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }  

        $id = $request['id'];

      if ($this->modelService->destroy($id)) {
           return response()->json(['success'=>'success'], 200);
        }
      return response()->json(['error'=>'section deliting error'], 401);   
    }

    public function datatables(Request $request){
      // return 'rerere';
      return $this->modelService->datatables($request);
    }
}
