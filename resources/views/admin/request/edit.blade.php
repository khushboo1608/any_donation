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
            <h1>Request</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">Request</li>
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
                <h3 class="card-title">Edit Request</h3>
              </div>
              <div class="col-md-12 col-xs-12">
                    <div class="search_list">
                          <a href="{{url('admin/request')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>                        
                    </div>
                </div>    
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
              <form class="form-horizontal" action="{{route('request.saverequest')}}" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="request_details_id" id="request_details_id" value="{{$requestData->request_details_id}}">   
                <div class="card-body">
                  
               
                <div class="form-group row">
                    <label for="user_id" class="col-sm-2 col-form-label">User Name :-</label>
                    <div class="col-sm-6">
                      <select name="user_id" id="user_id"  class="form-control" required="true">
                        @foreach ($userData['user'] as $item)
                          @php $selected = explode(",", $requestData['user_id']);
                          @endphp
                            <option value="{{$item['id']}}" {{ (in_array($item['id'], $selected)) ? 'selected' : '' }}>{{$item['name']}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="request_blood_group" class="col-sm-2 col-form-label">Type of Blood Group:-</label>
                    <div class="col-sm-6">
                    <select name="request_blood_group" id="request_blood_group"  class="form-control" >
                        <option value="">--Select Type blood--</option>
                        <option value="A+" {{ $requestData->request_blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ $requestData->request_blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ $requestData->request_blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ $requestData->request_blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="O+" {{ $requestData->request_blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ $requestData->request_blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                        <option value="AB+" {{ $requestData->request_blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ $requestData->request_blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                    </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="request_unit" class="col-sm-2 col-form-label">Request Unit :-</label>
                    <div class="col-sm-6">
                    <select name="request_unit" id="request_unit"  class="form-control" >
                        <option value="">--Select Type blood--</option>
                        <option value="1" {{ $requestData->request_unit == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $requestData->request_unit == '2' ? 'selected' : '' }}>2</option>
                        <option value="3" {{ $requestData->request_unit == '3' ? 'selected' : '' }}>3</option>
                        <option value="4" {{ $requestData->request_unit == '4' ? 'selected' : '' }}>4</option>
                        <option value="5" {{ $requestData->request_unit == '5' ? 'selected' : '' }}>5</option>
                    </select>
                    </div>
                </div>

                  <div class="form-group row">
                      <div class="col-sm-6 col-md-offset-3 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href="{{url('admin/request')}}">Cancel</a>
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