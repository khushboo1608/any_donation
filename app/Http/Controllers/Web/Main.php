<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Flash;
use Storage;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserAuthMaster;

use Response;
use DB;

class Main extends Controller
{
    // public function __construct() 
    // {
    //     $this->middleware('guest');
    // }
    public function index()
    { 
        return view('web.index');
    }
    
    public function test()
    {
        echo "test";exit();
    }
    public function userlogin()
    { 
        return view('auth.login');     
        // return Redirect::guest('auth.login');
    }
    
    public function verify_otp(Request $request)
    { 
        //   echo "<pre>";
        // print_r($request->all());die;
        $user= User::where('id',$request->user_id)->first();
        if($user->otp == $request->otp){
            if($user->name == '' && $user->email == ''){
                Auth::loginUsingId($user->id);
        //         $UserData=User::where('id',$auth_user->id)->first();
        // $UserData->imageurl = $this->GetImage($file_name = $UserData->imageurl,$path=config('global.file_path.user_profile'));
        // return view('web.userprofile',compact('UserData'));
                return view('web.userprofile')->with(['user_data' => $user]); 
                // return view('web.profileupdate');
            }
            else{
                Auth::loginUsingId($user->id); 
                return Redirect('/webhome');
            }
            
        }
        else{
            // return Redirect('/');
            // return back()->withInput()->with('error', 'Invalid phone no.');
            return view('web.verifyotp')->with(['userData' => $user,'error' => 'Verification code is not valid.']);
            // return redirect('/userlogin')->with('error','Verification code is not valid.'); 
           
        }
          
    }

   
    public function  resend_otp(Request $request)
    { 
        // echo "<pre>";
        // print_r($request->all());die;
        $input = $request->all();
        $user = User::where('phone',$input['phone'])->where('is_disable',0)->first();
        // $otp=rand(1000,9999);
        $otp='1234';
        $sms_data = $this->SentMobileVerificationCode($input['phone'],$otp);

        if($sms_data['flag'] == 'success')
        {
            $user_input = array(
                'otp' => $otp,
            );
            User::where('phone',$input['phone'])->update($user_input);
            return response()->json(['result' => true]);
            // return view('web.verifyotp');   
        }
        else
        {
            return response()->json(['result' => false]);
        }  
          
    }

    public function userregister()
    { 
        // echo 'in'; exit();
        // return view('auth.register');
        $data['state'] = State::orderBy('state_name')->where('state_status',0)->get();
        return view('auth.register')->with(['master_data' => $data]); 
          
    }
   

    public function aboutus()
    { 

        $SettingsData= Settings::first();
        return view('web.aboutus')->with(['SettingsData' => $SettingsData]);
    }

    public function privacypolicy()
    { 

        $SettingsData= Settings::first();
        return view('web.privacypolicy')->with(['SettingsData' => $SettingsData]);
    }
    public function contactus()
    { 
        return view('web.contactus');
    }

    public function codpolicy()
    { 
        $SettingsData= Settings::first();
        return view('web.codpolicy')->with(['SettingsData' => $SettingsData]);
    }
    public function shippingpolicy()
    { 
        $SettingsData= Settings::first();
        return view('web.shippingpolicy')->with(['SettingsData' => $SettingsData]);
    }
    public function termsandconditions()
    { 
        $SettingsData= Settings::first();
        return view('web.termsandconditions')->with(['SettingsData' => $SettingsData]);
    }
    public function refundexchangepolicy()
    { 
        $SettingsData= Settings::first();
        return view('web.refundexchangepolicy')->with(['SettingsData' => $SettingsData]);
    }
    public function faq()
    { 
        $SettingsData= Settings::first();
        return view('web.faq')->with(['SettingsData' => $SettingsData]);
    }
   
    
    
}