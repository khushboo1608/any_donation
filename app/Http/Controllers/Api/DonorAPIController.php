<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseAPIController as BaseAPIController;
use App\Models\User;
use App\Models\RequestDetails;
use App\Models\EyeDonation;
use App\Models\EventPromotion;
use App\Models\CrowdFunding;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\DashboardAPIController;
use Validator;
use Illuminate\Support\Str;

class DonorAPIController extends BaseAPIController
{
   
    public function donorListFilter(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                           
                // echo $auth_user->id;die;
                // $page           = $input['page'];
                    $donor_data = User::where('login_type', 2)
                    ->where('id', '!=', $auth_user->id)
                    ->where('is_interested',1)
                    ->where('is_disable', 0) 
                    ->where('blood_group',$input['blood_group'])
                    ->where('state_id',$input['state_id'])   
                    ->where('city_id',$input['city_id'])               
                    ->orderBy('created_at', 'desc')
                    ->get();
                    $data_donor = $this->donorListResponse($donor_data);

                // $result = $this->ResponseWithPagination($page,$filter_data);
                return $this->sendResponse($data_donor, __('messages.api.donor.donor_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'donorListFilter',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
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

    public function eyedonationList(Request $request)
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
           
                    $eyedonation_data = EyeDonation::where('eyedonation_status', 0)->orderBy('created_at', 'desc')
                    ->get()->filter(function ($eyedonation) use ($latitude, $longitude, $radius) {
                            $distance =$this->calculateDistance(
                                $latitude,
                                $longitude,
                                $eyedonation->eyedonation_lat,
                                $eyedonation->eyedonation_long
                            );
                        $eyedonation->distance =round($distance,1);
                        return $distance <= $radius;
                    });

                // echo "<pre>";
                // print_r($ngo_data);die;
                $filter_data =  $this->eyedonationListResponse($eyedonation_data);                
                // $result = $this->ResponseWithPagination($page,$filter_data);
                return $this->sendResponselist($page,$filter_data, __('messages.api.eyedonation.eye_donation_data_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'eyedonationList',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    

    public function eyedonationsingleDetails(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();  
                $latitude = $request->latitude;
                $longitude = $request->longitude; 
                $auth_user = Auth::guard('api')->user();           
                          
                $eyedonationData = EyeDonation::where('eyedonation_id', $input['eyedonation_id'])->where('eyedonation_status',0)->first();
             
                if($eyedonationData !=''){
                    $distance =$this->calculateDistance(
                        $latitude,
                        $longitude,
                        $eyedonationData->lat,
                        $eyedonationData->long
                    );
                    $eyedonationData->distance =round($distance,1);
                 
                }

                // echo "<pre>";
                // print_r($NgoData);die;
                $eyedonation_data = $this->eyedonationResponse($eyedonationData);
                return $this->sendResponse($eyedonation_data, __('messages.api.eyedonation.eye_donation_data_get_success'));               
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'eyedonationsingleDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function eventpromotionList(Request $request)
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
           
                    $event_data = EventPromotion::where('event_promotions_status', 0)->orderBy('created_at', 'desc')
                    ->get()->filter(function ($event) use ($latitude, $longitude, $radius) {
                            $distance =$this->calculateDistance(
                                $latitude,
                                $longitude,
                                $event->event_promotions_lat,
                                $event->event_promotions_long
                            );
                        $event->distance =round($distance,1);
                        return $distance <= $radius;
                    });

                // echo "<pre>";
                // print_r($event_data);die;
                $filter_data =  $this->eventpromotionListResponse($event_data);                
                // $result = $this->ResponseWithPagination($page,$filter_data);
                return $this->sendResponselist($page,$filter_data, __('messages.api.eventpromotion.event_promotion_data_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'eventpromotionList',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function eventpromotionsingleDetails(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();  
                $latitude = $request->latitude;
                $longitude = $request->longitude; 
                $auth_user = Auth::guard('api')->user();           
                          
                $eventData = EventPromotion::where('event_promotions_id', $input['event_promotions_id'])->where('event_promotions_status',0)->first();
             
                if($eventData !=''){
                    $distance =$this->calculateDistance(
                        $latitude,
                        $longitude,
                        $eventData->event_promotions_lat,
                        $eventData->event_promotions_long
                    );
                    $eventData->distance =round($distance,1);
                 
                }

                // echo "<pre>";
                // print_r($NgoData);die;
                $event_data = $this->eventpromotionResponse($eventData);
                return $this->sendResponse($event_data, __('messages.api.eventpromotion.event_promotion_data_get_success'));               
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'eventpromotionsingleDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function crowdfundingList(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                           
                // echo $auth_user->id;die;
                $page     = $input['page'];                
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $radius = 20; // 20 kilometers
           
                    $crowd_data = CrowdFunding::where('crowdfundings_status', 0)->orderBy('created_at', 'desc')
                    ->get()->filter(function ($crowd) use ($latitude, $longitude, $radius) {
                            $distance =$this->calculateDistance(
                                $latitude,
                                $longitude,
                                $crowd->crowdfundings_lat,
                                $crowd->crowdfundings_long
                            );
                        $crowd->distance =round($distance,1);
                        return $distance <= $radius;
                    });

                // echo "<pre>";
                // print_r($event_data);die;
                $filter_data =  $this->crowdListResponse($crowd_data);                
                // $result = $this->ResponseWithPagination($page,$filter_data);
                return $this->sendResponselist($page,$filter_data, __('messages.api.crowd.crowd_data_get_success'));                
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'crowdfundingList',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function crowdfundingsingleDetails(Request $request)
    {        
        try
        {
            if(Auth::guard('api')->check())
            {
                $input = $request->all();   
                $auth_user = Auth::guard('api')->user();           
                           
                // echo $auth_user->id;die;             
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $radius = 20; // 20 kilometers
                           
                $crowdData = CrowdFunding::where('crowdfundings_id', $input['crowdfundings_id'])->where('crowdfundings_status',0)->first();
             
                if($crowdData !=''){
                    $distance =$this->calculateDistance(
                        $latitude,
                        $longitude,
                        $crowdData->crowdfundings_lat,
                        $crowdData->crowdfundings_long
                    );
                    $crowdData->distance =round($distance,1);
                 
                }

                // echo "<pre>";
                // print_r($NgoData);die;
                $crowd_data = $this->crowdResponse($crowdData);

                return $this->sendResponse($crowd_data, __('messages.api.crowd.crowd_data_get_success'));                 
            }
            else
            {
                return $this->sendError(__('messages.api.authentication_err_message'), config('global.null_object'),401,false);
            }
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'crowdfundingsingleDetails',$user_id = $auth_user->id,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    
}
