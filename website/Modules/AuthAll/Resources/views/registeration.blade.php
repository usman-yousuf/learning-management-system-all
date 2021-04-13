@extends('authall::layouts.auth')

@section('page-title')
    Registeration
@endsection

@section('auth-content')
    <div class="col">
        <span class="welcome_text-s">Register</span>
    </div>
    <div class="col signup_text-s">
        <small>Already have an account? <a href="{{ route('login') }}">Login here</a>.</small>
    </div>
    <div class="col d-inline-flex">
        <div class="hl-color-s"></div>
        <div class="ml-2 hl-s"></div>
    </div>
    <!-- ------Sign up Form-----  -->
    <form action="" class="needs-validation pt-4" novalidate>
        <!-- ----First name & Last name----  -->
        <div class="form-group d-inline-flex">
            <div class="col-md-6">
                <label class="text-muted font-weight-normal ml-3">First Name</label>
                <input type="text" class="form-control form-control-lg login_input-s" name="username" placeholder="First Name" required>
            </div>
            <div class="col-md-6">
                <label class="text-muted font-weight-normal ml-3">Last Name</label>
                <input type="text" class="form-control form-control-lg login_input-s" name="username" placeholder="Last Name" required>
            </div>


        </div>
        <!-- -----User name input field---- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3">User Name</label>
            <input type="text" class="form-control form-control-lg login_input-s" name="username" placeholder="User Name" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
        </div>
        <!-- -------email input field--- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3">Email</label>
            <input type="text" class="form-control form-control-lg login_input-s" name="email" placeholder="Email" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
        </div>
        <!-- -----password input field---- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3 ">Password</label>
            <input type="password" class="form-control form-control-lg login_input-s" name="password" placeholder="Password" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
        </div>
        <!-- ------confrim password input field------- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3 ">Confirm Password</label>
            <input type="password" class="form-control form-control-lg login_input-s" name="confirm_password" placeholder="Confirm Password" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
        </div>
        <!-- ----- Button------ -->
        <div class="pt-5 login_button-s text-center">
            <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SIGNUP</button>
        </div>
    </form>
@endsection

@section('footer-scripts')
@endsection
