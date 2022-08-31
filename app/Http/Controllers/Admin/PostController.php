<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Facades\App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_author', ['only' => ['edit', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        checkPerm('admin-posts-index', true);
        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        checkPerm('admin-posts-show', true);
        return view('admin.posts.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        checkPerm('admin-posts-create', true);
        $data = $request->validated();
        $data = PostRepository::createPost($data);
        setAlert($data['status'] ? 'success' : 'error', $data['message']);
        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        checkPerm('admin-posts-show', true);
        return view('admin.posts.form', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\admin\PostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        checkPerm('admin-posts-edit', true);
        $data = $request->validated();
        $data = $request->validated();
        $data = PostRepository::updatePost($post, $data);
        setAlert($data['status'] ? 'success' : 'error', $data['message']);
        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        checkPerm('admin-posts-destroy', true);
        return $post->delete();
    }

    /**
     * Fetch Request
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        checkPerm('admin-posts-index', true);
        return match ($request->mode) {
            'datatable' => (
                PostRepository::datatable($request)
            ),
            'post-status' => (
                PostStatus::values()
            )
        };
    }
}
