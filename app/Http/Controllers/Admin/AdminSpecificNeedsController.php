<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers\Admin;
use App\DataTables\SpecificNeedsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SpecificNeeds;
use Response;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class AdminSpecificNeedsController extends Controller
{
    //
    public function __construct(SpecificNeedsDataTable $dataTable)
    {
        $this->middleware('is_admin');
        $this->dataTable = $dataTable;
    }


    public function index(Request $request)
    {
        // echo "innn";die;
        if ($request->ajax()) {
            return datatables()::of($this->dataTable->all($request))
            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" id="checkbox'.$data->specific_needs_id.'"  value="'.$data->specific_needs_id.'"  name="specific_needs_ids[]" class="specific_needs_ids" />';
            })
            ->addIndexColumn()                      
            ->addColumn('specific_needs_name', function($data){
                return $data->specific_needs_name;
            })
            ->addColumn('specific_needs_status', function ($data) {
                $btn1='';
                $checked = ($data['specific_needs_status'] == 1) ? "" : "checked";
                $title =  ($data['specific_needs_status'] == 1) ? "Disable" : "Enable";
                if($data['specific_needs_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->specific_needs_id.'\','.$data->specific_needs_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->specific_needs_id.'\','.$data->specific_needs_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })

            ->addColumn('action', function($data){

                $url=route("admin.specificneeds");
                $btn = '<a href="'.$url.'/edit/'.$data->specific_needs_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editSpecificNeeds"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deleteSpecificNeeds" onclick="DeleteSpecificNeeds(\''.$data->specific_needs_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';
                 return $btn;
         })
            ->rawColumns(['action','checkbox','specific_needs_status'])
            ->make(true);
        }
        return view('admin.specificneeds.index');
    }
    public function add_specificneeds(Request $request)
    {
        return view('admin.specificneeds.addspecificneed');
    }

    public function savespecificneeds(Request $request)
    {
        $specificneedsData = $request->all();
        // echo "<pre>";
        // print_r($specificneedsData);die;

        $message="";
       
        if($specificneedsData['id'] !=''){
            $specificneedsData['specific_needs_id'] = $specificneedsData['id'];
            $rules = [
                'specific_needs_name' => 'unique:specific_needs,specific_needs_name,'.$specificneedsData['specific_needs_id'].',specific_needs_id',
            ];
            
            $messages = [
                'specific_needs_name.unique'    => 'Specific needs name already exist. Try another.',
            ];
            $validator = Validator::make($specificneedsData, $rules,$messages); 
            if ($validator->fails())
            {
                $errors = $validator->errors()->first();                
                return redirect()->back()->with('error',$errors);
            }
            else
            {       
                // print_r($specificneedsData); die;
                $user = SpecificNeeds::find($specificneedsData['id']);
                $user->fill($specificneedsData);
                $user->save();
                $message="Specific Needs Updated Successfully";
               
            }

        }else{
            $rules = [
                'specific_needs_name' => 'unique:specific_needs,specific_needs_name',
            ];
            
            $messages = [
                'specific_needs_name.unique'    => 'Specific needs already exist. Try another.',
            ];
            $validator = Validator::make($specificneedsData, $rules,$messages); 
            if ($validator->fails())
            {
                $errors = $validator->errors()->first();                
                return redirect()->back()->with('error',$errors);
            }
            else
            {
                $specific_needs_id = $this->GenerateUniqueRandomString($table='specific_needs', $column="specific_needs_id", $chars=6);
                $specificneedsData['specific_needs_id'] = $specific_needs_id;
                // echo "<pre>";
                // print_r($specificneedsData); die;
                SpecificNeeds::create($specificneedsData);
                $message="Specific Needs Data Insert Successfully";
            }
        } 

        Session::flash('message', $message);      
        return redirect('admin/specificneeds');

    }

    public function specificneeds_data_edit($id)
    {       
        $specificneedsData=SpecificNeeds::where('specific_needs_id',$id)->where('specific_needs_status',0)->first();
        return view('admin.specificneeds.edit')->with(['specificneedsData' => $specificneedsData]);
    }

    public function specificneeds_delete(Request $request)
    {
        $specific_needs_id = $request->id;
        SpecificNeeds::where('specific_needs_id',$specific_needs_id)->delete();
        return Response::json(['result' => true,'message'=> 'Specific needs deleted..!']);
    }

    public function specificneeds_status(Request $request)
    {
        // echo $request->specific_needs_status;die;
        $specific_needs_id  = $request->id;
        SpecificNeeds::where('specific_needs_id',$specific_needs_id )->update(['specific_needs_status' => $request->specific_needs_status]);

        if($request->specific_needs_status == 0)
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
                SpecificNeeds::whereIn('specific_needs_id',$ids )->update(['specific_needs_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

			}else if($action=='disable'){

			    SpecificNeeds::whereIn('specific_needs_id',$ids )->update(['specific_needs_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
				
			}else if($action=='delete'){
				
				SpecificNeeds::whereIn('specific_needs_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
			}
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }
}
