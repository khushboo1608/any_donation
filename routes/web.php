<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminHomeController; 
use App\Http\Controllers\Admin\AdminUserController; 
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminRatingReviewController;
use App\Http\Controllers\Admin\AdminWalletHistroyController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\AdminGallaryController;
use App\Http\Controllers\Admin\AdminColorController;
use App\Http\Controllers\Admin\AdminDiscountController;
use App\Http\Controllers\Admin\AdminCollectionController;
use App\Http\Controllers\Web\Main;
use App\Http\Controllers\Web\WebHomeController;
use App\Http\Controllers\Admin\AdminPhotoController;
use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Admin\AdminMemberDetailsController;
use App\Http\Controllers\Admin\AdminRequestDetailsController;
use App\Http\Controllers\Admin\AdminSpecificNeedsDetailsController;
use App\Http\Controllers\Admin\AdminServiceNeedsDetailsController;
use App\Http\Controllers\Admin\AdminEventPromotionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

    Route::get('/',     [Main::class, 'index'])->name('home');
    // Route::get('/test', [Main::class, 'test'])->name('test');
    Route::get('/userlogin',     [Main::class, 'userlogin'])->name('userlogin');
    Route::post('verify_otp',[Main::class,'verify_otp']);
    Route::post('resend_otp',[Main::class,'resend_otp']);

    // Route::get('/userregister',     [Main::class, 'userregister'])->name('userregister');

    
   
    // Route::get('/thankyou',[Main::class,'thankyou']);
Route::middleware('auth','preventBackHistoryWeb')->group( function () {
    // event  
    Route::get('/webhome',     [WebHomeController::class, 'index'])->name('webhome');
    Route::get('/userProfile',     [WebHomeController::class, 'userProfile'])->name('userProfile');    
    Route::post('/saveProfile',     [WebHomeController::class, 'saveProfile'])->name('saveProfile');
    // Route::get('/productlist/{id}',[WebHomeController::class,'productlist']);
    // Route::get('/productdetails/{id}',[WebHomeController::class,'productdetails']);
   
});

Route::group(['prefix' => 'admin'], function () {

    Route::middleware('is_admin')->group( function () { 
        // admin Route
        Route::get('/home',                       [AdminHomeController::class, 'index'])->name('admin.home');
        
        // district admin
        // Route::get('/districthome',                       [AdminHomeController::class, 'districtindex'])->name('admin.districthome');

        //profile
        Route::get('/profile',                      [AdminHomeController::class, 'profile'])->name('admin.profile');
        Route::post('/update_profile',              [AdminHomeController::class, 'update_profile'])->name('admin.update-profile');
        Route::post('/check_old_password',          [AdminHomeController::class, 'check_old_password'])->name('admin.check-old-password');
        Route::post('/change_password',             [AdminHomeController::class, 'change_password'])->name('admin.change-password');
        
        //User
        Route::get('/user',                       [AdminUserController::class, 'index'])->name('admin.user');
        Route::get('/add_user/{type}',    [AdminUserController::class,'add_user']);
        Route::post('user/saveuser',   [AdminUserController::class,'saveuser'])->name('user.saveuser');
        Route::post('/user_delete',    [AdminUserController::class, 'user_delete'])->name('admin.delete-user');
        Route::post('/user_status',  [AdminUserController::class, 'user_status'])->name('admin.user_status');
        Route::post('/user_verified',  [AdminUserController::class, 'user_verified'])->name('admin.user_verified');
        //route set by khushboo
        Route::post('/fetch_city', [AdminUserController::class, 'fetchCity'])->name('admin.fetch_city');
        Route::post('/user_approved',  [AdminUserController::class, 'user_approved'])->name('admin.user_approved');
        Route::get('/userngo', [AdminUserController::class, 'userngo_index'])->name('admin.userngo_index');
        Route::get('/userblood', [AdminUserController::class, 'userblood_index'])->name('admin.userblood_index');

        //photos
        Route::get('/photos',[AdminPhotoController::class, 'index'])->name('admin.photos');
        Route::get('/add_photos',[AdminPhotoController::class, 'add_photos'])->name('admin.add_photos');
        Route::post('photos/savephotos',[AdminPhotoController::class, 'savephotos'])->name('photos.savephotos');
        Route::post('/fetch_user', [AdminPhotoController::class, 'fetchUser'])->name('admin.fetch_user');
        Route::post('/photos_delete', [AdminPhotoController::class, 'photos_delete'])->name('admin.photos_delete');
        Route::post('/photo_status',   [AdminPhotoController::class, 'photo_status'])->name('admin.photo_status');
        Route::post('/photos_multi_status', [AdminPhotoController::class, 'photos_multi_status'])->name('admin.photos_multi_status');
        Route::get('/photos/edit/{id}', [AdminPhotoController::class, 'photo_data_edit'])->name('admin.photo_data_edit');

        //video
        Route::get('/videos',[AdminVideoController::class, 'index'])->name('admin.videos');
        Route::get('/add_videos',[AdminVideoController::class, 'add_videos'])->name('admin.add_videos');
        Route::post('videos/savevideos',[AdminVideoController::class, 'savevideos'])->name('videos.savevideos');
        Route::post('/videos_delete', [AdminVideoController::class, 'videos_delete'])->name('admin.videos_delete');
        Route::post('/videos_status',   [AdminVideoController::class, 'videos_status'])->name('admin.videos_status');
        Route::post('/videos_multi_status', [AdminVideoController::class, 'videos_multi_status'])->name('admin.videos_multi_status');
        Route::get('/videos/edit/{id}', [AdminVideoController::class, 'video_data_edit'])->name('admin.video_data_edit');

        //member details
        Route::get('/member',[AdminMemberDetailsController::class, 'index'])->name('admin.member');
        Route::get('/add_member',[AdminMemberDetailsController::class, 'add_member'])->name('admin.add_member');
        Route::post('member/savemember',[AdminMemberDetailsController::class, 'savemember'])->name('member.savemember');
        Route::post('/member_delete', [AdminMemberDetailsController::class, 'member_delete'])->name('admin.member_delete');
        Route::post('/member_status',   [AdminMemberDetailsController::class, 'member_status'])->name('admin.member_status');
        Route::post('/member_multi_status', [AdminMemberDetailsController::class, 'member_multi_status'])->name('admin.member_multi_status');
        Route::get('/member/edit/{id}', [AdminMemberDetailsController::class, 'member_data_edit'])->name('admin.photo_data_edit');
        
        //request details
        Route::get('/request',[AdminRequestDetailsController::class, 'index'])->name('admin.request');
        Route::get('/add_request',[AdminRequestDetailsController::class, 'add_request'])->name('admin.add_request');
        Route::post('request/saverequest',[AdminRequestDetailsController::class, 'saverequest'])->name('request.saverequest');
        Route::post('/request_delete', [AdminRequestDetailsController::class, 'request_delete'])->name('admin.request_delete');
        Route::post('/request_status',   [AdminRequestDetailsController::class, 'request_status'])->name('admin.request_status');
        Route::post('/request_multi_status', [AdminRequestDetailsController::class, 'request_multi_status'])->name('admin.request_multi_status');
        Route::get('/request/edit/{id}', [AdminRequestDetailsController::class, 'request_data_edit'])->name('admin.request_data_edit');

        //specific_needs
        Route::get('/specific_needs',[AdminSpecificNeedsDetailsController::class, 'index'])->name('admin.specific_needs');
        Route::get('/add_specific',[AdminSpecificNeedsDetailsController::class, 'add_specific'])->name('admin.add_specific');
        Route::post('specific/savespecific',[AdminSpecificNeedsDetailsController::class, 'savespecific'])->name('specific.savespecific');
        Route::post('/specific_delete', [AdminSpecificNeedsDetailsController::class, 'specific_delete'])->name('admin.specific_delete');
        Route::post('/specific_status',   [AdminSpecificNeedsDetailsController::class, 'specific_status'])->name('admin.specific_status');
        Route::post('/specific_multi_status', [AdminSpecificNeedsDetailsController::class, 'specific_multi_status'])->name('admin.specific_multi_status');
        Route::get('/specific_needs/edit/{id}', [AdminSpecificNeedsDetailsController::class, 'specific_data_edit'])->name('admin.specific_data_edit');
        
        //service_needs
        Route::get('/service_needs',[AdminServiceNeedsDetailsController::class, 'index'])->name('admin.service_needs');
        Route::get('/add_service',[AdminServiceNeedsDetailsController::class, 'add_service'])->name('admin.add_service');
        Route::post('service/saveservice',[AdminServiceNeedsDetailsController::class, 'saveservice'])->name('service.saveservice');
        Route::post('/service_delete', [AdminServiceNeedsDetailsController::class, 'service_delete'])->name('admin.service_delete');
        Route::post('/service_status',   [AdminServiceNeedsDetailsController::class, 'service_status'])->name('admin.service_status');
        Route::post('/service_multi_status', [AdminServiceNeedsDetailsController::class, 'service_multi_status'])->name('admin._multi_status');
        Route::get('/service_needs/edit/{id}', [AdminServiceNeedsDetailsController::class, 'service_data_edit'])->name('admin.service_data_edit');














        
        Route::post('/user_multi_status', [AdminUserController::class, 'user_multi_status'])->name('admin.user_multi_status');

        Route::post('/user_details',              [AdminUserController::class, 'user_details'])->name('admin.user-details');
        Route::get('user/userdatadetails/{id}',   [AdminUserController::class, 'user_data_details'])->name('userdatadetails');  
        Route::get('user/edit/{id}/{type}',   [AdminUserController::class, 'user_data_edit'])->name('userdataedit');
        Route::get('userfile-export',   [AdminUserController::class, 'userfileexport'])->name('userfile-export');        
  
       // Category
       Route::get('/category',   [AdminCategoryController::class, 'index'])->name('admin.category');  
       Route::get('/add_category',[AdminCategoryController::class,'add_category']);
       Route::post('category/savecategory',[AdminCategoryController::class,'savecategory'])->name('category.savecategory');        
       Route::post('/category_status',   [AdminCategoryController::class, 'category_status'])->name('admin.category_status');
       
       Route::post('/category_delete', [AdminCategoryController::class, 'category_delete'])->name('admin.category_delete');
       Route::get('categoryfile-export', [AdminCategoryController::class, 'categoryfileExport'])->name('categoryfile-export');       
       Route::post('/category_multi_status', [AdminCategoryController::class, 'category_multi_status'])->name('admin.category_multi_status');
       Route::get('category/edit/{id}',   [AdminCategoryController::class, 'category_data_edit'])->name('category_data_edit');
   

       //Settings
       Route::get('/setting',   [AdminSettingController::class, 'index'])->name('admin.setting');
       Route::post('setting/savepagesetting',[AdminSettingController::class,'SavePageSetting'])->name('setting.savepagesetting');
       Route::get('/generalsetting',      [AdminSettingController::class, 'GeneralSetting'])->name('admin.generalsetting');
       Route::post('setting/savegeneral',[AdminSettingController::class,'SaveGeneral'])->name('setting.savegeneral');

        // Banner
        Route::get('/banner',   [AdminBannerController::class, 'index'])->name('admin.banner');  
        Route::get('/add_banner',[AdminBannerController::class,'add_banner']);
        Route::post('banner/savebanner',[AdminBannerController::class,'savebanner'])->name('banner.savebanner');        
        Route::post('/banner_status',   [AdminBannerController::class, 'banner_status'])->name('admin.banner_status');

        Route::post('/banner_delete', [AdminBannerController::class, 'banner_delete'])->name('admin.banner_delete');
        Route::get('bannerfile-export', [AdminBannerController::class, 'bannerfileExport'])->name('bannerfile-export');       
        Route::post('/banner_multi_status', [AdminBannerController::class, 'banner_multi_status'])->name('admin.banner_multi_status');
        Route::get('banner/edit/{id}',   [AdminBannerController::class, 'banner_data_edit'])->name('banner_data_edit');

       
          
        //Subadmin
        Route::get('/subadmin',   [SubAdminController::class, 'index'])->name('admin.subadmin');
        Route::get('/add_subadmin',    [SubAdminController::class,'add_subadmin']);
        Route::post('subadmin/savesubadmin',   [SubAdminController::class,'savesubadmin'])->name('subadmin.savesubadmin');
        Route::post('/subadmin_delete',    [SubAdminController::class, 'subadmin_delete'])->name('admin.delete-subadmin');
        Route::post('/subadmin_status',  [SubAdminController::class, 'subadmin_status'])->name('admin.subadmin_status');
        
        Route::post('/subadmin_multi_status', [SubAdminController::class, 'subadmin_multi_status'])->name('admin.subadmin_multi_status');

        Route::post('/subadmin_details',   [SubAdminController::class, 'subadmin_details'])->name('admin.subadmin-details');
        Route::get('subadmin/subadmindatadetails/{id}',   [SubAdminController::class, 'subadmin_data_details'])->name('subadmindatadetails');  
        Route::get('subadmin/edit/{id}',   [SubAdminController::class, 'subadmin_data_edit'])->name('subadmindataedit');
        Route::get('subadminfile-export',   [SubAdminController::class, 'subadminfileexport'])->name('subadminfile-export');  
          
        
         
    
    });
});