@extends('layouts.app')
@section('content')
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KNK2NX9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<main class="main__content_wrapper">
    <div class="container" style="margin-top: 50px; margin-bottom:50px;">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-sm-12 col-md-12 col-lg-6">
                <div class="main-address-box text-center">
                    <h3 class="form-h3">Update Profile</h3>
                    <form id="post-form" method="post" action="{{ route('saveProfile')}}" enctype="multipart/form-data">
                    @csrf
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <?php 
                    // echo "<pre>";
                    // print_r($user_data);die;
                    ?>
                    <input type="hidden" name="id" id="id" value="{{$user_data['id']}}">
                        
                        <div class="checkout__input--list mb-20">
                            <input class="checkout__input--field border-radius-5" placeholder="Enter Your Name" id="input1 name" name="name" type="text" value="{{$user_data['name']}}">
                        </div>
                        <div class="checkout__input--list mb-20">
                            <input class="checkout__input--field border-radius-5" placeholder="Enter Your Email" id="input2 email" name="email" type="email" value="{{$user_data['email']}}">
                        </div>
                        <div class="checkout__input--list mb-20">
                            <input class="checkout__input--field border-radius-5" placeholder="Enter Your Mobile number" id="input3 phone" name="phone" value="{{$user_data['phone']}}" type="number" readonly>
                        </div>
                       
                        <div class="checkout__input--list mb-20 form-group">
                        <div class="fileupload_block" style="border: 1px solid #999;padding: 10px;margin-bottom: 15px;float: left;width: 100%;border-radius: 2px;display: flex;justify-content: left;align-items: center;">
                            <input class="checkout__input--field border-radius-5 padding-11" id="imageurl" name="imageurl"  type="file" accept="image/png, image/jpeg, image/jpg" style="border: unset;">
                            @if(isset($user_data) && isset($user_data->imageurl))
                              @if($user_data->imageurl !='')
                            <div class="fileupload_img"><img type="image" src="{{$user_data->imageurl}}" style="height: 80px;width: 80px;"  /></div>
                            @else
                            <div class="fileupload_img"><img type="image" src="{{asset('admin_assets/images/add-image.png')}}" /></div>
                            @endif
                          @endif
                        </div>
                        </div>
                        <button type="submit" class="primary__btn w-200" name="submit" value="Submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('scripts')
<script>
    $(document).ready(function ()
    {    
        $('#post-form').validate({ // initialize the plugin
            rules: {
                imageurl: {
                    accept:"jpg,png,jpeg",
                },
                name:{
                    noSpace: true,
                    required: true
                    },
                email: {
                    noSpace: true,
                    required: true,
                    email: true
                },
                phone:{
                    noSpace: true,
                    required: true,
                    }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) { 
                error.addClass('invalid-feedback');               
                element.closest('.form-group').append(error);
                $('.error').css("font-weight", "bold"); 
                $('.error').css("color", "red");
                $('.error').css("float", "left");
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');                
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            messages: {
                imageurl:{
                    required: "{{__('messages.web.user.profile_photo_required')}}",
                    // accept:"Please upload jpg,png,jpeg image only",
                },
                name:{
                    required: "{{__('messages.web.user.name_required')}}"
                    },                
                email: {
                    required: "{{__('messages.web.user.email_required')}}",
                    email: "{{__('messages.web.user.email_format')}}"
                },
                phone:{
                    required: "{{__('messages.web.user.phone_number_required')}}"
                    }
            },
            submitHandler: function(form){
              
                    form.submit();
            },
            
        });
    });

</script>
@endsection
