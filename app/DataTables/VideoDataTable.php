<?php
namespace App\DataTables;
use App\Models\Videos;
use DB;
class VideoDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        // $data = Videos::orderBy('created_at','desc')->get();
        $data = Videos::select('videos.*', 'users.name as user_name')
        ->orderBy('videos.created_at', 'desc')
        ->leftJoin('users', 'videos.user_id', '=', 'users.id')
        ->get();

        // exit;
        return $data;
    }
    
}
