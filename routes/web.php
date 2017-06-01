<?php

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

Route::get('/', function () {
    $file = file_get_contents(base_path() . '/web/custom/html/header.html');

//    return response($file);
    return $file;
    redirect('/home');
});

Route::group(['namespace' => 'Home'], function () {

});
