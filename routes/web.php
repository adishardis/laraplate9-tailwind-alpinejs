<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth_verified']], function () {
    Route::get('/home', 'DashboardController@index')->name('dashboard');

    Route::group(['prefix' => 'super', 'middleware' => ['is_super'], 'namespace' => 'Super'], function () {
        /** Fetch Router */
        Route::any('fetch/{page}', function ($page) {
            return App::call('App\Http\Controllers\Super\\'.Str::singular(Str::studly($page)).'Controller@fetch');
        });

        /** Dashboard */
        Route::get('/dashboard', 'DashboardController@index')->name('super.dashboard');

        /** Users */
        Route::resource('users', 'UserController', ['as' => 'super']);

        /** Permissions */
        Route::resource('permissions', 'PermissionController', ['as' => 'super']);

        /** Posts */
        Route::resource('posts', 'PostController', ['as' => 'super']);

        /** Profile */
        Route::controller('ProfileController')->prefix('profile')->group(function () {
            Route::get('', 'index')->name('super.profile.index');
            Route::put('', 'update')->name('super.profile.update');
        });

        /** Notifications */
        Route::get('/notifications', 'NotificationController@index')->name('super.notifications.index');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['is_admin'], 'namespace' => 'Admin'], function () {
        /** Fetch Router */
        Route::any('fetch/{page}', function ($page) {
            return App::call('App\Http\Controllers\Admin\\'.Str::singular(Str::studly($page)).'Controller@fetch');
        });

        /** Dashboard */
        Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');

        /** Posts */
        Route::resource('posts', 'PostController', ['as' => 'admin']);

        /** Profile */
        Route::controller('ProfileController')->prefix('profile')->group(function () {
            Route::get('', 'index')->name('admin.profile.index');
            Route::put('', 'update')->name('admin.profile.update');
        });

        /** Notifications */
        Route::get('/notifications', 'NotificationController@index')->name('admin.notifications.index');
    });

    Route::group(['middleware' => ['is_user'], 'namespace' => 'User'], function () {
        /** Fetch Router */
        Route::any('/user/fetch/{page}', function ($page) {
            return App::call('App\Http\Controllers\User\\'.Str::singular(Str::studly($page)).'Controller@fetch');
        });

        /** Dashboard */
        Route::get('/dashboard', 'DashboardController@index')->name('user.dashboard');

        /**Profile */
        Route::controller('ProfileController')->prefix('profile')->group(function () {
            Route::get('', 'index')->name('user.profile.index');
            Route::put('', 'update')->name('user.profile.update');
        });

        /** Notifications */
        Route::get('/notifications', 'NotificationController@index')->name('user.notifications.index');
    });
});

/** Fetch Router */
Route::any('fetch/{page}', function ($page) {
    return App::call('App\Http\Controllers\\'.Str::singular(Str::studly($page)).'Controller@fetch');
});

// Post
Route::get('/posts/{post:slug}', 'PostController@show')->name('post.show');

// Login / Register Oauth
Route::group(['namespace' => 'Auth'], function () {
    Route::prefix('login')->group(function () {
        Route::get(
            '{socialite}',
            'OauthController@redirectSocialite'
        )->whereIn('socialite', ['github', 'google', 'facebook']);
        Route::get(
            '{socialite}/callback',
            'OauthController@handleCallback'
        )->whereIn('socialite', ['github', 'google', 'facebook']);
    });
});

require __DIR__.'/auth.php';
