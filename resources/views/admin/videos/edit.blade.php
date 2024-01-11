@extends('admin.layouts.app')
@section('content')
{{-- Sidebar goes here --}}
    @include('admin.layouts.sidebar')
    {{-- Header goes here --}}
    @include('admin.layouts.header')
<!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Video</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">Video</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
              
              <div class="col-md-5 col-xs-12">
                <h3 class="card-title">Edit Video</h3>
              </div>
              <div class="col-md-12 col-xs-12">
                    <div class="search_list">
                          <a href="{{url('admin/videos')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>                        
                    </div>
                </div>    
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
              <form class="form-horizontal" action="{{route('videos.savevideos')}}" method="post" enctype="multipart/form-data">
              @csrf
              @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
              <input type="hidden" name="video_id" id="video_id" value="{{$videoData->video_id}}">   
                <div class="card-body">
                  
                <div class="form-group row">
                    <label for="photo_type" class="col-sm-2 col-form-label">Video Type :-</label>
                    <div class="col-sm-6">
                      <select name="video_type" id="photo_type"  class="form-control" >
                        <option value="">--Select Video type--</option>
                        <option value="3" {{ $videoData->video_type == '3' ? 'selected' : '' }}>NGO</option>
                        <option value="4" {{ $videoData->video_type == '4' ? 'selected' : '' }}>Blood Bank</option>
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="user_id" class="col-sm-2 col-form-label">User Name :-</label>
                    <div class="col-sm-6">
                      <select name="user_id" id="user_id"  class="form-control" required="true">
                        @foreach ($userData['user'] as $item)
                          @php $selected = explode(",", $videoData['user_id']);
                          @endphp
                            <option value="{{$item['id']}}" {{ (in_array($item['id'], $selected)) ? 'selected' : '' }}>{{$item['name']}}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label for="video_url" class="col-sm-2 col-form-label">Video url :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="video_url" name="video_url" placeholder="Video url" required="true" value="{{$videoData->video_url}}">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                      <div class="col-sm-6 col-md-offset-3 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href="{{url('admin/videos')}}">Cancel</a>
                      </div>
                  </div>
                </div>

              </form>
                    <!-- </div> -->
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
         {{-- Footer goes here --}}
         <!-- @include('admin.layouts.footer') -->
     </div>
 </div>
                    <!-- </div> -->
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
         {{-- Footer goes here --}}
         <!-- @include('admin.layouts.footer') -->
     </div>
 </div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $('#photo_type').on('change', function () {
          var idPhotoType = this.value;
        //   alert(idPhotoType);
          $("#user_id").html('');
          $.ajax({
              url: "{{url('admin/fetch_user')}}",
              type: "POST",
              data: {
                  user_login_type : idPhotoType,
                  _token: '{{csrf_token()}}'
              },
              dataType: 'json',
              success: function (result) {
              
                  $('#user_id').html('<option value="">Select User</option>');
                  $.each(result.user, function (key, value) {
                    // alert(value);
                      $("#user_id").append('<option value="' + value
                          .id + '">' + value.name + '</option>');
                  });
                  
              }
          });
    });
});
</script>

@endsection