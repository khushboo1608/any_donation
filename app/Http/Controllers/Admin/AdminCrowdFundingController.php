<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrowdFunding;
use App\Models\City;
use App\Models\State;
use App\DataTables\CrowdFundingDataTable;
use Illuminate\Support\Facades\Session;
use Response;

class AdminCrowdFundingController extends Controller
{
    //
    public function __construct(CrowdFundingDataTable $dataTable){
        $this->middleware('is_admin');
        $this->dataTable = $dataTable;
    }

    public function index(Request $request){
           //
           if ($request->ajax()) {
            // print_r($data); die;
            return datatables()::of($this->dataTable->all($request))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '<input type="checkbox" id="checkbox'.$data->crowdfundings_id.'"  value="'.$data->crowdfundings_id.'"  name="crowdfundings_ids[]" class="crowdfundings_ids" />';
            })
            ->addColumn('crowdfundings_status', function ($data) {
                $btn1='';
                $checked = ($data['crowdfundings_status'] == 1) ? "" : "checked";
                $title =  ($data['crowdfundings_status'] == 1) ? "Disable" : "Enable";
                if($data['crowdfundings_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->crowdfundings_id.'\','.$data->crowdfundings_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->crowdfundings_id.'\','.$data->crowdfundings_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.crowd_funding");
                $btn = '<a href="'.$url.'/edit/'.$data->crowdfundings_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteCrowd(\''.$data->crowdfundings_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','crowdfundings_status','checkbox'])
            ->make(true);
        }
        return view('admin.crowd_funding.index');
    }

    public function add_crowd_funding()
    {
        //
        $stateData['state'] = State::orderBy('state_name')->get();
        return view('admin.crowd_funding.add',compact('stateData'));
    }

    public function savecrowdfunding(Request $request)
    {
        //
        $crowdData = $request->all();
        $message="";
        $crowdfundings_single_image = '';
        $crowdfundings_patient1_image = '';
        $crowdfundings_patient2_image = '';
        $crowdfundings_medical_certificate = '';
        $crowd_gallery= [];
        if($crowdData['crowdfundings_id'] !=''){
            $member=CrowdFunding::where(['crowdfundings_id'=>$crowdData['crowdfundings_id']])->first();
            if($request->crowdfundings_single_image != "")
            {   
                $crowdfundings_single_image = $this->UploadImage($file = $request->crowdfundings_single_image,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_single_image =$member->crowdfundings_single_image;
            }

            if($request->crowdfundings_patient1_image != "")
            {   
                $crowdfundings_patient1_image = $this->UploadImage($file = $request->crowdfundings_patient1_image,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_patient1_image =$member->crowdfundings_patient1_image;
            }

            if($request->crowdfundings_patient2_image != "")
            {   
                $crowdfundings_patient2_image = $this->UploadImage($file = $request->crowdfundings_patient2_image,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_patient2_image =$member->crowdfundings_patient2_image;
            }

            if($request->crowdfundings_medical_certificate != "")
            {   
                $crowdfundings_medical_certificate = $this->UploadImage($file = $request->crowdfundings_medical_certificate,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_medical_certificate =$member->crowdfundings_medical_certificate;
            }

            if($request->crowdfundings_multi_image != "")
            {   
                foreach ($crowdData['crowdfundings_multi_image'] as $key => $value) {
                    $crowd_gallery[] = $this->UploadImage($file = $value,$path = config('global.file_path.crowd_image'));
                }
                    $gallery = implode(',',$crowd_gallery); 
                // $gym_interior_images = $this->UploadImage($file = $request->gym_interior_images,$path = config('global.file_path.gym_img'));
            }else{
                $gallery = $request->crowdfundings_multi_image;
            }


        
            $arr2 = explode(",",$gallery);
                // dd($request->vendor_gallery);
                // exit;
                if($member->crowdfundings_multi_image != ""){
                    $arr1 = explode(",",$member->crowdfundings_multi_image);
                }else{
                    $arr1= [];
                }
                $arr2 = array_filter($arr2, 'strlen');
               
                if(count($arr1)>0){
                    $newarray = array_merge($arr1, $arr2);
                    $newarray1 = implode(',', $newarray);
                    // dd($newarray);
                    // exit;
                }else{
                    $newarray1 = implode(',', $arr2);
                }

            $crowdData['crowdfundings_multi_image'] = $newarray1;
            $crowdData['crowdfundings_single_image'] = $crowdfundings_single_image;
            $crowdData['crowdfundings_patient1_image'] = $crowdfundings_patient1_image;
            $crowdData['crowdfundings_patient2_image'] = $crowdfundings_patient2_image;
            $crowdData['crowdfundings_medical_certificate'] = $crowdfundings_medical_certificate;
            $member = CrowdFunding::find($crowdData['crowdfundings_id']);
            $member->fill($crowdData);
            $member->save();
            $message="Data Updated Successfully";

        }else{
            
            if($request->crowdfundings_single_image != "")
            {   
                $crowdfundings_single_image = $this->UploadImage($file = $request->crowdfundings_single_image,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_single_image =$request->crowdfundings_single_image;
            }

            if($request->crowdfundings_patient1_image != "")
            {   
                $crowdfundings_patient1_image = $this->UploadImage($file = $request->crowdfundings_patient1_image,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_patient1_image =$request->crowdfundings_patient1_image;
            }

            if($request->crowdfundings_patient2_image != "")
            {   
                $crowdfundings_patient2_image = $this->UploadImage($file = $request->crowdfundings_patient2_image,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_patient2_image =$request->crowdfundings_patient2_image;
            }

            if($request->crowdfundings_medical_certificate != "")
            {   
                $crowdfundings_medical_certificate = $this->UploadImage($file = $request->crowdfundings_medical_certificate,$path = config('global.file_path.crowd_image'));
            }
            else{
                $crowdfundings_medical_certificate =$request->crowdfundings_medical_certificate;
            }

            if($request->hasFile('crowdfundings_multi_image')){
                foreach($request->crowdfundings_multi_image as $key => $value){
                    $crowdfundings_multi_image[] = $this->UploadImage($file = $value,$path = config('global.file_path.crowd_image'));
                }
                // echo '<pre>';
                // print_r($vendor_gallery); exit;
                $gallery = implode(',',$crowdfundings_multi_image);
                
            } else {
                // echo 'else';
                $gallery = $request->crowdfundings_multi_image;
            }


            $crowdData['crowdfundings_single_image'] = $crowdfundings_single_image;
            $crowdData['crowdfundings_patient1_image'] = $crowdfundings_patient1_image;
            $crowdData['crowdfundings_patient2_image'] = $crowdfundings_patient2_image;
            $crowdData['crowdfundings_multi_image'] = $gallery;
            $crowdData['crowdfundings_medical_certificate'] = $gallery;
            $crowdfundings_id = $this->GenerateUniqueRandomString($table='crowd_fundings', $column="crowdfundings_id", $chars=6);
            $crowdData['crowdfundings_id'] = $crowdfundings_id;
            // $photoData['photo_status'] = 1;
            CrowdFunding::create($crowdData);
            $message="Data Insert Successfully";
        } 

        Session::flash('message', $message);      
        return redirect('admin/crowd_funding');
    }

    public function crowd_funding_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $crowd_id  = $request->id;
        CrowdFunding::where('crowdfundings_id',$crowd_id)->delete();
        return Response::json(['result' => true,'message'=> 'Crowd deleted..!']);
    }

    public function crowd_funding_status(Request $request)
    {
        //
        $crowd_id = $request->id;
        CrowdFunding::where('crowdfundings_id',$crowd_id)->update(['crowdfundings_status' => $request->crowd_status]);
        if($request->crowd_status == 0)
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

    public function crowd_funding_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                CrowdFunding::whereIn('crowdfundings_id',$ids )->update(['crowdfundings_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                CrowdFunding::whereIn('crowdfundings_id',$ids )->update(['crowdfundings_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                CrowdFunding::whereIn('crowdfundings_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
            }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }

    public function crowd_data_edit($id)
    {
        //
        $crowdData = CrowdFunding::where('crowdfundings_id', $id)->first();
        $crowdData->crowdfundings_single_image = $this->GetImage($file_name = $crowdData->crowdfundings_single_image,$path=config('global.file_path.crowd_image'));
        $crowdData->crowdfundings_patient1_image = $this->GetImage($file_name = $crowdData->crowdfundings_patient1_image,$path=config('global.file_path.crowd_image'));
        $crowdData->crowdfundings_patient2_image = $this->GetImage($file_name = $crowdData->crowdfundings_patient2_image,$path=config('global.file_path.crowd_image'));
        $crowdData->crowdfundings_medical_certificate = $this->GetImage($file_name = $crowdData->crowdfundings_medical_certificate,$path=config('global.file_path.crowd_image'));
        
        $gallerys = [];
        if(isset($crowdData->crowdfundings_multi_image))
        {  
            $gallery1 = explode(',',$crowdData->crowdfundings_multi_image);
            foreach ($gallery1 as $key => $val) {
                $gallerys[] = $this->GetImage($val,$path=config('global.file_path.crowd_image'));
            }
            // $gym_interior_images = explode(',',$gymData->gym_interior_images);
        } 
       
        
        $crowdData->crowdfundings_multi_image = $gallerys;
        
        // dd($userData);
        // exit;
        $stateData['state'] = State::orderBy('state_name')->get();
        $cityData['city'] = City::where('state_id',$crowdData->state_id)->orderBy('city_name')->get();
        return view('admin.crowd_funding.edit',compact('crowdData','stateData','cityData'));

    }

    public function crowd_delete_img($id, $img_id)
    {
        //
        // echo 'hii';exit;
        $data = CrowdFunding::where('crowdfundings_id',$id)->first();
        $gallery = explode(",", $data->crowdfundings_multi_image);  
        // dd($gallery);
        // exit;
        $image = "";
        foreach(array_keys($gallery, $img_id) as $key){
            unset($gallery[$key]);
        }

        if(count($gallery)>0){
            $image = implode(',',$gallery);
        }else{
            $image = NULL;
        }
        CrowdFunding::where('crowdfundings_id',$id)->update(['crowdfundings_multi_image' => $image]);
        $message="Image Deleted Successfully";
        Session::flash('message', $message);
        return $this->crowd_data_edit($id);
    }



}
