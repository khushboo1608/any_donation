<?php
namespace App\DataTables;
use App\Models\EventPromotion;
use DB;
class EventPromotionDataTable
{
    public function all()
    {
        // $data = Banner::where('is_disable',0)->get();
        $data = EventPromotion::orderBy('created_at','desc')->get();
        return $data;
    }
    
}
