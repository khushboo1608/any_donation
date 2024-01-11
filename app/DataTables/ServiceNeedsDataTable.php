<?php
namespace App\DataTables;
use App\Models\ServiceNeeds;
use DB;
class ServiceNeedsDataTable
{
    public function all()
    {
        $data = ServiceNeeds::orderBy('created_at','desc')->get();
        // echo "<pre>";
        // print_r($data);die;
        return $data;
    }
}
