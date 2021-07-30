@php
    
@endphp

    <div class="col">
        <span class="welcome_text-s">Register</span>
    </div>
    <div class="col signup_text-s">
        <small>Don't have an Account?&nbsp;<a href="javascript:void(0)">Create your Account</a>,&nbsp;it takes less then a minute.</small>
        <!-- <small>Already have an account? <a href=@if($profile_type == 'student')  {{ route('loginStudent') }} @elseif($profile_type == 'parent')  {{ route('loginParent') }} @else {{ route('login') }} @endif >Login here</a>.</small> -->
    </div>
    <div class="col d-inline-flex">
        <div class="hl-color-s"></div>
        <div class="ml-2 hl-s"></div>
    </div>
    {{-- {{ dd($profile_type) }} --}}
    <!-- ------Sign up Form-----  -->
    <form id='frm_register-d' action=@if($profile_type == 'student')  {{ route('registerStudent') }} @elseif($profile_type == 'parent')  {{ route('registerParent') }} @else {{ route('register') }} @endif class="needs-validation pt-4" method="POST" novalidate>
        @csrf
        <!-- ----First name & Last name----  -->
        <div class="form-group d-inline-flex">
            <div class="col-md-6">
                <label class="text-muted font-weight-normal ml-3" for="first_name">First Name</label>
                <input type="text" class="form-control form-control-lg login_input-s" name="first_name" placeholder="First Name" required="required">
                @error('first_name')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="text-muted font-weight-normal ml-3" for="last_name">Last Name</label>
                <input type="text" class="form-control form-control-lg login_input-s" name="last_name" placeholder="Last Name" required="required">
                @error('last_name')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- -----User name input field---- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3" for="username">User Name</label>
            <input type="text" class="form-control form-control-lg login_input-s" name="username" placeholder="User Name" required="required">
            @error('username')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <!-- -------email input field--- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3" for='email'>Email</label>
            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror login_input-s txt_email-d" id='email' name="email" placeholder="Email" required="required">
            @error('email')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <!-- -----password input field---- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3" for='password'>Password</label>
            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror login_input-s pswd_password-d" name="password" placeholder="Password" required="required">
            @error('password')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <!-- ------confrim password input field------- -->
        <div class="col form-group pt-3">
            <label class="text-muted font-weight-normal ml-3" for='password_confirmation'>Confirm Password</label>
            <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror login_input-s pswd_password-d" name="password_confirmation" placeholder="Confirm Password" required="required">
            @error('password_confirmation')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <!-- ----- Button------ -->
        <div class="pt-5 login_button-s text-center">
            <input type="hidden" name="profile_type" value=@if($profile_type == 'student') "student" @elseif($profile_type == 'parent')  "parent" @else "teacher" @endif>
            <input type="hidden" name="category_singup" value="">
            <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SIGNUP</button>
        </div>
    </form>