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
                    <img src="{{ asset('assets/images/logo.svg') }}" width="" alt="logo" />
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
                        <a href="" id="menu-toggle"><img src="assets/preview/menu.svg" alt="" width="25" class="filter-green-pin d-lg-none"></a>
                    </button>

                    <!--navbar menu-->
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav">
                            <!--home-->
                            <li class="nav-item pl-2 pr-xl-4 pr-lg-3 pr-2">
                              <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s active" href="javascript:void(0)"><strong>Home</strong></a>
                            </li>
                            <!--classes-->
                            <li class="nav-item px-xl-4 px-lg-3 px-2">
                              <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s" href="javascript:void(0)"><strong>Classes</strong></a>
                            </li>
                            <!--teacher-->
                            <li class="nav-item px-xl-4 px-lg-3 px-2">
                                <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s" href="javascript:void(0)"><strong>Teacher</strong></a>
                            </li>
                            <!--contacts-->
                            <li class="nav-item px-xl-4 px-lg-3 px-2">
                                <a class="nav-link text-dark fs_19px-s green_bottom_on_hover-s" href="javascript:void(0)"><strong>Contacts</strong></a>
                            </li>
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

        <section>
            <div class="row py-5 my-5">
                <div class="col-md-6 col-12 align-self-center">
                    <h1 class="fs_64px-s mb-5">The Smarter Way To learn <span class="fg_parrot_green-s"><u>Anything</u></span> </h1>
                    <span class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate visual form of a document or a typeface without relying on meaningful content.</span>
                </div>
                <div class="col-6 d-none d-xl-block d-lg-block d-md-block">
                    <p>&nbsp;</p>
                </div>
            </div>
        </section>

        {{-- Brag about ourself - START --}}
        <section>
            <div class="row justify-content-center py-5">
                <div class="col-lg-8 col-md-10 col-12 text-center">
                    <h1 class="fs_30px_on_small_Screen-s">Welcome To <span class="fg_parrot_green-s">Room A To Z</span> </h1>
                </div>
                <div class="col-lg-5 col-md-7 col-12 text-center">
                    <span class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate visual form</span>
                </div>
            </div>
            <div class="row mt-2">
                <!--awesome Teacher Card-->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/graduation_cap_icon.svg') }}" alt="graduation-cap-icon">
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Awesome Teachers</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--awesome teacher card end-->

                <!--Global certificate card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/global_education_icon.svg') }}" alt="global education icon">
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Global Certificate</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--GLobal certificate card End-->

                <!--BEst program card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/atom_icon.svg') }}" alt="star programs" />
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Best Program</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--BEst program card End-->

                <!--Student Support Service card-->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/student_support_icon.svg') }}" alt="student support icon">
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Student Support Service</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Student Support Service card End-->
            </div>
        </section>
        {{-- Brag about ourself - END --}}


        <!--Show course Section-->
        <section class="background_circle_image-s py-5">
            <div class="row mt-5">
                <div class="col-12">
                    <!--Title-->
                    <h1 class="ml-3 fs_30px_on_small_Screen-s">Our Courses</h1>
                    <!--intro-->
                    <p class="fg_light_grey-s text-wrap text-break">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate</p>
                </div>
            </div>

            <div class="row">
                <!--show courses carousal - START -->
                @include('partials/courses', [])
                <!--show courses carousal - END -->
            </div>

            <!--Course button-->
            <div class="row justify-content-center mt-4">
                <div class="col-md-3 col-5 ">
                    <button type="button" class="btn text-white bg_green_gradient-s rounded-pill border-0 w-100 py-2">Courses</button>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="row justify-content-center">
                <div class="col-5 text-center">
                    <h1  class="fs_30px_on_small_Screen-s">Our Awesome Teachers</h1>
                </div>
                <div class="col-8 text-center">
                    <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate</p>
                </div>
            </div>

            @php
                // dd(getAllApprovedTeachers());
            @endphp
            <div class="row mt-4">
                @include('partials.teachers', [])
            </div>
        </section>

        <section style='display:none;'>
            <div class="row justify-content-center">
                <div class="col-5 text-center">
                    <h1  class="fs_30px_on_small_Screen-s">Recent News</h1>
                </div>
                <div class="col-8 text-center">
                    <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card border-0" >
                        <div class="position-relative">
                            <img class="card-img-top br_19px-s img_h_240px-s w-100" src="assets/landing_page/teacher1.jpg" alt="Card image cap">
                            <div class="text-center text-white rounded-pill px-2 position-absolute top_95_left_5-s px-4 bg_parrot_green-s">English</div>
                        </div>
                        <div class="card-body px-3 pt-4">
                            <h6 class="fg_stone_color-s ">17/01/2021</h6>
                            <h5 class="card-title mb-1"><strong>Campus CLean WorkShop</strong></h5>
                            <small class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly </small>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card border-0" >
                        <div class="position-relative">
                            <img class="card-img-top br_19px-s img_h_240px-s w-100" src="assets/landing_page/teacher1.jpg" alt="Card image cap">
                            <div class="text-center text-white rounded-pill px-2 position-absolute top_95_left_5-s px-4 bg_parrot_green-s">English</div>
                        </div>
                        <div class="card-body px-3 pt-4">
                            <h6 class="fg_stone_color-s ">17/01/2021</h6>
                            <h5 class="card-title mb-1"><strong>Campus CLean WorkShop</strong></h5>
                            <small class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly </small>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card border-0" >
                        <div class="position-relative">
                            <img class="card-img-top br_19px-s img_h_240px-s w-100" src="assets/landing_page/teacher1.jpg" alt="Card image cap">
                            <div class="text-center text-white rounded-pill px-2 position-absolute top_95_left_5-s px-4 bg_parrot_green-s">English</div>
                        </div>
                        <div class="card-body px-3 pt-4">
                            <h6 class="fg_stone_color-s ">17/01/2021</h6>
                            <h5 class="card-title mb-1"><strong>Campus CLean WorkShop</strong></h5>
                            <small class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly </small>
                        </div>
                      </div>
                </div>
            </div>
        </section>

        <!-- <section class="py-5 footer_background_img-s">
            <div class="row  ">
                <div class="col-md-3 col-12">
                    <h2 class="text-white">Join Us and stay tuned!</h2>
                    <button type="button" class="btn fg-success-s bg-white rounded-pill border-0 w-100 py-2">Join US</button>
                </div>
                <div class="col-md-9 col-12 align-self-center">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-12 form-group">
                            <input type="text" placeholder="Name" vlaue="name" class="form-control form-control-lg rounded-pill fg_green_for_placeholder-s border-0">
                        </div>
                        <div class="col-lg-4 col-md-6 col-12 form-group">
                            <input type="text" placeholder="Email" class="form-control form-control-lg rounded-pill border-0">
                        </div>
                        <div class="col-lg-4 col-md-6 col-12 form-group">
                            <input type="text" placeholder="Class" class="form-control form-control-lg rounded-pill border-0">
                        </div>
                        <div class="col-12 form-group">
                            <input type="text" placeholder="Message" class="form-control form-control-lg rounded-pill border-0">
                        </div>
                    </div>

                </div>
            </div>
        </section> -->

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
                    <a href="javascript:void(0)" class="no_link-s fg_light_grey-s py-1">Our Classes</a>
                    <br>
                    <a href="javascript:void(0)" class="no_link-s fg_light_grey-s py-1 ">School Teachers</a>
                    <br>
                    <a href="javascript:void(0)" class="no_link-s fg_light_grey-s py-1">Recent Events</a>
                    <br>
                    <a href="javascript:void(0)" class="no_link-s fg_light_grey-s py-1">Our News</a>
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

                <div class=" pt-3 col-xl-3 col-lg-3 col-md-6 col-12 pb-5">
                    <div style="width: 100%"><iframe width="100%" height="259" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=259&amp;hl=en&amp;q=1%20Grafton%20Street,%20Dublin,%20Ireland+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a href="https://www.maps.ie/draw-radius-circle-map/"></a></div>
                </div>

            </div>
            <!-- Grid row -->

            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>

            <!-- Copyright -->
            <div class="footer-copyright fg_light_grey-s py-3">
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
