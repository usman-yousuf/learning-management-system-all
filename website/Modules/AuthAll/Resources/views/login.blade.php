@extends('authall::layouts.auth')

@section('page-title')
    Login
@endsection

@section('auth-content')
            <div class="container">
                {{-- @include('common::partials.flashes', []) --}}
                <div class="row">
                    <div class="col">
                        <span class="welcome_text-s">Welcome to <strong class="text-success font-weight-bold">Room A to Z</strong></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col login_text-s">
                        <small>We make it easy for everyone to study</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col d-inline-flex">
                        <div class="hl-color-s"></div>
                        <div class="ml-2 hl-s"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <!-- --------login Form----  -->
                    <form id="frm_login-d" action='{{ route('login') }}' class="needs-validation pt-4" method="POST" novalidate>
                        @csrf
                        <!-- ---User Name input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3" for='email'>Email</label>
                            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror login_input-s txt_email-d" id='email' name="email" placeholder="Email" required >
                            @error('email')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- -------Password Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3" for='password'>Password</label>
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror login_input-s pswd_password-d" name="password" placeholder="Password" required>
                            @error('password')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- --------checkbox----- -->
                        <div class="col form-check pt-3 d-lg-inline-flex login-checkout-s">
                            <label class="col form-check-label" for="remember_me">
                                <input type="checkbox" class="form-check-input" name='remember_me' />Remember me
                            </label>
                            <div class="float-right">
                                <a href="{{ route('forgotPassword') }}">Forgot Password</a>
                            </div>
                        </div>
                        <!-- ------Buttons------- -->
                        <div class="col pt-5 login_button-s">
                            <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">LOGIN</button>
                            <a href="{{ route('register') }}" class="btn btn- shadow float-right pt-lg-3 pb-lg-3">SIGNUP</a>
                        </div>
                    </form>
                </div>
            </div>
@endsection

@section('footer-scripts')
    <script type="text/javascript" src='{{ asset('modules/authall/assets/js/authall.js') }}'></script>
@endsection
