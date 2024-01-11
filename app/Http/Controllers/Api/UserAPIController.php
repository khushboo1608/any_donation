<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseAPIController as BaseAPIController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Settings;
use App\Models\UserAuthMaster;
use App\Models\NotificationMaster;
use App\Models\Ngo;
use Hash;
use Mail;
use Carbon\Carbon; 
use App\Models\PasswordReset;
use App\Mail\ForgotPasswordMail;

class UserAPIController extends BaseAPIController
{

    public function Registration(Request $request)
    {
        
        // $otp=rand(1000,9999); 
        $otp='1234';  
        try
        {
            $input = $request->all();

                
                $phone =$input['phone'];
                if($phone == '7777991598'){
                    $otp1='1234';   
                    $userdata = User::where('phone',$input['phone'])->first();
                    if($userdata !=''){
                        User::where('phone',$input['phone'])->update(['otp' => $otp1]);
                        $token = $userdata->createToken(env('APP_NAME'));
                        $userdata->token = $token->accessToken;
                        $oauth_access_token_id = $token->token->id;
                        
                        $getauth_data = UserAuthMaster::where('device_token',$input['device_token'])->first();
                        if(!$getauth_data)
                        {
                            $user_auth_id = $this->GenerateUniqueRandomString($table='user_auth_master', $column="user_auth_id", $chars=6);
                            $auth_input = array(
                                'user_auth_id'      => $user_auth_id,
                                'user_id' => $userdata->id,
                                'oauth_access_token_id' => $oauth_access_token_id,
                                'device_type'  => $input['device_type'],
                                'device_token' => $input['device_token'],
                            );
                            $user_auth_token = UserAuthMaster::create($auth_input);
                        }
                        else
                        {
                            $auth_input = array(
                                'oauth_access_token_id' => $oauth_access_token_id,
                                'device_type' => $input['device_type'],
                                'device_token' => $input['device_token'],
                            );
                            UserAuthMaster::where('device_token',$input['device_token'])->update($auth_input);
                        } 
                        // $userdata = User::where('phone',$input['phone'])->first();
                    $user_data = $this->UserResponse($userdata);
                    return $this->sendResponse($user_data, __('messages.api.user.register_success'));
                        }
                        else{
                            return $this->sendError(__('messages.api.user.invalid_phone'), config('global.null_object'),226,false); 
                        }
                    
                }
                else{

                    $user = User::where('phone',$input['phone'])->first();

                    if($user)
                    {

                        if($user->login_type != $input['login_type'] ){

                            $type='';
                            if($user->login_type == 2){
                                $type = 'User';
                            }
                            elseif($user->login_type == 3){
                                $type = 'NGO';
                            }
                            elseif($user->login_type == 4){
                                $type = 'Blood Bank';
                            }

                            $msg = 'User Phone No. already exist in '. $type;
                            return $this->sendError($msg, config('global.null_object'),226,false);
                        }
                        else{

                        
                            // echo 'if';die;
                            User::where('phone',$input['phone'])->update(['otp' => $otp]);
                            $userData = User::where('id',$user->id)->first();
                            $sms_data = $this->SentMobileVerificationCode($phone,$otp);
                            if($sms_data['flag'] == 'success')
                            {
                                $token = $user->createToken(env('APP_NAME'));
                                $userData->token = $token->accessToken;
                                $oauth_access_token_id = $token->token->id;
                                
                                $getauth_data = UserAuthMaster::where('device_token',$input['device_token'])->first();

                                if(!$getauth_data)
                                {
                                    // echo 'if';die;
                                    $user_auth_id = $this->GenerateUniqueRandomString($table='user_auth_master', $column="user_auth_id", $chars=6);
                                    $auth_input = array(
                                        'user_auth_id'      => $user_auth_id,
                                        'user_id' => $userData->id,
                                        'oauth_access_token_id' => $oauth_access_token_id,
                                        'device_type'  => $input['device_type'],
                                        'device_token' => $input['device_token'],
                                    );
                                    $user_auth_token = UserAuthMaster::create($auth_input);
                                }
                                else
                                {
                                    // echo 'else';die;
                                    $auth_input = array(
                                        'oauth_access_token_id' => $oauth_access_token_id,
                                        'device_type' => $input['device_type'],
                                        'device_token' => $input['device_token'],
                                    );
                                    UserAuthMaster::where('device_token',$input['device_token'])->update($auth_input);
                                } 
                                        
                                $user_data = $this->UserResponse($userData);
                                return $this->sendResponse($user_data, __('messages.api.user.register_success'));
                            }
                            else
                            {
                                return $this->sendError(__('messages.api.user.invalid_phone'), config('global.null_object'),226,false);
                            }
                        }
                    
                    }
                    else{
                    
                        // echo 'else';die;
                        $sms_data = $this->SentMobileVerificationCode($phone,$otp);

                        if($sms_data['flag'] == 'success')
                        {
                            $input['otp'] = $otp;
                            if($request->password !=''){
                                $input['password'] = Hash::make($request->password);
                            }
                            if($input['login_type']==2){
                                $input['is_approved'] = 1;
                            }
                            
                            $userData = User::create($input);   
                            $token = $userData->createToken(env('APP_NAME'));
                            $userData->token = $token->accessToken;
                            $oauth_access_token_id = $token->token->id;
                            $user_auth_id = $this->GenerateUniqueRandomString($table='user_auth_master', $column="user_auth_id", $chars=6);
                            $auth_input = array(
                                'user_auth_id'      => $user_auth_id,
                                'user_id'           => $userData->id,
                                'oauth_access_token_id' => $oauth_access_token_id,
                                'device_type'  => $input['device_type'],
                                'device_token' => $input['device_token'],
                            );
                            $user_auth_token = UserAuthMaster::create($auth_input);
                            if($input['login_type']==3){
                                $ngo_id = $this->GenerateUniqueRandomString($table='ngos', $column="ngo_id", $chars=6);

                                $inputngo['ngo_id']   = $ngo_id;
                                $inputngo['user_id']  = $userData->id;  

                                Ngo::create($inputngo);
                            }
                            $user_data = $this->UserResponse($userData);
                            return $this->sendResponse($user_data, __('messages.api.user.register_success'));
                        }
                        else
                        {
                            return $this->sendError(__('messages.api.user.invalid_phone'), config('global.null_object'),226,false);
                        }                

                    }
                }
            
        }
        catch(\Exception $e)
        {
            $this->serviceLogError($service_name = 'Registration',$user_id = 0,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

   
    public function VerifyOtp(Request $request)
    {
        // echo 'in';die;
        try
        {
            if(Auth::guard('api')->check())
            {
                // echo 'if';die;
                $input = $request->all();
                $auth_user = Auth::guard('api')->user();                
                
                $user_otp = User::where('id',$auth_user->id)->where('is_disable',0)->first();

                if($user_otp){
                    User::where('id', $auth_user->id)->where('otp', $input['otp'])->update(['is_verified' => 1]);
                    $user = User::where('id',$auth_user->id)->where('is_disable',0)->first();
                    $token = $request->bearerToken();
                    $user->token = $token;
                    $user_data = $this->UserResponse($user);
                   
                    return $this->sendResponse($user_data, __('messages.api.user.user_login_success'));
                }
                else{
                    return $this->sendError(__('messages.api.user.invalid_otp'), config('global.null_object'),401,false);
                }
                                  
            }
            else
            {
                // echo 'else';die;
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'VerifyOtp',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
 
    public function GetUserProfile(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();
                $auth_user = Auth::guard('api')->user();
                
                $user = User::find($auth_user->id);
                $token = $request->bearerToken();
                $user->token = $token;
                $user_data = $this->UserResponse($user);
                
                return $this->sendResponse($user_data, __('messages.api.user.user_get_profile_success'));              
            }
            else
            {                
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'GetUserProfile',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    
    public function ProfileUpdate(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {
                $auth_user = Auth::guard('api')->user();
                //  echo '<pre>';
                //  print_r($auth_user);die;
                
                $input = $request->all();
                $rules = [
                    'email' => 'unique:users,email,'.$auth_user->id.',id',
                    'phone' => 'unique:users,phone,'.$auth_user->id.',id',
                ];
                
                // 'email' => 'required|string|email|max:255|unique:users,email,'.$user->id
                $messages = [
                    'email.unique'    => 'User already exist with this email. Try another.',
                    'phone.unique'     => 'User already exist with this Phone no. Try another.',
            
                ];
                // $validator = Validator::make($gymData, $rules);
                $validator = Validator::make($input, $rules,$messages);
                
                if (!$validator->fails())
                {
                    $userdata = User::where('phone',$input['phone'])->where('id',$auth_user->id)->where('is_disable',0)->first();
                    
                    $profileimg = '';
                    if($request->hasFile('profile_image'))
                    {  
                        $profileimg = $this->UploadImage($file = $request->profile_image,$path = config('global.file_path.user_profile'));
                        $input['imageurl'] = $profileimg;
                    }
                    else{
                        $input['imageurl'] = $userdata->imageurl;
                    }
                    
                    $user = User::find($auth_user->id);
                    $user->fill($input);
                    $user->save();
                    $token = $request->bearerToken();
                    $user->token = $token;
                    
                    $user_data = $this->UserResponse($user);
                    return $this->sendResponse($user_data, __('messages.api.user.profile_setup_success'));

                }
                else
                {
                    $errors = $validator->errors()->first();   
                    return $this->sendError($errors, config('global.null_object'),226,false);
                }
            }
            else
            {                
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
            
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'ProfileUpdate',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    


    public function Logout(Request $request)
    {
        // echo 'in';die;
        try
        {
            if (Auth::guard('api')->check()) {
                // Auth::user()->token()->revoke();
                $user = Auth::guard('api')->user()->token();
                // print_r($user);exit();
                $oauth_access_token_id = $user->id;
                // print_r($oauth_access_token_id);exit();
                $user_id = $user->user_id;
                UserAuthMaster::where('oauth_access_token_id',$oauth_access_token_id)->delete();
                // UserAuthMaster::where('oauth_access_token_id',$oauth_access_token_id)->update(['device_token'=>'']);
                $user->revoke();
                // print_r($user);exit();
                // Auth::guard('api')->user()->AauthAcessToken()->delete();
                return $this->sendResponse(config('global.null_object'), __('messages.api.logout'));
                // Auth::user()->AauthAcessToken()->delete();
            }
            else
            {
                return $this->sendError(__('messages.api.user.user_not_found'), config('global.null_object'),404,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'Logout',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    

    
 }
?>    