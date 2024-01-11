<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers\Admin;
use App\DataTables\ServiceNeedsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceNeeds;
use Response;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class AdminServiceNeedsController extends Controller
{
    //
    public function __construct(ServiceNeedsDataTable $dataTable)
    {
        $this->middleware('is_admin');
        $this->dataTable = $dataTable;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()::of($this->dataTable->all($request))
            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" id="checkbox'.$data->service_needs_id.'"  value="'.$data->service_needs_id.'"  name="service_needs_ids[]" class="service_needs_ids" />';
            })
            ->addIndexColumn()                      
            ->addColumn('service_needs_name', function($data){
                return $data->service_needs_name;
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

                $url=route("admin.serviceneeds");
                $btn = '<a href="'.$url.'/edit/'.$data->service_needs_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editServiceNeeds"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deleteServiceNeeds" onclick="DeleteServiceNeeds(\''.$data->service_needs_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';
                 return $btn;
         })
            ->rawColumns(['action','checkbox','service_needs_status'])
            ->make(true);
        }
        return view('admin.serviceneeds.index');
    }
    public function add_serviceneeds(Request $request)
    {
        return view('admin.serviceneeds.addserviceneed');
    }

    public function saveserviceneeds(Request $request)
    {
        $serviceneedsData = $request->all();
        // echo "<pre>";
        // print_r($serviceneedsData);die;

        $message="";
       
        if($serviceneedsData['id'] !=''){
            $serviceneedsData['service_needs_id'] = $serviceneedsData['id'];
            $rules = [
                'service_needs_name' => 'unique:service_needs,service_needs_name,'.$serviceneedsData['service_needs_id'].',service_needs_id',
            ];
            
            $messages = [
                'service_needs_name.unique'    => 'Service needs name already exist. Try another.',
            ];
            $validator = Validator::make($serviceneedsData, $rules,$messages); 
            if ($validator->fails())
            {
                $errors = $validator->errors()->first();                
                return redirect()->back()->with('error',$errors);
            }
            else
            {       
                // print_r($serviceneedsData); die;
                $user = ServiceNeeds::find($serviceneedsData['id']);
                $user->fill($serviceneedsData);
                $user->save();
                $message="Service Needs Updated Successfully";
               
            }

        }else{
            $rules = [
                'service_needs_name' => 'unique:service_needs,service_needs_name',
            ];
            
            $messages = [
                'service_needs_name.unique'    => 'Service needs already exist. Try another.',
            ];
            $validator = Validator::make($serviceneedsData, $rules,$messages); 
            if ($validator->fails())
            {
                $errors = $validator->errors()->first();                
                return redirect()->back()->with('error',$errors);
            }
            else
            {
                $service_needs_id = $this->GenerateUniqueRandomString($table='service_needs', $column="service_needs_id", $chars=6);
                $serviceneedsData['service_needs_id'] = $service_needs_id;
                // echo "<pre>";
                // print_r($serviceneedsData); die;
                ServiceNeeds::create($serviceneedsData);
                $message="Service Needs Data Insert Successfully";
            }
        } 

        Session::flash('message', $message);      
        return redirect('admin/serviceneeds');

    }

    public function serviceneeds_data_edit($id)
    {       
        $serviceneedsData=ServiceNeeds::where('service_needs_id',$id)->where('service_needs_status',0)->first();
        return view('admin.serviceneeds.edit')->with(['serviceneedsData' => $serviceneedsData]);
    }

    public function serviceneeds_delete(Request $request)
    {
        $service_needs_id = $request->id;
        ServiceNeeds::where('service_needs_id',$service_needs_id)->delete();
        return Response::json(['result' => true,'message'=> 'Service needs deleted..!']);
    }

    public function serviceneeds_status(Request $request)
    {
        // echo $request->service_needs_status;die;
        $service_needs_id  = $request->id;
        ServiceNeeds::where('service_needs_id',$service_needs_id )->update(['service_needs_status' => $request->service_needs_status]);

        if($request->service_needs_status == 0)
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


}
