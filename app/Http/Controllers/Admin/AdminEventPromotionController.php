<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\EventPromotionDataTable;
use App\Models\EventPromotion;
use App\Models\State;
use App\Models\City;
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
                return '<input type="checkbox" id="checkbox'.$data->event_promotions_id.'"  value="'.$data->event_promotions_id.'"  name="event_promotions_ids[]" class="event_promotions_ids" />';
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

                $url=route("admin.event_promotions");
                $btn = '<a href="'.$url.'/edit/'.$data->event_promotions_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteEvent(\''.$data->event_promotions_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','event_promotions_status','checkbox'])
            ->make(true);
        }
        return view('admin.event_promotions.index');
    }

    public function add_event_promotions()
    {
        //
        $stateData['state'] = State::orderBy('state_name')->get();
        return view('admin.event_promotions.add',compact('stateData'));
    }
    
    public function saveevent(Request $request)
    {
        //
        $eventData = $request->all();
        $message="";
        $event_image = '';
        if($eventData['event_promotions_id'] !=''){
            $member=EventPromotion::where(['event_promotions_id'=>$eventData['event_promotions_id']])->first();
            if($request->event_promotions_image != "")
            {   
                $event_promotions_image = $this->UploadImage($file = $request->event_promotions_image,$path = config('global.file_path.event_image'));
            }
            else{
                $event_promotions_image =$member->event_promotions_image;
            }
            $eventData['event_promotions_image'] = $event_promotions_image;
            $member = EventPromotion::find($eventData['event_promotions_id']);
            $member->fill($eventData);
            $member->save();
            $message="Data Updated Successfully";

        }else{
            
            if($request->event_promotions_image != "")
            {   
                $event_promotions_image = $this->UploadImage($file = $request->event_promotions_image,$path = config('global.file_path.event_image'));
            }
            else{
                $event_promotions_image =$request->event_promotions_image;
            }
            $eventData['event_promotions_image'] = $event_promotions_image;
            $event_promotions_id = $this->GenerateUniqueRandomString($table='event_promotions', $column="event_promotions_id", $chars=6);
            $eventData['event_promotions_id'] = $event_promotions_id;
            // $photoData['photo_status'] = 1;
            EventPromotion::create($eventData);
            $message="Data Insert Successfully";
        } 

        Session::flash('message', $message);      
        return redirect('admin/event_promotions');
    }

    public function event_promotions_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $event_id  = $request->id;
        EventPromotion::where('event_promotions_id',$event_id)->delete();
        return Response::json(['result' => true,'message'=> 'Event deleted..!']);
    }
    
    public function event_promotions_status(Request $request)
    {
        //
        $event_id = $request->id;
        EventPromotion::where('event_promotions_id',$event_id)->update(['event_promotions_status' => $request->event_status]);
        if($request->event_status == 0)
        {
            $msg = __('Enable successfully');
            $text = "Enabled";
        }
        else
        {
            $msg = __('Disable successfully');
            $text = "Disabled";
           
        }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);

    }

    public function event_data_edit($id)
    {
        //
        $eventData = EventPromotion::where('event_promotions_id', $id)->first();
        $eventData->event_promotions_image = $this->GetImage($file_name = $eventData->event_promotions_image,$path=config('global.file_path.event_image'));

        // dd($userData);
        // exit;
        $stateData['state'] = State::orderBy('state_name')->get();
        $cityData['city'] = City::where('state_id',$eventData->state_id)->orderBy('city_name')->get();
        return view('admin.event_promotions.edit',compact('eventData','stateData','cityData'));

    }

    public function event_promotions_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                EventPromotion::whereIn('event_promotions_id',$ids )->update(['event_promotions_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                EventPromotion::whereIn('event_promotions_id',$ids )->update(['event_promotions_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                EventPromotion::whereIn('event_promotions_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
            }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }

}
