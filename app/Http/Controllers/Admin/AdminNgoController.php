<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ngo;
use App\DataTables\NgoDataTable;
use App\Models\User;
use App\Models\SpecificNeeds;
use App\Models\ServiceNeeds;
use Illuminate\Support\Facades\Session;
use Response;

class AdminNgoController extends Controller
{
    //
    public function __construct(NgoDataTable $dataTable){
        // print_r($dataTable);
        // exit;
        $this->middleware('is_admin');
        $this->dataTable = $dataTable;
    }

    public function index(Request $request){
        if($request->ajax()){
            // print_r($data); die;
            return datatables()::of($this->dataTable->all($request))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data){
                return '<input type="checkbox" id="checkbox'.$data->ngo_id.'"  value="'.$data->ngo_id.'"  name="ngo_ids[]" class="ngo_ids" />';
            })
            ->addColumn('ngo_status', function ($data) {
                $btn1='';
                $checked = ($data['ngo_status'] == 1) ? "" : "checked";
                $title =  ($data['ngo_status'] == 1) ? "Disable" : "Enable";
                if($data['ngo_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->ngo_id.'\','.$data->ngo_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->ngo_id.'\','.$data->ngo_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.ngo");
                $btn = '<a href="'.$url.'/edit/'.$data->ngo_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteNgo(\''.$data->ngo_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
            })
            ->rawColumns(['action','ngo_status','checkbox'])
            ->make(true);
        }
        return view('admin.ngo.index');
    }

    public function add_ngo()
    {
        //
        $userData = User::where("login_type",3)->get();
        $serviceData['service_needs'] = ServiceNeeds::orderBy('service_needs_name')->get();
        $specificData['specific_needs'] = SpecificNeeds::orderBy('specific_needs_name')->get();
        return view('admin.ngo.add',compact('userData','serviceData','specificData'));
    }

    public function savengo(Request $request)
    {
        //
        $ngoData = $request->all();
        $message="";
        $address_proof = '';
        $jj_act = '';
        $form_10_b = '';
        $a12_certificate ='';
        $cancelled_blank_cheque='';
        $pan_of_ngo='';
        $registration_certificate='';
        
        if($ngoData['ngo_id'] !=''){
            $member=Ngo::where(['ngo_id'=>$ngoData['ngo_id']])->first();
            if($request->address_proof != "")
            {   
                $address_proof = $this->UploadImage($file = $request->address_proof,$path = config('global.file_path.ngo_image'));
            }
            else{
                $address_proof =$member->address_proof;
            }

            if($request->jj_act != "")
            {   
                $jj_act = $this->UploadImage($file = $request->jj_act,$path = config('global.file_path.ngo_image'));
            }
            else{
                $jj_act =$member->jj_act;
            }

            if($request->form_10_b != "")
            {   
                $form_10_b = $this->UploadImage($file = $request->form_10_b,$path = config('global.file_path.ngo_image'));
            }
            else{
                $form_10_b =$member->form_10_b;
            }

            if($request->a12_certificate != "")
            {   
                $a12_certificate = $this->UploadImage($file = $request->a12_certificate,$path = config('global.file_path.ngo_image'));
            }
            else{
                $a12_certificate =$member->a12_certificate;
            }

            if($request->cancelled_blank_cheque != "")
            {   
                $cancelled_blank_cheque = $this->UploadImage($file = $request->cancelled_blank_cheque,$path = config('global.file_path.ngo_image'));
            }
            else{
                $cancelled_blank_cheque =$member->cancelled_blank_cheque;
            }

            if($request->pan_of_ngo != "")
            {   
                $pan_of_ngo = $this->UploadImage($file = $request->pan_of_ngo,$path = config('global.file_path.ngo_image'));
            }
            else{
                $pan_of_ngo =$member->pan_of_ngo;
            }

            if($request->registration_certificate != "")
            {   
                $registration_certificate = $this->UploadImage($file = $request->registration_certificate,$path = config('global.file_path.ngo_image'));
            }
            else{
                $registration_certificate =$member->registration_certificate;
            }

            
            $ngoData['address_proof'] = $address_proof;
            $ngoData['jj_act'] = $jj_act;
            $ngoData['form_10_b'] = $form_10_b;
            $ngoData['cancelled_blank_cheque'] = $cancelled_blank_cheque;
            $ngoData['a12_certificate'] = $a12_certificate;
            $ngoData['pan_of_ngo'] = $pan_of_ngo;
            $ngoData['registration_certificate'] = $registration_certificate;
            $member = Ngo::find($ngoData['ngo_id']);
            $member->fill($ngoData);
            $member->save();
            $message="Data Updated Successfully";

        }else{
            
            if($request->address_proof != "")
            {   
                $address_proof = $this->UploadImage($file = $request->address_proof,$path = config('global.file_path.ngo_image'));
            }
            else{
                $address_proof =$request->address_proof;
            }

            if($request->jj_act != "")
            {   
                $jj_act = $this->UploadImage($file = $request->jj_act,$path = config('global.file_path.ngo_image'));
            }
            else{
                $jj_act =$request->jj_act;
            }

            if($request->form_10_b != "")
            {   
                $form_10_b = $this->UploadImage($file = $request->form_10_b,$path = config('global.file_path.ngo_image'));
            }
            else{
                $form_10_b =$request->form_10_b;
            }

            if($request->cancelled_blank_cheque != "")
            {   
                $cancelled_blank_cheque = $this->UploadImage($file = $request->cancelled_blank_cheque,$path = config('global.file_path.ngo_image'));
            }
            else{
                $cancelled_blank_cheque =$request->cancelled_blank_cheque;
            }

            if($request->a12_certificate != "")
            {   
                $a12_certificate = $this->UploadImage($file = $request->a12_certificate,$path = config('global.file_path.ngo_image'));
            }
            else{
                $a12_certificate =$request->a12_certificate;
            }

            if($request->pan_of_ngo != "")
            {   
                $pan_of_ngo = $this->UploadImage($file = $request->pan_of_ngo,$path = config('global.file_path.ngo_image'));
            }
            else{
                $pan_of_ngo =$request->pan_of_ngo;
            }

            if($request->registration_certificate != "")
            {   
                $registration_certificate = $this->UploadImage($file = $request->registration_certificate,$path = config('global.file_path.ngo_image'));
            }
            else{
                $registration_certificate =$request->registration_certificate;
            }
            

            $ngoData['address_proof'] = $address_proof;
            $ngoData['jj_act'] = $jj_act;
            $ngoData['form_10_b'] = $form_10_b;
            $ngoData['a12_certificate'] = $a12_certificate;
            $ngoData['cancelled_blank_cheque'] = $cancelled_blank_cheque;
            $ngoData['pan_of_ngo'] = $pan_of_ngo;
            $ngoData['registration_certificate'] = $registration_certificate;
            $member_details_id = $this->GenerateUniqueRandomString($table='ngos', $column="ngo_id", $chars=6);
            $ngoData['ngo_id'] = $member_details_id;
            // $photoData['photo_status'] = 1;
            Ngo::create($ngoData);
            $message="Data Insert Successfully";
        } 

        Session::flash('message', $message);      
        return redirect('admin/ngo');
    }

    public function ngo_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $ngo_id  = $request->id;
        Ngo::where('ngo_id',$ngo_id)->delete();
        return Response::json(['result' => true,'message'=> 'NGO deleted..!']);
    }

    public function ngo_status(Request $request)
    {
        //
        $ngo_id = $request->id;
        Ngo::where('ngo_id',$ngo_id)->update(['ngo_status' => $request->ngo_status]);
        if($request->ngo_status == 0)
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

    public function ngo_data_edit($id)
    {
        //
        $ngoData = Ngo::where('ngo_id', $id)->first();
        $ngoData->address_proof = $this->GetImage($file_name = $ngoData->address_proof,$path=config('global.file_path.ngo_image'));
        $ngoData->jj_act = $this->GetImage($file_name = $ngoData->jj_act,$path=config('global.file_path.ngo_image'));
        $ngoData->form_10_b = $this->GetImage($file_name = $ngoData->form_10_b,$path=config('global.file_path.ngo_image'));
        $ngoData->a12_certificate = $this->GetImage($file_name = $ngoData->a12_certificate,$path=config('global.file_path.ngo_image'));
        $ngoData->cancelled_blank_cheque = $this->GetImage($file_name = $ngoData->cancelled_blank_cheque,$path=config('global.file_path.ngo_image'));
        $ngoData->pan_of_ngo = $this->GetImage($file_name = $ngoData->pan_of_ngo,$path=config('global.file_path.ngo_image'));
        $ngoData->registration_certificate = $this->GetImage($file_name = $ngoData->registration_certificate,$path=config('global.file_path.ngo_image'));

        $userData['user'] = User::where('login_type',3)->orderBy('name')->get();
        // dd($userData);
        // exit;
        $serviceData['service_needs'] = ServiceNeeds::orderBy('service_needs_name')->get();
        $specificData['specific_needs'] = SpecificNeeds::orderBy('specific_needs_name')->get();

        return view('admin.ngo.edit',compact('ngoData','userData','serviceData','specificData'));

    }

    public function ngo_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                Ngo::whereIn('ngo_id',$ids )->update(['ngo_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                Ngo::whereIn('ngo_id',$ids )->update(['ngo_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                Ngo::whereIn('ngo_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
            }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }

}
