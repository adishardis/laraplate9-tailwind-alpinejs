<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\UserRequest;
use App\Resources\Api\V1\UserResource;
use Facades\App\Models\User;
use Facades\App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    /**
    * @OA\Get(
    *       path="/api/v1/users",
    *       summary="Get list users",
    *       description="Endpoint to get list users",
    *       tags={"Users"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\Parameter(
    *           name="id",
    *           in="query",
    *           description="ID"
    *       ),
    *       @OA\Parameter(
    *           name="email",
    *           in="query",
    *           description="Email"
    *       ),
    *       @OA\Parameter(
    *           name="name",
    *           in="query",
    *           description="Name"
    *       ),
    *       @OA\Parameter(
    *           name="role",
    *           in="query",
    *           description="Role (super,admin,user)"
    *       ),
    *       @OA\Parameter(
    *           name="sort",
    *           in="query",
    *           description="1 for Ascending -1 for Descending"
    *       ),
    *       @OA\Parameter(
    *           name="sortBy",
    *           in="query",
    *           description="Field to sort"
    *       ),
    *       @OA\Parameter(
    *           name="limit",
    *           in="query",
    *           description="Limit (Default 10)"
    *       ),
    *       @OA\Parameter(
    *           name="page",
    *           in="query",
    *           description="Num Of Page"
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Get list user successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="data", type="object", example={}),
    *              @OA\Property(property="pagination", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Get list user failed",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Get list user failed"),
    *          )
    *      ),
    * )
    */
    public function index(Request $request)
    {
        checkPerm('api-users-index');
        $data = UserRepository::datatable($request);
        if (isset($data['status']) && !$data['status']) {
            $this->responseJson(false, __("Get list user failed"));
        }
        return $this->responseJson(
            'pagination',
            __('Get list user successfully'),
            $data,
            200,
            [$request->sortBy, $request->sort]
        );
    }

    /**
    * @OA\Post(
    *       path="/api/v1/users",
    *       summary="Create user",
    *       description="Endpoint to create user",
    *       tags={"Users"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\RequestBody(
    *          required=true,
    *          description="Pass user data",
    *          @OA\JsonContent(
    *              required={"name", "email", "password", "role_name"},
    *              @OA\Property(property="username", type="string", example="Nobita"),
    *              @OA\Property(property="name", type="string", example="Doraemon"),
    *              @OA\Property(property="email", type="email", format="email", example="user1@gmail.com"),
    *              @OA\Property(property="password", type="string", format="password", example="test123"),
    *              @OA\Property(
    *                   property="role_name",
    *                   type="array",
    *                   @OA\Items(
    *                       example="user",
    *                   ),
    *                   description="Roles"
    *              ),
    *          ),
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Create user successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Create user successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Create user failed",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Create user failed"),
    *          )
    *      ),
    * )
    */
    public function store(UserRequest $request)
    {
        checkPerm('api-users-store');
        $data = $request->validated();
        $data['email_verified_at'] = now();
        $data = UserRepository::registerUser($data, $data['role_name']);
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['status'] ? __('Create user successfully') : __('Create user failed'),
            $data['status'] ? new UserResource($data['data']) : '',
            $data['status'] ? 200 : 400,
        );
    }

    /**
    * @OA\Get(
    *       path="/api/v1/users/{id}",
    *       summary="Get detail user",
    *       description="Endpoint to get detail user",
    *       tags={"Users"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\Parameter(
    *           name="id",
    *           in="path",
    *           description="ID"
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Get user successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Get user successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="User not found",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="User not found"),
    *          )
    *      ),
    * )
    */
    public function show($id)
    {
        checkPerm('api-users-show');
        $user = User::find($id);
        return $this->responseJson(
            $user ? 'success' : 'error',
            $user ? __('Get user successfully') : __('User Not found'),
            $user ? new UserResource($user) : '',
            $user ? 200 : 404,
        );
    }

    /**
    * @OA\Put(
    *       path="/api/v1/users/{id}",
    *       summary="Update user",
    *       description="Endpoint to update user",
    *       tags={"Users"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\Parameter(
    *           name="id",
    *           in="path",
    *           description="ID"
    *       ),
    *       @OA\RequestBody(
    *          required=true,
    *          description="Pass user data",
    *          @OA\JsonContent(
    *              required={"name", "email", "role_name"},
    *              @OA\Property(property="username", type="string", example="Nobita"),
    *              @OA\Property(property="name", type="string", example="Doraemon"),
    *              @OA\Property(property="email", type="email", format="email", example="user1@gmail.com"),
    *              @OA\Property(property="password", type="string", format="password", example=""),
    *              @OA\Property(
    *                   property="role_name",
    *                   type="array",
    *                   @OA\Items(
    *                       example="user",
    *                   ),
    *                   description="Roles"
    *              ),
    *          ),
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Update user successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Update user successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Update user failed",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Update user failed"),
    *          )
    *      ),
    * )
    */
    public function update($id, UserRequest $request)
    {
        checkPerm('api-users-update');
        $user = User::find($id);
        if ($user) {
            $data = $request->validated();
            $data = UserRepository::updateUser($user, $data, $data['role_name']);
            $user = $data['data'];
        }
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['status'] ? __('Update user successfully') : __('Update user failed'),
            $data['status'] ? new UserResource($data['data']) : '',
            $data['status'] ? 200 : 400,
        );
    }

    /**
    * @OA\Delete(
    *       path="/api/v1/users/{id}",
    *       summary="Delete user",
    *       description="Endpoint to delete user",
    *       tags={"Users"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\Parameter(
    *           name="id",
    *           in="path",
    *           description="ID"
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Delete user successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Delete user successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="User not found",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="User not found"),
    *          )
    *      ),
    * )
    */
    public function destroy($id)
    {
        checkPerm('api-users-destroy');
        $user = User::find($id);
        return $this->responseJson(
            $user ? 'success' : 'error',
            $user ? __('Delete user successfully') : __('User Not found'),
            $user ? $user->delete() : '',
            $user ? 200 : 404,
        );
    }
}
