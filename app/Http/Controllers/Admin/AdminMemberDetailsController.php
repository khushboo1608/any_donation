<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\MemberDetailsDataTable;
use App\Models\User;
use App\Models\MemberDetails;
use Illuminate\Support\Facades\Session;
use Response;


class AdminMemberDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(MemberDetailsDataTable $dataTable)
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
                return '<input type="checkbox" id="checkbox'.$data->member_details_id.'"  value="'.$data->member_details_id.'"  name="member_ids[]" class="member_ids" />';
            })
            ->editColumn('member_image', function ($data) {
                $member_image = $this->GetImage($file_name = $data->member_image,$path=config('global.file_path.member_image'));
                
                if($member_image == '')
                {
                    $member_image = config('global.no_image.no_image');
                }
                return $member_image;
            })
            ->addColumn('member_details_status', function ($data) {
                $btn1='';
                $checked = ($data['member_details_status'] == 1) ? "" : "checked";
                $title =  ($data['member_details_status'] == 1) ? "Disable" : "Enable";
                if($data['member_details_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->member_details_id.'\','.$data->member_details_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->member_details_id.'\','.$data->member_details_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.member");
                $btn = '<a href="'.$url.'/edit/'.$data->member_details_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteMember(\''.$data->member_details_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','member_details_status','checkbox'])
            ->make(true);
        }
        return view('admin.member.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_member()
    {
        //
        $userData = User::where("login_type",3)->get();
        return view('admin.member.add',compact('userData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savemember(Request $request)
    {
        //
        $memberData = $request->all();
        $message="";
        $member_image = '';
        if($memberData['member_details_id'] !=''){
            $member=MemberDetails::where(['member_details_id'=>$memberData['member_details_id']])->first();
            if($request->member_image != "")
            {   
                $member_image = $this->UploadImage($file = $request->member_image,$path = config('global.file_path.member_image'));
            }
            else{
                $member_image =$member->member_image;
            }
            $memberData['member_image'] = $member_image;
            $member = MemberDetails::find($memberData['member_details_id']);
            $member->fill($memberData);
            $member->save();
            $message="Data Updated Successfully";

        }else{
            
            if($request->member_image != "")
            {   
                $member_image = $this->UploadImage($file = $request->member_image,$path = config('global.file_path.member_image'));
            }
            else{
                $member_image =$request->member_image;
            }
            $memberData['member_image'] = $member_image;
            $member_details_id = $this->GenerateUniqueRandomString($table='member_details', $column="member_details_id", $chars=6);
            $memberData['member_details_id'] = $member_details_id;
            // $photoData['photo_status'] = 1;
            MemberDetails::create($memberData);
            $message="Data Insert Successfully";
        } 

        Session::flash('message', $message);      
        return redirect('admin/member');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function member_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $member_id  = $request->id;
        MemberDetails::where('member_details_id',$member_id)->delete();
        return Response::json(['result' => true,'message'=> 'Member deleted..!']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function member_status(Request $request)
    {
        //
        $member_id = $request->id;
        MemberDetails::where('member_details_id',$member_id)->update(['member_details_status' => $request->member_status]);
        if($request->member_status == 0)
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function member_data_edit($id)
    {
        //
        $memberData = MemberDetails::where('member_details_id', $id)->first();
        $memberData->member_image = $this->GetImage($file_name = $memberData->member_image,$path=config('global.file_path.member_image'));

        $userData['user'] = User::where('login_type',3)->orderBy('name')->get();
        // dd($userData);
        // exit;
        return view('admin.member.edit',compact('memberData','userData'));

    }
    public function member_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                MemberDetails::whereIn('member_details_id',$ids )->update(['member_details_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                MemberDetails::whereIn('member_details_id',$ids )->update(['member_details_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                MemberDetails::whereIn('member_details_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
            }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }

}
