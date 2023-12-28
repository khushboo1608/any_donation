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
            'type_of_ngo'             => (isset($response->type_of_ngo) && $response->type_of_ngo != null) ? $response->type_of_ngo : 0,
            'type_of_blood_bank'        => (isset($response->type_of_blood_bank) && $response->type_of_blood_bank != null) ? $response->type_of_blood_bank : 0,
            'blood_bank_history'        => (isset($response->blood_bank_history) && $response->blood_bank_history != null) ? $response->blood_bank_history : '',
            'is_disable'=> (isset($response->is_disable) && $response->is_disable != null) ? $response->is_disable : 0,
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
        $user_image = '';
        if(isset($response->User->imageurl))
        {   
            $user_image = $this->GetImage($response->User->imageurl,$path=config('global.file_path.user_profile'));
        } 

        $created_at =$this->getLocalTime(strtotime($response->created_at), 'Asia/Kolkata');
         $updated_at =  $this->getLocalTime(strtotime($response->updated_at), 'Asia/Kolkata'); 
        $data = [
            'member_details_id '           => ($response->member_details_id ) ? $response->member_details_id  : '',
            'ngo_id'           => ($response->ngo_id) ? $response->ngo_id : '',  
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
                    'ngo_id'           => ($value->ngo_id) ? $value->ngo_id : '',  
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
}


