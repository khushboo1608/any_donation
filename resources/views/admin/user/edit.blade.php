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
            <h1>User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">User</li>
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
                <h3 class="card-title">Edit Users</h3>
              </div>
              <div class="col-md-12 col-xs-12">
                    <div class="search_list">
                          <a href="{{url('admin/user')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>                        
                    </div>
                </div>    
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
              <form class="form-horizontal" action="{{route('user.saveuser')}}" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="id" id="id"  value="{{$UserData->id}}">   
                <div class="card-body">
                  <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name :-</label>
                    <div class="col-sm-6">
                    <input id="name" name="name" value="{{$UserData->name}}" type="text" class="form-control" required="true">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email :-</label>
                    <div class="col-sm-6">
                    <input id="email" name="email" value="{{$UserData->email}}" type="email" class="form-control" required="true">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password :-</label>
                    <div class="col-sm-6">
                    <input id="password" name="password" value="" type="password" minlength="6" class="form-control" >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="phone" class="col-sm-2 col-form-label">Phone no. :-</label>
                    <div class="col-sm-6">
                    <input id="phone" name="phone" value="{{$UserData->phone}}" maxlength="10" type="number" class="form-control" required="true">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="imageurl" class="col-sm-2 col-form-label">Select Profile Image:- </label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                      <input name="imageurl"  type="file" accept="image/png, image/jpeg, image/jpg" value="{{$UserData->imageurl}}" id="imageurl"  >
                                @if(isset($UserData) && isset($UserData->imageurl))
                                @if($UserData->imageurl !='')
                            <div class="fileupload_img"><img type="image" src="{{$UserData->imageurl}}" style="height: 80px;width: 80px;margin-left: 20px;"/></div>
                            @else
                            <div class="fileupload_img"><img type="image" src="{{config('global.no_image.add_image');}}" /></div>
                            @endif
                          @endif
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                      <div class="col-sm-6 col-md-offset-3 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href="{{url('admin/user')}}">Cancel</a>
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

@endsection