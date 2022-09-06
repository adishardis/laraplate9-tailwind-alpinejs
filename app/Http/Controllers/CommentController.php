<?php

namespace App\Http\Controllers;

use App\Cores\ApiResponse;
use App\Models\Comment;
use App\Models\CommentSummary;
use Facades\App\Repositories\CommentRepository;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }

    /**
     * Like / Dislike
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    protected function likeDislike(Request $request)
    {
        checkPerm('likes-edit', true);
        $data = CommentRepository::likeDislike($request->id, $request->value ?? 0);

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            '',
            $data['status'] ? 200 : 400
        );
    }

    /**
     * Get like data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    protected function checkLike(Request $request)
    {
        checkPerm('likes-index', true);
        $data = Comment::findOrFail($request->id)->userLike(auth()->id());

        return $this->responseJson(
            $data ? 'success' : 'error',
            'Get data '.($data ? 'successfully' : 'failed'),
            $data,
            $data ? 200 : 404
        );
    }

    /**
     * Get summary data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    protected function getSummary(Request $request)
    {
        $data = CommentSummary::whereCommentId($request->id)->first();

        return $this->responseJson(
            $data ? 'success' : 'error',
            'Get data '.($data ? 'successfully' : 'failed'),
            $data,
            $data ? 200 : 404
        );
    }

    /**
     * Add comment
     *
     * @param  array|json  $request
     * @return Json
     */
    protected function addComment($request)
    {
        checkPerm('comments-create', true);
        checkApiMethod('create', $request, true);
        $data = CommentRepository::addComment(
            $request->post_id,
            $request->parent_id,
            ['comment' => $request->comment]
        );

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            $data['data'],
            $data['status'] ? 200 : 400
        );
    }

    /**
     * Update comment
     *
     * @param  array|json  $request
     * @return Json
     */
    protected function updateComment($request)
    {
        checkPerm('comments-edit', true);
        checkApiMethod('update', $request, true);
        $data = CommentRepository::updateComment(
            $request->post_id,
            ['comment' => $request->comment]
        );

        return $this->responseJson(
            $data['status'] ? 'success' : 'error',
            $data['message'],
            '',
            $data['status'] ? 200 : 400
        );
    }

    /**
     * Fetch Request
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        return match ($request->mode) {
            'post-comments' => (
                CommentRepository::comments($request)
            ),
            'like-dislike' => (
                $this->likeDislike($request)
            ),
            'add-comment' => (
                $this->addComment($request)
            ),
            'update-comment' => (
                $this->updateComment($request)
            ),
            'check-like' => (
                $this->checkLike($request)
            ),
            'get-summary' => (
                $this->getSummary($request)
            ),
        };
    }
}
