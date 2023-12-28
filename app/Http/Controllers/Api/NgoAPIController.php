<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseAPIController as BaseAPIController;
use App\Models\User;
use App\Models\Ngo;
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
                if($request->member_image)
                {   
                    $this->RemoveImage($name = $auth_user->story_image,$path = config('global.file_path.member_image'));
                    $input['member_image'] = $this->UploadImage($file = $request->story_image,$path = config('global.file_path.member_image'));
                }
                    $member_details_id = $this->GenerateUniqueRandomString($table='member_details', $column="member_details_id", $chars=6);

                    $input['member_details_id']   = $member_details_id;
                    $input['ngo_id']               = $auth_user->id;                   
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
                $member_data      = MemberDetails::where('ngo_id',$auth_user->id)->where('member_details_status',0)->orderBy('created_at','desc')->get();
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

    
}
