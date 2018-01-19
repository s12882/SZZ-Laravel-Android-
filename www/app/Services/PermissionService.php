<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Yajra\Datatables\Datatables;
use App\Helpers\DtButtonHelper;
use App\Enums\DtButtonType;

class PermissionService {

    public function get($id) {
        return Permission::findOrFail($id);
    }

    public function lists() {
        return Permission::orderBy('id')->select('name', 'id')->pluck('name', 'id');
    }

    public function store($input) {
        return Permission::create($input->except('_token'));
    }

    public function update($input) {
      return Permission::findOrFail($input['id'])->update($input->except(['id']));
    }

    public function destroy($id) {
        return Permission::findOrFail($id)->delete();
    }

    public function datatables($input) {
        $query = Permission::select();
        $datatables = Datatables::of($query)
                ->addColumn('actions', function($permission) {
                    $actions = '';
                    $actions .= DtButtonHelper::getByType(route('permission.edit', ['permission' => $permission->id]), DtButtonType::EDIT);
                    $actions .= DtButtonHelper::getByType(route('permission.destroy', ['client' => $permission->id]), DtButtonType::DELETE);
                    return $actions;
                })->rawColumns(['actions']);
        return $datatables->make(true);
    }

}
