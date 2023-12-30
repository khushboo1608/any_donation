<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\PhotoDataTable;
use App\Models\User;
use App\Models\Photos;
use Illuminate\Support\Facades\Session;
use Response;

class AdminPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(PhotoDataTable $dataTable)
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
                return '<input type="checkbox" id="checkbox'.$data->photo_id.'"  value="'.$data->photo_id.'"  name="photo_ids[]" class="photo_ids" />';
            })
            ->editColumn('photo_url', function ($data) {
                $photo_url = $this->GetImage($file_name = $data->photo_url,$path=config('global.file_path.photo_image'));
                
                if($photo_url == '')
                {
                    $photo_url = config('global.no_image.no_image');
                }
                return $photo_url;
            })
            ->addColumn('photo_status', function ($data) {
                $btn1='';
                $checked = ($data['photo_status'] == 1) ? "" : "checked";
                $title =  ($data['photo_status'] == 1) ? "Disable" : "Enable";
                if($data['photo_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->photo_id.'\','.$data->photo_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->photo_id.'\','.$data->photo_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.photos");
                $btn = '<a href="'.$url.'/edit/'.$data->photo_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeletePhotos(\''.$data->photo_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','photo_status','checkbox'])
            ->make(true);
        }
        return view('admin.photos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function photos_multi_status(Request $request)
        {
            $action=$request->action;

                if(!empty($request->id)) {
                    $ids=$request->id;
                }
                if($action=='enable'){				
                    Photos::whereIn('photo_id',$ids )->update(['photo_status' => 0]);
                    $msg = __('Enable successfully');
                    $text = "Enabled";

                }else if($action=='disable'){

                    Photos::whereIn('photo_id',$ids )->update(['photo_status' => 1]);
                    $msg = __('Disable successfully');
                    $text = "Disable";
                    
                }else if($action=='delete'){
                    
                    Photos::whereIn('photo_id',$ids )->delete();
                    $msg = __('Deleted successfully');
                    $text = "Deleted";
                }
            return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
        }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savephotos(Request $request)
    {
        //
         // echo "<pre>";
        // print_r($request->all());die;
        $photoData = $request->all();
        $message="";
        $photo_url = '';
        if($photoData['photo_id'] !=''){
            $photo=Photos::where(['photo_id'=>$photoData['photo_id']])->first();
            if($request->photo_url != "")
            {   
                $photo_url = $this->UploadImage($file = $request->photo_url,$path = config('global.file_path.photo_image'));
            }
            else{
                $photo_url =$photo->photo_url;
            }
            $photoData['photo_url'] = $photo_url;
            $photo = Photos::find($photoData['photo_id']);
            $photo->fill($photoData);
            $photo->save();
            $message="Data Updated Successfully";

        }else{
            
            if($request->photo_url != "")
            {   
                $photo_url = $this->UploadImage($file = $request->photo_url,$path = config('global.file_path.photo_image'));
            }
            else{
                $photo_url =$request->photo_url;
            }
            $photoData['photo_url'] = $photo_url;
            $photo_id = $this->GenerateUniqueRandomString($table='photos', $column="photo_id", $chars=6);
            $photoData['photo_id'] = $photo_id;
            // $photoData['photo_status'] = 1;
            Photos::create($photoData);
            $message="Data Insert Successfully";
        } 

        Session::flash('message', $message);      
        return redirect('admin/photos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photo_data_edit($id)
    {
        //
        $photoData = Photos::where('photo_id', $id)->first();
        $photoData->photo_url = $this->GetImage($file_name = $photoData->photo_url,$path=config('global.file_path.photo_image'));

        $userData['user'] = User::where('login_type',$photoData->photo_type)->orderBy('name')->get();
        // dd($userData);
        // exit;
        return view('admin.photos.edit',compact('photoData','userData'));
        // return view('admin.photos.edit')->with(['photoData' => $photoData]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photo_status(Request $request)
    {
        //
        $photo_id = $request->id;
        Photos::where('photo_id',$photo_id)->update(['photo_status' => $request->photo_status]);
        if($request->photo_status == 0)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photos_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $photos_id  = $request->id;
        Photos::where('photo_id',$photos_id)->delete();
        return Response::json(['result' => true,'message'=> 'Photo deleted..!']);
    }

    public function add_photos()
    {
        return view('admin.photos.add');
    }

    public function fetchUser(Request $request)
    {
        // dd($request);
        // exit;
        $data['user'] = User::where("login_type", $request->user_login_type)
                                ->get(["name", "id"]);
  
        return response()->json($data);
    }
}
