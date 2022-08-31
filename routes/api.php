<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.v1.')
->namespace('Api\\V1')
->prefix('v1')
->group(function () {
    // Auth
    Route::group(['prefix' => 'auth', 'name' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('register', 'AuthController@register')->name('register');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', 'AuthController@logout')->name('logout');
        });
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        // Profile
        Route::group(['prefix' => 'profile', 'name' => 'profile'], function () {
            Route::controller('ProfileController')->group(function () {
                Route::get('', 'index')->name('index');
                Route::put('', 'update')->name('update');
                Route::post('avatar', 'updateAvatar')->name('update.avatar');
            });
        });

        // Users
        Route::apiResource('users', 'UserController');

        // Posts
        Route::controller('PostController')
            ->prefix('posts')
            ->name('posts')
            ->group(function () {
                Route::put('{id}/like-dislike', 'likeDislike')->name('like-dislike');
            });
        Route::apiResource('posts', 'PostController');

        // Comments
        Route::controller('CommentController')
            ->prefix('comments')
            ->name('comments')
            ->group(function () {
                Route::put('{id}/like-dislike', 'likeDislike')->name('like-dislike');
            });
        Route::apiResource('comments', 'CommentController')->only(['index', 'store']);
    });
});
