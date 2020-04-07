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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('authorize', 'AuthorizationController@authorization');

Route::middleware('vkauth')->group(function () {

    Route::prefix('account')->group(function () {
        Route::post('register', 'AuthorizationController@register');
        Route::post('description', 'UserController@updateDescription');
        Route::post('photo', 'UserController@updatePhoto');
    });
});

Route::prefix('users')->group(function () {
    Route::get('search', 'UserController@search');
});

Route::prefix('tags')->group(function () {
    Route::get('tags', 'TagsController@index');
});
