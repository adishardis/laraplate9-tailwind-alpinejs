<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Resources\Super\UserResource;
use App\Traits\DatatableTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Users
     *
     * @return Json|Array
     */
    public function datatable(Request $request)
    {
        try {
            $query = User::with(['roles']);
            $filters = [
                [
                    'field' => 'id',
                    'value' => $request->id,
                ],
                [
                    'field' => 'email',
                    'value' => $request->email,
                    'query' => 'like',
                ],
                [
                    'field' => 'name',
                    'value' => $request->name,
                    'query' => 'like',
                ],
                [
                    'field' => 'name',
                    'value' => $request->role,
                    'query' => 'relation',
                    'relation' => 'roles',
                ],
            ];
            $request->sortBy =  $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);
            return UserResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Failed get users'));
        }
    }

    /**
     * Create / Register User
     *
     * @param array $data
     * @param array $roleNames
     * @return array
     */
    public function registerUser($data, $roleNames = ['user'])
    {
        \DB::beginTransaction();
        try {
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
            $roles = Role::whereIn('name', $roleNames)->pluck('id')->toArray();
            $user->attachRoles($roles);

            event(new Registered($user));

            \DB::commit();
            return $this->setResponse(true, __('Register user successfully'), $user);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);
            return $this->setResponse(false, __('Register user user'));
        }
    }

    /**
     * Update Register User
     *
     * @param App/Models/User $user
     * @param array $data
     * @param array $roleNames
     * @return array
     */
    public function updateUser($user, $data, $roleNames = [])
    {
        \DB::beginTransaction();
        try {
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            $user->update($data);
            if (!empty($roleNames)) {
                $user->roles()->detach();
                $roles = Role::whereIn('name', $roleNames)->pluck('id')->toArray();
                $user->attachRoles($roles);
            }

            \DB::commit();
            return $this->setResponse(true, __('Update user successfully'), $user);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);
            return $this->setResponse(false, __('Update user user'));
        }
    }
}
