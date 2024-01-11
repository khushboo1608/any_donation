<?php
namespace App\DataTables;
use App\Models\SpecificNeeds;
use DB;
class SpecificNeedDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        $data = SpecificNeeds::orderBy('created_at','desc')->get();
        return $data;
    }
    
}
