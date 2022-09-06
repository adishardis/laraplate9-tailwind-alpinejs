<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\ProfileAvatarRequest;
use App\Http\Requests\Api\V1\ProfileRequest;
use App\Resources\Api\V1\UserResource;
use Facades\App\Repositories\ProfileRepository;

class ProfileController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *       path="/api/v1/profile",
     *       summary="Get current user's profile",
     *       description="Endpoint to get logged in user",
     *       tags={"Profile"},
     *       security={
     *           {"token": {}}
     *       },
     *       @OA\Response(
     *          response=200,
     *          description="Get profile successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Get profile successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User Not Found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="User Not Found"),
     *          )
     *      ),
     * )
     */
    public function index()
    {
        $user = auth()->user();

        return $this->responseJson(
            $user ? 'success' : 'error',
            $user ? __('Get profile successfully') : __('User not found'),
            $user ? new UserResource($user) : '',
            $user ? 200 : 404
        );
    }

    /**
     * @OA\Put(
     *       path="/api/v1/profile",
     *       summary="Update current user's profile",
     *       description="Endpoint to update logged in user",
     *       tags={"Profile"},
     *       security={
     *           {"token": {}}
     *       },
     *       @OA\RequestBody(
     *          required=true,
     *          description="Pass user data",
     *          @OA\JsonContent(
     *              required={"name", "email"},
     *              @OA\Property(property="name", type="string", example="Doraemon"),
     *              @OA\Property(property="email", type="email", format="email", example="user1@gmail.com"),
     *              @OA\Property(property="password", type="string", format="password", example=""),
     *              @OA\Property(property="setting[is_notif_alert]", type="boolean", example=true),
     *          ),
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Update profile successfully",
     *           @OA\JsonContent(
     *               @OA\Property(property="status", type="boolean", example=true),
     *               @OA\Property(property="message", type="string", example="Update profile successfully"),
     *               @OA\Property(property="data", type="object", example={}),
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Update profile failed",
     *           @OA\JsonContent(
     *               @OA\Property(property="status", type="boolean", example=false),
     *               @OA\Property(property="message", type="string", example="Update profile failed"),
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Wrong credentials response",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="The given data was invalid."),
     *               @OA\Property(property="errors", type="object", example={}),
     *           )
     *       )
     * )
     */
    public function update(ProfileRequest $request)
    {
        $data = $request->validated();
        $data = ProfileRepository::updateProfile($data);

        return $this->responseJson(
            $data['status'] ? 'success' : 'warning',
            $data['message'],
            '',
            $data['status'] ? 200 : 400
        );
    }

    // FIXME : refactor to put/patch
    /**
     * @OA\Post(
     *       path="/api/v1/profile/avatar",
     *       summary="Update current user's avatar",
     *       description="Endpoint to update avatar logged in user",
     *       tags={"Profile"},
     *       security={
     *           {"token": {}}
     *       },
     *       @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(
     *                   required={"file"},
     *                   @OA\Property(
     *                       property="file",
     *                       type="file",
     *                   ),
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Update avatar successfully",
     *           @OA\JsonContent(
     *               @OA\Property(property="status", type="boolean", example=true),
     *               @OA\Property(property="message", type="string", example="Update avatar successfully"),
     *               @OA\Property(property="data", type="object", example={}),
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Update avatar failed",
     *           @OA\JsonContent(
     *               @OA\Property(property="status", type="boolean", example=false),
     *               @OA\Property(property="message", type="string", example="Update avatar failed"),
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Wrong credentials response",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="The given data was invalid."),
     *               @OA\Property(property="errors", type="object", example={}),
     *           )
     *       )
     * )
     */
    public function updateAvatar(ProfileAvatarRequest $request)
    {
        $data = ProfileRepository::updateAvatar($request->file);

        return $this->responseJson(
            $data['status'] ? 'success' : 'warning',
            $data['message'],
            '',
            $data['status'] ? 200 : 400
        );
    }
}
