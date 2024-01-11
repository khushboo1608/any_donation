<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Storage;
use DB;
use App\Models\User;
use App\Models\UserAuthMaster;
use App\Models\BookingDetails;
use App\Models\Booking;
use DateTime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function UploadImage($file,$path)
    {
        if($file)
        {  
            $fname = $file->getClientOriginalName();
            $image_name = time().'_'.$fname;

            // echo $path;die;
            $local_path = public_path($path);
            $file->move($local_path, $image_name);
            return $image_name;   
        }
        else
        {
            return '';
        }
    }
    
    public function UploadImageBase64($base64file,$path)
    {
        $data = $base64file;
        $image_name = time().'.png';
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        if($data)
        {      
            if(env('IMAGE_UPLOAD_PATH') == 'FOLDER')
            {   
                $local_path = public_path($path);
                file_put_contents($local_path.'/'.$image_name,$data);
                return $image_name;
            }
            else
            {
                return '';
            }
        }
        else
        {
            return '';
        }
    }
    
    public function GetImage($file_name,$path)
    {
        if($file_name != '')
        {
            if(file_exists(public_path($path.'/'.$file_name)))
			{
				return url('public').'/'.$path.'/'.$file_name; 
			}
			else
			{
                return '';
			}
        }
        else
        {
            return '';
        }
    }
    
    public static function GenerateUniqueRandomString($table, $column, $chars)
    {
        $unique = false;
        do{
            $randomStr = Str::random($chars);
            $match = DB::table($table)->where($column, $randomStr)->first();
            if($match)
            {
                continue;
            }
            $unique = true;
        }
        while(!$unique);
        return $randomStr;
    }
    
    public function SentMobileVerificationCode($mobile , $opt)
    {
        $YourAPIKey =env('SMS_API_KEY');
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        
         $url = "https://2factor.in/API/V1/$YourAPIKey/SMS/$mobile/$opt/sentotp" ;
        

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT =>$agent,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,                  
        ));
        $response = curl_exec($curl);
        $result = json_decode($response);

        $err = curl_error($curl);
        curl_close($curl);
        if($err)
        {
            return array('flag'=>'error','data'=>$err);
        }
        else
        {            
            return array('flag'=>'success','data'=>$result);
        }
    }

    public function UserResponse($response)
    {
        $imageurl = '';
        if(isset($response->imageurl))
        {   
            $imageurl = $this->GetImage($response->imageurl,$path=config('global.file_path.user_profile'));
        }
        $data = [
       
            'user_id'           => (int)$response->id,
            'login_type'         => (isset($response->login_type) && $response->login_type != null) ? $response->login_type : '',
            'email'             => (isset($response->email) && $response->email != null) ? $response->email : '',
            'name'        => (isset($response->name) && $response->name != null) ? $response->name : '',
            'phone'             => (isset($response->phone) && $response->phone != null) ? $response->phone : '',
            'otp'             => (isset($response->otp) && $response->otp != null) ? $response->otp :0,                    
            'is_verified'             => (isset($response->is_verified) && $response->is_verified != null) ? $response->is_verified : 0,

            'address'             => (isset($response->address) && $response->address != null) ? $response->address : '',
            'lat'             => (isset($response->lat) && $response->lat != null) ? $response->lat : 0,
            'long'             => (isset($response->long) && $response->long != null) ? $response->long : 0,
            'age'             => (isset($response->age) && $response->age != null) ? $response->age : 0,
            'gender'             => (isset($response->gender) && $response->gender != null) ? $response->gender : 0,
            'state_id'             => (isset($response->state_id) && $response->state_id != null) ? $response->state_id : 0,
            'city_id'             => (isset($response->city_id) && $response->city_id != null) ? $response->city_id : 0,
            'imageurl'     => $imageurl,
            'Authorization'     => (isset($response->token) &&$response->token != null) ? 'Bearer '.$response->token : '',

            'blood_group'             => (isset($response->blood_group) && $response->blood_group != null) ? $response->blood_group : '',
            'is_interested'        => (isset($response->is_interested) && $response->is_interested != null) ? $response->is_interested : 0,
            'type_of_ngo'             => (isset($response->type_of_ngo) && $response->type_of_ngo != null) ? $response->type_of_ngo : '',
            'type_of_blood_bank'        => (isset($response->type_of_blood_bank) && $response->type_of_blood_bank != null) ? $response->type_of_blood_bank :'',
            'blood_bank_history'        => (isset($response->blood_bank_history) && $response->blood_bank_history != null) ? $response->blood_bank_history : '',
            'is_disable'=> (isset($response->is_disable) && $response->is_disable != null) ? $response->is_disable : 0,
            'is_approved'=> (isset($response->is_approved) && $response->is_approved != null) ? $response->is_approved : 0,
            
        ];
        return $data;
    }

    public function RemoveImage($name,$path)
    {        
        $file = $path.'/'.$name;
        if($name !=''){
            Storage::disk('public')->delete($file);
        }
        
    }

    public function generateReferralCode()
    {
        do {
            $code = random_int(10000000, 99999999);
        } while (User::where("referral_code", "=", $code)->first());
  
        return $code;
    }
    public function ResponseWithPagination($page,$data)
    {
        $per_page = env('PER_PAGE');
        $current_page = ($page == 0) ? 1 : $page;
        // $response['total_page'] = round(count($data)/10,2);
        $response['total_record'] = count($data);
        $response['data_list'] = array_slice($data , ($current_page * $per_page) - $per_page, $per_page);
        return $response;
    }

    public function ResponseWithPaginationorder($page,$data,$message)
    {
        $per_page = env('PER_PAGE');
        $current_page = ($page == 0) ? 1 : $page;
        // $response['total_page'] = round(count($data)/10,2);
        $response['total_record'] = count($data);
        $response['data_list'] = array_slice($data , ($current_page * $per_page) - $per_page, $per_page);
        $response['success'] = true;
        $response['message'] = $message;
        return $response;
    }
         
    public function getLocalTime($str, $timezone) {
        $datetime = date("Y-m-d H:i:s" , $str);
        $given = new \DateTime($datetime, new \DateTimeZone("UTC"));
        $given->setTimezone(new \DateTimeZone($timezone));
        return $given->format("Y-m-d H:i:s");
    }
    
    public static function generateUniqueOrderId($table, $column)
    {
        $unique = false;
        do{
            $code = random_int(10000, 99990);
            $order_id = 'HWK-'.$code;
            $match = DB::table($table)->where($column, $order_id)->first();
            if($match)
            {
                continue;
            }
            $unique = true;
        }
        while(!$unique);
        return $order_id;
    }

    public function SettingResponse($response)
    {
        $app_logo = '';
        if(isset($response->app_logo))
        {   
            $app_logo = $this->GetImage($response->app_logo,$path=config('global.file_path.app_logo'));
        } 
        $app_upi_image = '';
        if(isset($response->app_upi_image))
        {   
            $app_upi_image = $this->GetImage($response->app_upi_image,$path=config('global.file_path.app_upi_image'));
        } 
      

        $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
         $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata'); 
        $data = [
            'setting_id'           => ($response->setting_id) ? $response->setting_id : '',
            'email_from'           => ($response->email_from) ? $response->email_from : '',  
            'firebase_server_key'           => ($response->firebase_server_key) ? $response->firebase_server_key : '',  
            'app_logo'  =>$app_logo,    
            'onesignal_app_id'        => ($response->onesignal_app_id) ? $response->onesignal_app_id : '',
            'onesignal_rest_key'        => ($response->onesignal_rest_key) ? $response->onesignal_rest_key : '',

            'app_name'        => ($response->app_name) ? $response->app_name : '',
            'app_email'        => ($response->app_email) ? $response->app_email : '',
            'app_author'        => ($response->app_author) ? $response->app_author : '',
            'app_contact'        => ($response->app_contact) ? $response->app_contact : '',
            'app_website'        => ($response->app_website) ? $response->app_website : '',
            'app_description'        => ($response->app_description) ? $response->app_description : '',
            'app_developed_by'        => ($response->app_developed_by) ? $response->app_developed_by : '',

            'app_privacy_policy'        => ($response->app_privacy_policy) ? $response->app_privacy_policy : '',
            'app_terms_condition'        => ($response->app_terms_condition) ? $response->app_terms_condition : '',
            'app_cancellation_refund'        => ($response->app_cancellation_refund) ? $response->app_cancellation_refund : '',
            'app_about_us'        => ($response->app_about_us) ? $response->app_about_us : '',

            'app_contact_us'        => ($response->app_contact_us) ? $response->app_contact_us : '',
            'agent_onboard_commission'        => (int)($response->agent_onboard_commission) ? $response->agent_onboard_commission : 0,

            'agent_approve_commission'        => (int)($response->agent_approve_commission) ? $response->agent_approve_commission : '',
            'add_min_wallet_amount'        => (int)($response->add_min_wallet_amount) ? $response->add_min_wallet_amount : '',
            'contribution'        =>(int)($response->contribution) ? $response->contribution : '',
            'radius'        =>(int) ($response->radius) ? $response->radius : '',
            'reffer_earn_amount'        =>(int) ($response->reffer_earn_amount) ? $response->reffer_earn_amount : '',
            'app_version'        => ($response->app_version) ? $response->app_version : '',
            'app_update_status'        => ($response->app_update_status) ? $response->app_update_status : '',
            'app_maintenance_status'        => ($response->app_maintenance_status) ? $response->app_maintenance_status : '',
            'app_update_description'        => ($response->app_update_description) ? $response->app_update_description : '',

            'app_update_cancel_button'        => ($response->app_update_cancel_button) ? $response->app_update_cancel_button : '',
            'app_update_factor_button'        => ($response->app_update_factor_button) ? $response->app_update_factor_button : '',
            'factor_apikey'        => ($response->factor_apikey) ? $response->factor_apikey : '',
            'app_address'        => ($response->app_address) ? $response->app_address : '',
            'app_gstin'        => ($response->app_gstin) ? $response->app_gstin : '',

            
            'app_pan'        => ($response->app_pan) ? $response->app_pan : '',
            'app_bank_name'        => ($response->app_bank_name) ? $response->app_bank_name : '',
            'app_acount_no'        => ($response->app_acount_no) ? $response->app_acount_no : '',
            'app_ifsc'        => ($response->app_ifsc) ? $response->app_ifsc : '',
            'app_branch'        => ($response->app_branch) ? $response->app_branch : '',

            'app_upi_image'        => $app_upi_image,
            'app_notes_desc'        => ($response->app_notes_desc) ? $response->app_notes_desc : '',
            'map_api_key'        => ($response->map_api_key) ? $response->map_api_key : '',
            'razorpay_key'        => ($response->razorpay_key) ? $response->razorpay_key : '',
            'cash_on_delivery_available'        => ($response->cash_on_delivery_available) ? $response->cash_on_delivery_available : '',

            'gst_charge'        => ($response->gst_charge) ? $response->gst_charge : '',

            'app_facebook'        => ($response->app_facebook) ? $response->app_facebook : '',
            'app_youtube'        => ($response->app_youtube) ? $response->app_youtube : '',
            'app_twitter'        => ($response->app_twitter) ? $response->app_twitter : '',
            'app_instagram'        => ($response->app_instagram) ? $response->app_instagram : '',
            'app_whatsapp'        => ($response->app_whatsapp) ? $response->app_whatsapp : '',
            'app_linkedin'        => ($response->app_linkedin) ? $response->app_linkedin : '',


            'created_at'            => $created_at,
            'updated_at'            => $updated_at,
            // 'created_at'            => ($response->created_at) ? $response->created_at : '',
            'banner_status'            => ($response->is_disable) ? $response->is_disable:0,
        ];
        return $data;
    }
    public function SendIOSPushNotification($device_token_arr,$data)
    {
        try
        {
            $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
            $data = [
                "registration_ids" => $device_token_arr, // for multiple device ids
                "notification" => array(
                    "title"                     => $data['title'], 
                    "message"                   => $data['message'],
                    "receiver_id"               => $data['receiver_id'],
                    "conversation_id"           => ($data['conversation_id'] == '')? '' : $data['conversation_id'],
                    "notification_type"         => $data['notification_type'],
                    "receiver_firebase_uid"     => ($data['receiver_firebase_uid'] == '') ? 'test' : $data['receiver_firebase_uid'],
                    "receiver_name"             => $data['receiver_name'],
                    "receiver_imageurl"    => $data['receiver_imageurl']
                )
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }
        catch (\Exception $e)
        {
            echo $this->sendError($e->getMessage(), config('global.null_object'),401,false); die;
        }
    }
    
    public function SendAndroidPushNotification($device_token_arr,$data)
    {
        try {
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

            $data = [
                "registration_ids" => $device_token_arr, // for multiple device ids
                "notification" => array(
                    "title"                     => $data['title'], 
                    "message"                   => $data['message'],
                    "receiver_id"               => $data['receiver_id'],
                    "conversation_id"           => ($data['conversation_id'] == '')? '' : $data['conversation_id'],
                    "notification_type"         => $data['notification_type'],
                    "receiver_firebase_uid"     => ($data['receiver_firebase_uid'] == '') ? 'test' : $data['receiver_firebase_uid'],
                    "receiver_name"             => $data['receiver_name'],
                    "receiver_imageurl"    => $data['receiver_imageurl']
                )
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key='.env('FIREBASE_SERVER_KEY'),
                'Content-Type: application/json'
            ];    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function WebPushNotificationFirebase($DevicesToken,$Title, $Body,$Badge)
    {
        try {
            $data = [
                "to" => $DevicesToken,
                "data" => [
                    "badge" => $Badge +1,
                    "sound"=> "default"
                ],
                "notification" =>
                    [
                        "title" => $Title,
                        "body" => $Body,
                        "icon" => "http://3.14.184.45/public/images/Notification.png",
                        "sound"=> ""
                    ],
            ];

            $dataString = json_encode($data);
            
            $serverkey = env('FIREBASE_SERVER_KEY');
            $headers = [
                'Authorization: key='.$serverkey,
                'Content-Type: application/json',
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            return $response;
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function NotificationResponse($response)
    {
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {    
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');           
                $arr = array(
                    'notification_id'                => $value->notification_id,
                    'sender_id'                      => (int)$value->sender_id,
                    'receiver_id'                    => (int)$value->receiver_id,
                    'notification_title'             => (isset($value->notification_title) && $value->notification_title != null) ? $value->notification_title : '',
                    'notification_text'              => (isset($value->notification_text) && $value->notification_text != null) ? $value->notification_text : '',  
                    'notification_type'              => (int) $value->notification_type ,
                    'is_view'                        => $value->is_view,
                    'user_id'                        => (isset($value->user_id) && $value->user_id != null) ? $value->user_id : '',
                    'product_id'                     => (isset($value->product_id) && $value->product_id != null) ? $value->product_id : '',
                    'agent_id'                     => (isset($value->agent_id) && $value->agent_id != null) ? $value->agent_id : '',
                    'booking_id'                     => (isset($value->booking_id) && $value->booking_id != null) ? $value->booking_id : '',
                    'first_name'                     => (isset($value->first_name) && $value->first_name != null) ? $value->first_name : '',
                    'last_name'                      => (isset($value->last_name) && $value->last_name != null) ? $value->last_name : '',
                    'imageurl'                  => $this->GetImage($value->imageurl,$path=config('global.file_path.user_profile')),
                    'is_disable'                     => $value->is_disable,
                    'created_at'            => $created_at,
                );
                array_push($data,$arr);
            }
        }
        return $data;
    }
    public function GetStateData($response)
    {
        $data2 = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            { 

                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $arr = [
                    'state_id'           => ($value->state_id) ? $value->state_id   : '',
                    'state_name'           => ($value->state_name) ? $value->state_name   : '',
                    'created_at'            => $created_at,
                ];
                array_push($data2,$arr);
            }
        }
        return $data2;
    }
    public function GetCityListData($response)
    {
        $data2 = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            { 

                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $arr = [
                    'city_id' =>($value->city_id) ? $value->city_id   : '',
                    'city_name' =>($value->city_name) ? $value->city_name   : '',
                    'state_id'           => ($value->state_id) ? $value->state_id   : '',
                    'state_name'           => ($value->stateData->state_name) ? $value->stateData->state_name   : '',
                    'created_at'            => $created_at
                ];
                array_push($data2,$arr);
            }
        }
        return $data2;
    }
    public function GetBannerData($response)
    {
        $data2 = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            { 
                
                $banner_image = $this->GetImage($file_name = $value->banner_image,$path=config('global.file_path.banner_image'));

                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $arr = [
                    'banner_id'           => ($value->banner_id) ? $value->banner_id   : '',
                    'banner_image'  =>$banner_image,
                    'is_clickable'=>($value->is_clickable) ? $value->is_clickable : 0,
                    'created_at'            => $created_at,
                    'banner_status'            => ($value->banner_status) ? $value->banner_status:0,
                ];
                array_push($data2,$arr);
            }
        }
        return $data2;
    }
    public function CategoryListResponse($response)
    {
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            { 
                $category_image = '';
                if(isset($value->category_image))
                {   
                    $category_image = $this->GetImage($value->category_image,$path=config('global.file_path.category_image'));
                } 
        
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata'); 

                $arr = [
                    'category_id'           => ($value->category_id ) ? $value->category_id  : '',
                    'category_image'  =>$category_image,    
                    'category_name'        => ($value->category_name) ? $value->category_name : '',
                    'created_at'            => $created_at,
                    'updated_at'            => $updated_at,
                    'category_status'            => ($value->category_status) ? $value->category_status:0,
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    public function MemberDetailsResponse($response)
    {
        $member_image = '';
        if(isset($response->member_image))
        {   
            $member_image = $this->GetImage($response->member_image,$path=config('global.file_path.member_image'));
        } 
        // echo $member_image;die;
        
        $user_image = '';
        if(isset($response->User->imageurl))
        {   
            $user_image = $this->GetImage($response->User->imageurl,$path=config('global.file_path.user_profile'));
        } 

        $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
         $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata'); 
        $data = [
            'member_details_id '           => ($response->member_details_id ) ? $response->member_details_id  : '',
            'user_id'           => ($response->user_id) ? $response->user_id : '',  
            'name'           => ($response->User->name) ? $response->User->name : '',  
            'user_image'  =>$user_image,    
            'member_image'  =>$member_image,
            'member_name'        => (isset($response->member_name) && $response->member_name != null) ? $response->member_name : '',
            'member_phone_number'        => (isset($response->member_phone_number) && $response->member_phone_number != null) ? $response->member_phone_number : '',
            
            'member_position'           => ($response->member_position) ? $response->member_position : '', 
            'created_at'            => $created_at,
            'updated_at'            => $updated_at,
            // 'created_at'            => ($response->created_at) ? $response->created_at : '',
            'member_details_status'            => ($response->member_details_status) ? $response->member_details_status:0,
        ];
        return $data;
    }
    
    public function MemberDetailsListResponse($response)
    {
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {
                $member_image = '';
                if(isset($value->member_image))
                {   
                    $member_image = $this->GetImage($value->member_image,$path=config('global.file_path.member_image'));
                }  
                $profile_image = '';
                if(isset($value->User->imageurl))
                {   
                    $profile_image = $this->GetImage($value->User->imageurl,$path=config('global.file_path.user_profile'));
                }  
                
                
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $arr = [
                    'member_details_id'           => ($value->member_details_id) ? $value->member_details_id : '',
                    'user_id'           => ($value->user_id) ? $value->user_id : '',  
                    'name'           => ($value->User->name) ? $value->User->name : '',  
                    'user_image'  =>$profile_image,  
                    'member_image'  =>$member_image,
                    'member_name'        => (isset($value->member_name) && $value->member_name != null) ? $value->member_name : '',
                    'member_phone_number'        => (isset($value->member_phone_number) && $value->member_phone_number != null) ? $value->member_phone_number : '',
                    'created_at'            => $created_at,
                    'updated_at'            => $updated_at,
                    'member_position'           => ($value->member_position) ? $value->member_position : '', 
                    // 'created_at'            => ($value->created_at) ? $value->created_at : '',
                    'member_details_status'            => ($value->member_details_status) ? $value->member_details_status:0,
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }
    
    public function ngoResponse($response)
    {
        $imageurl = '';
        if(isset($response->imageurl))
        {   
            $imageurl = $this->GetImage($response->imageurl,$path=config('global.file_path.user_profile'));
        }
        $address_proof = '';  
        if(isset($response->address_proof))
        {   
            $address_proof = $this->GetImage($response->address_proof,$path=config('global.file_path.document_img'));
        }  
        $jj_act = '';  
        if(isset($response->jj_act))
        {   
            $jj_act = $this->GetImage($response->jj_act,$path=config('global.file_path.document_img'));
        }  
        $form_10_b = '';  
        if(isset($response->form_10_b))
        {   
            $form_10_b = $this->GetImage($response->form_10_b,$path=config('global.file_path.document_img'));
        }
        $a12_certificate ='';
        if(isset($response->a12_certificate))
        {   
            $a12_certificate = $this->GetImage($response->a12_certificate,$path=config('global.file_path.document_img'));
        }

        $cancelled_blank_cheque ='';
        if(isset($response->cancelled_blank_cheque))
        {   
            $cancelled_blank_cheque = $this->GetImage($response->cancelled_blank_cheque,$path=config('global.file_path.document_img'));
        }

        $pan_of_ngo ='';
        if(isset($response->pan_of_ngo))
        {   
            $pan_of_ngo = $this->GetImage($response->pan_of_ngo,$path=config('global.file_path.document_img'));
        }

        $registration_certificate ='';
        if(isset($response->registration_certificate))
        {   
            $registration_certificate = $this->GetImage($response->registration_certificate,$path=config('global.file_path.document_img'));
        }

        $distance = isset($response->distance) ? $response->distance : 0.0;
        $data = [
       
            'ngo_id'           => $response->ngo_id,
            'user_id'           => (int)$response->user_id,
            'name'        => (isset($response->name) && $response->name != null) ? $response->name : '',
            'phone'           => (isset($response->phone) && $response->phone != null) ? $response->phone : '',
            'email'           => (isset($response->email ) && $response->email  != null) ? $response->email : '',
            'address'           => (isset($response->address ) && $response->address  != null) ? $response->address : '',
            'lat'             => (isset($response->lat) && $response->lat != null) ? $response->lat : 0,
            'long'             => (isset($response->long) && $response->long != null) ? $response->long : 0,
            'distance'  => $distance,
            'imageurl' =>  $imageurl,
            'type_of_ngo'         => (isset($response->type_of_ngo) && $response->type_of_ngo != null) ? $response->type_of_ngo : '',
            'account_number'         => (isset($response->account_number) && $response->account_number != null) ? $response->account_number : '',
            'account_holder_name'             => (isset($response->account_holder_name) && $response->account_holder_name != null) ? $response->account_holder_name : '',
            'ifsc_code'        => (isset($response->ifsc_code) && $response->ifsc_code != null) ? $response->ifsc_code : '',
            'upi_number'             => (isset($response->upi_number) && $response->upi_number != null) ? $response->upi_number : '',
            'gpay_number'             => (isset($response->gpay_number) && $response->gpay_number != null) ? $response->gpay_number :'',                    
            'address_proof'             => $address_proof,
            'jj_act'             => $jj_act,
            'form_10_b'             => $form_10_b,
            '12a_certificate'             => $a12_certificate,
            'cancelled_blank_cheque'             => $cancelled_blank_cheque,
            'pan_of_ngo'             => $pan_of_ngo,
            'registration_certificate'             => $registration_certificate,
            'achievements'             => (isset($response->achievements) && $response->achievements != null) ? $response->achievements : '',
            'history'     => (isset($response->history) &&$response->history != null) ? $response->history : '',

            'purpose'             => (isset($response->purpose) && $response->purpose != null) ? $response->purpose : '',
            'started_in'        => (isset($response->started_in) && $response->started_in != null) ? $response->started_in : '',
            'size'             => (isset($response->size) && $response->size != null) ? $response->size : '',
            'specific_needs_id'        => (isset($response->specific_needs_id) && $response->specific_needs_id != null) ? $response->specific_needs_id : '',
            'service_needs_id'        => (isset($response->service_needs_id) && $response->service_needs_id != null) ? $response->service_needs_id : '',
            'memberData'              => ($response->memberData) ? $response->memberData:[], 
            'ngo_status'=> (isset($response->ngo_status) && $response->ngo_status != null) ? $response->ngo_status : 0,
        ];
        return $data;
    }
   
    public function ngoListResponse($response)
    {
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {
                $imageurl = '';
                if(isset($value->imageurl))
                {   
                    $imageurl = $this->GetImage($value->imageurl,$path=config('global.file_path.user_profile'));
                }

                $address_proof = '';  
                if(isset($value->address_proof))
                {   
                    $address_proof = $this->GetImage($value->address_proof,$path=config('global.file_path.document_img'));
                }  
                $jj_act = '';  
                if(isset($value->jj_act))
                {   
                    $jj_act = $this->GetImage($value->jj_act,$path=config('global.file_path.document_img'));
                }  
                $form_10_b = '';  
                if(isset($value->form_10_b))
                {   
                    $form_10_b = $this->GetImage($value->form_10_b,$path=config('global.file_path.document_img'));
                }
                $a12_certificate ='';
                if(isset($value->a12_certificate))
                {   
                    $a12_certificate = $this->GetImage($value->a12_certificate,$path=config('global.file_path.document_img'));
                }

                $cancelled_blank_cheque ='';
                if(isset($value->cancelled_blank_cheque))
                {   
                    $cancelled_blank_cheque = $this->GetImage($value->cancelled_blank_cheque,$path=config('global.file_path.document_img'));
                }

                $pan_of_ngo ='';
                if(isset($value->pan_of_ngo))
                {   
                    $pan_of_ngo = $this->GetImage($value->pan_of_ngo,$path=config('global.file_path.document_img'));
                }

                $registration_certificate ='';
                if(isset($value->registration_certificate))
                {   
                    $registration_certificate = $this->GetImage($value->registration_certificate,$path=config('global.file_path.document_img'));
                }
                
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $distance = isset($value->distance) ? $value->distance : 0.0;
                $distance = number_format($distance, 1);
              
                $arr = [
       
                    'ngo_id'           => $value->ngo_id,
                    'user_id'           => (int)$value->user_id,
                    'name'        => (isset($value->name) && $value->name != null) ? $value->name : '',
                    'phone'           => (isset($value->phone) && $value->phone != null) ? $value->phone : '',
                    'email'           => (isset($value->email ) && $value->email  != null) ? $value->email : '',
                    'address'           => (isset($value->address ) && $value->address  != null) ? $value->address : '',
                    'lat'             => (isset($value->lat) && $value->lat != null) ? $value->lat : 0,
                    'long'             => (isset($value->long) && $value->long != null) ? $value->long : 0,
                    'imageurl' =>  $imageurl,
                    'type_of_ngo'         => (isset($value->type_of_ngo) && $value->type_of_ngo != null) ? $value->type_of_ngo : '',
                    'account_number'         => (isset($value->account_number) && $value->account_number != null) ? $value->account_number : '',
                    'account_holder_name'             => (isset($value->account_holder_name) && $value->account_holder_name != null) ? $value->account_holder_name : '',
                    'ifsc_code'        => (isset($value->ifsc_code) && $value->ifsc_code != null) ? $value->ifsc_code : '',
                    'upi_number'             => (isset($value->upi_number) && $value->upi_number != null) ? $value->upi_number : '',
                    'gpay_number'             => (isset($value->gpay_number) && $value->gpay_number != null) ? $value->gpay_number :'',                    
                    'address_proof'             => $address_proof,
                    'distance'  =>  $distance,
                    'jj_act'             => $jj_act,
                    'form_10_b'             => $form_10_b,
                    '12a_certificate'             => $a12_certificate,
                    'cancelled_blank_cheque'             => $cancelled_blank_cheque,
                    'pan_of_ngo'             => $pan_of_ngo,
                    'registration_certificate'             => $registration_certificate,
                    'achievements'             => (isset($value->achievements) && $value->achievements != null) ? $value->achievements : '',
                    'history'     => (isset($value->history) &&$value->history != null) ? $value->history : '',
        
                    'purpose'             => (isset($value->purpose) && $value->purpose != null) ? $value->purpose : '',
                    'started_in'        => (isset($value->started_in) && $value->started_in != null) ? $value->started_in : '',
                    'size'             => (isset($value->size) && $value->size != null) ? $value->size : '',
                    'specific_needs_id'        => (isset($value->specific_needs_id) && $value->specific_needs_id != null) ? $value->specific_needs_id : '',
                    'service_needs_id'        => (isset($value->service_needs_id) && $value->service_needs_id != null) ? $value->service_needs_id : '',
                    'memberData'              => ($value->memberData) ? $value->memberData:[], 
                    'ngo_status'=> (isset($value->ngo_status) && $value->ngo_status != null) ? $value->ngo_status : 0,
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    public function GetServiceNeedListResponse($response)
    {
        // echo "<pre>";
        // print_r($response); die;
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {
                $arr = [
                    'service_needs_id'           => ($value->service_needs_id) ? $value->service_needs_id : '',
                    'service_needs_name'        => (isset($value->service_needs_name) && $value->service_needs_name != null) ? $value->service_needs_name : '',
                    'service_needs_status'            => ($value->service_needs_status) ? $value->service_needs_status:0,
                ];
                array_push($data,$arr);
            }
        }

        // echo "<pre>";
        // print_r($data); die;
        return $data;
    }

    public function GetSpecificNeedListResponse($response)
    {
        // echo "<pre>";
        // print_r($response); die;
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {
                $arr = [
                    'specific_needs_id'           => ($value->specific_needs_id) ? $value->specific_needs_id : '',
                    'specific_needs_name'        => (isset($value->specific_needs_name) && $value->specific_needs_name != null) ? $value->specific_needs_name : '',
                    'specific_needs_status'            => ($value->specific_needs_status) ? $value->specific_needs_status:0,
                ];
                array_push($data,$arr);
            }
        }

        // echo "<pre>";
        // print_r($data); die;
        return $data;
    }

    
    public function PhotosResponse($response)
    {
        $photo_url = '';
        if(isset($response->photo_url))
        {   
            $photo_url = $this->GetImage($response->photo_url,$path=config('global.file_path.photo_url'));
        } 
        // echo $member_image;die;
        
        // $user_image = '';
        // if(isset($response->User->imageurl))
        // {   
        //     $user_image = $this->GetImage($response->User->imageurl,$path=config('global.file_path.user_profile'));
        // } 

        $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
         $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata'); 
        $data = [
            'photo_id'           => ($response->photo_id  ) ? $response->photo_id   : '',
            'user_id'           => ($response->user_id) ? $response->user_id : '',  
            'photo_name'           => ($response->photo_name) ? $response->photo_name : '',  
            'photo_url'  =>$photo_url, 
            
            'photo_type'           => ($response->photo_type) ? $response->photo_type : '', 
            'created_at'            => $created_at,
            'updated_at'            => $updated_at,
            // 'created_at'            => ($response->created_at) ? $response->created_at : '',
            'photo_status'            => ($response->photo_status) ? $response->photo_status:0,
        ];
        return $data;
    }
    
    public function PhotosListResponse($response)
    {
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {
                $photo_url = '';
                if(isset($value->photo_url))
                {   
                    $photo_url = $this->GetImage($value->photo_url,$path=config('global.file_path.photo_url'));
                }  
                
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $arr = [
                    'photo_id'           => ($value->photo_id  ) ? $value->photo_id   : '',
                    'user_id'           => ($value->user_id) ? $value->user_id : '',  
                    'photo_name'           => ($value->photo_name) ? $value->photo_name : '',  
                    'photo_url'  =>$photo_url, 
                    
                    'photo_type'           => ($value->photo_type) ? $value->photo_type : '', 
                    'created_at'            => $created_at,
                    'updated_at'            => $updated_at,
                    'photo_status'            => ($value->photo_status) ? $value->photo_status:0,
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    public function VideosResponse($response)
    {
       
        $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
         $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata'); 
        $data = [
            'video_id'           => ($response->video_id) ? $response->video_id: '',
            'user_id'           => ($response->user_id) ? $response->user_id : '',  
            'video_type'           => ($response->video_type) ? $response->video_type : '', 
            'video_url'           => ($response->video_url) ? $response->video_url : '', 
            'created_at'            => $created_at,
            'updated_at'            => $updated_at,
            'video_status'            => ($response->video_status) ? $response->video_status:0,
        ];
        return $data;
    }
    
    public function VideosListResponse($response)
    {
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {
                              
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $arr = [
                    'video_id'           => ($value->video_id) ? $value->video_id : '',
                    'user_id'           => ($value->user_id) ? $value->user_id : '',  
                    'video_type'           => ($value->video_type) ? $value->video_type : '',
                    'video_url'           => ($value->video_url) ? $value->video_url : '',  
                    'created_at'            => $created_at,
                    'updated_at'            => $updated_at,
                    'video_status'            => ($value->video_status) ? $value->video_status:0,
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    
    public function ngobankResponse($response)
    {

        $data = [
       
            'ngo_id'           => $response->ngo_id,
            'user_id'           => (int)$response->user_id,            
            'account_number'         => (isset($response->account_number) && $response->account_number != null) ? $response->account_number : '',
            'account_holder_name'             => (isset($response->account_holder_name) && $response->account_holder_name != null) ? $response->account_holder_name : '',
            'ifsc_code'        => (isset($response->ifsc_code) && $response->ifsc_code != null) ? $response->ifsc_code : '',
            'upi_number'             => (isset($response->upi_number) && $response->upi_number != null) ? $response->upi_number : '',
            'gpay_number'             => (isset($response->gpay_number) && $response->gpay_number != null) ? $response->gpay_number :'',           
            'ngo_status'=> (isset($response->ngo_status) && $response->ngo_status != null) ? $response->ngo_status : 0,
        ];
        return $data;
    }


    public function bloodbankResponse($response)
    {

       
        $imageurl = '';
        if(isset($response->imageurl))
        {   
            $imageurl = $this->GetImage($response->imageurl,$path=config('global.file_path.user_profile'));
        }
                        
        $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
        $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata');
        $distance = isset($response->distance) ? $response->distance : 0.0;
        $distance = number_format($distance, 1);
        $data = [

            'user_id'           => (int)$response->id,
            'login_type'         => (isset($response->login_type) && $response->login_type != null) ? $response->login_type : '',
            'email'             => (isset($response->email) && $response->email != null) ? $response->email : '',
            'name'        => (isset($response->name) && $response->name != null) ? $response->name : '',
            'phone'             => (isset($response->phone) && $response->phone != null) ? $response->phone : '',                  
            'distance'  =>  $distance,
            'address'             => (isset($response->address) && $response->address != null) ? $response->address : '',
            'lat'             => (isset($response->lat) && $response->lat != null) ? $response->lat : 0,
            'long'             => (isset($response->long) && $response->long != null) ? $response->long : 0,
            'age'             => (isset($response->age) && $response->age != null) ? $response->age : 0,
            'gender'             => (isset($response->gender) && $response->gender != null) ? $response->gender : 0,
            'state_id'             => (isset($response->state_id) && $response->state_id != null) ? $response->state_id : 0,
            'city_id'             => (isset($response->city_id) && $response->city_id != null) ? $response->city_id : 0,
            'imageurl'     => $imageurl,        
            'type_of_blood_bank'        => (isset($response->type_of_blood_bank) && $response->type_of_blood_bank != null) ? $response->type_of_blood_bank : '',
            'blood_bank_history'        => (isset($response->blood_bank_history) && $response->blood_bank_history != null) ? $response->blood_bank_history : '',
            'requestData'              => ($response->requestData) ? $response->requestData:[],
            'is_disable'=> (isset($response->is_disable) && $response->is_disable != null) ? $response->is_disable : 0,
            'is_approved'=> (isset($response->is_approved) && $response->is_approved != null) ? $response->is_approved : 0,
            
        ];
        return $data;
    }

    public function bloodbankListResponse($response)
    {

        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {

                $imageurl = '';
                if(isset($value->imageurl))
                {   
                    $imageurl = $this->GetImage($value->imageurl,$path=config('global.file_path.user_profile'));
                }
                              
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $distance = isset($value->distance) ? $value->distance : 0.0;
                $distance = number_format($distance, 1);
                $arr = [
       
                    'user_id'           => (int)$value->id,
                    'login_type'         => (isset($value->login_type) && $value->login_type != null) ? $value->login_type : '',
                    'email'             => (isset($value->email) && $value->email != null) ? $value->email : '',
                    'name'        => (isset($value->name) && $value->name != null) ? $value->name : '',
                    'phone'             => (isset($value->phone) && $value->phone != null) ? $value->phone : '',                  
                    'distance'  =>$distance,
                    'address'             => (isset($value->address) && $value->address != null) ? $value->address : '',
                    'lat'             => (isset($value->lat) && $value->lat != null) ? $value->lat : 0,
                    'long'             => (isset($value->long) && $value->long != null) ? $value->long : 0,
                    'age'             => (isset($value->age) && $value->age != null) ? $value->age : 0,
                    'gender'             => (isset($value->gender) && $value->gender != null) ? $value->gender : 0,
                    'state_id'             => (isset($value->state_id) && $value->state_id != null) ? $value->state_id : 0,
                    'city_id'             => (isset($value->city_id) && $value->city_id != null) ? $value->city_id : 0,
                    'imageurl'     => $imageurl,        
                    'type_of_blood_bank'        => (isset($value->type_of_blood_bank) && $value->type_of_blood_bank != null) ? $value->type_of_blood_bank : '',
                    'blood_bank_history'        => (isset($value->blood_bank_history) && $value->blood_bank_history != null) ? $value->blood_bank_history : '',
                    'requestData'              => ($value->requestData) ? $value->requestData:[],
                    'is_disable'=> (isset($value->is_disable) && $value->is_disable != null) ? $value->is_disable : 0,
                    'is_approved'=> (isset($value->is_approved) && $value->is_approved != null) ? $value->is_approved : 0,
                    
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }


    public function donorListResponse($response)
    {

        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {

                $imageurl = '';
                if(isset($value->imageurl))
                {   
                    $imageurl = $this->GetImage($value->imageurl,$path=config('global.file_path.user_profile'));
                }
                              
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');

                $distance = isset($value->distance) ? $value->distance : 0.0;
                $distance = number_format($distance, 1);
                

                $arr = [
       
                    'user_id'           => (int)$value->id,
                    'login_type'         => (isset($value->login_type) && $value->login_type != null) ? $value->login_type : '',
                    'email'             => (isset($value->email) && $value->email != null) ? $value->email : '',
                    'name'        => (isset($value->name) && $value->name != null) ? $value->name : '',
                    'phone'             => (isset($value->phone) && $value->phone != null) ? $value->phone : '',                  
                    'distance'  =>$distance,
                    'address'             => (isset($value->address) && $value->address != null) ? $value->address : '',
                    'lat'             => (isset($value->lat) && $value->lat != null) ? $value->lat : 0,
                    'long'             => (isset($value->long) && $value->long != null) ? $value->long : 0,
                  
                    'age'             => (isset($value->age) && $value->age != null) ? $value->age : 0,
                    'gender'             => (isset($value->gender) && $value->gender != null) ? $value->gender : 0,
                    'state_id'             => (isset($value->state_id) && $value->state_id != null) ? $value->state_id : 0,
                    'city_id'             => (isset($value->city_id) && $value->city_id != null) ? $value->city_id : 0,
                    'imageurl'     => $imageurl,
                    'blood_group'             => (isset($value->blood_group) && $value->blood_group != null) ? $value->blood_group : '',
                    'is_interested'        => (isset($value->is_interested) && $value->is_interested != null) ? $value->is_interested : 0,
                   
                    'is_disable'=> (isset($value->is_disable) && $value->is_disable != null) ? $value->is_disable : 0,
                    'is_approved'=> (isset($value->is_approved) && $value->is_approved != null) ? $value->is_approved : 0,
                    
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    
    public function eyedonationListResponse($response)
    {

        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {

                $imageurl = '';
                if(isset($value->eyedonation_image))
                {   
                    $imageurl = $this->GetImage($value->eyedonation_image,$path=config('global.file_path.eyedonation_image'));
                }
                              
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $distance = isset($value->distance) ? $value->distance : 0.0;
                $distance = number_format($distance, 1);
                $arr = [
       
                    'eyedonation_id'    => $value->eyedonation_id,
                    'eyedonation_title' => (isset($value->eyedonation_title) && $value->eyedonation_title != null) ? $value->eyedonation_title : '',
                    'eyedonation_type'         => (isset($value->eyedonation_type) && $value->eyedonation_type != null) ? $value->eyedonation_type : '',
                    'eyedonation_purpose'             => (isset($value->eyedonation_purpose) && $value->eyedonation_purpose != null) ? $value->eyedonation_purpose : '',
                    'eyedonation_achievement'        => (isset($value->eyedonation_achievement) && $value->eyedonation_achievement != null) ? $value->eyedonation_achievement : '',
                    'eyedonation_started_in'             => (isset($value->eyedonation_started_in) && $value->eyedonation_started_in != null) ? $value->eyedonation_started_in : '',                  
                    'distance'  =>$distance,
                    'eyedonation_size' => (isset($value->eyedonation_size) && $value->eyedonation_size != null) ? $value->eyedonation_size : '',
                    'service_needs_id' => (isset($value->service_needs_id) && $value->service_needs_id != null) ? $value->service_needs_id : '',
                    'specific_needs_id' => (isset($value->specific_needs_id) && $value->specific_needs_id != null) ? $value->specific_needs_id : '',
                    'eyedonation_number' => (isset($value->eyedonation_number) && $value->eyedonation_number != null) ? $value->eyedonation_number : '',
                    'eyedonation_email' => (isset($value->eyedonation_email) && $value->eyedonation_email != null) ? $value->eyedonation_email : '',

                    'eyedonation_address'             => (isset($value->eyedonation_address) && $value->eyedonation_address != null) ? $value->eyedonation_address : '',
                    'eyedonation_lat'             => (isset($value->eyedonation_lat) && $value->eyedonation_lat != null) ? $value->eyedonation_lat : 0,
                    'eyedonation_long'             => (isset($value->eyedonation_long) && $value->eyedonation_long != null) ? $value->eyedonation_long : 0,
                    'state_id'             => (isset($value->state_id) && $value->state_id != null) ? $value->state_id : 0,
                    'city_id'             => (isset($value->city_id) && $value->city_id != null) ? $value->city_id : 0,
                    'eyedonation_image'     => $imageurl,        
                    'eyedonation_history'        => (isset($value->eyedonation_history) && $value->eyedonation_history != null) ? $value->eyedonation_history : '',
                   
                    'eyedonation_status'=> (isset($value->eyedonation_status) && $value->eyedonation_status != null) ? $value->eyedonation_status : 0,
                    
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }
    public function eyedonationResponse($response)
    {

                $imageurl = '';
                if(isset($response->eyedonation_image))
                {   
                    $imageurl = $this->GetImage($response->eyedonation_image,$path=config('global.file_path.eyedonation_image'));
                }
                              
                $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata');
                $distance = isset($response->distance) ? $response->distance : 0.0;
                $distance = number_format($distance, 1);
                $data = [
       
                    'eyedonation_id'    => $response->eyedonation_id,
                    'eyedonation_title' => (isset($response->eyedonation_title) && $response->eyedonation_title != null) ? $response->eyedonation_title : '',
                    'eyedonation_type'         => (isset($response->eyedonation_type) && $response->eyedonation_type != null) ? $response->eyedonation_type : '',
                    'eyedonation_purpose'             => (isset($response->eyedonation_purpose) && $response->eyedonation_purpose != null) ? $response->eyedonation_purpose : '',
                    'eyedonation_achievement'        => (isset($response->eyedonation_achievement) && $response->eyedonation_achievement != null) ? $response->eyedonation_achievement : '',
                    'eyedonation_started_in'             => (isset($response->eyedonation_started_in) && $response->eyedonation_started_in != null) ? $response->eyedonation_started_in : '',                  
                    'distance'  =>$distance,
                    'eyedonation_size' => (isset($response->eyedonation_size) && $response->eyedonation_size != null) ? $response->eyedonation_size : '',
                    'service_needs_id' => (isset($response->service_needs_id) && $response->service_needs_id != null) ? $response->service_needs_id : '',
                    'specific_needs_id' => (isset($response->specific_needs_id) && $response->specific_needs_id != null) ? $response->specific_needs_id : '',
                    'eyedonation_number' => (isset($response->eyedonation_number) && $response->eyedonation_number != null) ? $response->eyedonation_number : '',
                    'eyedonation_email' => (isset($response->eyedonation_email) && $response->eyedonation_email != null) ? $response->eyedonation_email : '',

                    'eyedonation_address'             => (isset($response->eyedonation_address) && $response->eyedonation_address != null) ? $response->eyedonation_address : '',
                    'eyedonation_lat'             => (isset($response->eyedonation_lat) && $response->eyedonation_lat != null) ? $response->eyedonation_lat : 0,
                    'eyedonation_long'             => (isset($response->eyedonation_long) && $response->eyedonation_long != null) ? $response->eyedonation_long : 0,
                    'state_id'             => (isset($response->state_id) && $response->state_id != null) ? $response->state_id : 0,
                    'city_id'             => (isset($response->city_id) && $response->city_id != null) ? $response->city_id : 0,
                    'eyedonation_image'     => $imageurl,        
                    'eyedonation_history'        => (isset($response->eyedonation_history) && $response->eyedonation_history != null) ? $response->eyedonation_history : '',
                   
                    'eyedonation_status'=> (isset($response->eyedonation_status) && $response->eyedonation_status != null) ? $response->eyedonation_status : 0,
                    
                ];
        
        return $data;
    }
    
    public function eventpromotionListResponse($response)
    {

        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {

                $imageurl = '';
                if(isset($value->event_promotions_image))
                {   
                    $imageurl = $this->GetImage($value->event_promotions_image,$path=config('global.file_path.event_image'));
                }
                              
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $distance = isset($value->distance) ? $value->distance : 0.0;
                $distance = number_format($distance, 1);
                $arr = [
       
                    'event_promotions_id'    => $value->event_promotions_id,
                    'event_promotions_title' => (isset($value->event_promotions_title) && $value->event_promotions_title != null) ? $value->event_promotions_title : '',
                    'event_promotions_type'         => (isset($value->event_promotions_type) && $value->event_promotions_type != null) ? $value->event_promotions_type : '',
                    'event_promotions_purpose'             => (isset($value->event_promotions_purpose) && $value->event_promotions_purpose != null) ? $value->event_promotions_purpose : '',
                    'event_promotions_number'        => (isset($value->event_promotions_number) && $value->event_promotions_number != null) ? $value->event_promotions_number : '',
                    'event_promotions_email'             => (isset($value->event_promotions_email) && $value->event_promotions_email != null) ? $value->event_promotions_email : '',                  
                    'distance'  =>$distance,
                  
                    'event_promotions_address'             => (isset($value->event_promotions_address) && $value->event_promotions_address != null) ? $value->event_promotions_address : '',
                    'event_promotions_lat'             => (isset($value->event_promotions_lat) && $value->event_promotions_lat != null) ? $value->event_promotions_lat : 0,
                    'event_promotions_long'             => (isset($value->event_promotions_long) && $value->event_promotions_long != null) ? $value->event_promotions_long : 0,
                    'state_id'             => (isset($value->state_id) && $value->state_id != null) ? $value->state_id : 0,
                    'city_id'             => (isset($value->city_id) && $value->city_id != null) ? $value->city_id : 0,
                    'event_promotions_image'     => $imageurl,        
                    'event_promotions_history'        => (isset($value->event_promotions_history) && $value->event_promotions_history != null) ? $value->event_promotions_history : '',
                   
                    'event_promotions_status'=> (isset($value->event_promotions_status) && $value->event_promotions_status != null) ? $value->event_promotions_status : 0,
                    
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    public function eventpromotionResponse($response)
    {

                $imageurl = '';
                if(isset($response->event_promotions_image))
                {   
                    $imageurl = $this->GetImage($response->event_promotions_image,$path=config('global.file_path.event_image'));
                }
                              
                $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata');
                $distance = isset($response->distance) ? $response->distance : 0.0;
                $distance = number_format($distance, 1);
                $data = [
       
                    'event_promotions_id'    => $response->event_promotions_id,
                    'event_promotions_title' => (isset($response->event_promotions_title) && $response->event_promotions_title != null) ? $response->event_promotions_title : '',
                    'event_promotions_type'         => (isset($response->event_promotions_type) && $response->event_promotions_type != null) ? $response->event_promotions_type : '',
                    'event_promotions_purpose'             => (isset($response->event_promotions_purpose) && $response->event_promotions_purpose != null) ? $response->event_promotions_purpose : '',
                    'event_promotions_number'        => (isset($response->event_promotions_number) && $response->event_promotions_number != null) ? $response->event_promotions_number : '',
                    'event_promotions_email'             => (isset($response->event_promotions_email) && $response->event_promotions_email != null) ? $response->event_promotions_email : '',                  
                    'distance'  =>$distance,
                  
                    'event_promotions_address'             => (isset($response->event_promotions_address) && $response->event_promotions_address != null) ? $response->event_promotions_address : '',
                    'event_promotions_lat'             => (isset($response->event_promotions_lat) && $response->event_promotions_lat != null) ? $response->event_promotions_lat : 0,
                    'event_promotions_long'             => (isset($response->event_promotions_long) && $response->event_promotions_long != null) ? $response->event_promotions_long : 0,
                    'state_id'             => (isset($response->state_id) && $response->state_id != null) ? $response->state_id : 0,
                    'city_id'             => (isset($response->city_id) && $response->city_id != null) ? $response->city_id : 0,
                    'event_promotions_image'     => $imageurl,        
                    'event_promotions_history'        => (isset($response->event_promotions_history) && $response->event_promotions_history != null) ? $response->event_promotions_history : '',
                   
                    'event_promotions_status'=> (isset($response->event_promotions_status) && $response->event_promotions_status != null) ? $response->event_promotions_status : 0,
                    
                ];
            
        return $data;
    }
   

    public function crowdListResponse($response)
    {

        $data = [];
       
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {

                $imageurl = '';
                if(isset($value->crowdfundings_single_image))
                {   
                    $imageurl = $this->GetImage($value->crowdfundings_single_image,$path=config('global.file_path.crowd_image'));
                }
                   
               
                $patient1_image = '';
                if(isset($value->crowdfundings_patient1_image))
                {   
                    $patient1_image = $this->GetImage($value->crowdfundings_patient1_image,$path=config('global.file_path.crowd_image'));
                }
                
                $patient2_image = '';
                if(isset($value->crowdfundings_patient2_image))
                {   
                    $patient2_image = $this->GetImage($value->crowdfundings_patient2_image,$path=config('global.file_path.crowd_image'));
                }
                
                $medical_certificate = '';
                if(isset($value->crowdfundings_medical_certificate))
                {   
                    $medical_certificate = $this->GetImage($value->crowdfundings_medical_certificate,$path=config('global.file_path.crowd_image'));
                }

                $multi_images = [];
                if(isset($value->crowdfundings_multi_image))
                {  
                    $interior_images = explode(',',$value->crowdfundings_multi_image);
                    foreach ($interior_images as $key => $val) {
                        $multi_images[] = $this->GetImage($val,$path=config('global.file_path.crowd_image'));
                    }
                } 

                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');

                $distance = isset($value->distance) ? $value->distance : 0.0;
                $distance = number_format($distance, 1);
                
                $arr = [
       
                    'crowdfundings_id'   => $value->crowdfundings_id,
                    'crowdfundings_title'         => (isset($value->crowdfundings_title) && $value->crowdfundings_title != null) ? $value->crowdfundings_title : '',
                    'crowdfundings_type'             => (isset($value->crowdfundings_type) && $value->crowdfundings_type != null) ? $value->crowdfundings_type : '',
                    'crowdfundings_purpose'        => (isset($value->crowdfundings_purpose) && $value->crowdfundings_purpose != null) ? $value->crowdfundings_purpose : '',
                    'crowdfundings_issue'             => (isset($value->crowdfundings_issue) && $value->crowdfundings_issue != null) ? $value->crowdfundings_issue : '',                  
                    'distance'  =>$distance,
                    'crowdfundings_amount'             => (isset($value->crowdfundings_amount) && $value->crowdfundings_amount != null) ? $value->crowdfundings_amount : '',
                    'crowdfundings_patient1_name'             => (isset($value->crowdfundings_patient1_name) && $value->crowdfundings_patient1_name != null) ? $value->crowdfundings_patient1_name : '',
                    'crowdfundings_patient1_age'             => (isset($value->crowdfundings_patient1_age) && $value->crowdfundings_patient1_age != null) ? $value->crowdfundings_patient1_age : 0,
                  
                    'crowdfundings_patient1_image' => $patient1_image,
                    'crowdfundings_patient2_name' => (isset($value->crowdfundings_patient2_name) && $value->crowdfundings_patient2_name != null) ? $value->crowdfundings_patient2_name : '',
                    'crowdfundings_patient2_age'             => (isset($value->crowdfundings_patient2_age) && $value->crowdfundings_patient2_age != null) ? $value->crowdfundings_patient2_age : 0,
                    'crowdfundings_patient2_image'  => $patient2_image,
                    'crowdfundings_single_image'     => $imageurl,
                    'crowdfundings_multi_image' => $multi_images,
                    'crowdfundings_medical_certificate'  => $medical_certificate,
                    'crowdfundings_address'        => (isset($value->crowdfundings_address) && $value->crowdfundings_address != null) ? $value->crowdfundings_address :'',
                    'crowdfundings_lat'        => (isset($value->crowdfundings_lat) && $value->crowdfundings_lat != null) ? $value->crowdfundings_lat :'',
                    'crowdfundings_long'        => (isset($value->crowdfundings_long) && $value->crowdfundings_long != null) ? $value->crowdfundings_long :'',
                    'state_id'             => (isset($value->state_id) && $value->state_id != null) ? $value->state_id : 0,
                    'city_id'             => (isset($value->city_id) && $value->city_id != null) ? $value->city_id : 0,
                    'crowdfundings_account_number'        => (isset($value->crowdfundings_account_number) && $value->crowdfundings_account_number != null) ? $value->crowdfundings_account_number :'',
                    'crowdfundings_account_holder_name'        => (isset($value->crowdfundings_account_holder_name) && $value->crowdfundings_account_holder_name != null) ? $value->crowdfundings_account_holder_name :'',
                    'crowdfundings_ifsc_code'        => (isset($value->crowdfundings_ifsc_code) && $value->crowdfundings_ifsc_code != null) ? $value->crowdfundings_ifsc_code :'',
                    'crowdfundings_upi_number'             => (isset($value->crowdfundings_upi_number) && $value->crowdfundings_upi_number != null) ? $value->crowdfundings_upi_number : '',
                    'crowdfundings_gpay_number'             => (isset($value->crowdfundings_gpay_number) && $value->crowdfundings_gpay_number != null) ? $value->crowdfundings_gpay_number : '',
                   
                    'crowdfundings_status'=> (isset($value->crowdfundings_status) && $value->crowdfundings_status != null) ? $value->crowdfundings_status : 0,
                    
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    public function crowdResponse($response)
    {
                $imageurl = '';
                if(isset($response->crowdfundings_single_image))
                {   
                    $imageurl = $this->GetImage($response->crowdfundings_single_image,$path=config('global.file_path.crowd_image'));
                }
                   
               
                $patient1_image = '';
                if(isset($response->crowdfundings_patient1_image))
                {   
                    $patient1_image = $this->GetImage($response->crowdfundings_patient1_image,$path=config('global.file_path.crowd_image'));
                }
                
                $patient2_image = '';
                if(isset($response->crowdfundings_patient2_image))
                {   
                    $patient2_image = $this->GetImage($response->crowdfundings_patient2_image,$path=config('global.file_path.crowd_image'));
                }
                
                $medical_certificate = '';
                if(isset($response->crowdfundings_medical_certificate))
                {   
                    $medical_certificate = $this->GetImage($response->crowdfundings_medical_certificate,$path=config('global.file_path.crowd_image'));
                }

                $multi_images = [];
                if(isset($response->crowdfundings_multi_image))
                {  
                    $interior_images = explode(',',$response->crowdfundings_multi_image);
                    foreach ($interior_images as $key => $val) {
                        $multi_images[] = $this->GetImage($val,$path=config('global.file_path.crowd_image'));
                    }
                } 

                $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata');

                $distance = isset($response->distance) ? $response->distance : 0.0;
                $distance = number_format($distance, 1);
                
                $data = [
       
                    'crowdfundings_id'   => $response->crowdfundings_id,
                    'crowdfundings_title'         => (isset($response->crowdfundings_title) && $response->crowdfundings_title != null) ? $response->crowdfundings_title : '',
                    'crowdfundings_type'             => (isset($response->crowdfundings_type) && $response->crowdfundings_type != null) ? $response->crowdfundings_type : '',
                    'crowdfundings_purpose'        => (isset($response->crowdfundings_purpose) && $response->crowdfundings_purpose != null) ? $response->crowdfundings_purpose : '',
                    'crowdfundings_issue'             => (isset($response->crowdfundings_issue) && $response->crowdfundings_issue != null) ? $response->crowdfundings_issue : '',                  
                    'distance'  =>$distance,
                    'crowdfundings_amount'             => (isset($response->crowdfundings_amount) && $response->crowdfundings_amount != null) ? $response->crowdfundings_amount : '',
                    'crowdfundings_patient1_name'             => (isset($response->crowdfundings_patient1_name) && $response->crowdfundings_patient1_name != null) ? $response->crowdfundings_patient1_name : '',
                    'crowdfundings_patient1_age'             => (isset($response->crowdfundings_patient1_age) && $response->crowdfundings_patient1_age != null) ? $response->crowdfundings_patient1_age : 0,
                  
                    'crowdfundings_patient1_image' => $patient1_image,
                    'crowdfundings_patient2_name' => (isset($response->crowdfundings_patient2_name) && $response->crowdfundings_patient2_name != null) ? $response->crowdfundings_patient2_name : '',
                    'crowdfundings_patient2_age'             => (isset($response->crowdfundings_patient2_age) && $response->crowdfundings_patient2_age != null) ? $response->crowdfundings_patient2_age : 0,
                    'crowdfundings_patient2_image'  => $patient2_image,
                    'crowdfundings_single_image'     => $imageurl,
                    'crowdfundings_multi_image' => $multi_images,
                    'crowdfundings_medical_certificate'  => $medical_certificate,
                    'crowdfundings_address'        => (isset($response->crowdfundings_address) && $response->crowdfundings_address != null) ? $response->crowdfundings_address :'',
                    'crowdfundings_lat'        => (isset($response->crowdfundings_lat) && $response->crowdfundings_lat != null) ? $response->crowdfundings_lat :'',
                    'crowdfundings_long'        => (isset($response->crowdfundings_long) && $response->crowdfundings_long != null) ? $response->crowdfundings_long :'',
                    'state_id'             => (isset($response->state_id) && $response->state_id != null) ? $response->state_id : 0,
                    'city_id'             => (isset($response->city_id) && $response->city_id != null) ? $response->city_id : 0,
                    'crowdfundings_account_number'        => (isset($response->crowdfundings_account_number) && $response->crowdfundings_account_number != null) ? $response->crowdfundings_account_number :'',
                    'crowdfundings_account_holder_name'        => (isset($response->crowdfundings_account_holder_name) && $response->crowdfundings_account_holder_name != null) ? $response->crowdfundings_account_holder_name :'',
                    'crowdfundings_ifsc_code'        => (isset($response->crowdfundings_ifsc_code) && $response->crowdfundings_ifsc_code != null) ? $response->crowdfundings_ifsc_code :'',
                    'crowdfundings_upi_number'             => (isset($response->crowdfundings_upi_number) && $response->crowdfundings_upi_number != null) ? $response->crowdfundings_upi_number : '',
                    'crowdfundings_gpay_number'             => (isset($response->crowdfundings_gpay_number) && $response->crowdfundings_gpay_number != null) ? $response->crowdfundings_gpay_number : '',
                   
                    'crowdfundings_status'=> (isset($response->crowdfundings_status) && $response->crowdfundings_status != null) ? $response->crowdfundings_status : 0,
                    
                ];
        return $data;
    }

    public function bloodrequestResponse($response)
    {
         $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
         $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata'); 
        $data = [
            'request_details_id'           => ($response->request_details_id  ) ? $response->request_details_id   : '',
            'user_id'           => ($response->user_id) ? $response->user_id : '',  
            'request_blood_group'           => ($response->request_blood_group) ? $response->request_blood_group : '',  
            'request_unit'           => ($response->request_unit) ? $response->request_unit : '', 
            'created_at'            => $created_at,
            'updated_at'            => $updated_at,
            // 'created_at'            => ($response->created_at) ? $response->created_at : '',
            'request_details_status'            => ($response->request_details_status) ? $response->request_details_status:0,
        ];
        return $data;
    }


    public function bloodRequestListResponse($response)
    {
        $data = [];
        if(count($response) > 0)
        {
            foreach($response as $key=>$value)
            {
                
                $created_at =$this->getLocalTime(strtotime($value->created_at), 'Asia/Kolkata');
                $updated_at =  $this->getLocalTime(strtotime($value->updated_at), 'Asia/Kolkata');
                $arr = [
                    'request_details_id'           => ($value->request_details_id  ) ? $value->request_details_id   : '',
                    'user_id'           => ($value->user_id) ? $value->user_id : '',  
                    'request_blood_group'           => ($value->request_blood_group) ? $value->request_blood_group : '',  
                    'request_unit'           => ($value->request_unit) ? $value->request_unit : '', 
                    'created_at'            => $created_at,
                    'updated_at'            => $updated_at,
                    // 'created_at'            => ($value->created_at) ? $value->created_at : '',
                    'request_details_status'            => ($value->request_details_status) ? $value->request_details_status:0,
                ];
                array_push($data,$arr);
            }
        }
        return $data;
    }

    
}


