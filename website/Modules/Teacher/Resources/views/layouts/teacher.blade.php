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
            let current_user_profile_id = "{{ \Auth::user()->profile->id ?? '' }}";
            let current_user_profile_uuid = "{{ \Auth::user()->profile->uuid ?? '' }}";
            let current_user_profile_fname = "{{ \Auth::user()->profile->first_name ?? '' }}";
            let TEACHER_DASHBOARD_URL = "{{ route('teacher.dashboard') }}";
            let STUDENT_DASHBOARD_URL = "{{ route('student.dashboard') }}";
            let UPLOAD_URL = "{{ asset('uploads/') }}";
            let ASSET_URL = "{{ asset('') }}";

            let user_placeholder = "{{ asset('assets/images/placeholder_user.png') }}";
            let certificate_placeholder = "{{ asset('assets/images/certification_placeholder.svg') }}";
            let assignment_placeholder = "{{ asset('assets/images/certification_placeholder.svg') }}";
            let word_file_placeholder = "{{ asset('assets/images/word_file_placeholder.png') }}";

            let upload_files_url = "{{ route('uploadFiles') }}";

            const currentUser = {
                profile_uuid : "{{ \Auth::user()->profile->uuid ?? '' }}",
                profile_id : "{{ \Auth::user()->profile->id ?? '' }}",
                profile_fname : "{{ \Auth::user()->profile->first_name ?? '' }}",
                profile_lname : "{{ \Auth::user()->profile->last_name ?? '' }}",
                profile_type : "{{ \Auth::user()->profile->profile_type ?? '' }}",
                profile_image : "{{ getFileUrl(\Auth::user()->profile->profile_image ?? '', null, 'profile') }}",
            };
        </script>
        @stack('header-scripts')
        <style>
            #bg_color: {
                background-color: #5B933A;
            }
        </style>
    </head>
    <body>
        <div id="loader" class='loader_container-s' style="display: none;">
            <img class='img_200_x_200-s' src="{{ asset("assets/images/loader.gif") }}">
        </div>

        <div class="d-flex" id="wrapper">
            <!-- Sidebar -->
            <div class="bg-light" id="sidebar-wrapper">
                <div class="sidebar-heading text-center p-2">
                    @php
                        $route = route('home');
                        if(\Auth::check()){
                            if('teacher' == \Auth::user()->profile_type){
                                // $route = isset(app('request')->last_page)? 'javascript:void(0)' : route('teacher.dashboard');
                                $route = (\Auth::user()->profile->approver_id == null)? 'javascript:void(0)' : route('teacher.dashboard');
                            }
                            else if('student' == \Auth::user()->profile_type){
                                $route = route('student.dashboard');
                            }
                            else if('parent' == \Auth::user()->profile_type){
                                $route = route('home');
                            }
                            else if('admin' == \Auth::user()->profile_type){
                                $route = route('home');
                            }
                        }
                    @endphp
                    <a href="{{ $route }}" class="logo_link-d">
                        <img class='logo_image-d' src="{{ asset('assets/images/logo.svg') }}" width="58" alt="logo" />
                    </a>
                </div>
                <div class="list-group list-group-flush sidebar_text-s">
                    @include('teacher::layouts.nav_links')
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <div id="page-content-wrapper" style="position: relative;">

                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    {{-- <a href="javascript:void(0)" id="menu-toggle"><img src="{{ asset('assets/images/burger_menu.svg') }}" alt="menu" width="25" class="filter-green-pin"></a> --}}
                    <button class="navbar-toggler mt-2 mb-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item active pt-2 align-items-center d-flex">
                                <a class="nav-link mx-lg-5" href="javascript:void(0)">
                                    <img src="{{ asset('assets/images/map_pin.svg') }}" alt="map-pin" class="filter-green-pin" width="25" />
                                    <span class="country_text-s">Pakistan</span>
                                </a>
                            </li>

                            <li class="nav-item mx-lg-5 pt-2">
                                <a class="nav-link notification_link-d" href="{{ route('notifications.index') }}">
                                    <h4>
                                        @if(\Auth::check())
                                            <img src="{{ asset('assets/images/bell_icon.svg') }}" alt="bell-icon" />
                                            <span class="badge badge-info">{{ getUnReadNotificationCount() }}</span>
                                        @endif
                                    </h4>
                                </a>
                            </li>
                            @if(\Auth::check())
                                @php
                                    $profile_image = (\Auth::user() != null)? \Auth::user()->profile->profile_image : null;
                                @endphp
                                <li class="pt-2 align-items-center d-flex">
                                    <img src="{{ getFileUrl($profile_image, null, 'profile') }}" class="rounded-circle top_navbar_profile_image-d" width="40" height="40" alt="profile-pic" />
                                </li>
                                <li class="nav-item dropdown pt-2 align-items-center d-flex">
                                    <a class="nav-link dropdown-toggle top_navbar_profile_link-d" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-profile_uuid="{{ \Auth::user()->profile->uuid ?? '' }}">
                                        {{ getTruncatedString(\Auth::user()->profile->first_name . ' ' . \Auth::user()->profile->last_name) }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item top_nav_bar_profile_setting_link-d" href="{{ route('updateprofileSetting') }}">Profile Setting</a>
                                        <div class="dropdown-divider top_nav_bar_profile_divider-d"></div>
                                        <a class="dropdown-item" href="{{ route('signout') }}">Logout</a>
                                    </div>
                                </li>
                            @else
                                <li class="nav-item dropdown pt-2 align-items-center d-flex">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                                <li class="nav-item dropdown pt-2 align-items-center d-flex">
                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="registration_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-profile_uuid="{{ \Auth::user()->profile->uuid ?? '' }}">
                                        Register
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="registration_options">
                                        <a class="dropdown-item" href="{{ route('register') }}">Teacher</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('registerStudent') }}">Student</a>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>

                <div class="container-fluid">
                    @yield('content')
                </div>
                @php
                    $chatLinks = ['/chat'];
                    $pageUrl = $_SERVER['REQUEST_URI'];
                @endphp
                @if( checkStringAgainstList($chatLinks, $pageUrl) )
                    <footer class="page-footer footer-s position-absolute font-small">
                        <!-- Copyright -->
                        <div class="footer-copyright text-center py-3">
                            ?? 2021 Copyright:
                            <a href="javascript:void(0)">@LMS </a>
                        </div>
                        <!-- Copyright -->
                    </footer>
                @endif

            </div>
        </div>

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
