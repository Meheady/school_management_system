<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;

use App\Http\Controllers\backend\user\UserController;
use App\Http\Controllers\backend\StudentClassController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login',[AuthController::class,'login']);

Route::post('/refresh', [AuthController::class,'refresh'])->middleware('jwtrefresh:api');

Route::middleware('api.auth:api')->group(function (){
    Route::post('/logout',[AuthController::class,'logout']);


    Route::prefix('class')->controller(StudentClassController::class)->group(function (){
        Route::get('/index',[StudentClassController::class,'index']);
        Route::get('/show',[StudentClassController::class,'show']);
        Route::post('/store',[StudentClassController::class,'store']);
        Route::post('/update',[StudentClassController::class,'update']);
        Route::post('/delete',[StudentClassController::class,'destroy']);
    });

   Route::apiResource('user',UserController::class);


});
