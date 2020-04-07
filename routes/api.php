<?php

use Illuminate\Support\Facades\Route;

Route::get('authorize', 'AuthorizationController@authorization');

Route::middleware('vkauth')->group(function () {

    Route::prefix('account')->group(function () {
        Route::post('register', 'AuthorizationController@register');
        Route::post('description', 'UserController@updateDescription');
        Route::post('photo', 'UserController@updatePhoto');
        Route::post('update', 'UserController@updateInfo');
    });
});

Route::prefix('users')->group(function () {
    Route::get('search', 'UserController@search');
});

Route::prefix('tags')->group(function () {
    Route::get('tags', 'TagsController@index');
});
