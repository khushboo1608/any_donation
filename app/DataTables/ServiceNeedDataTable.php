<?php
namespace App\DataTables;
use App\Models\ServiceNeeds;
use DB;
class ServiceNeedDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        $data = ServiceNeeds::orderBy('created_at','desc')->get();
        return $data;
    }
    
}
