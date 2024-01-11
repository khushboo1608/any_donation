<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\UserAuthMaster;

use DB;
use Validator;

use Response;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WebHomeController extends Controller
{
	public function __construct() 
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('web.index');            
	}
	public function thankyou()
    { 
        return view('web.thankyou');     
    }
    
    public function userProfile()
	{
		$auth_user = Auth::user();
        // return view('web.userprofile',compact('auth_user'));  

        $user_data=User::where('id',$auth_user->id)->first();
        $user_data->imageurl = $this->GetImage($file_name = $user_data->imageurl,$path=config('global.file_path.user_profile'));
        return view('web.profileupdate',compact('user_data'));
	}
    
    public function saveProfile(Request $request)
	{
		$user_data = $request->all();

        // echo "<pre>";
        // print_r($user_data);die;
        $message="";
        $imageurl = '';
        // if($user_data['id'] !=''){
            $user=User::where(['id'=>$user_data['id']])->first();
            $rules = [
                'email' => 'unique:users,email,'.$user_data['id'].',id',
                'phone' => 'unique:users,phone,'.$user_data['id'].',id',
            ];
            
            // 'email' => 'required|string|email|max:255|unique:users,email,'.$user->id
            $messages = [
                'email.unique'    => 'User already exist with this email. Try another.',
                'phone.unique'     => 'User already exist with this Phone no. Try another.',
        
            ];
            $validator = Validator::make($user_data, $rules,$messages);       

            if ($validator->fails())
            {
                // echo 'in'; die;
                $errors = $validator->errors()->first();  
                
                $user_data=User::where(['id'=>$user->id])->first();
                // $user_data->imageurl = $this->GetImage($file_name = $user_data->imageurl,$path=config('global.file_path.user_profile'));            
                // return redirect()->back()->with('error',$errors);
                Session::flash('error', $errors); 
                return view('web.userprofile',compact('user_data'));
            }
            else
            {
                // echo 'else';die;
                if($request->imageurl != "")
                {   
                    $imageurl = $this->UploadImage($file = $request->imageurl,$path = config('global.file_path.user_profile'));
                }
                else{
                    $imageurl =$user->imageurl;
                }
                $user_data['imageurl'] = $imageurl;
                if($request->password !=''){
                    $user_data['password'] = Hash::make($request->password);
                }
                else{
                    $user_data['password'] = $user->password;
                }
                $user = User::find($user_data['id']);
                $user->fill($user_data);
                $user->save();
                $message="User Data Updated Successfully";

                $user_data=User::where(['id'=>$user->id])->first();
                $user_data->imageurl = $this->GetImage($file_name = $user_data->imageurl,$path=config('global.file_path.user_profile'));
                // Session::flash('message', $message); 
                Auth::loginUsingId($user->id); 
                return Redirect('/webhome');
                // return view('web.userprofile',compact('user_data','master_data'));
            }
            
            // return view('web.userprofile',compact('user_data')); 
        //}
	}
    
}


