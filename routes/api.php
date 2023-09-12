<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;

use App\Http\Controllers\backend\user\UserController;
use App\Http\Controllers\backend\StudentClassController;
use App\Http\Controllers\backend\StudentYearController;
use App\Http\Controllers\backend\StudentGroupController;
use App\Http\Controllers\backend\StudentShiftController;
use App\Http\Controllers\backend\FeeCategoryController;
use App\Http\Controllers\backend\FeeCategoryAmountController;
use App\Http\Controllers\backend\ExamTypeController;
use App\Http\Controllers\backend\SchoolSubjectController;
use App\Http\Controllers\backend\AssignSubjectController;
use App\Http\Controllers\backend\DesignationController;
use App\Http\Controllers\backend\student\StudentRegController;
use App\Http\Controllers\backend\employee\EmployeeRegController;
use App\Http\Controllers\backend\employee\EmpSalaryController;
use App\Http\Controllers\backend\student\StudentRollController;
use App\Http\Controllers\backend\student\RegistrationFeeController;



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
        Route::get('/show/{id}',[StudentClassController::class,'show']);
        Route::post('/store',[StudentClassController::class,'store']);
        Route::post('/update',[StudentClassController::class,'update']);
        Route::get('/delete/{id}',[StudentClassController::class,'destroy']);
    });

    Route::prefix('year')->controller(StudentYearController::class)->group(function (){
        Route::get('/index',[StudentYearController::class,'index']);
        Route::get('/show/{id}',[StudentYearController::class,'show']);
        Route::post('/store',[StudentYearController::class,'store']);
        Route::post('/update',[StudentYearController::class,'update']);
        Route::get('/delete/{id}',[StudentYearController::class,'destroy']);
    });

    Route::prefix('group')->controller(StudentGroupController::class)->group(function (){
       Route::get('/index','index');
       Route::get('/show/{id}','show');
       Route::post('/store','store');
       Route::post('/update','update');
       Route::get('/delete/{id}','destroy');
    });

    Route::prefix('shift')->controller(StudentShiftController::class)->group(function (){
       Route::get('/index','index');
       Route::get('/show/{id}','show');
       Route::post('/store','store');
       Route::post('/update','update');
       Route::get('/delete/{id}','destroy');
    });

    Route::prefix('fee-category')->controller(FeeCategoryController::class)->group(function (){
       Route::get('/index','index');
       Route::get('/show/{id}','show');
       Route::post('/store','store');
       Route::post('/update','update');
       Route::get('/delete/{id}','destroy');
    });

    Route::prefix('fee-category-amount')->controller(FeeCategoryAmountController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/show/{category_id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
        Route::get('/delete/{id}','destroy');
    });

    Route::prefix('exam-type')->controller(ExamTypeController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/show/{id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
        Route::get('/delete/{id}','destroy');
    });

    Route::prefix('school-subject')->controller(SchoolSubjectController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/show/{id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
        Route::get('/delete/{id}','destroy');
    });

    Route::prefix('assign-subject')->controller(AssignSubjectController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/show/{id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
        Route::get('/delete/{id}','destroy');
    });

    Route::prefix('designation')->controller(DesignationController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/show/{id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
        Route::get('/delete/{id}','destroy');
    });

    Route::prefix('student-reg')->controller(StudentRegController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/show/{student_id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
        Route::get('/delete/{id}','destroy');
        Route::post('/search/','search');
        Route::post('/promotion/','promotion');
        Route::get('/student-details/{student_id}','studentDetail');

    });

    Route::prefix('student-roll-generate')->controller(StudentRollController::class)->group(function (){
        Route::post('/search','search');
        Route::post('/update','update');
    });
    Route::prefix('registration-fee')->controller(RegistrationFeeController::class)->group(function (){
        Route::post('/search','search');
        Route::post('/generate','generate');
    });

    Route::prefix('employee-reg')->controller(EmployeeRegController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/index/{employee_id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
    });

    Route::prefix('employee-salary')->controller(EmpSalaryController::class)->group(function (){
        Route::get('/index','index');
        Route::get('/index/{employee_id}','show');
        Route::post('/store','store');
        Route::post('/update','update');
    });

   Route::apiResource('user',UserController::class);


});
