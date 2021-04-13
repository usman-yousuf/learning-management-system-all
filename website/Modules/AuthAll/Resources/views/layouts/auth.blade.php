<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />

    @yield('header-scripts')
    @yield('header-css')

    <title>@yield('page-title', 'LMS')}</title>
</head>

<body>

    <div class="container">
        <div class="row align-items-center h-100 p-5">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 h-100">
                <img src="{{ asset('assets/images/auth_main_img.svg') }}" width="100%" alt="">
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                @yield('auth-content')
            </div>
        </div>
    </div>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    {{--  Custom Scripts  --}}
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('footer-head-scripts')
    @yield('footer-scripts')

</body>

</html>
