<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('page-title', 'LMS')</title>
        <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/svg+xml" sizes="16x16">

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
        {{--  <link rel="stylesheet" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />  --}}

        <link rel="stylesheet" href="css/usman_stylesheet.css">
        <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />

        <script type="text/javascript">
            let TEACHER_DASHBOARD_URL = "{{ route('teacher.dashboard') }}";
            let ASSET_URL = "{{ asset('uploads/') }}";
        </script>
        @yield('header-scripts')
        @yield('header-css')
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar -->
            <div class="bg-light" id="sidebar-wrapper">
                <div class="sidebar-heading text-center">
                    <img src="assets/preview/dashboard_logo.svg" width="30" alt="">
                </div>
                <div class="list-group list-group-flush sidebar_text-s">
                    <a href="dashboard.html" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/home_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Dashboard</span>
                    </a>
                    <a href="courses.html" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/course_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Courses</span>
                    </a>
                    <a href="students.html" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/student_icon.svg" class="ml-3 filter-green-student" width="25" alt="">
                        <span class="px-3">Students</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/calendar_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Calendar</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/quiz_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Quiz</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/report_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Report</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/sales-report_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Sales Report</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/certificate_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Certification</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/payment_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Payment</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/privacy_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Privacy</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3">
                        <img src="assets/preview/about_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">About Us</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-3 my-5">
                        <img src="assets/preview/logout_icon.svg" class="ml-3" width="25" alt="">
                        <span class="px-3">Log Out</span>
                    </a>
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <!-- <button class="btn btn-primary" >Toggle Menu</button> -->
                <a href="" id="menu-toggle"><img src="assets/preview/menu.svg" alt="" width="25" class="filter-green-pin"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link mx-lg-5" href="#"><img src="assets/preview/map_pin.svg" alt="" class="filter-green-pin" width="25"> <span class="country_text-s">Pakistan</span></a>
                        </li>
                        <li class="nav-item mx-lg-5">
                            <a class="nav-link" href="#"><img src="assets/preview/bell.svg" alt=""></a>
                        </li>
                        <li>
                            <img src="assets/preview/user_img.png" class="rounded-circle" width="40" alt="">
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Amelia Emily
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        {{--  <script src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>  --}}

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
        <script src="js/usman_java.js"></script>
        <script src="{{ asset('assets/js/common.js') }}"></script>

        @stack('footer-head-scripts')
        @yield('footer-scripts')
    </body>
</html>
