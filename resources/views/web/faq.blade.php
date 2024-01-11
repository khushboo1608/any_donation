@extends('layouts.app')
@section('content')
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KNK2NX9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@include('layouts.header')
<main class="main__content_wrapper">
    <div class="container" style="margin-top: 50px; margin-bottom:50px;">
        <h1 class="success-title text-center">FAQ’s</h1>
        <p class="sub-title-success text-center"> {!! $SettingsData->app_faq !!}</p>
    </div>
</main>
@include('layouts.footer')
@endsection
