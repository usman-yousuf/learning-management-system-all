
@extends('authall::layouts.auth')

@section('page-title')
    Registeration
@endsection

@section('auth-content')
    <div class="col">
        <span class="welcome_text-s">Enter Code</span>
    </div>
    <div class="col login_text-s">
        <small>Lorem Ipsum is simply dummy text of the printing
            and typesetting industry.</small>
    </div>
    <div class="col d-inline-flex mb-3">
        <div class="hl-color-s"></div>
        <div class="ml-2 hl-s"></div>
    </div>

    <!-- --------login Form----  -->
    <form action="" class="needs-validation pt-4" novalidate>
        <div class="col d-inline-flex text-center py-4">
            <div class="col-md-2 p-3 code_border-s">
                <input type="text" maxlength="1" class='form-control v_code-d' placeholder="2" />
            </div>
            <div class="col-md-2 offset-1 p-3 code_border-s">
                <input type="text" maxlength="1" class='form-control v_code-d' placeholder="0" />
            </div>
            <div class="col-md-2 offset-1 p-3 code_border-s">
                <input type="text" maxlength="1" class='form-control v_code-d' placeholder="6" />
            </div>
            <div class="col-md-2 offset-1 p-3 code_border-s">
                <input type="text" maxlength="1" class='form-control v_code-d' placeholder="8" />
            </div>
        </div>

        <div class="col d-inline-flex mb-5">
            <div class="">
                <a href="" class="receive_code-s">Didn’t receive the code?</a>
            </div>
            <div class="col">
                <a href="{{ route('resend_verification_code') }}" class="float-right mr-lg-3 resend_code-s">Resend Code</a>
            </div>
        </div>
        <!-- ------ Send Button------- -->
        <div class="col pt-5 login_button-s text-center">
            {{--  <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SUBMIT</button>  --}}
            <a href="{{ route('change_password') }}" class="btn btn-success pt-lg-3 pb-lg-3">Submit</a>
        </div>
    </form>
@endsection

@section('footer-scripts')
@endsection
