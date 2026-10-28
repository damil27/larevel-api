<?php

use App\Http\Controllers\Api\v1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum', 'throttle:api') ->group(function () {
    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) 
    {
    return $request->user();
    });
    Route::prefix('v1/')->group( function ()
    {
    Route::apiResource('posts', PostController::class);
});
});




require __DIR__.'/auth.php';