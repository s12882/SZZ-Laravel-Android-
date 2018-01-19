<?php

namespace App\Services;

use App\Enums\DtButtonType;
use App\Enums\TaskStatus;
use App\Helpers\DtButtonHelper;
use App\Models\Section;
use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\TaskImage;
use Auth;
use Yajra\Datatables\Datatables;

class TaskService
{
    public function get($id)
    {
        return $this->tasks()->where('id', $id)->first();
    }

    public function tasks()
    {
        $baseQuery = Task::with('section');
        if (Auth::user()->hasPermissionTo('see all sections/users/tasks')) {
            $tasks = $baseQuery;
        } else if (Auth::user()->hasPermissionTo('see own and slave sections/users/tasks') && Auth::user()->section) {
            $userSection = Auth::user()->section;
            $tasks = $baseQuery->whereIn('section_id', SectionService::slaveIds($userSection));
        } else {
            $tasks = $baseQuery->where('section_id', Auth::user()->section_id)
                ->where(function ($query) {
                    $query->whereDoesntHave('users')
                        ->orwhereHas('users', function ($query) {
                            $query->where('user_id', Auth::id());
                        });
                })
                ->WhereDate('scheduled_for', '<=', date("Y-m-d"))
                ->where('status', '!=', TaskStatus::Zakończone);

        }
        return $tasks;
    }

    public function lists()
    {
        return Task::orderBy('name')->select('name', 'id')->pluck('name', 'id');
    }

    public function store($input)
    {
        if ($input['section_id'] == 0) {
            $input['section_id'] = `null`;
        }
        try{
            \DB::beginTransaction();
            $users = json_decode($input['assignedUsers'], true);
            $input['status'] = TaskStatus::Nowe;
            $task = Task::create($input->except('_token'));
            $task->save();
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'users' => $input['assignedUsers'],
                'previous_version' => null
            ]);
            $task->users()->sync($users);
            \DB::commit();
            return $task;
        }catch(\PDOException $e){
            \DB::rollBack();
            return false;
        }
    }

    public function manageItems($input, $id){
        try{
            \DB::beginTransaction();
            $task = $this->get($id);
            $items = array_values(json_decode($input['items'], true));
            $task->items()->detach();
            foreach($items as $item){
                $task->items()->attach($item['item_id'], ['amount' => $item['amount']]);
            }
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'users' => $task->users->pluck('id'),
                'previous_version' => $task->makeHidden(['section','users','finished_at','updated_at'])->toJSON()
            ]);
            \DB::commit();
            return true;
        }catch(\PDOException $e){
            \DB::rollBack();
            return false;
        }
    }

    public function update($input)
    {
        if ($input['section_id'] == 0) {
            $input['section_id'] = `null`;
        }

        try {
            \DB::beginTransaction();
            $task = Task::findOrFail($input['id']);
            $users = json_decode($input['assignedUsers'], true);
            $task->users()->sync($users);
            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'users' => $input['assignedUsers'],
                'previous_version' => $task->makeHidden(['finished_at','updated_at'])->toJSON()
            ]);
            if($input["status"] == TaskStatus::Zakończone)
                $input['finished_at'] = date('Y-m-d H:m:s');
            $task->update($input->except(['id', 'assignedUsers']));
            \DB::commit();
            return true;
        } catch (\PDOException $e) {
            \DB::rollBack();
            return false;
        }
    }

    public function get_image($id) {
        return TaskImage::findOrFail($id);
    }

    public function store_image($input) {
        return TaskImage::create($input);
    }

    public function destroy_image($id) {
        return TaskImage::findOrFail($id)->delete();
    }

    public function destroy($id)
    {
        return Task::findOrFail($id)->delete();
    }

    public function restore($id)
    {
        return Task::withTrashed()->findOrFail($id)->restore();
    }

    public function reserve($request){
        $id = $request->route('id');
        $task = $this->get($id);
        $task->status = TaskStatus::W_trakcie_wykonywania;
        $task->save();
        return $task->users()->attach(Auth::id());
    }

    public function forwardToCheck($input){
        return Task::findOrFail($input->id)->update(['status' => TaskStatus::Weryfikacja]);
    }

    public function datatables($input)
    {
        $query = $this->tasks()->with('users')->select();
        $datatables = Datatables::of($query)
            ->addColumn('actions', function ($task) {
                $actions = '';
                if(Auth::user()->hasPermissionTo('update task'))
                    $actions .= DtButtonHelper::getByType(route('task.edit', ['task' => $task->id]), DtButtonType::EDIT);
                $actions .= DtButtonHelper::getByType(route('task.show', ['task' => $task->id]), DtButtonType::SHOW);
                if (Auth::user()->hasPermissionTo('delete task')) {
                    $actions .= DtButtonHelper::getByType(route('task.destroy', ['task' => $task->id]), DtButtonType::DELETE);
                }
                return $actions;
            })->addColumn('status', function($task){
                    return $task->status_string;
            })->addColumn('users', function ($task) {
                return $task->users->map(function($user) {
                    return $user->first_name." ".$user->surname;
                })->implode(',');
            });

        if ($input['status'])
            $datatables->where('status', $input['status']);

        if ($input['section_id']) 
            $datatables->where('section_id', $input['section_id']);

        if ($input['date_from']) 
            $datatables->where('created_at', '>=', $input['date_from'].' 23:59:59');
        
        if ($input['date_to']) 
            $datatables->where('created_at', '<=', $input['date_to'].' 23:59:59');

        return $datatables->rawColumns(['actions'])->make(true);
    }

}
