<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\RequestDetailsDataTable;
use App\Models\User;
use App\Models\RequestDetails;
use Illuminate\Support\Facades\Session;
use Response;


class AdminRequestDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(RequestDetailsDataTable $dataTable)
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
                return '<input type="checkbox" id="checkbox'.$data->request_details_id.'"  value="'.$data->request_details_id.'"  name="request_ids[]" class="request_ids" />';
            })
            ->addColumn('request_details_status', function ($data) {
                $btn1='';
                $checked = ($data['request_details_status'] == 1) ? "" : "checked";
                $title =  ($data['request_details_status'] == 1) ? "Disable" : "Enable";
                if($data['request_details_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->request_details_id.'\','.$data->request_details_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->request_details_id.'\','.$data->request_details_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.request");
                $btn = '<a href="'.$url.'/edit/'.$data->request_details_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteRequest(\''.$data->request_details_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','request_details_status','checkbox'])
            ->make(true);
        }
        return view('admin.request.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_request()
    {
        //
        $userData = User::where("login_type",4)->get();
        return view('admin.request.add',compact('userData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saverequest(Request $request)
    {
        //
         //
         $requestData = $request->all();
         $message="";
         $member_image = '';
         if($requestData['request_details_id'] !=''){
             $member=RequestDetails::where(['request_details_id'=>$requestData['request_details_id']])->first();
            
             $member = RequestDetails::find($requestData['request_details_id']);
             $member->fill($requestData);
             $member->save();
             $message="Data Updated Successfully";
 
         }else{
             
             $request_details_id = $this->GenerateUniqueRandomString($table='request_details', $column="request_details_id", $chars=6);
             $requestData['request_details_id'] = $request_details_id;
             // $photoData['photo_status'] = 1;
             RequestDetails::create($requestData);
             $message="Data Insert Successfully";
         } 
 
         Session::flash('message', $message);      
         return redirect('admin/request');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function request_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $request_id  = $request->id;
        RequestDetails::where('request_details_id',$request_id)->delete();
        return Response::json(['result' => true,'message'=> 'Request deleted..!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function request_status(Request $request)
    {
        //
        $request_id = $request->id;
        RequestDetails::where('request_details_id',$request_id)->update(['request_details_status' => $request->request_status]);
        if($request->request_status == 0)
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

    public function request_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                RequestDetails::whereIn('request_details_id',$ids )->update(['request_details_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                RequestDetails::whereIn('request_details_id',$ids )->update(['request_details_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                RequestDetails::whereIn('request_details_id',$ids )->delete();
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
    public function request_data_edit($id)
    {
        //
        $requestData = RequestDetails::where('request_details_id', $id)->first();

        $userData['user'] = User::where('login_type',4)->orderBy('name')->get();
        // dd($userData);
        // exit;
        return view('admin.request.edit',compact('requestData','userData'));
    }
}
