<?php

namespace App\Repositories;

use App\Models\Role;
use App\Resources\RoleResource;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Roles
     *
     * @return Json|Array
     */
    public function datatable(Request $request)
    {
        try {
            $query = Role::query();
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
            ];
            $data = $this->filterDatatable($query, $filters, $request);
            return RoleResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Failed get roles'));
        }
    }
}
