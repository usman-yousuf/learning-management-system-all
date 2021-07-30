@extends('authall::layouts.auth')

{{--  Set variables for layout view - START  --}}
@section('page-title') Set Password @endsection
@section('back-link-attribute') '' @endsection
@section('back-link-url') {{ route('validatePasswordCode') }} @endsection
{{--  Set variables for layout view - END  --}}


@section('auth-content')
    <div id='set_password_container-d'>
        <div class="col">
            <span class="welcome_text-s">Create New Password</span>
        </div>
        <div class="col login_text-s">
            <small>Enter Your Password</small>
        </div>
        <div class="col d-inline-flex">
            <div class="hl-color-s"></div>
            <div class="ml-2 hl-s"></div>
        </div>

        <!-- --------login Form----  -->
        <form id='frm_set_password-d' action="{{ route('resetPassword') }}" method="POST" class="needs-validation pt-4" novalidate>
            @csrf
            <!-- ---Password input field-------  -->
            <div class="col form-group">
                <label class="text-muted font-weight-normal ml-3">Password</label>
                <input type="password" class="form-control form-control-lg login_input-s set_pass_pswd-d" name="password" placeholder="Password" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
            <!-- -------Confirm Password Input Field------  -->
            <div class="col form-group pt-3 pb-lg-4">
                <label class="text-muted font-weight-normal ml-3 ">Confirm Password</label>
                <input type="password" class="form-control form-control-lg login_input-s" name="password_confirmation" placeholder="Confirm Password" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <!-- ------Buttons------- -->
            <div class="pt-5 login_button-s text-center">
                <input type='hidden' name='email' id='hdn_set_pass_email-d' value='{{ $email ?? '' }}' />
                <input type='hidden' name='code' id='hdn_set_pass_activation_code-d' value="{{ $vcode ?? '' }}" />
                <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">CONFIRM</button>
            </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    <script>
        let login_page_url = "{{ route('login') }}"
    </script>
    <script src="{{ asset('modules/authall/assets/js/authall.js') }}"></script>
@endsection
