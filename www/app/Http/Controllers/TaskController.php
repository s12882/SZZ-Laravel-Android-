<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\TaskRequest;
use App\Services\SectionService;
use App\Services\TaskService;
use App\Models\Item;
use App\Models\User;
use App\Services\UserService;
use App\Services\ItemService;
use Illuminate\Http\Request;
use App\Logic\FileHandler;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\API\NotificationTrait;

class TaskController extends Controller
{
     use NotificationTrait;

    public function __construct(TaskService $itemService, TaskService $taskService, SectionService $sectionService, UserService $userService)
    {
        $this->modelService = $taskService;
        $this->sectionService = $sectionService;
        $this->userService = $userService;
        $this->itemService = $itemService;
        $this->messageModel = trans('models.task');
        $this->filesFolder = '/images/tasks';
    }

    public function index()
    {
        return view('task.index', [
            'taskStatus' => TaskStatus::getSelect(),
            'sections' => $this->sectionService->lists()
        ]);
    }

    public function create()
    {
        $sections = $this->sectionService->lists();
        $users = $this->userService->users()->get();
        return view('task.edit', [
            'pageTitle' => 'Nowe Zadanie',
            'task' => null,
            'postAction' => route('task.store'),
            'sections' => $sections,
            'users' => $users,
            'taskUsers' => '[]',
            'actionMethod' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $task = $this->modelService->store($request);
        if ($task) {
            $images = $request->file('images');
            if (count($images) > 0) {
                foreach ($images as $image) {
                    try {
                        $fileHandler = new FileHandler($this->filesFolder);
                        $fileName = $fileHandler->setAndGetFilename($image);

                        if ($this->modelService->store_image($fileHandler->getFileInfoForTask($task->id))) {
                            $fileHandler->save();
                        }
                    } catch (\Exception $e) {
                        Log::warning($e->getMessage());
                    }
                }
            }
            $users = User::where('section_id', $request['section_id'])->get();
            $message = "Nowe zadanie w twoim działu";
	        $this ->sendToMany($users, "Task created", $message, $task->id);

            $usersIds = json_decode($request['assignedUsers']);
            $message = "Nowe zadanie dla ciebie";
	        $this ->sendToManyByid($usersIds, "Task Create", $message, $task->id);

            return redirect()->route('task.index')->withSuccess(trans('actions.created_n', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_created_n', ['object' => $this->messageModel]));
    }

    public function show($id)
    {
        $task = $this->modelService->get($id);
        return view('task.show', [
            'task' => $task,
            'postAction' => route('comment.store'),
            'actionMethod' => 'POST',
            'deleteURL' => env('APP_URL') . '/comment/',
            'reserveURL' => route('task.reserve',['task' => $task])
        ]);
    }

    public function download_image($id) {
        $image = $this->modelService->get_image($id);
        $headers = array(
          'Content-Type' => $image->type,
        );
        return response()->download($image->fullPath(), $image->original_file_name, $headers);
    }

    public function destroy_image($id) {
        $image = $this->modelService->get_image($id);

        try {
            $fileHandler = new FileHandler($this->filesFolder);
            if ($this->modelService->destroy_image($id)) {
                $fileHandler->deleteFile($image->webPath());
            } else {
                return back()->withErros(trans('actions.image_not_deleted'));
            }
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return back()->withErrors($e->getMessage());
        }

        return back()->with('success', trans('actions.image_deleted'));
    }

    public function edit($id)
    {
        $sections = $this->sectionService->lists();
        $task = $this->modelService->get($id);
        $users = $this->userService->users()->get();
        $postAction = route('task.update', ['task' => $task->id]);
        $actionMethod = 'PATCH';
        return view('task.edit', [
            'task' => $task,
            'postAction' => $postAction,
            'actionMethod' => $actionMethod,
            'sections' => $sections,
            'users' => $users,
            'taskUsers' => $task->users,
            'pageTitle' => 'Edycja ' . $task->name,
            'status' => TaskStatus::getSelect(),
        ]);
    }

    public function update(TaskRequest $request)
    {
        $task = $this->modelService->update($request);
        if ($task) {
            $images = $request->file('images');
            if (count($images) > 0) {
                foreach ($images as $image) {
                    try {
                        $fileHandler = new FileHandler($this->filesFolder);
                        $fileName = $fileHandler->setAndGetFilename($image);
                        if ($this->modelService->store_image($fileHandler->getFileInfoForTask($request->get('id')))) {
                            $fileHandler->save();
                        }
                    } catch (\Exception $e) {
                        Log::warning($e->getMessage());
                    }
                }
            }
           
            $users = User::where('section_id', $request['section_id'])->get();
            $message = "Zadanie zostalo zmodyfikowane";
	        $this ->sendToMany($users, "Task Update", $message, $request['id']);
            
            $usersIds = json_decode($request['assignedUsers']);
            $message = "Zmiany w twoich zadaniach";
	        $this ->sendToManyByid($usersIds, "Task Assign", $message, $request['id']);

            return redirect()->route('task.index')->withSuccess(trans('actions.updated_n', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_updated_n', ['object' => $this->messageModel]));
    }

    public function destroy($id)
    {
        $task = $this->modelService->get($id);
        $users = User::where('section_id', $task->section_id)->get();
        $images = $task->images;
        if (count($images) > 0) {
            foreach ($images as $image) {
                try {
                    $fileHandler = new FileHandler($this->filesFolder);
                    $fileHandler->deleteFile($this->filesFolder.'/'.$image->file_name);
                } catch (\Exception $e) {
                    Log::warning($e->getMessage());
                }
            }
        }

        if($this->modelService->destroy($id)){
            $message = "Zadanie zostalo usunięte";
	        $this ->sendToMany($users, "Task Delete", $message, $id);
            return redirect()->route('task.index')->withSuccess(trans('actions.deleted_n', ['object' => $this->messageModel]));
        }       
        return back()->withErrors(trans('actions.not_deleted_n', ['object' => $this->messageModel]));
    }

    public function reserve(TaskRequest $request)
    {
            $id = $request->route('id');
            $message = "Rezerwacja zadania";
            $user = Auth::user();
	        $this ->sendToOne($user , "Task Reserve", $message, $id);

        return $this->modelService->reserve($request);
    }

    public function forwardToCheck(Request $request)
    {   
        if($this->modelService->forwardToCheck($request))
            return back()->withSuccess(trans('actions.forwarded_ok_n', ['object' => $this->messageModel]));
        return back()->withErrors(trans('actions.forwarded_error_n', ['object' => $this->messageModel]));
    }

    public function manageItems(Request $request, $id){
        if($this->modelService->manageItems($request, $id)){
            return back()->withSuccess(trans('actions.changes_ok', ['object' => $this->messageModel]));
        }          
        return back()->withErrors(trans('actions.changes_error', ['object' => $this->messageModel]));
    }

    public function datatables(Request $request)
    {
        return $this->modelService->datatables($request);
    }
}
