<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAPIController; 
use App\Http\Controllers\Api\SettingAPIController;
use App\Http\Controllers\Api\CategoryAPIController;
use App\Http\Controllers\Api\BannerAPIController;
use App\Http\Controllers\Api\NgoAPIController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('Registration',     [UserAPIController::class, 'Registration']);
Route::post('Login',            [UserAPIController::class, 'Login']);
 //Settings
 Route::get('SettingList',     [SettingAPIController::class,'SettingList']); 

//StateList
Route::get('StateList',     [SettingAPIController::class,'StateList']);

//City
Route::post('CityList',     [SettingAPIController::class,'CityList']);

//BannerList
Route::get('BannerList',     [BannerAPIController::class,'BannerList']);

//Category
Route::get('CategoryList',     [CategoryAPIController::class,'CategoryList']); 


Route::middleware('auth:api')->group( function () {
   // user
   Route::post('VerifyOtp',     [UserAPIController::class,'VerifyOtp']);
   Route::post('ProfileUpdate',     [UserAPIController::class,'ProfileUpdate']);
   Route::get('GetUserProfile',   [UserAPIController::class,'GetUserProfile']);
   Route::get('Logout',            [UserAPIController::class,'Logout']);

   
   //member
   Route::post('addMember',     [NgoAPIController::class,'addMember']);
   Route::get('MemberList',     [NgoAPIController::class,'MemberList']);
   
   
});