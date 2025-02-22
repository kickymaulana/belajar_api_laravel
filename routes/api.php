<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/


Route::post('/user', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('/user/current', [UserController::class, 'get']);
    Route::patch('/user/current', [UserController::class, 'update']);
    Route::delete('/user/logout', [UserController::class, 'logout']);

    Route::post('/contacts', [ContactController::class, 'create']);
    Route::get('/contacts', [ContactController::class, 'search']);
    Route::get('/contacts/{id}', [ContactController::class, 'get'])->where('id', '[0-9]+');
    Route::put('/contacts/{id}', [ContactController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/contacts/{id}', [ContactController::class, 'delete'])->where('id', '[0-9]+');
});
