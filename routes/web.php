<?php

//首页
Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', 'App\Modules\Home\Controllers\HomePage@index');
Route::get('/article/detail', 'App\Modules\Article\Controllers\ArticlePage@index');
Route::get('/article/edit', 'App\Modules\Article\Controllers\ArticlePage@edit')->middleware('auth');

Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
    Route::get('login', 'AuthPage@login')->middleware('guest');
    Route::get('register', 'AuthPage@register');
});