<?php

//首页
Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', 'App\Modules\Home\Controllers\HomePage@index');
