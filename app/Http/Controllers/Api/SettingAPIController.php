<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseAPIController as BaseAPIController;
use App\Models\User;
use App\Models\Settings;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\DashboardAPIController;
use Validator;
use Illuminate\Support\Str;

class SettingAPIController extends BaseAPIController
{
    public function SettingList(Request $request)
    {        
        try
        {            
            $input = $request->all();  
            $setting = Settings::where('is_disable',0)->orderBy('created_at','desc')->first();
            $data_setting = $this->SettingResponse($setting);
            // $keys = array_column($data_complaint, 'created_at');
            // $result = $this->ResponseWithPagination($page,$data_complaint);
            return $this->sendResponse($data_setting, __('messages.api.setting.setting_get_success'));                
           
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'SettingList',$user_id = 0,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }

    public function StateList(Request $request)
    {    
        try
        {
                $input = $request->all();            
                              
                // $page           = $input['page'];                    
                $data_state = State::get();
                $result = $this->GetStateData($data_state);
                // $keys = array_column($data_state, 'created_at');
                // $result = $this->ResponseWithPagination($page,$data_state);
                return $this->sendResponse($result, __('messages.api.state.state_get_success'));          
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'StateList',$user_id = 0,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    public function CityList(Request $request)
    {    
        try
        {
                $input = $request->all();            
                                                
                $data_city = City::where('state_id',$input['state_id'])->get();
                $result = $this->GetCityListData($data_city);
                return $this->sendResponse($result, __('messages.api.city.city_get_success'));          
        }
        catch(\Exception $e)
        {
            $auth_user = Auth::guard('api')->user();
            $this->serviceLogError($service_name = 'CityList',$user_id = 0,$message = $e->getMessage(),$requested_field = json_encode($request->all()),$response_data=$e);
            return $this->sendError($e->getMessage(), config('global.null_object'),401,false);
        }
    }
    

    
}
