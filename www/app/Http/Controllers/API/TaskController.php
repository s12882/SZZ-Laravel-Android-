<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Validator;

class TaskController extends Controller
{
     use NotificationTrait;
    
    public function __construct(TaskService $taskService) {
        $this->modelService = $taskService;
        $this->messageModel = trans('models.task');
    }
    
        /**
         * API for a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $tasks = Task::all();    
            return response()->json(['success' => $tasks], 200);    
        }
        /**
         * API for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'location' => 'required',
                'scheduled_for' => 'required',
                'section_id' => 'required',
                'assignedUsers' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);            
            }  

            $task = $this->modelService->store($request);
            if($task){
                return response()->json(['success'=>'success'], 201); 

                $users = User::where('section_id', $request['section_id'])->get();
                $message = "Nowe zadanie w twoim działu";
	            $this ->sendToMany($users, "Task Create", $message, $task->id);

                $usersIds = json_decode($request['assignedUsers']);
                $message = "Nowe zadanie dla ciebie";
	            $this ->sendToManyByid($usersIds, "Task Create", $message, $task->id); 

                  
            }
            return response()->json(['error'=>'task create error'], 501);  
        }   
        /**
         * API to get the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function get(Request $request)
        {
            $id = $request['id'];
            if($this->modelService->get($id)){
                return response()->json(['success'=>$this->modelService->get($id)], 200); 
            }
            return response()->json(['error'=>'Error - no such a task'], 501); 
            
        }

        public function getBySection(Request $request)
        {
            $section_id = $request['section_id'];
            $tasks = Task::where('section_id', $section_id)->get();
            return response()->json(['success'=>$tasks], 200); 
        }
          
        /**
         * API for Update the specified resource in storage.
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
                'description' => 'required',
                'location' => 'required',
                'section_id' => 'required',
             ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);            
            }  

            if ($this->modelService->update($request)) {
                $users = User::where('section_id', $request['section_id'])->get();
                $message = "Zadanie zostalo zmodyfikowane";
	            $this ->sendToMany($users, "Task Update", $message, $request['id']);
            
                $usersIds = json_decode($request['assignedUsers']);
                $message = "Zmiany w twoich zadaniach";
	            $this ->sendToManyByid($usersIds, "Task Assign", $message, $request['id']);

                return response()->json(['success'=>'success'], 200);
            }     

            return response()->json(['error'=>'task edit error'], 501);   
        }

        /**
         *  API for Reserve the Task.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */

        public function reserve(Request $request)   
        {

            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'user_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);            
            }  
            $user = User::where('id', $request['user_id'])->first();
            $task = Task::where('id', $request['id'])->first();

            $task->status = TaskStatus::W_trakcie_wykonywania;
            $task->save();
            $task->users()->attach($user);
            
            return response()->json(['success'=>'success'], 201);   
        }

        public function getAssignedUsers(Request $request)   
        {
            $task = Task::where('id', $request['id'])->first();
            $users = $task->users;

            return response()->json(['success'=> $users], 200);   
        }

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
            return response()->json(['error'=>'task deliting error'], 401);
        }
    
    }