<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseAPIController as BaseAPIController;
use App\Models\User;
use App\Models\Ngo;
use App\Models\Photos;
use App\Models\Videos;
use App\Models\ServiceNeeds;
use App\Models\SpecificNeeds;
use App\Models\MemberDetails;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\DashboardAPIController;
use Validator;
use Illuminate\Support\Str;

class NgoAPIController extends BaseAPIController
{
   
    public function addMember(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {              
                $auth_user = Auth::guard('api')->user();
                $input = $request->all();
                // echo "<pre>";
                //     print_r($input);die;
                if($request->member_image != "")
                {   
                    $member_image = $this->UploadImage($file = $request->member_image,$path = config('global.file_path.member_image'));
                }
                else{
                    $member_image ='';
                }
                $input['member_image'] = $member_image;

                    $member_details_id = $this->GenerateUniqueRandomString($table='member_details', $column="member_details_id", $chars=6);

                    $input['member_details_id']   = $member_details_id;
                    $input['user_id']               = $auth_user->id;  
                    
                    // echo "<pre>";
                    // print_r($input);die;
                    $MemberDetails = MemberDetails::create($input);
                    $member_details_data = $this->MemberDetailsResponse($MemberDetails);
                    return $this->sendResponse($member_details_data, __('messages.api.member.member_create_success'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'addMember',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function MemberList(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                              
                // $page           = $input['page'];
                $member_data      = MemberDetails::where('user_id',$auth_user->id)->where('member_details_status',0)->orderBy('created_at','desc')->get();
                $data_member =  $this->MemberDetailsListResponse($member_data);
                // $keys = array_column($data_member, 'created_at');
                // $result = $this->ResponseWithPagination($page,$data_member);
                return $this->sendResponse($data_member, __('messages.api.member.member_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'MemberList',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function addNgoOtherDetails(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {              
                $auth_user = Auth::guard('api')->user();
                $input = $request->all();

                if($request->hasFile('address_proof'))
                {    
                    $input['address_proof']= $this->UploadImage($file = $request->address_proof,$path = config('global.file_path.document_img'));
                }
                if($request->hasFile('jj_act'))
                {    
                    $input['jj_act']= $this->UploadImage($file = $request->jj_act,$path = config('global.file_path.document_img'));
                }
                
                if($request->hasFile('form_10_b'))
                {    
                    $input['form_10_b']= $this->UploadImage($file = $request->form_10_b,$path = config('global.file_path.document_img'));
                }
                if($request->hasFile('a12_certificate'))
                {    
                    $input['a12_certificate']= $this->UploadImage($file = $request->a12_certificate,$path = config('global.file_path.document_img'));
                }
                if($request->hasFile('cancelled_blank_cheque'))
                {    
                    $input['cancelled_blank_cheque']= $this->UploadImage($file = $request->cancelled_blank_cheque,$path = config('global.file_path.document_img'));
                }
                if($request->hasFile('pan_of_ngo'))
                {    
                    $input['pan_of_ngo']= $this->UploadImage($file = $request->pan_of_ngo,$path = config('global.file_path.document_img'));
                }
                if($request->hasFile('registration_certificate'))
                {    
                    $input['registration_certificate']= $this->UploadImage($file = $request->registration_certificate,$path = config('global.file_path.document_img'));
                }
                Ngo::where('user_id', $auth_user->id)->update($input);
                $NgoData = Ngo::rightJoin('users', 'ngos.user_id', '=', 'users.id')->where('user_id', $auth_user->id)->where('ngo_status',0)->first();

                // echo "<pre>";
                // print_r($NgoData);die;
                $Ngo_data = $this->ngoResponse($NgoData);
                return $this->sendResponse($Ngo_data, __('messages.api.ngodetails.ngo_details_added_success'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'addBankDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function getNgoOtherDetails(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {              
                $auth_user = Auth::guard('api')->user();
                $NgoData = Ngo::rightJoin('users', 'ngos.user_id', '=', 'users.id')->where('user_id', $auth_user->id)->where('ngo_status',0)->first();
                $Ngo_data = $this->ngoResponse($NgoData);
                return $this->sendResponse($Ngo_data, __('messages.api.ngodetails.ngo_details_get_success'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'getBankDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function addPhotos(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {              
                $auth_user = Auth::guard('api')->user();
                $input = $request->all();
                // echo "<pre>";
                //     print_r($input);die;
                if($request->photo_url != "")
                {   
                    $photo_url = $this->UploadImage($file = $request->photo_url,$path = config('global.file_path.photo_url'));
                }
                else{
                    $photo_url ='';
                }
                $input['photo_url'] = $photo_url;

                    $photo_id = $this->GenerateUniqueRandomString($table='photos', $column="photo_id", $chars=6);

                    $input['photo_id']   = $photo_id;
                    $input['user_id']    = $auth_user->id;  
                    
                    // echo "<pre>";
                    // print_r($input);die;
                    $PhotosDetails = Photos::create($input);
                    $photos_data = $this->PhotosResponse($PhotosDetails);
                    return $this->sendResponse($photos_data, __('messages.api.photos.photo_added_success'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'addPhotos',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function getPhotos(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                              
                // $page           = $input['page'];
                $member_data      = Photos::where('user_id',$auth_user->id)->where('photo_type',$input['photo_type'])->where('photo_status',0)->orderBy('created_at','desc')->get();
                $data_member =  $this->PhotosListResponse($member_data);
                // $keys = array_column($data_member, 'created_at');
                // $result = $this->ResponseWithPagination($page,$data_member);
                return $this->sendResponse($data_member, __('messages.api.photos.photo_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'getPhotos',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function getPhotosofngo(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                              
                // $page           = $input['page'];
                $member_data      = Photos::where('user_id',$input['user_id'])->where('photo_type',$input['photo_type'])->where('photo_status',0)->orderBy('created_at','desc')->get();
                $data_member =  $this->PhotosListResponse($member_data);
                // $keys = array_column($data_member, 'created_at');
                // $result = $this->ResponseWithPagination($page,$data_member);
                return $this->sendResponse($data_member, __('messages.api.photos.photo_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'getPhotosofngo',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function addVideos(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {              
                $auth_user = Auth::guard('api')->user();
                $input = $request->all();
                // echo "<pre>";
                //     print_r($input);die;
              

                    $video_id = $this->GenerateUniqueRandomString($table='videos', $column="video_id", $chars=6);

                    $input['video_id']   = $video_id;
                    $input['user_id']    = $auth_user->id;  
                    
                    // echo "<pre>";
                    // print_r($input);die;
                    $VideosDetails = Videos::create($input);
                    $video_data = $this->VideosResponse($VideosDetails);
                    return $this->sendResponse($video_data, __('messages.api.video.video_added_success'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'addVideos',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function getVideos(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                              
                // $page           = $input['page'];
                $video_data      = Videos::where('user_id',$auth_user->id)->where('video_type',$input['video_type'])->where('video_status',0)->orderBy('created_at','desc')->get();
                $data_video =  $this->VideosListResponse($video_data);
                // $keys = array_column($data_member, 'created_at');
                // $result = $this->ResponseWithPagination($page,$data_member);
                return $this->sendResponse($data_video, __('messages.api.video.video_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'getVideos',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function getVideosofngo(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                              
                // $page           = $input['page'];
                $video_data      = Videos::where('user_id',$input['user_id'])->where('video_type',$input['video_type'])->where('video_status',0)->orderBy('created_at','desc')->get();
                $data_video =  $this->VideosListResponse($video_data);
                // $keys = array_column($data_member, 'created_at');
                // $result = $this->ResponseWithPagination($page,$data_member);
                return $this->sendResponse($data_video, __('messages.api.video.video_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'getVideosofngo',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers 20km

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // Distance in kilometers

        return $distance;
    }

    public function ngoListFilter(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                           
                // echo $auth_user->id;die;
                $page           = $input['page'];                
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $search_word_type = $request->search_word_type;
                $search_word_specific_needs= $request->search_word_specific_needs;
                $search_word_service_needs = $request->search_word_service_needs;
                $radius = 20; // 20 kilometers
            
                if ($search_word_type || $search_word_specific_needs || $search_word_service_needs) {
                    $ngo_data = User::leftJoin('ngos', 'users.id', '=', 'ngos.user_id')
                    ->where('login_type', 3)
                    ->where('users.id', '!=', $auth_user->id)
                    ->where('is_disable', 0)
                    ->select('users.*', 'ngos.*') // Select columns from both tables if needed
                    ->orderBy('users.created_at', 'desc')
                    ->get()->filter(function ($ngo) use ($latitude, $longitude, $radius) {
                            $distance =$this->calculateDistance(
                                $latitude,
                                $longitude,
                                $ngo->lat,
                                $ngo->long
                            );
                        $ngo->distance =round($distance,1);
                        return $distance <= $radius;
                    });
                    // $members = MemberDetails::where('user_id',$input['user_id'])->where('member_details_status',0)->get();
                    // $members->map(function($members){
                    //     $members->member_name =($members->member_name) ? $members->member_name : '';
                    //     $members->member_image =($members->member_image) ? $this->GetImage($members->member_image,$path=config('global.file_path.member_image')) : '';
                    //     unset($members->created_at);
                    // unset($members->updated_at);
                    //     });
                    // $NgoData->memberData =$members;
                    $data_ngo = $this->ngoListResponse($ngo_data);
                    $filter_data = array_filter($data_ngo, function ($var) use ($search_word_type, $search_word_specific_needs, $search_word_service_needs) {
                        $search_words = explode(',', $search_word_type);
                        $search_specific_need = explode(',', $search_word_specific_needs);
                        $search_service_needs = explode(',', $search_word_service_needs);
                
                        // Check for matching values
                        $typeMatch = !$search_word_type || array_intersect($search_words, explode(',', $var['type_of_ngo']));
                        $specificNeedsMatch = !$search_word_specific_needs || array_intersect($search_specific_need, explode(',', $var['specific_needs_id']));
                        $serviceNeedsMatch = !$search_word_service_needs || array_intersect($search_service_needs, explode(',', $var['service_needs_id']));
                
                        return $typeMatch && $specificNeedsMatch && $serviceNeedsMatch;
                    });
                }
                else{
                        $ngo_data = User::leftJoin('ngos', 'users.id', '=', 'ngos.user_id')
                        ->where('login_type', 3)
                        ->where('users.id', '!=', $auth_user->id)
                        ->where('is_disable', 0)
                        ->select('users.*', 'ngos.*') // Select columns from both tables if needed
                        ->orderBy('users.created_at', 'desc')
                        ->get()->filter(function ($ngo) use ($latitude, $longitude, $radius) {
                                $distance =$this->calculateDistance(
                                    $latitude,
                                    $longitude,
                                    $ngo->lat,
                                    $ngo->long
                                );
                            $ngo->distance =round($distance,1);
                            return $distance <= $radius;
                        });

                    // echo "<pre>";
                    // print_r($ngo_data);die;
                    $filter_data =  $this->ngoListResponse($ngo_data);
                }
                // $result = $this->ResponseWithPagination($page,$filter_data);
                return $this->sendResponselist($page,$filter_data, __('messages.api.ngo.ngo_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'ngoListFilter',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function ngosingleDetails(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();  
                $latitude = $request->latitude;
                $longitude = $request->longitude; 
                $auth_user = Auth::guard('api')->user();           
                              
                $NgoData = Ngo::rightJoin('users', 'ngos.user_id', '=', 'users.id')->where('user_id', $input['user_id'])->where('ngo_status',0)->first();
               
                if($NgoData !=''){
                    $distance =$this->calculateDistance(
                        $latitude,
                        $longitude,
                        $NgoData->lat,
                        $NgoData->long
                    );
                    $NgoData->distance =round($distance,1);
                    $members = MemberDetails::where('user_id',$input['user_id'])->where('member_details_status',0)->get();
                    $members->map(function($members){
                        $members->member_name =($members->member_name) ? $members->member_name : '';
                        $members->member_image =($members->member_image) ? $this->GetImage($members->member_image,$path=config('global.file_path.member_image')) : '';
                        unset($members->created_at);
                    unset($members->updated_at);
                        });
                    $NgoData->memberData =$members;
                }

                // echo "<pre>";
                // print_r($NgoData);die;
                $Ngo_data = $this->ngoResponse($NgoData);
                return $this->sendResponse($Ngo_data, __('messages.api.ngodetails.ngo_details_get_success'));               
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'ngosingleDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    
    public function ngobankDetails(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();  
                $latitude = $request->latitude;
                $longitude = $request->longitude; 
                $auth_user = Auth::guard('api')->user();           
                              
                $NgoData = Ngo::rightJoin('users', 'ngos.user_id', '=', 'users.id')->where('user_id', $input['user_id'])->where('ngo_status',0)->first();
               
                               // echo "<pre>";
                // print_r($NgoData);die;
                $Ngo_data = $this->ngobankResponse($NgoData);
                return $this->sendResponse($Ngo_data, __('messages.api.ngodetails.ngo_details_get_success'));               
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'ngobankDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    
}
