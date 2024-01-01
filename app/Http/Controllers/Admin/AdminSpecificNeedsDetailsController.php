<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\SpecificNeedDataTable;
use App\Models\SpecificNeeds;
use Illuminate\Support\Facades\Session;
use Response;

class AdminSpecificNeedsDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(SpecificNeedDataTable $dataTable)
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
                return '<input type="checkbox" id="checkbox'.$data->specific_needs_id.'"  value="'.$data->specific_needs_id.'"  name="specific_ids[]" class="specific_ids" />';
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

                $url=route("admin.specific_needs");
                $btn = '<a href="'.$url.'/edit/'.$data->specific_needs_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteSpecific(\''.$data->specific_needs_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','specific_needs_status','checkbox'])
            ->make(true);
        }
        return view('admin.specific_needs.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_specific()
    {
        //
        return view('admin.specific_needs.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savespecific(Request $request)
    {
        //
         //
         $specificData = $request->all();
         $message="";
         if($specificData['specific_needs_id'] !=''){
             $member=SpecificNeeds::where(['specific_needs_id'=>$specificData['specific_needs_id']])->first();
            
             $member = SpecificNeeds::find($specificData['specific_needs_id']);
             $member->fill($specificData);
             $member->save();
             $message="Data Updated Successfully";
 
         }else{
             
             $specific_needs_id = $this->GenerateUniqueRandomString($table='specific_needs', $column="specific_needs_id", $chars=6);
             $specificData['specific_needs_id'] = $specific_needs_id;
             // $photoData['photo_status'] = 1;
             SpecificNeeds::create($specificData);
             $message="Data Insert Successfully";
         } 
 
         Session::flash('message', $message);      
         return redirect('admin/specific_needs');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function specific_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $specific_id  = $request->id;
        SpecificNeeds::where('specific_needs_id',$specific_id)->delete();
        return Response::json(['result' => true,'message'=> 'Specific Needs deleted..!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function specific_status(Request $request)
    {
        //
        $specific_id = $request->id;
        SpecificNeeds::where('specific_needs_id',$specific_id)->update(['specific_needs_status' => $request->specific_status]);
        if($request->specific_status == 0)
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
    public function specific_multi_status(Request $request)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function specific_data_edit($id)
    {
        //
        $specificData = SpecificNeeds::where('specific_needs_id', $id)->first();
        // dd($userData);
        // exit;
        return view('admin.specific_needs.edit',compact('specificData'));
    }
}
