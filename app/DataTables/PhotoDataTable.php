<?php
namespace App\DataTables;
use App\Models\Photos;
use DB;
class PhotoDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        // $data = Photos::orderBy('created_at','desc')->get();
        $data = Photos::select('photos.*', 'users.name as user_name')
        ->orderBy('photos.created_at', 'desc')
        ->leftJoin('users', 'photos.user_id', '=', 'users.id')
        ->get();
        // exit;
        return $data;
    }
    
}
