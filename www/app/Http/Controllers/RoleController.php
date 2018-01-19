<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Services\RoleService;
use App\Services\PermissionService;

class RoleController extends Controller
{

  public function __construct(RoleService $roleService, PermissionService $permissionService){
    $this->modelService = $roleService;
    $this->permissionService = $permissionService;
    $this->messageModel = trans('models.role');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("permission.role.index");
    }

    public function create()
    {
      $postAction = route('role.store');
      $actionMethod = 'POST';
      $permissions = $this->permissionService->lists();

      return view('permission.role.edit', [
        'model' => null,
        'postAction' => $postAction,
        'actionMethod' => $actionMethod,
        'pageTitle' => 'Nowa rola',
        'permissions' => $permissions
      ]);
    }

    public function store(Request $request)
    {
      if ($this->modelService->store($request))
      {
          return redirect()->route('role.index')->withSuccess(trans('actions.created_f', ['object' => $this->messageModel]));
      }
      return back()->withErrors(trans('actions.not_created_f', ['object' => $this->messageModel]));
    }

    public function edit(Role $role)
    {
      $postAction = route('role.update', ['role' => $role->id]);
      $actionMethod = 'PATCH';
      $permissions = $this->permissionService->lists();

      return view('permission.role.edit', [
        'model' => $role,
        'postAction' => $postAction,
        'actionMethod' => $actionMethod,
        'pageTitle' => 'Edycja ' . $role->name,
        'permissions' => $permissions
      ]);
    }

    public function update(Request $request, $id)
    {
      if ($this->modelService->update($request))
        return redirect()->route('role.index')->withSuccess(trans('actions.updated_f', ['object' => $this->messageModel]));
      return back()->withErrors(trans('actions.not_updated_f', ['object' => $this->messageModel]));
    }

    public function destroy($id)
    {
      if ($this->modelService->destroy($id)) {
          return redirect()->route('role.index')->withSuccess(trans('actions.deleted_f', ['object' => $this->messageModel]));
      }
    return back()->withErrors(trans('actions.not_deleted_f', ['object' => $this->messageModel]));
    }

    public function datatables(Request $request){
      return $this->modelService->datatables($request);
    }
}
