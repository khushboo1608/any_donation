<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\EventPromotionDataTable;
use App\Models\EventPromotion;
use Illuminate\Support\Facades\Session;
use Response;

class AdminEventPromotionController extends Controller
{
    //
    public function __construct(EventPromotionDataTable $dataTable)
    {
        $this->middleware('is_admin');
        $this->dataTable = $dataTable;
    }

    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            // print_r($data); die;
            return datatables()::of($this->dataTable->all($request))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" id="checkbox'.$data->event_promotions_id.'"  value="'.$data->event_promotions_id.'"  name="event_promotions_ids[]" class="request_ids" />';
            })
            ->addColumn('event_promotions_status', function ($data) {
                $btn1='';
                $checked = ($data['event_promotions_status'] == 1) ? "" : "checked";
                $title =  ($data['event_promotions_status'] == 1) ? "Disable" : "Enable";
                if($data['event_promotions_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->event_promotions_id.'\','.$data->event_promotions_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->event_promotions_id.'\','.$data->event_promotions_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.request");
                $btn = '<a href="'.$url.'/edit/'.$data->event_promotions_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteEvent(\''.$data->event_promotions_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','event_promotions_status','checkbox'])
            ->make(true);
        }
        return view('admin.event.index');
    }
}
