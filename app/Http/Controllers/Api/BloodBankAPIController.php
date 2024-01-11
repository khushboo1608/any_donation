<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseAPIController as BaseAPIController;
use App\Models\User;
use App\Models\RequestDetails;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\DashboardAPIController;
use Validator;
use Illuminate\Support\Str;

class BloodBankAPIController extends BaseAPIController
{
   
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

    public function bloodbankList(Request $request)
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
                $radius = 20; // 20 kilometers
           
                    $bloodbank_data = User::where('login_type', 4)
                    ->where('users.id', '!=', $auth_user->id)
                    ->where('is_disable', 0)->orderBy('created_at', 'desc')
                    ->get()->filter(function ($bloodbank) use ($latitude, $longitude, $radius) {
                            $distance =$this->calculateDistance(
                                $latitude,
                                $longitude,
                                $bloodbank->lat,
                                $bloodbank->long
                            );
                        $bloodbank->distance =round($distance,1);
                        return $distance <= $radius;
                    });

                // echo "<pre>";
                // print_r($ngo_data);die;
                $filter_data =  $this->bloodbankListResponse($bloodbank_data);                
                // $result = $this->ResponseWithPagination($page,$filter_data);
                return $this->sendResponselist($page,$filter_data, __('messages.api.bloodbank.blood_bank_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'bloodbankList',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function bloodbanksingleDetails(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();  
                $latitude = $request->latitude;
                $longitude = $request->longitude; 
                $auth_user = Auth::guard('api')->user();           
                              
                $bloodbankData = User::where('id', $input['user_id'])->where('is_disable',0)->first();
             
                if($bloodbankData !=''){
                    $distance =$this->calculateDistance(
                        $latitude,
                        $longitude,
                        $bloodbankData->lat,
                        $bloodbankData->long
                    );
                    $bloodbankData->distance =round($distance,1);
                    $Request = RequestDetails::where('user_id',$input['user_id'])->where('request_details_status',0)->get();
                    $Request->map(function($Request){
                        $Request->request_blood_group =($Request->request_blood_group) ? $Request->request_blood_group : '';

                        $Request->request_unit =($Request->request_unit) ? $Request->request_unit : '';
                        
                        
                        unset($Request->created_at);
                    unset($Request->updated_at);
                        });
                    $bloodbankData->requestData =$Request;
                }

                // echo "<pre>";
                // print_r($NgoData);die;
                $bloodbank_data = $this->bloodbankResponse($bloodbankData);
                return $this->sendResponse($bloodbank_data, __('messages.api.bloodbank.blood_bank_get_success'));               
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'bloodbanksingleDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function addbloodRequest(Request $request)
    {
        try
        {
            if(Auth::guard('api')->check())
            {              
                $auth_user = Auth::guard('api')->user();
                $input = $request->all();

                $request_details_id = $this->GenerateUniqueRandomString($table='request_details', $column="request_details_id", $chars=6);

                $input['request_details_id']   = $request_details_id;
                $input['user_id']    = $auth_user->id;  
                
                $requestDetails = RequestDetails::create($input);
                $request_data = $this->bloodrequestResponse($requestDetails);
                return $this->sendResponse($request_data, __('messages.api.request.request_added_success'));
                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'addbloodRequest',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function getbloodRequest(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                              
                // $page           = $input['page'];
                $request_data      = RequestDetails::where('user_id',$auth_user->id)->where('request_details_status',0)->orderBy('created_at','desc')->get();
                $data_request =  $this->bloodRequestListResponse($request_data);
                return $this->sendResponse($data_request, __('messages.api.request.request_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'getbloodRequest',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function nearbydonorList(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                           
                // echo $auth_user->id;die;
                $page           = $input['page'];
                    $donor_data = User::where('login_type', 2)
                    ->where('id', '!=', $auth_user->id)
                    ->where('is_interested',1)
                    ->where('is_disable', 0) 
                    ->where('blood_group',$input['blood_group'])             
                    ->orderBy('created_at', 'desc')
                    ->get();
                    $data_donor = $this->donorListResponse($donor_data);

                // $result = $this->ResponseWithPagination($page,$filter_data);
                // return $this->sendResponse($data_donor, __('messages.api.donor.donor_get_success'));  
                return $this->sendResponselist($page,$data_donor, __('messages.api.donor.donor_get_success'));               
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'nearbydonorList',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    
}
