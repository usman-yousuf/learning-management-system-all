<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" />

    <script type="text/javascript">
        let APP_URL = "{{ route('welcome') }}";
        let ASSET_URL = "{{ asset('uploads/') }}";
    </script>
    @yield('header-scripts')
    @yield('header-css')

    <title>@yield('page-title', 'LMS')</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-12 mt-3">
                <a href="@yield('back-link-url', 'javascript:void(0)')" class="" @yield('back-link-attribute', 'hidden')>
                    <img src="{{ asset('assets/images/left_arrow.svg') }}" width="18px" alt="back-icon">
                </a>
            </div>
        </div>
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

    <!--Begin Comm100 Live Chat Code-->
    {{-- <div id="comm100-button-b28740fb-670b-4ae9-a2a4-dd2ef7aed62e"></div>
    <script type="text/javascript">
        var Comm100API=Comm100API||{};(function(t){function e(e){var a=document.createElement("script"),c=document.getElementsByTagName("script")[0];a.type="text/javascript",a.async=!0,a.src=e+t.site_id,c.parentNode.insertBefore(a,c)}t.chat_buttons=t.chat_buttons||[],t.chat_buttons.push({code_plan:"b28740fb-670b-4ae9-a2a4-dd2ef7aed62e",div_id:"comm100-button-b28740fb-670b-4ae9-a2a4-dd2ef7aed62e"}),t.site_id=10004199,t.main_code_plan="b28740fb-670b-4ae9-a2a4-dd2ef7aed62e",e("https://vue.comm100.com/livechat.ashx?siteId="),setTimeout(function(){t.loaded||e("https://standby.comm100vue.com/livechat.ashx?siteId=")},5e3)})(Comm100API||{})
    </script> --}}
    <!--End Comm100 Live Chat Code-->
</body>

</html>
