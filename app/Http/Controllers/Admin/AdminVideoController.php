<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\VideoDataTable;
use App\Models\Videos;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Response;

class AdminVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(VideoDataTable $dataTable)
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
                return '<input type="checkbox" id="checkbox'.$data->video_id.'"  value="'.$data->video_id.'"  name="video_ids[]" class="video_ids" />';
            })
            ->editColumn('video_type', function ($data){
                $type = '';
                if($data['video_type'] == 3)
                {
                    $type = 'NGO';
                }else if($data['video_type'] == 4){
                    $type = 'Blood Bank';
                }
                return $type;
            })
            ->addColumn('video_status', function ($data) {
                $btn1='';
                $checked = ($data['video_status'] == 1) ? "" : "checked";
                $title =  ($data['video_status'] == 1) ? "Disable" : "Enable";
                if($data['video_status'] == 1){
                    $btn1 = '<button type="button"  class="btn btn-danger btn-sm" onclick="Status(\''.$data->video_id.'\','.$data->video_status.')">'.$title.' </i>
                    </button>';
                }
                else{
                    $btn1 = '<button type="button"  class="btn btn-success btn-sm" onclick="Status(\''.$data->video_id.'\','.$data->video_status.')" >'.$title.' </i>
                    </button>';  
                }               
                return $btn1;
            })
            ->addColumn('action', function($data){

                $url=route("admin.videos");
                $btn = '<a href="'.$url.'/edit/'.$data->video_id.'" style="color: white !important" ><button type="button" class="edit btn btn-primary btn-sm editPost"  title="edit"><i class="fa fa-edit"></i>
                </button></a>&nbsp;&nbsp;<button type="button"  class="btn btn-danger btn-sm deletePost" onclick="DeleteVideos(\''.$data->video_id.'\')" title="edit"><i class="fa fa-trash"></i>
                </button>';

                 return $btn;
         })
            ->rawColumns(['action','video_status','checkbox'])
            ->make(true);
        }
        return view('admin.videos.index');
    }

    public function add_videos()
    {
        return view('admin.videos.add');
    }

    public function savevideos(Request $request)
    {
        //
         // echo "<pre>";
        // print_r($request->all());die;
        $videoData = $request->all();
        $message="";
        if($videoData['video_id'] !=''){
            $video=Videos::where(['video_id'=>$videoData['video_id']])->first();
            $video = Videos::find($videoData['video_id']);
            $video->fill($videoData);
            $video->save();
            $message="Data Updated Successfully";

        }else{

            $video_id = $this->GenerateUniqueRandomString($table='videos', $column="video_id", $chars=6);
            $videoData['video_id'] = $video_id;
            // $photoData['photo_status'] = 1;
            Videos::create($videoData);
            $message="Data Insert Successfully";
        } 

        Session::flash('message', $message);      
        return redirect('admin/videos');
    }

    public function videos_delete(Request $request)
    {
        //
        // dd($request);
        // exit;
        $videos_id  = $request->id;
        Videos::where('video_id',$videos_id)->delete();
        return Response::json(['result' => true,'message'=> 'Photo deleted..!']);
    }

    public function videos_status(Request $request)
    {
        //
        $video_id = $request->id;
        Videos::where('video_id',$video_id)->update(['video_status' => $request->video_status]);
        if($request->video_status == 0)
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

    public function videos_multi_status(Request $request)
    {
        $action=$request->action;

            if(!empty($request->id)) {
                $ids=$request->id;
            }
            if($action=='enable'){				
                Videos::whereIn('video_id',$ids )->update(['video_status' => 0]);
                $msg = __('Enable successfully');
                $text = "Enabled";

            }else if($action=='disable'){

                Videos::whereIn('video_id',$ids )->update(['video_status' => 1]);
                $msg = __('Disable successfully');
                $text = "Disable";
                
            }else if($action=='delete'){
                
                Videos::whereIn('video_id',$ids )->delete();
                $msg = __('Deleted successfully');
                $text = "Deleted";
            }
        return Response::json(['result' => true,'message'=>$msg,'text' =>$text]);
    }

    public function video_data_edit($id)
    {
        //
        $videoData = Videos::where('video_id', $id)->first();

        $userData['user'] = User::where('login_type',$videoData->video_type)->orderBy('name')->get();
        // dd($userData);
        // exit;
        return view('admin.videos.edit',compact('videoData','userData'));
        // return view('admin.photos.edit')->with(['photoData' => $photoData]);
    }
    
}


   