<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ServiceNeedDataTable;
use App\Models\ServiceNeeds;
use Illuminate\Support\Facades\Session;
use Response;

class AdminServiceNeedsDetailsController extends Controller
{
    public function __construct(ServiceNeedDataTable $dataTable)
    {
        $this->middleware('is_admin');
        $this->dataTable = $dataTable;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            // print_r($data); die;
            return datatables()::of($this->dataTable->all($request))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" id="checkbox'.$data->service_needs_id.'"  value="'.$data->service_needs_id.'"  name="service_ids[]" class="service_ids" />';
            })
            ->addColumn('service_needs_status', function ($data) {
                $btn1='';
                $checked = ($data['service_needs_status'] == 1) ? "" : "checked";
                $title =  ($data['service_needs_status'] == 1) ? "Disable" : "Enable";
                if($data['service_needs_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->service_needs_id.'\','.$data->service_needs_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->service_needs_id.'\','.$data->service_needs_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.service_needs");
                $btn = '<a href="'.$url.'/edit/'.$data->service_needs_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteService(\''.$data->service_needs_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','service_needs_status','checkbox'])
            ->make(true);
        }
        return view('admin.service_needs.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_service()
    {
        //
        return view('admin.service_needs.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveservice(Request $request)
    {
        //
         //
         $serviceData = $request->all();
         if($serviceData['service_needs_id'] !=''){
             $member=ServiceNeeds::where(['service_needs_id'=>$serviceData['service_needs_id']])->first();
            
             $member = ServiceNeeds::find($serviceData['service_needs_id']);
             $member->fill($serviceData);
             $member->save();
             $message="Data Updated Successfully";
 
         }else{
             
             $service_needs_id = $this->GenerateUniqueRandomString($table='service_needs', $column="service_needs_id", $chars=6);
             $serviceData['service_needs_id'] = $service_needs_id;
             // $photoData['photo_status'] = 1;
             ServiceNeeds::create($serviceData);
             $message="Data Insert Successfully";
         } 
 
         Session::flash('message', $message);      
         return redirect('admin/service_needs');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function service_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $service_id  = $request->id;
        ServiceNeeds::where('service_needs_id',$service_id)->delete();
        return Response::json(['result' => true,'message'=> 'Service Needs deleted..!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function service_status(Request $request)
    {
        //
        $service_id = $request->id;
        ServiceNeeds::where('service_needs_id',$service_id)->update(['service_needs_status' => $request->service_status]);
        if($request->service_status == 0)
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function service_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                ServiceNeeds::whereIn('service_needs_id',$ids )->update(['service_needs_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                ServiceNeeds::whereIn('service_needs_id',$ids )->update(['service_needs_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                ServiceNeeds::whereIn('service_needs_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
            }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function service_data_edit($id)
    {
        //
        $serviceData = ServiceNeeds::where('service_needs_id', $id)->first();
        // dd($userData);
        // exit;
        return view('admin.service_needs.edit',compact('serviceData'));
    }
}
