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

    Route::get('/get-dropdown-options',[Main::class,'getDropdownOptions']);
    Route::get('/get-dropdown-taluka-options',[Main::class,'getDropdownTalukaOptions']);
    Route::get('/get-dropdown-pincode-options',[Main::class,'getDropdownPincodeOptions']);
    Route::get('/get-dropdown-product-options',[Main::class,'getDropdownProductOptions']);

    Route::get('/productsearch', [Main::class, 'productsearch'])->name('productsearch');
    // Route::get('/productlist/{type}/{id}',[Main::class,'productlist']);
    Route::get('/productlist', [Main::class,'productlist'])->name('productlist');    
 
    // Route::get('/subproductlist/{id}',[Main::class,'subproductlist']);
    Route::get('/productdetails/{id}',[Main::class,'productdetails']);
    Route::post('changeprice',[Main::class,'changeprice']);

    Route::post('/checkstock',[Main::class,'checkstock']);
    
    Route::get('/testimonialList',[Main::class,'testimonialList']);
    Route::get('/blogDetails/{id}',[Main::class,'blogDetails']);

    Route::get('testimonialList/get-options',[Main::class,'getOptions']);
    Route::get('testimonialList/get-taluka-options',[Main::class,'getTalukaOptions']);
    Route::get('testimonialList/get-pincode-options',[Main::class,'getPincodeOptions']);
    Route::get('testimonialList/get-product-options',[Main::class,'getProductOptions']);
    Route::post('testimonialList/filterproductdata',[Main::class,'filterproductdata']);
    Route::post('testimonialList/filterregiondata',[Main::class,'filterregiondata']);

    Route::get('aboutus',[Main::class,'aboutus']);
    Route::get('privacypolicy',[Main::class,'privacypolicy']);
    Route::get('contactus',[Main::class,'contactus']);    
    Route::get('codpolicy',[Main::class,'codpolicy']);
    Route::get('shippingpolicy',[Main::class,'shippingpolicy']);
    Route::get('termsandconditions',[Main::class,'termsandconditions']);
    Route::get('refundexchangepolicy',[Main::class,'refundexchangepolicy']);
    Route::get('faq',[Main::class,'faq']);
    
    
   
    // Route::get('/thankyou',[Main::class,'thankyou']);
Route::middleware('auth','preventBackHistoryWeb')->group( function () {
    // event  
    Route::get('/webhome',     [WebHomeController::class, 'index'])->name('webhome');
    Route::get('/userProfile',     [WebHomeController::class, 'userProfile'])->name('userProfile');
    
    Route::post('/saveProfile',     [WebHomeController::class, 'saveProfile'])->name('saveProfile');
    // Route::get('/productlist/{id}',[WebHomeController::class,'productlist']);
    // Route::get('/productdetails/{id}',[WebHomeController::class,'productdetails']);
    Route::get('/thankyou',[WebHomeController::class,'thankyou']);
    Route::get('/cart',     [WebHomeController::class, 'cart'])->name('cart');
    Route::post('/addtocart',[WebHomeController::class,'addtocart']);
    Route::post('/addorder',[WebHomeController::class,'addorder']);
    
    Route::post('/ratings', [WebHomeController::class, 'ratingstore'])->name('ratings.store');
    
    Route::get('/addressform',     [WebHomeController::class, 'addressform'])->name('addressform');
    Route::post('/addAddress',[WebHomeController::class,'addAddress'])->name('addAddress'); 
    Route::get('/myaddress',[WebHomeController::class,'myaddress'])->name('myaddress');
    Route::get('/checkout',[WebHomeController::class,'checkout']); 
    Route::get('/orderthankyou',[WebHomeController::class,'orderthankyou']); 
    Route::get('/myorder',[WebHomeController::class,'myorder']);   
    Route::get('/orderdetails/{id}',[WebHomeController::class,'orderdetails']);  
    Route::post('/DeleteAddress',[WebHomeController::class,'DeleteAddress']); 
    Route::get('myaddress/edit/{id}',   [WebHomeController::class, 'edit_address'])->name('edit_address');
    Route::post('/deletecart',[WebHomeController::class,'deletecart']);
    Route::post('/clearcart',[WebHomeController::class,'clearcart']);
    
    // Route::get('/testimonialList',[WebHomeController::class,'testimonialList']);
    Route::post('/ordercancle',[WebHomeController::class,'ordercancle']);
    Route::post('/updatequantity',[WebHomeController::class,'updatequantity']);
    
    
    
    //wishlist    
    Route::post('/WishListAdd',[WebHomeController::class,'WishListAdd']);
    Route::get('/wishlist',[WebHomeController::class,'wishlist']);


    //WalletHistory
    Route::get('wallethistory',  [WebHomeController::class,'wallethistory']); 
    Route::get('add_wallet_money',  [WebHomeController::class,'add_wallet_money']);
    Route::post('addmoney',  [WebHomeController::class,'addmoney']);  
    
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
        Route::get('/add_user',    [AdminUserController::class,'add_user']);
        Route::post('user/saveuser',   [AdminUserController::class,'saveuser'])->name('user.saveuser');
        Route::post('/user_delete',    [AdminUserController::class, 'user_delete'])->name('admin.delete-user');
        Route::post('/user_status',  [AdminUserController::class, 'user_status'])->name('admin.user_status');
        Route::post('/user_verified',  [AdminUserController::class, 'user_verified'])->name('admin.user_verified');
        

        Route::post('/user_multi_status', [AdminUserController::class, 'user_multi_status'])->name('admin.user_multi_status');

        Route::post('/user_details',              [AdminUserController::class, 'user_details'])->name('admin.user-details');
        Route::get('user/userdatadetails/{id}',   [AdminUserController::class, 'user_data_details'])->name('userdatadetails');  
        Route::get('user/edit/{id}',   [AdminUserController::class, 'user_data_edit'])->name('userdataedit');
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