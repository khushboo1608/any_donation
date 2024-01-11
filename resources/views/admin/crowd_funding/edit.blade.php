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
            <h1>Crowd Funding</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">Crowd Funding</li>
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
                <h3 class="card-title">Edit Crowd Funding</h3>
              </div>
              <div class="col-md-12 col-xs-12">
                    <div class="search_list">
                          <a href="{{url('admin/crowd_funding')}}"><button type="button"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</button></a>                        
                    </div>
                </div>    
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
              <form class="form-horizontal" action="{{route('crowd_funding.savecrowdfunding')}}" method="post" enctype="multipart/form-data">
              @csrf
                  <div class="col-md-12">
                        <div class="col-md-12 col-sm-12">
                            @if(session()->has('message')) 
                             <div class="alert alert-success alert-dismissible" role="alert"> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                {{session('message')}}</a> 
                            </div>
                            @endif   
                        </div>
              <input type="hidden" name="crowdfundings_id" id="crowdfundings_id" value="{{$crowdData->crowdfundings_id}}">   
                <div class="card-body">
                  
                <div class="form-group row">
                    <label for="crowdfundings_title" class="col-sm-2 col-form-label"> Title :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_title" name="crowdfundings_title" placeholder="Event promotions title" required="true" value="{{$crowdData->crowdfundings_title}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_single_image" class="col-sm-2 col-form-label">Select Image :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="crowdfundings_single_image"  type="file" value="fileupload" id="fileupload"  accept="image/png, image/jpeg, image/jpg">
                        <!-- <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div> -->
                        @if(isset($crowdData) && isset($crowdData->crowdfundings_single_image))
                              @if($crowdData->crowdfundings_single_image !='')
                            <div class="fileupload_img"><img type="image" src="{{$crowdData->crowdfundings_single_image}}" style="height: 80px;width: 80px;margin-left: 20px;"  /></div>
                            @else
                            <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}" /></div>
                            @endif
                          @endif
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_multi_image" class="col-md-2 control-label">Select Mutiple Image :-
                      <!-- <p class="control-label-help">(Recommended resolution: 696x300 Image)</p> -->
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="crowdfundings_multi_image[]" value="fileupload" id="fileupload" multiple>
                            
                            <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}" alt="category image" /></div>
                           
                      </div>
                    </div>
                  </div>
                  <br>
                    <div class="form-group">
                      <?php 
                      $vendor_gallery = [];
                      $url=route("admin.crowd_funding");
                      // echo $VendorData->vendor_gallery;
                      // exit;
                      ?>
                      @if(isset($crowdData) && isset($crowdData->crowdfundings_multi_image))
                        @if($crowdData->crowdfundings_multi_image !='')
                          @foreach ($crowdData->crowdfundings_multi_image as $item)
                          <?php //dd($item); ?>
                          <?php //echo $item;  ?>
                            <div class="col-md-2">
                              <div class="fileupload_block">
                                  <div class="fileupload_img">
                                    <img type="image" src="{{$item}}" alt="category image" style=" height: 150px;width: 150px;"/>
                                    <a href="{{$item}}" target="_blank" ><b> View </b></a> 
                                    @if($item !='')
                                    @php 
                                      $img_id = explode('crowd_image', $item); 
                                    
                                      $gallery_img = $img_id[1];
                                      @endphp
                                      
                                      <a href="{{$url}}/delete_img/{{$crowdData->crowdfundings_id}}{{$gallery_img}}" style="margin-left: 20px;"><b> Delete </b></a>
                                    @endif
                                    
                                  </div>
                                </div>
                                        
                            </div> 
                           
                            @endforeach
                            <?php //exit; ?>
                        @endif
                      @endif
                     
                     
                    </div>
                    @if(isset($crowdData) && isset($crowdData->crowdfundings_multi_image))
                        @if(is_array($crowdData->crowdfundings_multi_image))
                            {{-- Convert the array to a string --}}
                            <?php  $galleryValue = implode(',', $crowdData->crowdfundings_multi_image); ?>
                            <input id="crowdfundings_multi_image_edit1" type="hidden" name="crowdfundings_multi_image_edit1" value="{{ htmlspecialchars($galleryValue) }}">
                        @else
                            <input id="crowdfundings_multi_image_edit" type="hidden" name="crowdfundings_multi_image_edit" value="{{ htmlspecialchars($crowdData->crowdfundings_multi_image) }}">
                        @endif
                    @endif
                    <br>                   
                    </div>

                  <div class="form-group row">
                    <label for="crowdfundings_type" class="col-sm-2 col-form-label"> Type :-</label>
                    <div class="col-sm-6">
                      <select name="crowdfundings_type" id="crowdfundings_type"  class="form-control" required="true">
                        <option value="">--Select Type--</option>
                        <option value="Orphange1" {{ $crowdData->crowdfundings_type == 'Orphange1' ? 'selected' : '' }}>Orphange1</option>
                        <option value="Orphange2" {{ $crowdData->crowdfundings_type == 'Orphange2' ? 'selected' : '' }}>Orphange2</option>
                        <option value="Orphange3" {{ $crowdData->crowdfundings_type == 'Orphange3' ? 'selected' : '' }}>Orphange3</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_purpose" class="col-sm-2 col-form-label"> Purpose :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_purpose" name="crowdfundings_purpose" placeholder="Crowd Fundings Purpose" required="true" value="{{$crowdData->crowdfundings_purpose}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_issue" class="col-sm-2 col-form-label"> Issue :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_issue" name="crowdfundings_issue" placeholder="Event promotions Number" required="true" value="{{$crowdData->crowdfundings_issue}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_amount" class="col-sm-2 col-form-label"> Amount :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_amount" name="crowdfundings_amount" placeholder="Event promotions Email" required="true" value="{{$crowdData->crowdfundings_amount}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_patient1_age" class="col-sm-2 col-form-label"> Patient1 Name :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_patient1_age" name="crowdfundings_patient1_age" placeholder="Event promotions Email" required="true" value="{{$crowdData->crowdfundings_patient1_age}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_amount" class="col-sm-2 col-form-label"> Patient1 Age :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_amount" name="crowdfundings_amount" placeholder="Event promotions Email" required="true" value="{{$crowdData->crowdfundings_amount}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_patient1_image" class="col-sm-2 col-form-label">Patient1 Select Image :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="crowdfundings_patient1_image"  type="file" value="fileupload" id="fileupload"  accept="image/png, image/jpeg, image/jpg">
                        <!-- <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div> -->
                        @if(isset($crowdData) && isset($crowdData->crowdfundings_patient1_image))
                              @if($crowdData->crowdfundings_patient1_image !='')
                            <div class="fileupload_img"><img type="image" src="{{$crowdData->crowdfundings_patient1_image}}" style="height: 80px;width: 80px;margin-left: 20px;"  /></div>
                            @else
                            <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}" /></div>
                            @endif
                          @endif
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_patient2_name" class="col-sm-2 col-form-label"> Patient2 Name :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_patient2_name" name="crowdfundings_patient2_name" placeholder="Event promotions Email" required="true" value="{{$crowdData->crowdfundings_patient2_name}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_patient2_age" class="col-sm-2 col-form-label"> Patient2 Age :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_patient2_age" name="crowdfundings_patient2_age" placeholder="Event promotions Email" required="true" value="{{$crowdData->crowdfundings_patient2_age}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_patient2_image" class="col-sm-2 col-form-label">Patient2 Select Image :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="crowdfundings_patient2_image"  type="file" value="fileupload" id="fileupload"  accept="image/png, image/jpeg, image/jpg">
                        <!-- <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div> -->
                        @if(isset($crowdData) && isset($crowdData->crowdfundings_patient2_image))
                              @if($crowdData->crowdfundings_patient2_image !='')
                            <div class="fileupload_img"><img type="image" src="{{$crowdData->crowdfundings_patient2_image}}" style="height: 80px;width: 80px;margin-left: 20px;"  /></div>
                            @else
                            <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}" /></div>
                            @endif
                          @endif
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_medical_certificate" class="col-sm-2 col-form-label"> Certificate Select Image :-</label>
                    <div class="col-sm-6">
                      <div class="fileupload_block">
                        <input name="crowdfundings_medical_certificate"  type="file" value="fileupload" id="fileupload"  accept="image/png, image/jpeg, image/jpg">
                        <!-- <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}"  /></div> -->
                        @if(isset($crowdData) && isset($crowdData->crowdfundings_medical_certificate))
                              @if($crowdData->crowdfundings_medical_certificate !='')
                            <div class="fileupload_img"><img type="image" src="{{$crowdData->crowdfundings_medical_certificate}}" style="height: 80px;width: 80px;margin-left: 20px;"  /></div>
                            @else
                            <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}" /></div>
                            @endif
                          @endif
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_address" class="col-md-2 control-label">Address :-</label>
                    <div class="col-md-6">
                      <input type="text"  name="crowdfundings_address" id="address" value="{{$crowdData->crowdfundings_address}}" class="form-control" required>
                      <div id="map" style="display: none"></div>
                            <input type="hidden" name="crowdfundings_lat" id="latitude" value="{{$crowdData->crowdfundings_lat}}">
                            <input type="hidden" name="crowdfundings_long" id="longitude" value="{{$crowdData->crowdfundings_long}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="state_id" class="col-sm-2 col-form-label">State Name :-</label>
                    <div class="col-sm-6">
                      <select name="state_id" id="state_id"  class="form-control" required="true">
                        <option value="">--Select State name--</option>
                        
                        @foreach ($stateData['state'] as $item)
                        @php $selected = explode(",", $crowdData['state_id']);
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
                          @php $selected = explode(",", $crowdData['city_id']);
                          @endphp
                            <option value="{{$item['city_id']}}" {{ (in_array($item['city_id'], $selected)) ? 'selected' : '' }}>{{$item['city_name']}}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="crowdfundings_account_number" class="col-sm-2 col-form-label"> Account Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_account_number" name="crowdfundings_account_number" placeholder="Crowd Fundings account number" required="true" value="{{$crowdData->crowdfundings_account_number}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_account_holder_name" class="col-sm-2 col-form-label"> Account Holder Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_account_holder_name" name="crowdfundings_account_holder_name" placeholder="Crowd Fundings account holder number" required="true" value="{{$crowdData->crowdfundings_account_holder_name}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_ifsc_code" class="col-sm-2 col-form-label"> IFSC Code :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_ifsc_code" name="crowdfundings_ifsc_code" placeholder="Crowd Fundings ifsc code" required="true" value="{{$crowdData->crowdfundings_ifsc_code}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_upi_number" class="col-sm-2 col-form-label"> UPI Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_upi_number" name="crowdfundings_upi_number" placeholder="Crowd Fundings upi number" required="true" value="{{$crowdData->crowdfundings_upi_number}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="crowdfundings_gpay_number" class="col-sm-2 col-form-label"> GPay Number :-</label>
                    <div class="col-sm-6">
                      <input type="Text" class="form-control" id="crowdfundings_gpay_number" name="crowdfundings_gpay_number" placeholder="Crowd Fundings gpay number" required="true" value="{{$crowdData->crowdfundings_gpay_number}}">
                    </div>
                  </div>

                  <div class="form-group row">
                      <div class="col-sm-6 col-md-offset-3 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href="{{url('admin/crowd_funding')}}">Cancel</a>
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

<script async defer src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{Helper::AppMapKey()}}&callback=initMap"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>


<script type="text/javascript">
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