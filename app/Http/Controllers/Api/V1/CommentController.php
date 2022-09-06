<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\CommentLikeDislikeRequest;
use App\Http\Requests\Api\V1\CommentRequest;
use App\Resources\Api\V1\CommentShowResource;
use Facades\App\Models\Comment;
use Facades\App\Repositories\CommentRepository;

class CommentController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *       path="/api/v1/comments",
     *       summary="Get list comments",
     *       description="Endpoint to get list comments",
     *       tags={"Comments"},
     *       security={
     *           {"token": {}}
     *       },
     *       @OA\Parameter(
     *           name="post_id",
     *           in="query",
     *           description="Post ID"
     *       ),
     *       @OA\Parameter(
     *           name="id",
     *           in="query",
     *           description="ID"
     *       ),
     *       @OA\Response(
     *          response=200,
     *          description="Get list comment successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", example={}),
     *              @OA\Property(property="pagination", type="object", example={}),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Get list comment failed",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Get list comment failed"),
     *          )
     *      ),
     * )
     */
    public function index(CommentRequest $request)
    {
        checkPerm('api-comments-index');
        $data = CommentRepository::comments($request);
        if (isset($data['status']) && ! $data['status']) {
            $this->responseJson(false, __('Get list comment failed'));
        }

        return $this->responseJson(
            'pagination',
            __('Get list comment successfully'),
            $data,
            200,
            [$request->sortBy, $request->sort]
        );
    }

    /**
     * @OA\Post(
     *       path="/api/v1/comments",
     *       summary="Create comment",
     *       description="Endpoint to create comment",
     *       tags={"Comments"},
     *       security={
     *           {"token": {}}
     *       },
     *       @OA\RequestBody(
     *          required=true,
     *          description="Pass comment data",
     *          @OA\JsonContent(
     *              required={"comment"},
     *              @OA\Property(property="post_id", type="number", example=1),
     *              @OA\Property(property="parent_id", type="number", example=null),
     *              @OA\Property(property="comment", type="string", example="comment"),
     *          ),
     *       ),
     *       @OA\Response(
     *          response=200,
     *          description="Create comment successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Create comment successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Create comment failed",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Create comment failed"),
     *          )
     *      ),
     * )
     */
    public function store(CommentRequest $request)
    {
        checkPerm('api-comments-store');
        $data = $request->validated();
        $data = CommentRepository::addComment($request->post_id, $request->parent_id, $data);
        $comment = '';
        if ($data['status']) {
            $commentId = $data['data']['id'];
            $comment = Comment::find($commentId);
            $comment = new CommentShowResource($comment);
        }

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['status'] ? __('Create comment successfully') : __('Create comment failed'),
            $data['status'] ? $comment : '',
            $data['status'] ? 200 : 400,
        );
    }

    /**
     * @OA\Put(
     *       path="/api/v1/comments/{id}/like-dislike",
     *       summary="Like / dislike post",
     *       description="Endpoint to like / dislike post",
     *       tags={"Comments"},
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
    public function likeDislike($id, CommentLikeDislikeRequest $request)
    {
        checkPerm('api-likes-update');
        $data = CommentRepository::likeDislike($id, $request->value);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            '',
            $data['status'] ? 200 : 400,
        );
    }
}
