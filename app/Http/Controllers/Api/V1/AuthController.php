<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Models\User;
use Facades\App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Post(
     *      path="/api/v1/auth/login",
     *      summary="Sign in",
     *      description="Login by email, password",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="email", example="super@laraplate.com"),
     *              @OA\Property(property="password", type="string", format="password", example="test123"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Login successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong credentials response",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={}),
     *          )
     *      )
     * )
     */
    public function login(User $user, LoginRequest $request)
    {
        $findUser = $user->firstWhere('email', $request->email);

        if (! $findUser) {
            return $this->responseJson('error', 'Unauthorized. Email not found', '', 401);
        }

        if (! Auth::attempt($request->validated())) {
            return $this->responseJson('error', 'Unauthorized.', '', 401);
        }

        $token = $findUser->createToken('authToken');

        return $this->responseJson(
            'success',
            __('Login successfully'),
            ['accessToken' => $token->plainTextToken]
        );
    }

    /**
     * @OA\Post(
     *      path="/api/v1/auth/register",
     *      summary="Sign up",
     *      description="Sign up by name, email and password",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="my name is ..."),
     *              @OA\Property(property="email", type="email", example="user1@laraplate.com"),
     *              @OA\Property(property="password", type="string", format="password", example="test123"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Register successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Register successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong credentials response",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={}),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Register failed",
     *      ),
     * )
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data = UserRepository::registerUser($data);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            '',
            $data['status'] ? 201 : 500
        );
    }

    /**
     * @OA\Post(
     *       path="/api/v1/auth/logout",
     *       summary="Log user out ",
     *       description="Endpoint to log current user out",
     *       tags={"Auth"},
     *       security={
     *           {"token": {}}
     *       },
     *       @OA\Response(
     *           response=200,
     *           description="Logout successfully",
     *           @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Logout successfully"),
     *           )
     *       ),
     *       @OA\Response(
     *           response=400,
     *           description="Logout failed",
     *           @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Logout failed"),
     *           )
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Unauthorized",
     *           @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthorized"),
     *           )
     *       ),
     * )
     */
    public function logout()
    {
        $user = auth()->user();
        if (! $user) {
            return $this->responseJson('error', 'Unauthorized.', '', 401);
        }

        $revoke = $user->currentAccessToken()->delete();

        /**Use below code if you want to log current user out in all devices */
        // $revoke = auth()->user()->tokens()->delete();
        return $this->responseJson(
            $revoke ? 'success' : 'error',
            $revoke ? __('Logout successfully') : __('Logout failed')
        );
    }
}
