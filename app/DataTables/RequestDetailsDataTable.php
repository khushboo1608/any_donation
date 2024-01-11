<?php
namespace App\DataTables;
use App\Models\RequestDetails;
use DB;
class RequestDetailsDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        // $data = Photos::orderBy('created_at','desc')->get();
        $data = RequestDetails::select('request_details.*', 'users.name as user_name')
        ->orderBy('request_details.created_at', 'desc')
        ->leftJoin('users', 'request_details.user_id', '=', 'users.id')
        ->get();
        // exit;
        return $data;
    }
    
}
