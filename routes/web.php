<?php

//首页
Route::get('/', function () {
    $file = file_get_contents(base_path() . '/web/custom/html/header.html');
    return $file;
});
