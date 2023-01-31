<?php

namespace App\Http\Controllers;

use App\Cores\ApiResponse;
use App\Models\Post;
use App\Models\PostSummary;
use Facades\App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
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
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        $post = Post::with(['author', 'summary'])->findOrFail($post->id);

        return view('detail', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
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
        $data = PostRepository::likeDislike($request->id, $request->value ?? 0);

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
        $data = Post::findOrFail($request->id)->userLike(auth()->id());
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
        $data = PostSummary::wherePostId($request->id)->first();

        return $this->responseJson(
            $data ? 'success' : 'error',
            'Get data '.($data ? 'successfully' : 'failed'),
            $data,
            $data ? 200 : 404
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
            'datatable' => (
                PostRepository::landingData($request)
            ),
            'like-dislike' => (
                $this->likeDislike($request)
            ),
            'check-like' => (
                $this->checkLike($request)
            ),
            'get-summary' => (
                $this->getSummary($request)
            )
        };
    }
}
