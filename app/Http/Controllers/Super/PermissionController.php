<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Http\Requests\Super\PermissionRequest;
use App\Models\Permission;
use Facades\App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        checkPerm('super-permissions-index', true);

        return view('super.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        checkPerm('super-permissions-show', true);

        return view('super.permissions.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Super\PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        checkPerm('super-permissions-create', true);
        $data = $request->validated();
        try {
            PermissionRepository::addPermission($data, $data['role_name']);
            setAlert('success', 'Create permission successfully');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            setAlert('error', $th->getMessage());
        }

        return redirect()->route('super.permissions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        checkPerm('super-permissions-show', true);

        return view('super.permissions.form', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Super\PermissionRequest  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        checkPerm('super-permissions-edit', true);
        $data = $request->validated();
        try {
            PermissionRepository::updatePermission($permission, $data, $data['role_name']);
            setAlert('success', 'Update permission successfully');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            setAlert('error', $th->getMessage());
        }

        return redirect()->route('super.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        checkPerm('super-permissions-destroy', true);

        return $permission->delete();
    }

    /**
     * Fetch Request
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        checkPerm('super-permissions-index', true);

        return match ($request->mode) {
            'datatable' => (
                PermissionRepository::datatable($request)
            )
        };
    }
}
