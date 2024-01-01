<?php
namespace App\DataTables;
use App\Models\MemberDetails;
use DB;
class MemberDetailsDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        // $data = Photos::orderBy('created_at','desc')->get();
        $data = MemberDetails::select('member_details.*', 'users.name as user_name')
        ->orderBy('member_details.created_at', 'desc')
        ->leftJoin('users', 'member_details.user_id', '=', 'users.id')
        ->get();
        // exit;
        return $data;
    }
    
}
