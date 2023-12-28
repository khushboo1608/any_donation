<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OctaAPIController;
use App\Models\User;
use App\Models\UserAuthMaster;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        // echo "hi";exit();
       if(Auth::user())
       {
            return Redirect('login');
       }
       else
       {
            return view('auth.admin.login');
       }
        
    }
    public function login(Request $request)
    {   
        // echo '<pre>'; 
        $input = $request->all();
        // print_r($input); die;
        
            // echo "if";die;
            if($request->login_type == 'web')
            {
                $user = User::where('phone',$input['phone'])->where('is_disable',0)->first();

                // echo "<Pre>";
                // print_r($user);die;
                // echo "web";die;

                if($user)
                {
                    if ($request->login_type == 'web' &&  $user->login_type == 2) {
                        // echo 'if';die;
                        
                            if($input['phone'] == $user->phone) 
                            {
                                // echo 'if';die;
                                // Auth::loginUsingId($user->id); 
                                // return Redirect('/webhome');
                                if($user->phone == '7777991598'){

                                    // echo "iff 777";die;
                                    $otp1='1234';   
                                    $userdata = User::where('phone',$input['phone'])->first();
                                    if($userdata !=''){
                                        
                                        $user_input = array(
                                            'otp' => $otp1,
                                        );
                                        User::where('phone',$input['phone'])->update($user_input);
                                        // User::where('phone',$input['phone'])->update(['otp' => $otp1]);
                                        $token = $userdata->createToken(env('APP_NAME'));
                                        $userdata->token = $token->accessToken;
                                        $oauth_access_token_id = $token->token->id;
                                        
                                        $getauth_data = UserAuthMaster::where('user_id',$user->id)->where('device_token','webtoken')->first();
                                        if(!$getauth_data)
                                        {
                                            $user_auth_id = $this->GenerateUniqueRandomString($table='user_auth_master', $column="user_auth_id", $chars=6);
                                            $auth_input = array(
                                                'user_auth_id'      => $user_auth_id,
                                                'user_id' => $user->id,
                                                'oauth_access_token_id' => $oauth_access_token_id,
                                                'device_type'  => 3,
                                                'device_token' => 'webtoken',
                                            );
                                            $user_auth_token = UserAuthMaster::create($auth_input);
                                        }
                                        else
                                        {
                                            $auth_input = array(
                                                'oauth_access_token_id' => $oauth_access_token_id,
                                                'device_type'  => 3,
                                                'device_token' => 'webtoken',
                                            );
                                            UserAuthMaster::where('user_id',$user->id)->where('device_token','webtoken')->update($auth_input);
                                        } 
                                        // $SettingsData= Settings::first();
                                         return view('web.verifyotp')->with(['userData' => $user]);
                                        // return view('web.verifyotp'); 
                                    }
                                    else{
                                        return back()->withInput()->with('error', 'Invalid phone no.');
                                    }
                                    
                                }
                                else{
                                    // $otp=rand(1000,9999);

                                    $otp='1234';
                                    $userdata = User::where('phone',$input['phone'])->first();
                                    if($userdata !=''){
                                        
                                        $user_input = array(
                                            'otp' => $otp,
                                        );
                                        User::where('phone',$input['phone'])->update($user_input);
                                        // User::where('phone',$input['phone'])->update(['otp' => $otp1]);
                                        $token = $userdata->createToken(env('APP_NAME'));
                                        $userdata->token = $token->accessToken;
                                        $oauth_access_token_id = $token->token->id;
                                        
                                        $getauth_data = UserAuthMaster::where('user_id',$user->id)->where('device_token','webtoken')->first();
                                        if(!$getauth_data)
                                        {
                                            $user_auth_id = $this->GenerateUniqueRandomString($table='user_auth_master', $column="user_auth_id", $chars=6);
                                            $auth_input = array(
                                                'user_auth_id'      => $user_auth_id,
                                                'user_id' => $user->id,
                                                'oauth_access_token_id' => $oauth_access_token_id,
                                                'device_type'  => 3,
                                                'device_token' => 'webtoken',
                                            );
                                            $user_auth_token = UserAuthMaster::create($auth_input);
                                        }
                                        else
                                        {
                                            $auth_input = array(
                                                'oauth_access_token_id' => $oauth_access_token_id,
                                                'device_type'  => 3,
                                                'device_token' => 'webtoken',
                                            );
                                            UserAuthMaster::where('user_id',$user->id)->where('device_token','webtoken')->update($auth_input);
                                        } 
                                        // $SettingsData= Settings::first();
                                         return view('web.verifyotp')->with(['userData' => $user]);
                                        // return view('web.verifyotp'); 
                                    }
                                    else{
                                        return back()->withInput()->with('error', 'Invalid phone no.');
                                    }
                                    return view('web.verifyotp')->with(['userData' => $user]);
                                }
                            }
                            else{
                                // echo 'else';die;
                                return back()->withInput()->with('error', 'Invalid phone no.');
                            }                            
                        
                    }else{
                        return back()->withInput()->with('error', 'User could not found with this credential.');
                        
                    }
                }
                else{
                    
                    // echo"<pre>";print_r($userData);die;
                    $userData = User::where('phone',$input['phone'])->first();
                    $phone =$input['phone'];
                    
                            // echo 'else';die;
                            // $otp=rand(1000,9999);
                    $otp='1234';
                    $sms_data = $this->SentMobileVerificationCode($phone,$otp);

                    if($sms_data['flag'] == 'success')
                    {
                        $input['otp'] = $otp;
                        $referral_code = $this->generateReferralCode();
                        $input['referral_code'] = $referral_code;
                        unset($input['login_type']);
                        
                        $input['login_type'] ='2';
                        //  echo "<pre>";
                        // print_r($input);die;
                        $userData = User::create($input);   
                        $token = $userData->createToken(env('APP_NAME'));
                        $userData->token = $token->accessToken;
                        $oauth_access_token_id = $token->token->id;
                        $user_auth_id = $this->GenerateUniqueRandomString($table='user_auth_master', $column="user_auth_id", $chars=6);
                        $auth_input = array(
                            'user_auth_id'      => $user_auth_id,
                            'user_id'           => $userData->id,
                            'oauth_access_token_id' => $oauth_access_token_id,
                            'device_type'  => 3,
                            'device_token' => 'webtoken',
                        );
                        UserAuthMaster::create($auth_input);
                        return view('web.verifyotp')->with(['userData' => $userData]);
                        // return view('web.verifyotp');   
                    }
                    else
                    {
                        return back()->withInput()->with('error', 'Invalid email or password.');
                    }                

                    //}
                }
                
                    
            }
            else{
                $this->validate($request, [
                    'email' => 'required',
                    'password' => 'required',
                ]);
                // $user = User::where('email',$request->email)->where('is_disable',0)->first();
                $user = User::where('email',$input['email'])->where('is_disable',0)->first();
                // print_r($user); die;
                if($user)
                {
                    // echo 'admin';die;
                        // echo '<pre>'; 
                        // print_r(auth()->user());die;
                        // session()->put('AdminRole', $user->login_type);
                        if ($user->login_type == 1) {
                            if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
                            {
                                // echo 'if';die;
                            Auth::guard('admin')->loginUsingId($user->id);
                            session()->put('AdminRole', $user->login_type);
                                return redirect('admin/home');
                            }else{
                                return back()->withInput()->with('message', 'Admin could not found with this credential.');
                            }
                        }
                    else{
                        return back()->withInput()->with('message', 'Invalid email or password.');
                    }
                }
                else{
                    return back()->withInput()->with('error', 'User not found with this email.');
                    
                }
                
            }
       
    }
    public function logout(Request $request)
    {
        if(isset($request->is_web))
        {
            $this->performLogout($request);
            // return Redirect('userlogin');
            // Session::flush();
            return Redirect('/');
        }
        else
        {
            // Session::flush();
            $this->performLogout($request);
            return Redirect('admin');
        }
    }
    
}
