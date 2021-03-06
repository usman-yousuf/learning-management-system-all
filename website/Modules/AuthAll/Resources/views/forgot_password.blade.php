@extends('authall::layouts.auth')


{{--  Set variables for layout view - START  --}}
@section('page-title') Forgot Password @endsection
@section('back-link-attribute') '' @endsection
@php
    define('backUrl', url()->previous());
@endphp
{{-- @section('back-link-url') {{ route('login') }} @endsection --}}
@section('back-link-url') {{ backUrl }} @endsection
{{--  Set variables for layout view - END  --}}



@section('auth-content')
        <div class="col">
            <span class="welcome_text-s">Forgot Password?</span>
        </div>
        <div class="col login_text-s">
            <small>Enter your Email</small>
        </div>
        <div class="col d-inline-flex">
            <div class="hl-color-s"></div>
            <div class="ml-2 hl-s"></div>
        </div>

        <!-- --------login Form----  -->
        <form id="frm_fogrot_password-d" action="{{ route('forgotPassword') }}" method="POST" class="needs-validation pt-4" novalidate>
            @csrf
            <!-- ---Email input field-------  -->
            <div class="form-group col my-5">
                <label class="text-muted font-weight-normal ml-3">Email</label>
                <input type="text" id='txt_forgot_pass_email-d' class="form-control form-control-lg login_input-s" name="email" placeholder="Email" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <!-- ------ Send Button------- -->
            <div class="col pt-5 login_button-s text-center">
                <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SEND</button>
            </div>
        </form>
@endsection

@section('footer-scripts')
    
    <script>
        let HOMEURL = "{{ route('home') }}";
    </script>
    <script type="text/javascript" src='{{ asset('modules/authall/assets/js/authall.js') }}'></script>
@endsection
