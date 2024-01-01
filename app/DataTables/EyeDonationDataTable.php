<?php
namespace App\DataTables;
use App\Models\EyeDonation;
use DB;
class EyeDonationDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        $data = EyeDonation::orderBy('created_at','desc')->get();
        return $data;
    }
    
}
