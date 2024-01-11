@extends('layouts.app')
@section('content')
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KNK2NX9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<main class="main__content_wrapper">
<div class="login__section section--padding">
    <div class="container">

                    <form method="post" id="login-form" action="{{ url('login') }}" name="login-form">
                    <input type="hidden" name="login_type" value="web">
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
                        <div class="login__section--inner">
                        <div class="row row-cols-md-2 row-cols-1 justify-content-center">
                            <div class="col">
                            <div class="account__login">
                                <div class="account__login--header mb-25">
                                <h3 class="account__login--header__title mb-10">
                                    Login / Register
                                </h3>
                                <p class="account__login--header__desc">
                                    Login if you are returning customer.
                                </p>
                                </div>
                                <div class="account__login--inner ">
                                <div class="checkout__input--list mb-20 form-group">
                                <label>
                                    <input class="account__login--input" required="true" placeholder="Your Mobile No" type="number" name="phone" id="phone" maxlength="10">
                                </label>
</div>
                                <div class="account__login--remember__forgot mb-15 d-flex justify-content-between align-items-center">
                                    <div class="account__login--remember position__relative">
                                    <div class="checkout__input--list mb-20 form-group">   
                                    
                                    <label class="checkout__checkbox--label login__remember--label" for="check1">I agree to the Terms &amp; Condition</label>
                                    <input class="checkout__checkbox--input" id="check1"  name="check1" type="checkbox" required>
                                    <span class="checkout__checkbox--checkmark"></span>
</div>
                                    </div>
                                </div>
                                <button class="account__login--btn primary__btn" type="submit">
                                    Login
                                </button>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </form>
                    </div>
    </div>
</main>
@endsection
@section('scripts')
<script>
    $(document).ready(function ()
    {
        // $('.pace-done').css('background','#f3f3f4');
        $('#login-form').validate({ // initialize the plugin
            rules: {
                phone:{
                    noSpace: true,
                    required: true,
                    maxlength:10,
                    },
                check1: {
                    required: true,
                    noSpace:true
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {            
                error.addClass('invalid-feedback');               
                element.closest('.form-group').append(error);
                $('.error').css("font-weight", "bold");
                $('.error').css("color", "red");
                // $('.error').css("float", "left");
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');                
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            messages: {
                phone:{
                    required: "{{__('messages.web.user.phone_number_required')}}"
                    },
                check1: {
                    required: "{{__('messages.web.user.check1_required')}}",
                   
                }
            }
        });

        setTimeout(function(){
            $("div.alert").remove();
        }, 5000 ); // 5 secs
   
    });  
</script>
@endsection
