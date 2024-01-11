<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class BaseAPIController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function sendResponselist($page,$result, $message)
    {
        $per_page = env('PER_PAGE');
        $current_page = ($page == 0) ? 1 : $page;
    	$response = [
            'success' => true,
            'total_record'    =>  count($result),
            'data'    => array_slice($result , ($current_page * $per_page) - $per_page, $per_page),
            'message' => $message,
        ];
        return response()->json($response, 200);
    }
    

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
 
    public function serviceLogError($service_name = 'service_name',$user_id = 0,$message = 'message',$requested_field = 'requested_field',$response_data="response_data")
    {
        $service_error_log = [
            'service_name'      => $service_name,
            'user_id'           => $user_id,
            'message'           => $message,
            'requested_field'   => $requested_field,
            'response_data'     => $response_data,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ];
        DB::table('service_error_log')->insert($service_error_log);
    }

}
