<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\NotificationTrait;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\SectionService;
use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;
use URL;
use View;

class UserController extends Controller
{
    use NotificationTrait;
    public function __construct(UserService $userService, SectionService $sectionService, RoleService $roleService)
    {
        $this->modelService = $userService;
        $this->sectionService = $sectionService;
        $this->roleService = $roleService;
        $this->messageModel = trans('models.user');
    }

    public $notificationTheme = "User";

    public function index()
    {
        return view('user.index', [
            'sections' => $this->sectionService->lists()
        ]);
    }

    public function create()
    {
        $sections = $this->sectionService->lists();
        $roles = $this->roleService->lists();
        $postAction = route('user.store');
        $actionMethod = 'POST';

        return view('user.edit', [
            'user' => null,
            'postAction' => $postAction,
            'actionMethod' => $actionMethod,
            'sections' => $sections,
            'roles' => $roles,
            'pageTitle' => 'Nowy pracownik',
            'back_button_action' => route('user.index'),
        ]);
    }

    public function store(UserRequest $request)
    {
        if ($this->modelService->store($request)) {
            return redirect()->route('user.index')->withSuccess(trans('actions.created', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_created', ['object' => $this->messageModel]));
    }

    public function activate(User $user)
    {
        if ($this->modelService->activate($user->id)) {
            $message = "Aktywacja twojego profilu";
	        $this ->sendToOne($user ,"Profile activate", $message, $user->id);
            return redirect()->route('user.index')->withSuccess(trans('actions.activated', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_activated', ['object' => $this->messageModel]));
    }

    public function deactivate(User $user)
    {
        if ($this->modelService->deactivate($user->id)) {

            $message = "Dezaktywacja twojego profilu";
	        $this ->sendToOne($user ,"Profile deactivate", $message, $user->id);
            return redirect()->route('user.index')->withSuccess(trans('actions.deactivated', ['object' => $this->messageModel]));
        }
        return redirect()->route('user.index')->withErrors(trans('actions.not_deactivated', ['object' => $this->messageModel]));
    }

    public function edit(User $user)
    {
        $sections = $this->sectionService->lists();
        $roles = $this->roleService->lists();
        $postAction = route('user.update', ['user' => $user->id]);
        $actionMethod = 'PATCH';

        return view('user.edit', [
            'user' => $user,
            'postAction' => $postAction,
            'actionMethod' => $actionMethod,
            'sections' => $sections,
            'roles' => $roles,
            'pageTitle' => 'Edycja ' . $user->name . " " . $user->surname,
            'back_button_action' => route('user.index'),
        ]);
    }

    public function update(UserRequest $request)
    {   
        if ($this->modelService->update($request)) {}
        {   
            $message = "Modyfikacja twojego profilu";
            $user = $this->modelService->get($request['id'])->first();
	        $this ->sendToOne($user ,"Profile update", $message, $request['id']);
            $route = preg_match('/profile/', URL::previous()) == 1 ? 'profile.edit' : 'user.index';
            return redirect()->route($route)->withSuccess(trans('actions.updated', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_updated', ['object' => $this->messageModel]));
    }

    public function destroy($id)
    {
        if ($this->modelService->destroy($id)) {
            return redirect()->route('user.index')->withSuccess(trans('actions.deleted', ['object' => $this->messageModel]));
        }
        return back()->withErrors(trans('actions.not_deleted', ['object' => $this->messageModel]));
    }

    public function profile()
    {
        $user = Auth::user();
        $postAction = route('user.update', ['user' => Auth::id()]);
        $actionMethod = 'PATCH';
        $sections = $this->sectionService->lists();
        $roles = $this->roleService->lists();
        return view('user.profile', [
            'user' => $user,
            'postAction' => $postAction,
            'actionMethod' => $actionMethod,
            'sections' => $sections,
            'roles' => $roles,
            'passwordInput' => true,
            'pageTitle' => 'Edycja ' . $user->first_name . " " . $user->surname,
            'back_button_action' => route('profile.index'),
        ]);
    }

    public function datatables(Request $request)
    {
        return $this->modelService->datatables($request);
    }
}
