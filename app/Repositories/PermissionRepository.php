<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Resources\Super\PermissionResource;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Permissions
     *
     * @return Json|array
     */
    public function datatable(Request $request)
    {
        try {
            $query = Permission::with(['roles']);
            $filters = [
                [
                    'field' => 'id',
                    'value' => $request->id,
                ],
                [
                    'field' => 'name',
                    'value' => $request->name,
                    'query' => 'like',
                ],
                [
                    'field' => 'display_name',
                    'value' => $request->display_name,
                    'query' => 'like',
                ],
                [
                    'field' => 'name',
                    'value' => $request->role,
                    'query' => 'relation',
                    'relation' => 'roles',
                ],
            ];
            $request->sortBy = $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);

            return PermissionResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Failed get permissions'));
        }
    }

    /**
     * Add permission to role user
     *
     * @param  array  $data
     * @param  array  $roleNames
     * @return App/Models/Permission
     */
    public function addPermission($data, $roleNames = ['super'])
    {
        $permission = Permission::create($data);
        $roles = Role::whereIn('name', $roleNames)->get();
        foreach ($roles as $role) {
            $role->attachPermission($permission);
        }

        return $permission;
    }

    /**
     * Update permission role user
     *
     * @param App/Models/Permission $permission
     * @param  array  $data
     * @param  array  $roleNames
     * @return App/Models/Permission
     */
    public function updatePermission($permission, $data, $roleNames = [])
    {
        $permission->update($data);
        PermissionRole::where('permission_id', $permission->id)->delete();
        $roles = Role::whereIn('name', $roleNames)->get();
        foreach ($roles as $role) {
            $role->attachPermission($permission);
        }

        return $permission;
    }
}
