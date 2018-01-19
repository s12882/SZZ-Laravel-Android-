<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\Datatables\Datatables;
use App\Helpers\DtButtonHelper;
use App\Enums\DtButtonType;
use Auth;

class RoleService {

    public function get($id) {
        return Role::findOrFail($id);
    }

    public function lists() {
        return Role::orderBy('name')->select('name', 'id')->pluck('name', 'id');
    }

    public function store($input) {
      $role = Role::create(['name' => $input['name']]);
      $perm = $input['permissions'];
      $role->syncPermissions($perm);

      if((in_array('create user', $perm) || in_array('update user', $perm) || in_array('delete user', $perm)) && !in_array('list users', $perm))
        $role->givePermissionTo('list users');
      if((in_array('create section', $perm) || in_array('update section', $perm) || in_array('delete section', $perm)) && !in_array('list sections', $perm))
        $role->givePermissionTo('list sections');
      if((in_array('create role', $perm) || in_array('update role', $perm) || in_array('delete role', $perm)) && !in_array('list roles', $perm))
        $role->givePermissionTo('list roles');
      return $role;

    }

    public function update($input) {
      $role = Role::findOrFail($input['id']);
      $perm = $input['permissions']; 
      $role->syncPermissions($perm);
      return $role->update($input->except(['id', 'permissions']));
    }

    public function destroy($id) {
        return Role::findOrFail($id)->delete();
    }

    public function datatables($input) {
        $query = Role::select();
        $datatables = Datatables::of($query);
        if(Auth::user()->hasAnyPermission(['update role', 'delete role'])){
          $datatables = $datatables->addColumn('actions', function($role) {
              $actions = '';
              if(Auth::user()->hasPermissionTo('update role'))
                $actions .= DtButtonHelper::getByType(route('role.edit', ['role' => $role->id]), DtButtonType::EDIT);
              if(Auth::user()->hasPermissionTo('delete role'))
                $actions .= DtButtonHelper::getByType(route('role.destroy', ['client' => $role->id]), DtButtonType::DELETE);
              return $actions;
          })->rawColumns(['actions']);
        }
        return $datatables->make(true);
    }
}
