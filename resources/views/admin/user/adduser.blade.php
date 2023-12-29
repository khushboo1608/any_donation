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
                <h3 class="card-title">Add Users</h3>
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
              <input type="hidden" name="id" id="id" value="">   
                <div class="card-body">

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">User Type :-</label>
                    <div class="col-sm-6">
                      <select name="login_type" id="login_type"  class="form-control" required="true">
                        <option value="">--Select User type--</option>
                        <option value="2" >User</option>
                        <option value="3" >NGO</option>
                        <option value="4">Blood Bank</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name :-</label>
                    <div class="col-sm-6">
                    <input id="name" name="name" value="" type="text" class="form-control" required="true">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email :-</label>
                    <div class="col-sm-6">
                    <input id="email" name="email" value="" type="email" class="form-control" required="true">
                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password :-</label>
                    <div class="col-sm-6">
                    <input id="password" name="password" value="" minlength="6" type="password" class="form-control" required="true">
                    </div>
                  </div> -->
                  <div class="form-group row">
                    <label for="phone " class="col-sm-2 col-form-label">Phone no. :-</label>
                    <div class="col-sm-6">
                    <input id="phone" name="phone" value="" maxlength="10" type="number" class="form-control" required="true">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="imageurl" class="col-sm-2 col-form-label">Select Profile Image:-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="imageurl"  type="file" value="fileupload" id="imageurl" required="true" accept="image/png, image/jpeg, image/jpg">
                        <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="age" class="col-sm-2 col-form-label">Age :-</label>
                    <div class="col-sm-6">
                    <input id="age" name="age" value="" type="text" class="form-control" required="true">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="address" class="col-md-2 control-label">Address :-</label>
                    <div class="col-md-6">
                      <input type="text"  name="address" id="address" value="" class="form-control" required>
                      <div id="map" style="display: none"></div>
                            <input type="hidden" name="lat" id="latitude">
                            <input type="hidden" name="long" id="longitude">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="state_id" class="col-sm-2 col-form-label">State Name :-</label>
                    <div class="col-sm-6">
                      <select name="state_id" id="state_id"  class="form-control" required="true">
                        <option value="">--Select State name--</option>
                        
                        @foreach ($stateData['state'] as $item)
                          <option value="{{$item['state_id']}}">{{$item['state_name']}}</option>
                        @endforeach

                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="city_id" class="col-sm-2 col-form-label">City Name :-</label>
                    <div class="col-sm-6">
                      <select name="city_id" id="city_id"  class="form-control" required="true">
                       
                      </select>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label for="gender" class="col-sm-2 col-form-label">Gender :-</label>
                    <div class="col-sm-6">
                      <input type="radio" name="gender" id="gender" value="1" > Male
                      <input type="radio" name="gender" id="gender" value="2" > Female 
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="profession" class="col-sm-2 col-form-label">Profession :-</label>
                    <div class="col-sm-6">
                    <input id="profession" name="profession" value="" type="text" class="form-control" required="true">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="blood_group" class="col-sm-2 col-form-label">Blood Group :-</label>
                    <div class="col-sm-6">
                    <input id="blood_group" name="blood_group" value="" type="text" class="form-control" required="true">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="is_interested" class="col-sm-2 col-form-label">Interested in Blood Donation* :-</label>
                    <div class="col-sm-6">
                      <input type="radio" name="is_interested" id="is_interested" value="1" > Yes
                      <input type="radio" name="is_interested" id="is_interested" value="0" > No 
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{Helper::AppMapKey()}}&callback=initMap"></script>
<script type="text/javascript">
  $(document).on('click', '.remove_activity', function() {
      $(this).closest('.row').remove();
      $('.selectpicker').selectpicker();      
  });

    function initMap()
    {
        var map = new google.maps.Map(document.getElementById('map'), {
        mapTypeControl: false,
        center: {lat: 41.85, lng: -87.65},
        zoom: 7
        });
        new AutocompleteDirectionsHandler(map);
    }
    function AutocompleteDirectionsHandler(map)
    {
        this.map = map;
        var originInput = document.getElementById('address');
        var originAutocomplete = new google.maps.places.Autocomplete(
            originInput, {});
        google.maps.event.addListener(originAutocomplete, 'place_changed', function () {
            var place = originAutocomplete.getPlace();
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            $("#latitude").val(lat);
            $("#longitude").val(lng);
        });
    }

    function show1(){
      document.getElementById('morning_eve_div').style.display ='block';
      document.getElementById('full_day_div').style.display = 'none';
    }
    function show2(){
      document.getElementById('full_day_div').style.display = 'block';
      document.getElementById('morning_eve_div').style.display = 'none';
    }

    $(document).ready(function () {
      $('#state_id').on('change', function () {
          var idState = this.value;
          // alert(idState);
          $("#city_id").html('');
          $.ajax({
              url: "{{url('admin/fetch_city')}}",
              type: "POST",
              data: {
                  state_id: idState,
                  _token: '{{csrf_token()}}'
              },
              dataType: 'json',
              success: function (result) {
              
                  $('#city_id').html('<option value="">Select City</option>');
                  $.each(result.city, function (key, value) {
                    // alert(value);
                      $("#city_id").append('<option value="' + value
                          .city_id + '">' + value.city_name + '</option>');
                  });
                  
              }
          });
      });
    });
</script>

@endsection