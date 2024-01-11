<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\EyeDonationDataTable;
use App\Models\EyeDonation;
use App\Models\State;
use App\Models\City;
use App\Models\ServiceNeeds;
use App\Models\SpecificNeeds;
use Illuminate\Support\Facades\Session;
use Response;

class AdminEyeDonationController extends Controller
{
    //
    public function __construct(EyeDonationDataTable $dataTable)
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
                return '<input type="checkbox" id="checkbox'.$data->eyedonation_id.'"  value="'.$data->eyedonation_id.'"  name="eyedonation_ids[]" class="eyedonation_ids" />';
            })
            ->addColumn('eyedonation_status', function ($data) {
                $btn1='';
                $checked = ($data['eyedonation_status'] == 1) ? "" : "checked";
                $title =  ($data['eyedonation_status'] == 1) ? "Disable" : "Enable";
                if($data['eyedonation_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->eyedonation_id.'\','.$data->eyedonation_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->eyedonation_id.'\','.$data->eyedonation_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.eye_donation");
                $btn = '<a href="'.$url.'/edit/'.$data->eyedonation_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteEye(\''.$data->eyedonation_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','eyedonation_status','checkbox'])
            ->make(true);
        }
        return view('admin.eye_donation.index');
    }

    public function add_eye_donation()
    {
        //
        $stateData['state'] = State::orderBy('state_name')->get();
        $serviceData['service_needs'] = ServiceNeeds::orderBy('service_needs_name')->get();
        $specificData['specific_needs'] = SpecificNeeds::orderBy('specific_needs_name')->get();
        return view('admin.eye_donation.add',compact('stateData','serviceData','specificData'));
    }
    
    public function saveeyedonation(Request $request)
    {
        //
        $eyeData = $request->all();
        $message="";
        $event_image = '';
        if($eyeData['eyedonation_id'] !=''){
            $member=EyeDonation::where(['eyedonation_id'=>$eyeData['eyedonation_id']])->first();
            if($request->eyedonation_image != "")
            {   
                $eyedonation_image = $this->UploadImage($file = $request->eyedonation_image,$path = config('global.file_path.eyedonation_image'));
            }
            else{
                $eyedonation_image =$member->eyedonation_image;
            }
            $eyeData['eyedonation_image'] = $eyedonation_image;
            $member = EyeDonation::find($eyeData['eyedonation_id']);
            $member->fill($eyeData);
            $member->save();
            $message="Data Updated Successfully";

        }else{
            
            if($request->eyedonation_image != "")
            {   
                $eyedonation_image = $this->UploadImage($file = $request->eyedonation_image,$path = config('global.file_path.eyedonation_image'));
            }
            else{
                $eyedonation_image =$request->eyedonation_image;
            }
            $eyeData['eyedonation_image'] = $eyedonation_image;
            $eyedonation_id = $this->GenerateUniqueRandomString($table='eye_donations', $column="eyedonation_id", $chars=6);
            $eyeData['eyedonation_id'] = $eyedonation_id;
            // $photoData['photo_status'] = 1;
            EyeDonation::create($eyeData);
            $message="Data Insert Successfully";
        } 

        Session::flash('message', $message);      
        return redirect('admin/eye_donation');
    }
    
    public function eye_donation_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $eye_id  = $request->id;
        EyeDonation::where('eyedonation_id',$eye_id)->delete();
        return Response::json(['result' => true,'message'=> 'Eye Donate deleted..!']);
    }

    public function eye_donation_status(Request $request)
    {
        //
        $eye_id = $request->id;
        EyeDonation::where('eyedonation_id',$eye_id)->update(['eyedonation_status' => $request->eye_status]);
        if($request->eye_status == 0)
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

    public function eye_data_edit($id)
    {
        //
        $eyeData = EyeDonation::where('eyedonation_id', $id)->first();
        $eyeData->eyedonation_image = $this->GetImage($file_name = $eyeData->eyedonation_image,$path=config('global.file_path.eyedonation_image'));

        // dd($userData);
        // exit;
        $stateData['state'] = State::orderBy('state_name')->get();
        $cityData['city'] = City::where('state_id',$eyeData->state_id)->orderBy('city_name')->get();
        $serviceData['service_needs'] = ServiceNeeds::orderBy('service_needs_name')->get();
        $specificData['specific_needs'] = SpecificNeeds::orderBy('specific_needs_name')->get();
        return view('admin.eye_donation.edit',compact('eyeData','stateData','cityData','serviceData','specificData'));

    }

    public function eye_donation_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                EyeDonation::whereIn('eyedonation_id',$ids )->update(['eyedonation_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                EyeDonation::whereIn('eyedonation_id',$ids )->update(['eyedonation_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                EyeDonation::whereIn('eyedonation_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
            }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }

    
    
}
