<?php

use App\Http\Controllers\Api\v1\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


