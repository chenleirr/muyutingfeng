<?php

use Illuminate\Http\Request;

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

Route::group(['namespace' => 'App\Modules\Home\Controllers', 'prefix' => 'article'], function () {
    Route::post('insert', 'HomeApi@insert')->middleware('auth');
    Route::get('get_by_id', 'HomeApi@getById');
    Route::get('get_list', 'HomeApi@getList');
});

Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
    Route::post('login', 'LoginController@login')->middleware('guest');
    Route::post('register', 'RegisterController@register');
    Route::get('logout', 'LoginController@logout');
});