<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAPIController; 
use App\Http\Controllers\Api\SettingAPIController;
use App\Http\Controllers\Api\CategoryAPIController;
use App\Http\Controllers\Api\BannerAPIController;
use App\Http\Controllers\Api\NgoAPIController;
use App\Http\Controllers\Api\BloodBankAPIController;
use App\Http\Controllers\Api\DonorAPIController;
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

//ServiceList
Route::get('ServiceNeedList',     [SettingAPIController::class,'ServiceNeedList']);

//SpecificList
Route::get('SpecificNeedList',     [SettingAPIController::class,'SpecificNeedList']);

Route::middleware('auth:api')->group( function () {
   // user
   Route::post('VerifyOtp',     [UserAPIController::class,'VerifyOtp']);
   Route::post('ProfileUpdate',     [UserAPIController::class,'ProfileUpdate']);
   Route::get('GetUserProfile',   [UserAPIController::class,'GetUserProfile']);
   Route::get('Logout',            [UserAPIController::class,'Logout']);

   
   //member
   Route::post('addMember',     [NgoAPIController::class,'addMember']);
   Route::get('MemberList',     [NgoAPIController::class,'MemberList']);
   
   //bankdetails   
   Route::post('addNgoOtherDetails',     [NgoAPIController::class,'addNgoOtherDetails']);
   Route::get('getNgoOtherDetails',     [NgoAPIController::class,'getNgoOtherDetails']);
   
   
   //photos
   Route::post('addPhotos',     [NgoAPIController::class,'addPhotos']);
   Route::post('getPhotos',     [NgoAPIController::class,'getPhotos']);

   //Videos
   Route::post('addVideos',     [NgoAPIController::class,'addVideos']);
   Route::post('getVideos',     [NgoAPIController::class,'getVideos']);

   //Ngo
   
   Route::post('ngoListFilter',     [NgoAPIController::class,'ngoListFilter']);
   Route::post('ngosingleDetails',     [NgoAPIController::class,'ngosingleDetails']);
   Route::post('ngobankDetails',     [NgoAPIController::class,'ngobankDetails']);
   Route::post('getPhotosofngo',     [NgoAPIController::class,'getPhotosofngo']);
   Route::post('getVideosofngo',     [NgoAPIController::class,'getVideosofngo']);
   

   //bloodbank
   Route::post('bloodbankList',     [BloodBankAPIController::class,'bloodbankList']);
   Route::post('bloodbanksingleDetails',     [BloodBankAPIController::class,'bloodbanksingleDetails']);
   Route::post('addbloodRequest',     [BloodBankAPIController::class,'addbloodRequest']);
  Route::post('getbloodRequest',     [BloodBankAPIController::class,'getbloodRequest']); 
  Route::post('nearbydonorList',     [BloodBankAPIController::class,'nearbydonorList']); 
  
   
   //donor   
   Route::post('donorListFilter',     [DonorAPIController::class,'donorListFilter']);

   //eye donation   
   Route::post('eyedonationList',     [DonorAPIController::class,'eyedonationList']);
   Route::post('eyedonationsingleDetails',     [DonorAPIController::class,'eyedonationsingleDetails']);

   //event promotion
   Route::post('eventpromotionList',     [DonorAPIController::class,'eventpromotionList']);
   Route::post('eventpromotionsingleDetails',     [DonorAPIController::class,'eventpromotionsingleDetails']);
   
   // crowdfundingList
   Route::post('crowdfundingList',     [DonorAPIController::class,'crowdfundingList']);
   Route::post('crowdfundingsingleDetails', [DonorAPIController::class,'crowdfundingsingleDetails']);
   
   
});