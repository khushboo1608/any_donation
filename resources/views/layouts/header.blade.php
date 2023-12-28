<header class="b-header-main x-mobile-nav x-mobile-announcement d-none-mobile" role="banner" >
    <!-- <section class="wrapper-section-bar">
        <div class="chrome-width">
        <article tabindex="0" class="b-announcements">
            <h3 class="primary-announcement">15% Off All Sizes of Fabric</h3>
        </article>
        </div>
    </section> -->
    <section class="wrapper-section-subheader small-desktop-down-hide">
        <div class="chrome-width">
        <div class="wrapper-logo-subheader">
            <a
            href="@if (Auth::check()) {{url('webhome')}} @else {{url('/')}} @endif"
            class="logo"
            title="Spoonflower"
            aria-label="Spoonflower"
            target="_self"
            rel="noreferrer "
            ><img
                class="logo-svg"
                src="{{ url('assets/img/logo/nav-log.webp') }}"
                width="193"
                height="55"
                alt="Spoonflower"
            /></a>
        </div>
        <div class="wrapper-search small-desktop-down-hide">
            <div class="b-search-box">
            <section
                class="search-text-input-wrapper"
                role="search"
                aria-label="Site Search"
            >
                <!-- <div class="locationa-wrapper">
                <span class="locationa">location</span>
                </div> -->
                <div class="d-flex align-items-center search-wrapper">
                <button
                    id="submit-search"
                    type="submit"
                    class="btn x-dark x-square next-to-input search-box-button"
                    aria-label="Search"
                    title="Search" style="cursor: context-menu;">
                    <span class="ico ico-search" aria-hidden="true"></span>
                </button>
                <form action="#" id="searchForm" method="GET">
                @csrf
                <input
                    aria-owns="autofill-categories"
                    aria-autocomplete="list"
                    aria-controls="autofill-categories"
                    aria-expanded="false"
                    autocomplete="off"
                    class="search-input"
                    placeholder="Search"
                    role="searchbox"
                    value=""
                    id="searchbox"
                    name="query" style="width: 640px !important;"
                />
                </form>
                
                </div>
            </section>
            </div>
        </div>
        <section class="subheader-button-group">
        @if (Auth::check())
        <article class="b-login-join">
                <!-- <a href="{{route('logout')}}" class="link-button x-dark">LogOut </a> -->
               
                <div class="dropdown-user">
                    <div class="d-flex align-items-center">
                    <div class="icon-wrapper">
                        <img
                        src="{{Helper::LoggedWebUserImage()}}"
                        alt="user-icon"
                        />
                    </div>
                    <?php 
                    // echo "<pre>";
                    // print_r(Auth::user());die;
                    ?>
                    <h5 class="user-heading" style="display:inline-block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;max-width: 10ch;">{{(Auth::user()->login_type ==2) ?  Auth::user()->name:''}}</h5>
                    </div>
                    <div class="dropdown-content-user">
                    <a href="{{url('userProfile')}}" class="nav-link">My Account </a>
                    
                    <a class="dropdown-item" href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Log out</a>
                    </div>
                </div>
                <!-- <div class="b-cart-pop">
              <a href="{{url('cart')}}" class="cart-link link-button x-dark">
                <div class="icon-cart-number">
                  <span class="count"></span>
                  <img src="{{ url('assets/img/icon/c1.png') }}" />
                </div>
                <span class="cart-title">Cart</span>
              </a>
            </div>  -->
                <form id="logout-form" action="{{route('logout')}}" method="HEAD" style="display: none;">
                    <input type="hidden" name="is_web" value = "1">
                    @csrf
                </form>
              </article>
        @else
            <article class="b-login-join">
                <a href="{{route('userlogin')}}" class="link-button x-dark">Login / Register</a>
                <!-- <a href="{{route('register')}}" class="link-button x-dark">Register</a> -->
              </article>
              @endif
        </section>
        </div>
    </section>
</header>

<?php 
// $categories = Helper::Categorylist();
// echo "<pre>";
// print_r($categories);die;
?>

<!-- End header area-desktop -->
<!-- Start header area-mobile -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $(".mobile-nav-btn").click(function () {
    $(".b-mobile-navigation-cover").toggleClass("transform");
    $("body").addClass("overflow-hidden");
    });
});
$(document).ready(function () {
    $(".btn-close").click(function () {
    $(".b-mobile-navigation-cover").toggleClass("transform");
    $("body").removeClass("overflow-hidden");
    });

    // Assuming your form has an ID of "myForm" and the input has an ID of "myInput"
    $('#searchbox').on('keypress', function (e) {
            if (e.which === 13) {
                // 13 is the ASCII code for the "Enter" key
                e.preventDefault(); // Prevent the default form submission
                $('#searchForm').submit(); // Submit the form
            }
        });
});

</script>

<header class="b-header-main x-mobile-nav x-mobile-announcement d-none-desktop" role="banner" >
<article tabindex="0" class="b-announcements">
    <h3 class="primary-announcement">
    15% Off All Sizes of Fabric<span
        class="ico ico-chevron-down"
        aria-hidden="true"
    ></span>
    </h3>
    <ul tabindex="0" class="announcement-list">
    <li class="announcement-item">
        <a
        href="https://www.spoonflower.com/en/shop?on=fabric"
        class="announcement-link"
        >Save 15% on all sizes of fabric through 11:59 p.m. ET on April
        9, 2023. Exclusions may apply.</a
        >
    </li>
    </ul>
</article>
<section class="wrapper-section-bar">
    <div class="chrome-width">
    <button
        type="button"
        class="mobile-nav-btn x-non-full-width-mobile-button"
    >
        <span
        class="ico ico-menu"
        aria-hidden="false"
        aria-label="Mobile Navigation"
        title="Mobile Navigation"
        ></span></button
    ><a
        href="index.html"
        class="logo large-desktop-hide"
        title="Spoonflower"
        aria-label="Spoonflower"
        target="_self"
        rel="noreferrer "
        ><img
        class="logo-svg"
        src="{{ url('assets/img/logo/nav-log.webp') }}"
        width="138"
        height="33"
        alt="Spoonflower"
    /></a>
    <section class="b-user-navigation">
        <div class="b-cart-pop">
        <a href="cart.html" class="cart-link link-button x-dark">
            <div class="icon-cart-number">
            <span class="count">0</span>
            <img src="{{ url('assets/img/icon/c1.png') }}" />
            </div>
        </a>
        </div>
    </section>
    </div>
</section>
<section class="wrapper-search-menu large-desktop-hide">
    <div class="b-search-box">
    <section
        class="search-text-input-wrapper"
        role="search"
        aria-label="Site Search"
    >
        <div class="locationa-wrapper" onclick="selectCity()">
        <span class="locationa">location</span>
        </div>
        <div class="d-flex align-items-center search-wrapper">
        <button
            id="submit-search"
            type="submit"
            class="btn x-dark x-square next-to-input search-box-button"
            aria-label="Search"
            title="Search"
        >
            <span class="ico ico-search" aria-hidden="true"></span>
        </button>
        <input
            aria-owns="autofill-categories"
            aria-autocomplete="list"
            aria-controls="autofill-categories"
            aria-expanded="false"
            autocomplete="off"
            class="search-input"
            placeholder="Search By Products"
            role="searchbox"
            value=""
        />
        
        </div>
    </section>
    </div>
</section>
</header>
<section class="b-mobile-navigation-cover d-none-desktop" aria-modal="true" role="dialog"
>
<nav class="b-mobile-navigation">
    <button type="button" title="Close" aria-label="Close" class="btn btn-close" >
    <span class="ico ico-close" aria-hidden="false" aria-label="Close" title="Close"></span>
    </button>
    <ul class="b-menu-items show-hide">
    <li class="list-element x-headline-element">Welcome!</li>
    <li class="list-element x-guest-element">
        <article class="b-login-join x-menu">
        <a href="{{route('userlogin')}}" class="link-button">Login / Register</a>
        </article>
    </li>
    <div class="dropdown-user">
        <div class="d-flex align-items-center">
        <div class="icon-wrapper">
            <img
            src="{{ url('assets/img/other/comment-thumb2.webp') }}"
            alt="user-icon"
            />
        </div>
        <h5 class="user-heading">User Name</h5>
        </div>
        <div class="dropdown-content-user">
        <a href="{{url('userProfile')}}" class="nav-link">My Account </a>
        <a class="dropdown-item" href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Log out</a>
        </div>
    </div>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Fabric</span>
        <span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
        <ul class="drop-list">
        <li class="list-element x-parent-element">
            <button class="nav-link" type="button" aria-label="Open">
            <span class="nav-link-text">Fabric1</span>
            </button>
        </li>
        </ul>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Wallpaper</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
        <ul class="drop-list">
        <li class="list-element x-parent-element">
            <button class="nav-link" type="button" aria-label="Open">
            <span class="nav-link-text">Wallpaper2</span>
            </button>
        </li>
        </ul>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Living &amp; Decor</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Dining</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Bedding</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Design &amp; Sell</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Blog</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Programs &amp; Discounts</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
    </li>
    <li class="list-element x-parent-element">
        <button class="nav-link" type="button" aria-label="Open">
        <span class="nav-link-text">Help</span
        ><span class="ico ico-chevron-right" aria-hidden="true"></span>
        </button>
    </li>
    <li class="list-element">
        <a href="#" class="nav-link">Order</a>
    </li>
    </ul>
</nav>
</section>
</div>