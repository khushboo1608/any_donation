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
            <h1>NGO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">NGO</li>
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
                <h3 class="card-title">Add NGO</h3>
              </div>
              <div class="col-md-12 col-xs-12">
                    <div class="search_list">
                          <a href="{{url('admin/ngo')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>                        
                    </div>
                </div>    
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
              <form class="form-horizontal" action="{{route('ngo.savengo')}}" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="ngo_id" id="ngo_id" value="">   
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
                    <label for="account_holder_name" class="col-sm-2 col-form-label"> Account Holder name :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="account_holder_name" name="account_holder_name" placeholder="Account Holder name" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="account_number" class="col-sm-2 col-form-label"> Account Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="account_number" name="account_number" placeholder="Account Number" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="ifsc_code" class="col-sm-2 col-form-label"> IFSC code :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder="IFSC code" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="upi_number" class="col-sm-2 col-form-label"> UPI Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="upi_number" name="upi_number" placeholder="UPI Number" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="gpay_number" class="col-sm-2 col-form-label"> Gpay Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="gpay_number" name="gpay_number" placeholder="Gpay Number" required="true">
                    </div>
                  </div>
                  
                  
                  <div class="form-group row">
                    <label for="address_proof" class="col-sm-2 col-form-label">Address Proof :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="address_proof"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="jj_act" class="col-sm-2 col-form-label">JJ act :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="jj_act"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="form_10_b" class="col-sm-2 col-form-label">Form 10b :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="form_10_b"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="a12_certificate" class="col-sm-2 col-form-label">A12 Certificate :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="a12_certificate"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="cancelled_blank_cheque" class="col-sm-2 col-form-label">Cancelled blank cheque :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="cancelled_blank_cheque"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="pan_of_ngo" class="col-sm-2 col-form-label">Pan of ngo:-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="pan_of_ngo"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="registration_certificate" class="col-sm-2 col-form-label">Registration Certificate:-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="registration_certificate"  type="file" value="fileupload" id="fileupload" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="achievements" class="col-sm-2 col-form-label"> Achievements :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="achievements" name="achievements" placeholder="Achievements" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="history" class="col-sm-2 col-form-label"> History :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="history" name="history" placeholder="History" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="purpose" class="col-sm-2 col-form-label"> Purpose :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="purpose" name="purpose" placeholder="Purpose" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="started_in" class="col-sm-2 col-form-label"> Started In :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="started_in" name="started_in" placeholder="Started in" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="size" class="col-sm-2 col-form-label"> Size :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="size" name="size" placeholder="Size" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="service_needs_id" class="col-sm-2 col-form-label">Service Needs :-</label>
                    <div class="col-sm-6">
                      <select name="service_needs_id" id="service_needs_id"  class="form-control" required="true">
                        <option value="">--Select Service name--</option>
                        
                        @foreach ($serviceData['service_needs'] as $item)
                          <option value="{{$item['service_needs_id']}}">{{$item['service_needs_name']}}</option>
                        @endforeach

                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="specific_needs_id" class="col-sm-2 col-form-label">Specific Needs :-</label>
                    <div class="col-sm-6">
                      <select name="specific_needs_id" id="specific_needs_id"  class="form-control" required="true">
                        <option value="">--Select Specific name--</option>
                        
                        @foreach ($specificData['specific_needs'] as $item)
                          <option value="{{$item['specific_needs_id']}}">{{$item['specific_needs_name']}}</option>
                        @endforeach

                      </select>
                    </div>
                  </div>
                  
                

                  <div class="form-group row">
                      <div class="col-sm-6 col-md-offset-3 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href="{{url('admin/ngo')}}">Cancel</a>
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