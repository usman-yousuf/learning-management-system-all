@extends('authall::layouts.auth')

@section('page-title')
    Login
@endsection

@section('auth-content')
                <div class="col">
                    <span class="welcome_text-s">Welcome to <strong class="text-success font-weight-bold">Room A to Z</strong></span>
                </div>
                <div class="col login_text-s">
                    <small>We make it easy for everyone to study</small>
                </div>
                <div class="col d-inline-flex">
                    <div class="hl-color-s"></div>
                    <div class="ml-2 hl-s"></div>
                </div>

                <!-- --------login Form----  -->
                <form action="" class="needs-validation pt-4" novalidate>
                    <!-- ---User Name input field-------  -->
                    <div class="col form-group">
                        <label class="text-muted font-weight-normal ml-3">User Name</label>
                        <input type="text" class="form-control form-control-lg login_input-s" name="username" placeholder="User Name" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <!-- -------Password Input Field------  -->
                    <div class="col form-group pt-3">
                        <label class="text-muted font-weight-normal ml-3 ">Password</label>
                        <input type="password" class="form-control form-control-lg login_input-s" name="password" placeholder="Password" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <!-- --------checkbox----- -->
                    <div class="col form-check pt-3 d-lg-inline-flex login-checkout-s">
                        <label class="col form-check-label">
                            <input type="checkbox" class="form-check-input" value="">Remember me
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
@endsection

@section('footer-scripts')
@endsection
