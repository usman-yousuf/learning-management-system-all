@extends('authall::layouts.auth')


{{--  Set variables for layout view - START  --}}
@section('page-title') Forgot Password @endsection
@section('back-link-attribute') '' @endsection
@section('back-link-url') {{ route('login') }} @endsection
{{--  Set variables for layout view - END  --}}


@section('auth-content')
        <div class="col">
            <span class="welcome_text-s">Enter Email</span>
        </div>
        <div class="col login_text-s">
            <small>Lorem Ipsum is simply dummy text of the printing
                and typesetting industry.</small>
        </div>
        <div class="col d-inline-flex">
            <div class="hl-color-s"></div>
            <div class="ml-2 hl-s"></div>
        </div>

        <!-- --------login Form----  -->
        <form action="" class="needs-validation pt-4" novalidate>
            <!-- ---Email input field-------  -->
            <div class="form-group col my-5">
                <label class="text-muted font-weight-normal ml-3">Email</label>
                <input type="text" class="form-control form-control-lg login_input-s" name="email" placeholder="Email" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <!-- ------ Send Button------- -->
            <div class="col pt-5 login_button-s text-center">
                {{--  <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SEND</button>  --}}
                <a href="{{ route('validatePasswordCode') }}" class="btn btn-success pt-lg-3 pb-lg-3">SEND</a>
            </div>
        </form>
@endsection

@section('footer-scripts')
@endsection
