<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Http\Requests\Super\UserRequest;
use App\Models\User;
use Facades\App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        checkPerm('super-users-index', true);

        return view('super.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        checkPerm('super-users-show', true);

        return view('super.users.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Super\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        checkPerm('super-users-create', true);
        $data = $request->validated();
        try {
            $data['email_verified_at'] = now();
            UserRepository::registerUser($data, $data['role_name']);
            setAlert('success', 'Create user successfully');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            setAlert('error', $th->getMessage());
        }

        return redirect()->route('super.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        checkPerm('super-users-show', true);

        return view('super.users.form', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Super\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        checkPerm('super-users-edit', true);
        $data = $request->validated();
        try {
            UserRepository::updateUser($user, $data, $data['role_name']);
            setAlert('success', 'Update user successfully');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            setAlert('error', $th->getMessage());
        }

        return redirect()->route('super.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        checkPerm('super-users-destroy', true);

        return $user->delete();
    }

    /**
     * Fetch Request
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        checkPerm('super-users-index', true);

        return match ($request->mode) {
            'datatable' => (
                UserRepository::datatable($request)
            )
        };
    }
}
