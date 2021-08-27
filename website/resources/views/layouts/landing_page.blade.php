<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('page-title', 'LMS')</title>
        <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/svg+xml" sizes="16x16">

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        {{-- <link rel="stylesheet" href="css/usman_stylesheet.css"> --}}
        <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />
        @yield('header-css')
        @stack('header-css-stack')

        <script type="text/javascript">
            let UPLOAD_URL = "{{ asset('uploads/') }}";
            let ASSET_URL = "{{ asset('') }}";
        </script>
        @stack('header-scripts')
        <style>
            #bg_color: {
                background-color: #5B933A;
            }
        </style>
    </head>
    <body class="background_image-s">
        <div class="container">
            {{-- header section - START --}}
            <div class="row justify-content-between py-3">
                <div class="col-md-1 col-12">
                    <!--Logo -->
                    <div class="">
                        <a href='javascript:void(0)'>
                            <img src="{{ asset('assets/images/logo.svg') }}" width="" alt="logo" />
                        </a>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-10 col-md-11 justify-content-md-end d-md-flex pr-0">
                    <!--phone no.-->
                    <div class="px-md-3 pt-md-2 pt-3 d-flex">
                        <div>
                            <img src="{{ asset('assets/images/telephone_icon.svg') }}" alt="phone" />
                        </div>
                        <div class="pl-2">
                            <span><strong>Call</strong></span>
                            <br>
                            <samll> +2 342 4456 45</samll>
                        </div>
                    </div>
                    <!--Email-->
                    <div class="px-md-3 pt-md-2 pt-3 d-flex">
                        <div>
                            <img src="{{ asset('assets/images/email_icon.svg') }}" alt="email" />
                        </div>
                        <div class="pl-2">
                            <span><strong>Mail</strong></span>
                            <br>
                            <samll>+roomatoz@gmail.com</samll>
                        </div>
                    </div>
                    <!--Address-->
                    <div class="px-md-3 pt-md-2 pt-3 d-flex">
                        <div>
                            <img src="{{ asset('assets/images/hompage_location_icon.svg') }}" alt="address" />
                        </div>
                        <div class="pl-2">
                            <span><strong>Address</strong></span>
                            <br>
                            <samll>Franklin St, Greenpoint Ave</samll>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            {{-- header section - END --}}


            <!--navbar Start-->
            <div class="row justify-content-between">
                <div class="col-lg-6 col-4 px-0">
                    <nav class="navbar navbar-expand-lg navbar-light px-lg-0">
                        <!--toggler menu button for medium screen-->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <a href="" id="menu-toggle"><img src="{{ asset('assets/images/menu.svg') }}" alt="" width="25" class="filter-green-pin d-lg-none"></a>
                        </button>

                        <!--navbar menu-->
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav">
                                @include('partials.header_links', [])
                            </ul>
                        </div>
                    </nav>
                </div>

                <!--social Links Icons-->
                <div class="col-md-4 col-8  px-0">
                    <div class="navbar navbar-expand float-right  px-lg-0">
                        <div class="collapse navbar-collapse ">
                            <ul class="nav navbar-nav ">
                                <li class="nav-item px-xl-4 px-lg-3 px-2">
                                    @auth
                                        @php
                                            $redirectRoute = 'javascript:void(0)';//route('home');
                                            if(\Auth::check()){
                                                if(\Auth::user()->profile_type =='teacher'){
                                                    $redirectRoute = route('teacher.dashboard');
                                                } else if(\Auth::user()->profile_type == 'student'){
                                                    $redirectRoute = route('student.dashboard');
                                                } else if (\Auth::user()->profile_type == 'parent') {
                                                    $redirectRoute = route('parent.dashboard');
                                                }
                                                else {
                                                    $redirectRoute = route('adminDashboard');
                                                }
                                            }
                                        @endphp
                                        <a class="nav-link text-dark  fs_19px-s green_bottom_on_hover-s" href="{{ $redirectRoute }}"><strong>Dashboard</strong></a>
                                    @else
                                        <a class="nav-link text-dark  fs_19px-s green_bottom_on_hover-s" href="{{ route('login') }}"><strong>Login</strong></a>
                                    @endauth
                                </li>

                                <li class="nav-item dropdown pr-2 h_35px-s">
                                    @auth

                                        <a class="nav-link dropdown-toggle top_navbar_profile_link-d text-dark fs_19px-s" href="javascript:void(0)" id="auth_dropdown-d" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ \Auth::user()->profile->full_name ?? 'Profile Setting' }}</a>
                                        <div class="dropdown-menu bg_green_on_hover-s dropdown-menu-right" aria-labelledby="auth_dropdown-d">
                                            <a class="dropdown-item" href="{{ route('updateprofileSetting') }}">Profile Setting</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('signout') }}">Logout</a>
                                        </div>
                                    @else
                                        <a class="nav-link dropdown-toggle top_navbar_profile_link-d text-dark fs_19px-s" href="javascript:void(0)" id="no_auth_dropdown-d" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Register</a>
                                        <div class="dropdown-menu bg_green_on_hover-s dropdown-menu-right" aria-labelledby="no_auth_dropdown-d">
                                            <a class="dropdown-item" href="{{ route('register') }}">Teacher</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('registerStudent') }}">Student</a>
                                        </div>
                                    @endauth
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--navbar end-->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <footer class="page-footer font-large fg_footer-s ">

            <!-- Footer Links -->
            <div class="container py-5">
                <!-- Grid row -->
                <div class="row justify-content-center">
                    <!-- Grid column -->
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 pt-3 text-left">
                        <!-- Content -->
                        <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                        <p class="pt-4 fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly</p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 pt-3 text-left fg_light_grey-s">
                        <!--phone no.-->
                        <div class="pb-2 ">
                            <span><strong>Call</strong></span>
                            <br>
                            <samll> +2 342 4456 45</samll>
                        </div>
                        <!--Email-->
                        <div class="py-2">
                            <span><strong>Mail</strong></span>
                            <br>
                            <samll>+roomatoz@gmail.com</samll>
                        </div>
                        <!--Address-->
                        <div class="py-2">
                            <span><strong>Address</strong></span>
                            <br>
                            <samll>Franklin St, Greenpoint Ave</samll>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 pt-3 text-left">
                        <h5 class="pb-1"><strong>Quick LInks</strong></h5>
                        <a href="{{ route('cms.privacy-policy', []) }}" class="no_link-s fg_light_grey-s py-1">Privacy Policy</a>
                        <br>
                        <a href="{{ route('cms.terms-and-services', []) }}" class="no_link-s fg_light_grey-s py-1">Terms of Service</a>
                        <br>
                        <a href="{{ route('cms.payment-refund-policy', []) }}" class="no_link-s fg_light_grey-s py-1 ">Refund Policy</a>
                        <br>
                        <a href="{{ route('cms.cookies-policy', []) }}" class="no_link-s fg_light_grey-s py-1">Cookies Policy</a>
                        <br>
                        <a href="{{ route('cms.about-us', []) }}" class="no_link-s fg_light_grey-s py-1">About Us</a>
                        <br>
                        <a href="{{ route('contactUs', []) }}" class="no_link-s fg_light_grey-s py-1">Contact Us</a>
                        <br><br>
                        <div class="d-flex">
                            <!--facebook logo-->
                            <div class="pr-2">
                                <a href="javascript:void(0)" class="no_link-s">
                                    <img src="{{ asset('assets/images/facebook_icon.svg') }}" alt="facebook icon">
                                </a>
                            </div>
                            <!--insta logo-->
                            <div class="px-2">
                                <a href="javascript:void(0)" class="no_link-s">
                                    <img src="{{ asset('assets/images/insta_icon.svg') }}" alt="insta icon">
                                </a>
                            </div>
                            <!--tweeter logo-->
                            <div class="px-2">
                                <a href="javascript:void(0)" class="no_link-s">
                                    <img src="{{ asset('assets/images/tweeter_icon.svg') }}" alt="twitter icon">
                                </a>
                            </div>
                        </div>

                    </div>

                    {{-- Generated Google map - START --}}
                    <div class="pt-3 col-xl-3 col-lg-3 col-md-6 col-12 pb-5">
                        <div class="row">
                            <div class="col-12">
                                <div style="width: 100%">
                                    <div class="mapouter">
                                        <div class="gmap_canvas">
                                            <iframe width="100%" height="259" id="gmap_canvas" src="https://maps.google.com/maps?q=Franklin%20St,%20Greenpoint%20Ave&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                            <a href="https://www.embedgooglemap.net/blog/divi-discount-code-elegant-themes-coupon/">divi discount</a>
                                            <br>
                                            <style>
                                            .mapouter{position:relative;text-align:right;height:259px;width:100%;}</style>
                                            <a href="https://www.embedgooglemap.net">interactive google maps for website</a>
                                            <style>.gmap_canvas {overflow:hidden;background:none!important;height:259px;width:100%;}</style>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Generated Google map - END --}}
                </div>
                <!-- Grid row -->

                <div class="row">
                    <div class="col-12">
                        <hr>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="footer-copyright fg_light_grey-s fg_light_grey-d fg_light_grey-t py-3">
                    © 2020 Copyright Room A to Z All rights Reserved.
                </div>
                <!-- Copyright -->
            </div>
            <!-- Footer Links -->



        </footer>
        <!-- Footer -->

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

        <script>
            $(function (event) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
        </script>

        {{--  Custom Scripts  --}}
        <script src="{{ asset('assets/js/theme.js') }}"></script>
        <script src="{{ asset('assets/js/common.js') }}"></script>

        @stack('footer-head-scripts')
        @yield('footer-scripts')

    </body>
</html>
