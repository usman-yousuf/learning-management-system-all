
@extends('authall::layouts.auth')

{{--  Set variables for layout view - START  --}}
@section('page-title') Validate Code @endsection
@section('back-link-attribute') '' @endsection
@section('back-link-url') {{ route('forgotPassword') }} @endsection
{{--  Set variables for layout view - END  --}}


@section('auth-content')
    <div id='validate_code_container-d'>
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
        <form id='frm_validate_code-d' class='frm_validate_code-s' method="POST" action="{{ route('validatePasswordCode') }}" class="needs-validation pt-4" novalidate>
            @csrf
            <div class="col d-inline-flex text-center py-4">
                <div class="col-md-2 p-3 code_border-s code_border-d">
                    <input type="number" name='number_box_1' id='number_box_1' min='0' max="9" maxlength="1" class='form-control v_code-d v_code-s' placeholder="2" />
                </div>
                <div class="col-md-2 offset-1 p-3 code_border-s code_border-d">
                    <input type="number" name='number_box_2' id='number_box_2' min='0' max="9" maxlength="1" class='form-control v_code-d v_code-s' placeholder="0" />
                </div>
                <div class="col-md-2 offset-1 p-3 code_border-s code_border-d">
                    <input type="number" name='number_box_3' id='number_box_3' min='0' max="9" maxlength="1" class='form-control v_code-d v_code-s' placeholder="6" />
                </div>
                <div class="col-md-2 offset-1 p-3 code_border-s code_border-d">
                    <input type="number" name='number_box_4' id='number_box_4' min='0' max="9" maxlength="1" class='form-control v_code-d v_code-s last-d' placeholder="8" />
                </div>
            </div>

            <div class="col d-inline-flex mb-5">
                <div class="">
                    <a data-href="{{ route('resendVerificationCode') }}" class="receive_code-s">Didn’t receive the code?</a>
                </div>
                <div class="col">
                    <a href='javascript:void(0)' data-href="{{ route('resendVerificationCode') }}" class="float-right mr-lg-3 resend_code-s resend_code-d">Resend Code</a>
                </div>
            </div>
            <!-- ------ Send Button------- -->
            <div class="col pt-5 login_button-s text-center">
                <input type='hidden' name='email' id='hdn_email-d' value='{{ $email ?? '' }}' />
                <input type='hidden' name='activation_code' id='hdn_activation_code-d' />
                <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SUBMIT</button>
            </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    <script type="text/javascript" src='{{ asset('modules/authall/assets/js/authall.js') }}'></script>
@endsection
