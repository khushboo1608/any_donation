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
            <h1>Member</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">Member</li>
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
                <h3 class="card-title">Add Member</h3>
              </div>
              <div class="col-md-12 col-xs-12">
                    <div class="search_list">
                          <a href="{{url('admin/member')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>                        
                    </div>
                </div>    
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
              <form class="form-horizontal" action="{{route('member.savemember')}}" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="member_details_id" id="member_details_id" value="">   
                <div class="card-body">

                <div class="form-group row">
                    <label for="user_id" class="col-sm-2 col-form-label">User Name :-</label>
                    <div class="col-sm-6">
                      <select name="user_id" id="user_id"  class="form-control" required="true">
                        <option value=""> Select NGO User </option>
                        @foreach ($userData as $item)
                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="member_name" class="col-sm-2 col-form-label"> Member Name :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="member_name" name="member_name" placeholder="Member Name" required="true">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="member_image" class="col-sm-2 col-form-label">Select Image :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="member_image"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="member_phone_number" class="col-sm-2 col-form-label"> Member Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="member_phone_number" name="member_phone_number" placeholder="Member Number" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="member_position" class="col-sm-2 col-form-label"> Member Position :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="member_position" name="member_position" placeholder="Member Position" required="true">
                    </div>
                  </div>
                

                  <div class="form-group row">
                      <div class="col-sm-6 col-md-offset-3 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href="{{url('admin/member')}}">Cancel</a>
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