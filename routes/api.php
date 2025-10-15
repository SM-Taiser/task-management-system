<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
         
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('tasks', TaskController::class);
});
