<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use App\Models\User;
use App\Services\SectionService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\API\NotificationTrait;

class SectionController extends Controller
{

    use NotificationTrait;

    public function __construct(SectionService $sectionService, UserService $userService)
    {
        $this->modelService = $sectionService;
        $this->userService = $userService;
        $this->messageModel = trans('models.section');
    }

    public $notificationTheme ="Section";

    public function index()
    {
        return View('section.index');
    }

    public function create(Request $request)
    {
        $parent = null;
        $section = Section::where('id', $request->parent)->first();
        if (count($section) > 0) {
            $parent = $section->id;
        }

        $postAction = route('section.store');
        $actionMethod = 'POST';

        return view('section.edit', [
            'model' => null,
            'postAction' => $postAction,
            'actionMethod' => $actionMethod,
            'pageTitle' => 'Nowy dział',
            'sections' => $this->modelService->lists(),
            'parent' => $parent,
        ]);    
    }

    public function store(SectionRequest $request)
    {
        $saved = $this->modelService->store($request);
        if ($saved) {
            return redirect()->route('section.index')->withSuccess(trans('actions.created', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_created', ['object' => $this->messageModel]));
    }

    public function edit($id)
    {
        $postAction = route('section.update', ['section' => $id]);
        $actionMethod = 'PATCH';
        $section = $this->modelService->get($id);
        $ids = $this->modelService->slaveIds($section);
        $sections = array_except($this->modelService->lists(), $ids);
        return view('section.edit', [
            'model' => $section,
            'postAction' => $postAction,
            'actionMethod' => $actionMethod,
            'pageTitle' => 'EDYCJA '.$section->name,
            'sections' => $sections->forget($id),
            'parent' => null,
        ]);
    }

    public function update(SectionRequest $request, $id)
    {
        if ($this->modelService->update($request)) {
            
            $users = User::where('section_id', $id)->get();
            $message = "Dział zostal zmodyfikowany";
	        $this ->sendToMany($users, "Section Update", $message, $request['id']);

            return redirect()->route('section.index')->withSuccess(trans('actions.updated', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_updated', ['object' => $this->messageModel]));
    }

    public function destroy($id)
    {
        if ($this->modelService->destroy($id)) {
	    $users = User::where('section_id', $id)->get();
        $message = "Dział zostal usunięty";
	    $this ->sendToMany($users, "Section Deleted", $message, $id);
            return redirect()->route('section.index')->withSuccess(trans('actions.deleted', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_deleted', ['object' => $this->messageModel]));
    }

    public function treedata(Request $request)
    {
        return $this->modelService->treedata($request);
    }
}
