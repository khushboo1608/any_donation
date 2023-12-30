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
                    <?php  $type =  request()->type; ?>
                        @if(isset($type) && $type !='') 
                        @if($type ==0)
                          <a href="{{url('admin/user')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>                        
                        @elseif($type ==1)
                          <a href="{{url('admin/userngo')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>
                        @elseif($type ==2)
                          <a href="{{url('admin/userblood')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>
                        @endif
                        @endif  
                      </div>
                </div>    
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
              <form class="form-horizontal" action="{{route('user.saveuser')}}" method="post" enctype="multipart/form-data">
              @if(isset($type) && $type !='') 
              @if($type ==0)
              <input type="hidden" name="type" id="type"  value="0">                           
              @elseif($type ==1)
              <input type="hidden" name="type" id="type"  value="1">   
              @elseif($type ==2)
              <input type="hidden" name="type" id="type"  value="2">   
              @endif
              @endif  


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

                  <div class="form-group row login_type">
                    <label for="name" class="col-sm-2 col-form-label">User Type :-</label>
                    <div class="col-sm-6">
                      <select name="login_type" id="login_type"  class="form-control" required="true">
                        <option value="">--Select User type--</option>
                        <option value="2" {{ $UserData->login_type == '2' ? 'selected' : '' }}>User</option>
                        <option value="3" {{ $UserData->login_type == '3' ? 'selected' : '' }}>NGO</option>
                        <option value="4" {{ $UserData->login_type == '4' ? 'selected' : '' }}>Blood Bank</option>
                      </select>
                    </div>
                  </div>

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
                  <!-- <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password :-</label>
                    <div class="col-sm-6">
                    <input id="password" name="password" value="" type="password" minlength="6" class="form-control" >
                    </div>
                  </div> -->
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
                            <div class="fileupload_img"><img type="image" src="{{config('global.no_image.add_image')}}" /></div>
                            @endif
                          @endif
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="age" class="col-sm-2 col-form-label">Age :-</label>
                    <div class="col-sm-6">
                    <input id="age" name="age" value="{{$UserData->age}}" type="text" class="form-control" required="true">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="address" class="col-md-2 control-label">Address :-</label>
                    <div class="col-md-6">
                      <input type="text"  name="address" id="address" value="{{$UserData->address}}" class="form-control" required>
                      <div id="map" style="display: none"></div>
                            <input type="hidden" name="lat" id="latitude" value="{{$UserData->lat}}">
                            <input type="hidden" name="long" id="longitude" value="{{$UserData->long}}">
                    </div>
                  </div>
                  <!-- {{ $UserData->type_of_ngo }} -->
                  @if($UserData->login_type == 3)
                  
                  <div class="form-group row">
                    <label for="type_of_ngo" class="col-sm-2 col-form-label">Type of NGO :-</label>
                    <div class="col-sm-6">
                      <select name="type_of_ngo" id="type_of_ngo"  class="form-control" >
                        <option value="">--Select Type NGO--</option>
                        <option value="International NGOs" {{ $UserData->type_of_ngo == 'International NGOs' ? 'selected' : '' }}>International NGOs</option>
                        <option value="National NGOs" {{ $UserData->type_of_ngo == 'National NGOs' ? 'selected' : '' }} >National NGOs</option>
                        <option value="Citywide organizations" {{ $UserData->type_of_ngo == 'Citywide organizations' ? 'selected' : '' }} >Citywide organizations</option>
                        <option value="Advocacy NGOs" {{ $UserData->type_of_ngo == 'Advocacy NGOs' ? 'selected' : '' }}>Advocacy NGOs</option>
                        <option value="Charitable orientation" {{ $UserData->type_of_ngo == 'Charitable orientation' ? 'selected' : '' }} >Charitable orientation</option>
                        <option value="Civil society" {{ $UserData->type_of_ngo == 'Civil society' ? 'selected' : '' }} >Civil society</option>
                        <option value="Participatory orientation" {{ $UserData->type_of_ngo == 'Participatory orientation' ? 'selected' : '' }} >Participatory orientation</option>
                        <option value="Greenpeace" {{ $UserData->type_of_ngo == 'Greenpeace' ? 'selected' : '' }}>Greenpeace</option>
                        <option value="Oxfam international" {{ $UserData->type_of_ngo == 'Oxfam international' ? 'selected' : '' }} >Oxfam international</option>
                      </select>
                    </div>
                  </div>
                  @else
                  <div class="form-group row type_of_ngo" style="display:none;">
                    <label for="type_of_ngo" class="col-sm-2 col-form-label">Type of NGO :-</label>
                    <div class="col-sm-6">
                      <select name="type_of_ngo" id="type_of_ngo"  class="form-control" >
                        <option value="">--Select Type NGO--</option>
                        <option value="International NGOs" {{ $UserData->type_of_ngo == 'International NGOs' ? 'selected' : '' }}>International NGOs</option>
                        <option value="National NGOs" {{ $UserData->type_of_ngo == 'National NGOs' ? 'selected' : '' }} >National NGOs</option>
                        <option value="Citywide organizations" {{ $UserData->type_of_ngo == 'Citywide organizations' ? 'selected' : '' }} >Citywide organizations</option>
                        <option value="Advocacy NGOs" {{ $UserData->type_of_ngo == 'Advocacy NGOs' ? 'selected' : '' }}>Advocacy NGOs</option>
                        <option value="Charitable orientation" {{ $UserData->type_of_ngo == 'Charitable orientation' ? 'selected' : '' }} >Charitable orientation</option>
                        <option value="Civil society" {{ $UserData->type_of_ngo == 'Civil society' ? 'selected' : '' }} >Civil society</option>
                        <option value="Participatory orientation" {{ $UserData->type_of_ngo == 'Participatory orientation' ? 'selected' : '' }} >Participatory orientation</option>
                        <option value="Greenpeace" {{ $UserData->type_of_ngo == 'Greenpeace' ? 'selected' : '' }}>Greenpeace</option>
                        <option value="Oxfam international" {{ $UserData->type_of_ngo == 'Oxfam international' ? 'selected' : '' }} >Oxfam international</option>
                      </select>
                    </div>
                  </div>
                  @endif

                  @if($UserData->login_type == 4)
                  <div class="form-group row">
                    <label for="type_of_blood_bank" class="col-sm-2 col-form-label">Type of Blood :-</label>
                    <div class="col-sm-6">
                      <select name="type_of_blood_bank" id="type_of_blood_bank"  class="form-control" >
                        <option value="">--Select Type blood--</option>
                        <option value="O negative" {{ $UserData->type_of_blood_bank == 'O negative' ? 'selected' : '' }}>O negative</option>
                        <option value="A negative" {{ $UserData->type_of_blood_bank == 'A negative' ? 'selected' : '' }}>A negative</option>
                        <option value="B negative" {{ $UserData->type_of_blood_bank == 'B negative' ? 'selected' : '' }}>B negative</option>
                        <option value="AB negative" {{ $UserData->type_of_blood_bank == 'AB negative' ? 'selected' : '' }}>AB negative</option>
                      </select>
                    </div>
                  </div>
                  @else
                  <div class="form-group row type_of_blood_bank" style="display:none;">
                    <label for="type_of_blood_bank" class="col-sm-2 col-form-label">Type of Blood :-</label>
                    <div class="col-sm-6">
                      <select name="type_of_blood_bank" id="type_of_blood_bank"  class="form-control" >
                        <option value="">--Select Type blood--</option>
                        <option value="O negative" {{ $UserData->type_of_blood_bank == 'O negative' ? 'selected' : '' }}>O negative</option>
                        <option value="A negative" {{ $UserData->type_of_blood_bank == 'A negative' ? 'selected' : '' }}>A negative</option>
                        <option value="B negative" {{ $UserData->type_of_blood_bank == 'B negative' ? 'selected' : '' }}>B negative</option>
                        <option value="AB negative" {{ $UserData->type_of_blood_bank == 'AB negative' ? 'selected' : '' }}>AB negative</option>
                      </select>
                    </div>
                  </div>
                  @endif

                  <div class="form-group row">
                    <label for="state_id" class="col-sm-2 col-form-label">State Name :-</label>
                    <div class="col-sm-6">
                      <select name="state_id" id="state_id"  class="form-control" required="true">
                        <option value="">--Select State name--</option>
                        
                        @foreach ($stateData['state'] as $item)
                        @php $selected = explode(",", $UserData['state_id']);
                        @endphp
                          <option value="{{$item['state_id']}}" {{ (in_array($item['state_id'], $selected)) ? 'selected' : '' }}>{{$item['state_name']}}</option>
                        @endforeach

                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="city_id" class="col-sm-2 col-form-label">City Name :-</label>
                    <div class="col-sm-6">
                      <select name="city_id" id="city_id"  class="form-control" required="true">
                        @foreach ($cityData['city'] as $item)
                          @php $selected = explode(",", $UserData['city_id']);
                          @endphp
                            <option value="{{$item['city_id']}}" {{ (in_array($item['city_id'], $selected)) ? 'selected' : '' }}>{{$item['city_name']}}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  @if($UserData->login_type == 4)
                  <div class="form-group row">
                    <label for="blood_bank_history" class="col-sm-2 col-form-label">History :-</label>
                    <div class="col-sm-6">
                    <input id="blood_bank_history" name="blood_bank_history" value="{{$UserData->blood_bank_history}}" type="text" class="form-control" >
                    </div>
                  </div>
                  @else
                  <div class="form-group row blood_bank_history" style="display:none;">
                    <label for="blood_bank_history" class="col-sm-2 col-form-label">History :-</label>
                    <div class="col-sm-6">
                    <input id="blood_bank_history" name="blood_bank_history" value="{{$UserData->blood_bank_history}}" type="text" class="form-control" >
                    </div>
                  </div>
                  @endif

                  <div class="form-group row">
                    <label for="gender" class="col-sm-2 col-form-label">Gender :-</label>
                    <div class="col-sm-6">
                      <input type="radio" name="gender" id="gender" value="1" {{ $UserData->gender == '1' ? 'checked' : '' }} > Male
                      <input type="radio" name="gender" id="gender" value="2" {{ $UserData->gender == '2' ? 'checked' : '' }}> Female 
                    </div>
                  </div>
                  @if($UserData->login_type == 2)
                  
                  <div class="form-group row">
                    <label for="profession" class="col-sm-2 col-form-label">Profession :-</label>
                    <div class="col-sm-6">
                    <input id="profession" name="profession" value="{{$UserData->profession}}" type="text" class="form-control" >
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="blood_group" class="col-sm-2 col-form-label">Blood Group :-</label>
                    <div class="col-sm-6">
                    <input id="blood_group" name="blood_group" value="{{$UserData->blood_group}}" type="text" class="form-control" >
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="is_interested" class="col-sm-2 col-form-label">Interested in Blood Donation* :-</label>
                    <div class="col-sm-6">
                      <input type="radio" name="is_interested" id="is_interested" value="1" {{ $UserData->is_interested == '1' ? 'checked' : '' }}> Yes
                      <input type="radio" name="is_interested" id="is_interested" value="0" {{ $UserData->is_interested == '0' ? 'checked' : '' }}> No 
                    </div>
                  </div>
                  @else
                  
                  <div class="form-group row profession" style="display:none;">
                    <label for="profession" class="col-sm-2 col-form-label">Profession :-</label>
                    <div class="col-sm-6">
                    <input id="profession" name="profession" value="{{$UserData->profession}}" type="text" class="form-control" >
                    </div>
                  </div>

                  <div class="form-group row blood_group" style="display:none;">
                    <label for="blood_group" class="col-sm-2 col-form-label">Blood Group :-</label>
                    <div class="col-sm-6">
                    <input id="blood_group" name="blood_group" value="{{$UserData->blood_group}}" type="text" class="form-control" >
                    </div>
                  </div>
                  
                  <div class="form-group row is_interested" style="display:none;">
                    <label for="is_interested" class="col-sm-2 col-form-label">Interested in Blood Donation* :-</label>
                    <div class="col-sm-6">
                      <input type="radio" name="is_interested" id="is_interested" value="1" {{ $UserData->is_interested == '1' ? 'checked' : '' }}> Yes
                      <input type="radio" name="is_interested" id="is_interested" value="0" {{ $UserData->is_interested == '0' ? 'checked' : '' }}> No 
                    </div>
                  </div>
                  @endif
                  
                  <div class="form-group row">
                      <div class="col-sm-6 col-md-offset-3 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <?php  $type =  request()->type; ?>
                        @if(isset($type) && $type !='') 
                        @if($type ==0)
                        <a class="btn btn-danger" href="{{url('admin/user')}}">Cancel</a>
                        @elseif($type ==1)
                        <a class="btn btn-danger" href="{{url('admin/userngo')}}">Cancel</a>
                        @elseif($type ==2)
                        <a class="btn btn-danger" href="{{url('admin/userblood')}}">Cancel</a>
                        @endif
                        @endif
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(e) {
  $(".login_type").change(function(){
    var type=$("#login_type").val();
    // alert(type);
    if(type == 2)
    {
      $(".profession").show();
      $(".blood_group").show();
      $(".is_interested").show();
      $(".type_of_ngo").hide();
      $(".type_of_blood_bank").hide();
      $(".blood_bank_history").hide();
  
    }else if(type == 3)
    {
      $(".profession").hide();
      $(".blood_group").hide();
      $(".is_interested").hide();
      $(".type_of_ngo").show();
      $(".type_of_blood_bank").hide();
      $(".blood_bank_history").hide();
    }else if(type == 4)
    {
      $(".profession").hide();
      $(".blood_group").hide();
      $(".is_interested").hide();
      $(".type_of_ngo").hide();
      $(".type_of_blood_bank").show();
      $(".blood_bank_history").show();
    }
  });
});
</script>
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