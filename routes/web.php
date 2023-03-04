<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\Servicecontroller;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\contactController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PhotoController;


Route::get('/',[HomeController::class,'HomeIndex'])->middleware('LoginCheck');
Route::get('/visitor',[VisitorController::class,'VisitorIndex'])->middleware('LoginCheck');

//Admin panel Service Management
Route::get('/service',[Servicecontroller::class,'ServiceIndex'])->middleware('LoginCheck');
Route::get('/getserviceData',[Servicecontroller::class,'getServiceData'])->middleware('LoginCheck');
Route::post('/serviceDetails',[Servicecontroller::class,'getServiceDetails'])->middleware('LoginCheck');
Route::post('/serviceDelete',[Servicecontroller::class,'ServiceDelete'])->middleware('LoginCheck');
Route::post('/serviceUpdate',[Servicecontroller::class,'ServiceUpdate'])->middleware('LoginCheck');
Route::post('/ServiceAdd',[Servicecontroller::class,'ServiceAdd'])->middleware('LoginCheck');


//Admin panel Courses Management
Route::get('/courses',[CoursesController::class,'CoursesIndex'])->middleware('LoginCheck');
Route::get('/getCoursesData',[CoursesController::class,'getCoursesData'])->middleware('LoginCheck');
Route::post('/CoursesDetails',[CoursesController::class,'getCoursesDetails'])->middleware('LoginCheck');
Route::post('/CoursesDelete',[CoursesController::class,'CoursesDelete'])->middleware('LoginCheck');
Route::post('/CoursesUpdate',[CoursesController::class,'CoursesUpdate'])->middleware('LoginCheck');
Route::post('/CoursesAdd',[CoursesController::class,'CoursesAdd'])->middleware('LoginCheck');


//Admin panel Project Management
Route::get('/Projects',[ProjectsController::class,'ProjectsIndex'])->middleware('LoginCheck');
Route::get('/getProjectsData',[ProjectsController::class,'getProjectsData'])->middleware('LoginCheck');
Route::post('/ProjectsDetails',[ProjectsController::class,'getProjectsDetails'])->middleware('LoginCheck');
Route::post('/ProjectsDelete',[ProjectsController::class,'ProjectsDelete'])->middleware('LoginCheck');
Route::post('/ProjectsUpdate',[ProjectsController::class,'ProjectsUpdate'])->middleware('LoginCheck');
Route::post('/ProjectsAdd',[ProjectsController::class,'ProjectsAdd'])->middleware('LoginCheck');

//Admin panel Contact Management
Route::get('/Contact',[contactController::class,'ContactIndex'])->middleware('LoginCheck');
Route::get('/getContactData',[contactController::class,'getContactData'])->middleware('LoginCheck');
Route::post('/ContactDelete',[contactController::class,'ContactDelete'])->middleware('LoginCheck');

//Admin panel Review Management
Route::get('/Review',[ReviewController::class,'ReviewIndex'])->middleware('LoginCheck');
Route::get('/getReviewData',[ReviewController::class,'getReviewData'])->middleware('LoginCheck');
Route::post('/ReviewDelete',[ReviewController::class,'ReviewDelete'])->middleware('LoginCheck');
Route::post('/getReviewDetails',[ReviewController::class,'getReviewDetails'])->middleware('LoginCheck');
Route::post('/ReviewUpdate',[ReviewController::class,'ReviewUpdate'])->middleware('LoginCheck');
Route::post('/ReviewAdd',[ReviewController::class,'ReviewAdd'])->middleware('LoginCheck');

//Admin panel Login Management 
Route::get('/Login',[LoginController::class,'LoginIndex']);
Route::post('/onLogin',[LoginController::class,'onLogin']);
Route::get('/Logout',[LoginController::class,'onLogout']);

//Admin Photo Gallery
Route::get('/gallery',[PhotoController::class,'PhotoIndex']);
Route::post('/photoupload',[PhotoController::class,'PhotoUpload']);
Route::get('/photojson',[PhotoController::class,'PhotoJSON']);
Route::get('/PhotoJSONByID/{id}',[PhotoController::class,'PhotoJSONByID']);
Route::post('/PhotoDelete',[PhotoController::class,'PhotoDelete']);