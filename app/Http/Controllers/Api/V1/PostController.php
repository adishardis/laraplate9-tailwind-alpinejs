<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\PostLikeDislikeRequest;
use App\Http\Requests\Api\V1\PostRequest;
use App\Resources\Api\V1\PostShowResource;
use Facades\App\Models\Post;
use Facades\App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponse;

    /**
    * @OA\Get(
    *       path="/api/v1/posts",
    *       summary="Get list posts",
    *       description="Endpoint to get list posts",
    *       tags={"Posts"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\Parameter(
    *           name="id",
    *           in="query",
    *           description="ID"
    *       ),
    *       @OA\Parameter(
    *           name="title",
    *           in="query",
    *           description="Title"
    *       ),
    *       @OA\Parameter(
    *           name="description",
    *           in="query",
    *           description="Description"
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
    *          description="Get list post successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="data", type="object", example={}),
    *              @OA\Property(property="pagination", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Get list post failed",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Get list post failed"),
    *          )
    *      ),
    * )
    */
    public function index(Request $request)
    {
        checkPerm('api-posts-index');
        $data = PostRepository::landingData($request);
        if (isset($data['status']) && !$data['status']) {
            $this->responseJson(false, __("Get list post failed"));
        }
        return $this->responseJson(
            'pagination',
            __('Get list post successfully'),
            $data,
            200,
            [$request->sortBy, $request->sort]
        );
    }

    /**
    * @OA\Post(
    *       path="/api/v1/posts",
    *       summary="Create post",
    *       description="Endpoint to create post",
    *       tags={"Posts"},
    *       security={
    *           {"token": {}}
    *       },
    *       @OA\RequestBody(
    *          required=true,
    *          description="Pass post data",
    *          @OA\JsonContent(
    *              required={"title", "description", "type"},
    *              @OA\Property(property="title", type="string", example="Title"),
    *              @OA\Property(property="description", type="string", example="Description"),
    *              @OA\Property(property="status", type="string", example="published"),
    *          ),
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Create post successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Create post successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Create post failed",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Create post failed"),
    *          )
    *      ),
    * )
    */
    public function store(PostRequest $request)
    {
        checkPerm('api-posts-store');
        $data = $request->validated();
        $data = PostRepository::createPost($data);
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            $data['status'] ? new PostShowResource($data['data']) : '',
            $data['status'] ? 200 : 400,
        );
    }

    /**
    * @OA\Get(
    *       path="/api/v1/posts/{id}",
    *       summary="Get detail post",
    *       description="Endpoint to get detail post",
    *       tags={"Posts"},
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
    *          description="Get post successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Get post successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Post not found",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Post not found"),
    *          )
    *      ),
    * )
    */
    public function show($id)
    {
        checkPerm('api-posts-show');
        $post = Post::find($id);
        return $this->responseJson(
            $post ? 'success' : 'error',
            $post ? __('Get post successfully') : __('Post Not found'),
            $post ? new PostShowResource($post) : '',
            $post ? 200 : 404,
        );
    }

    /**
    * @OA\Put(
    *       path="/api/v1/posts/{id}",
    *       summary="Update post",
    *       description="Endpoint to update post",
    *       tags={"Posts"},
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
    *          description="Pass post data",
    *          @OA\JsonContent(
    *              required={"title", "description", "type"},
    *              @OA\Property(property="title", type="string", example="Title"),
    *              @OA\Property(property="description", type="string", example="Description"),
    *              @OA\Property(property="status", type="string", example="published"),
    *          ),
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Update post successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Update post successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Update post failed",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Update post failed"),
    *          )
    *      ),
    * )
    */
    public function update($id, PostRequest $request)
    {
        checkPerm('api-posts-update');
        $post = Post::findOrFail($id);
        $data = $request->validated();
        $data = PostRepository::updatePost($post, $data);
        $post = $data['data'];
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            $data['status'] ? new PostShowResource($data['data']) : '',
            $data['status'] ? 200 : 400,
        );
    }

    /**
    * @OA\Delete(
    *       path="/api/v1/posts/{id}",
    *       summary="Delete post",
    *       description="Endpoint to delete post",
    *       tags={"Posts"},
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
    *          description="Delete post successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Delete post successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Post not found",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Post not found"),
    *          )
    *      ),
    * )
    */
    public function destroy($id)
    {
        checkPerm('api-posts-destroy');
        $post = Post::find($id);
        return $this->responseJson(
            $post ? 'success' : 'error',
            $post ? __('Delete post successfully') : __('Post Not found'),
            $post ? $post->delete() : '',
            $post ? 200 : 404,
        );
    }

    /**
    * @OA\Put(
    *       path="/api/v1/posts/{id}/like-dislike",
    *       summary="Like / dislike post",
    *       description="Endpoint to like / dislike post",
    *       tags={"Posts"},
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
    *          description="Pass post data",
    *          @OA\JsonContent(
    *              required={"value"},
    *              @OA\Property(property="value", type="number", example=1),
    *          ),
    *       ),
    *       @OA\Response(
    *          response=200,
    *          description="Like / dislike post successfully",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=true),
    *              @OA\Property(property="message", type="string", example="Like / dislike post successfully"),
    *              @OA\Property(property="data", type="object", example={}),
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Like / dislike post failed",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="boolean", example=false),
    *              @OA\Property(property="message", type="string", example="Like / dislike post failed"),
    *          )
    *      ),
    * )
    */
    public function likeDislike($id, PostLikeDislikeRequest $request)
    {
        checkPerm('api-likes-update');
        $data = PostRepository::likeDislike($id, $request->value);
        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            '',
            $data['status'] ? 200 : 400,
        );
    }
}
