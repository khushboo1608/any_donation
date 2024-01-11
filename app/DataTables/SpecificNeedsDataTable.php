<?php
namespace App\DataTables;
use App\Models\SpecificNeeds;
use DB;
class SpecificNeedsDataTable
{
    public function all()
    {
        $data = SpecificNeeds::orderBy('created_at','desc')->get();
        // echo "<pre>";
        // print_r($data);die;
        return $data;
    }
}
