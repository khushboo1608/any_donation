<?php

namespace App\Helper;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Settings;
use App\Models\Product;
use App\Models\Cart;
use App\Models\SubCategory;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Helper 
{
	public static function LoggedUserImage()
	{
		$auth_user = Auth::guard('admin')->user();
		$data = $auth_user->toArray();

		// echo "<pre>";
		// print_r($auth_user);die;
		if(isset($data['imageurl']))
		{
			if(file_exists(public_path(config('global.file_path.user_profile').'/'.$data['imageurl'])))
			{
				$Image = url('public/'.config('global.file_path.user_profile')).'/'.$data['imageurl'];
			}
			else
			{	
				$Image = config('global.no_image.no_user');
			}
		}
		else
		{
			$Image = config('global.no_image.no_user');
		}
		return $Image;
	}

	public static function LoggedWebUserImage()
	{
		$auth_user = Auth::guard('web')->user();
		// echo "<pre>";
		// print_r($auth_user);die;
		$data = $auth_user->toArray();
		if(isset($data['imageurl']))
		{
			$con = new Controller();
			$Image = $con->GetImage($data['imageurl'],$path=config('global.file_path.user_profile'));
			if($Image == '')
			{
				$Image = config('global.no_image.no_user');
			}
		}
		else
		{
			$Image = config('global.no_image.no_user');
		}
		return $Image;
	}

	public static function AppLogoImage()
	{
		$setting =  Settings::first();
		if(isset($setting->app_logo))
		{
			if(file_exists(public_path(config('global.file_path.app_logo').'/'.$setting->app_logo)))
			{
				$Image = url('public/'.config('global.file_path.app_logo')).'/'.$setting->app_logo;
			}
			else
			{	
				$Image = config('global.no_image.no_image');
			}
		}
		else
		{
			$Image = config('global.no_image.no_image');
		}
		return $Image;
	}

	public static function AppName()
	{
		$setting =  Settings::first();
		if(isset($setting->app_name))
		{
			// echo 'if';die;
			
			if($setting->app_name !='')
			{
				$Name = $setting->app_name;
			}
			else
			{	
				$Name = env('APP_NAME');
			}
		}
		else
		{
			// echo env('APP_NAME'); die;
			$Name = env('APP_NAME');
		}
		return $Name;
	}

	

	public static function MinAmount()
	{
		$setting =  Settings::first();
		if(isset($setting->add_min_wallet_amount))
		{
			// echo 'if';die;
			
			if($setting->add_min_wallet_amount !='')
			{
				$amount = $setting->add_min_wallet_amount;
			}
			else
			{	
				$amount = 0;
			}
		}
		else
		{
			// echo env('APP_NAME'); die;
			$amount = 0;
		}
		return $amount;
	}

	
	public static function ProductImage()
	{
		$auth_user = Auth::guard('admin')->user();
		$data = $auth_user->toArray();

		// echo "<pre>";
		// print_r($data);die;
		if(isset($data['imageurl']))
		{
			if(file_exists(public_path(config('global.file_path.user_profile').'/'.$data['imageurl'])))
			{
				$Image = url('public/'.config('global.file_path.user_profile')).'/'.$data['imageurl'];
			}
			else
			{	
				$Image = config('global.no_image.no_user');
			}
		}
		else
		{
			$Image = config('global.no_image.no_user');
		}
		return $Image;
	}

	public static function readMoreHelper($story_desc, $chars = 38)
	{
		// $story_desc = substr($story_desc,0,$chars);  
		// $story_desc = substr($story_desc,0,strrpos($story_desc,' '));  
		// $story_desc = $story_desc."...";  
		$story_desc = (strlen($story_desc) > $chars) ?substr($story_desc,0,$chars)."...":$story_desc;
		return $story_desc;  
	}

	
	public static function CartCount($id)
	{
		$cart_count = Cart::where('user_id',$id)->where('cart_status',0)->count();
		
		return $cart_count;
	}

	public static function Categorylist()
	{
		$CategoryData = Category::where('category_status',0)->orderBy('created_at','desc')->get();
		
		return $CategoryData;
	}
	
	public static function SubCategorylist($id)
	{
		$SubCategoryData = SubCategory::where('category_id',$id)->where('sub_category_status',0)->orderBy('created_at','desc')->get();
		
		return $SubCategoryData;
	}

}
?>