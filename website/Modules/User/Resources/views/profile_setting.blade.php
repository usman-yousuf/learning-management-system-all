@extends('user::layouts.master')

@section('profile-header-content')

    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <a href="javascript:void(0)" class="">
            <img class='logo_image-d' src="{{ asset('assets/images/logo.svg') }}" width="30" alt="logo" />
        </a>
        {{-- <a href="javascript:void(0)" id="menu-toggle"><img src="{{ asset('assets/images/burger_menu.svg') }}" alt="menu" width="25" class="filter-green-pin"></a> --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    &nbsp;
                </li>
                <li class="nav-item mx-lg-5">
                    &nbsp;
                </li>
                @if(\Auth::check())
                    @php
                        $profile_image = (\Auth::user() != null)? \Auth::user()->profile->profile_image : null;
                    @endphp
                    <li>
                        <img src="{{ getFileUrl($profile_image, null, 'profile') }}" class="rounded-circle top_navbar_profile_image-d" width="40" height="40" alt="profile-pic" />
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle top_navbar_profile_link-d" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-profile_uuid="{{ \Auth::user()->profile->uuid ?? '' }}">
                            {{ getTruncatedString(\Auth::user()->profile->first_name . ' ' . \Auth::user()->profile->last_name) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <!-- <div class="dropdown-divider"></div> -->
                            @if ((null != $profile->approver_id) && ('' !=$profile->approver_id ))
                                <a class="dropdown-item" href="{{ route('teacher.dashboard') }}">Dashboard</a>
                                <div class="dropdown-divider"></div>
                            @endif

                            <a class="dropdown-item" href="{{ route('signout') }}">Logout</a>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endsection

@section('profile-content')
    @php
        $redirectRoute = 'javascript:void(0)';//route('home');
        if($profile->profile_type =='teacher') {
            if(null != $profile->approver_id) {
                $redirectRoute = route('teacher.dashboard');
            }
        }
        else if($profile->profile_type == 'student') {
            $redirectRoute = route('student.dashboard');
        }
        else {
            $redirectRoute = route('parent.dashboard');
        }
    @endphp
    <div class="d-flex" id="wrapper">
        <div id="page-content-wrapper">


        <a href="{{ $redirectRoute }}" type="button" class="login_button-s text-center mb-4 mt-3">
            <img src="{{ asset('assets/images/angle_left_icon.svg') }}" class="shadow p-3 mb-2 bg-white rounded d-none" width="60" height="60" alt="back" />
        </a>
        <form action="{{ route('updateprofileSetting') }}" id="frm_profile_setting-d" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 choose_profile_image-s">
                        <div class="row">
                            <div class="col">
                                <div class="text-center py-5 profile_image-s">
                                    <img class="profile_img-d" src="{{ getFileUrl($profile->profile_image ?? null, null, 'profile') }}" class="rounded-circle img-fluid" width="35%" alt="">
                                    <input type='hidden' name='profile_image' id='hdn_profile_image-d' value='{{ $profile->profile_image ?? '' }}' />
                                </div>
                                <div class="col text-center">
                                    <button type="button" class="btn btn-outline- bg-white text-secondary choose_image_btn-s" data-toggle="modal" data-target="#uploadFileModalPopup">
                                        Upload Image &nbsp;&nbsp;&nbsp;&nbsp; <img src="{{ asset('assets/images/camera_icon_green.svg') }}" alt="upload" width="20px">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- -----Profile Setting text field-------  -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-5">
                        <div class="col profile_text-s py-lg-4">
                            <span>Profile Settings</span>
                        </div>
                        <!-- <form action="" class="needs-validation pt-4" novalidate> -->
                            <!-- ---User Name input field-------  -->
                            <div class="col form-group">
                                <label class="text-muted font-weight-normal ml-3">First Name</label>
                                <input type="text" class="form-control form-control-lg login_input-s" name="first_name" value="{{ $profile->first_name ?? '' }}" placeholder="Name" />
                            </div>
                            <!-- -------Last Name Input Field------  -->
                            <div class="col form-group pt-3">
                                <label class="text-muted font-weight-normal ml-3">Last Name</label>
                                <input type="text" class="form-control  login_input-s w-100 p-4" name="last_name" value="{{ $profile->last_name ?? '' }}" placeholder="Last Name" />
                            </div>

                            <!-- ---------Gender------- -->
                            <div class="col form-group pt-3">
                                <label for="gender" class="text-muted font-weight-normal ml-3">Gender</label>
                                <select class="form-control input_radius-s" id="gender-d" name='gender'>
                                    <option value='male' @if((isset($profile) && $profile->gender == 'male')) selected='selected' @endif>Male</option>
                                    <option value='female'@if((isset($profile) && $profile->gender == 'female')) selected='selected' @endif>Female</option>
                                    <option value='trans'@if((isset($profile) && $profile->gender == 'trans')) selected='selected' @endif>Trans Gender</option>
                                </select>
                            </div>

                        <!-- </form> -->
                    </div>
                </div>


                <!-- ----------DatePicker------- -->
                <div class="row">
                    <div class="col-sm-6 pt-3">
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3 ">Date of Birth</label>
                            <input type="date" class="form-control input_radius-s" name="dob" max="{{ date('Y-m-d', strtotime('-10 years')) }}" value="{{ $profile->dob ? date('Y-m-d', strtotime($profile->dob ?? '-10 years')) : '' }}">
                        </div>
                    </div>
                </div>
                <!-- ----------Complete Address Form -------  -->
                <h5 class="p-3">Complete Address</h5>
                <!-- <form action="" class="needs-validation" novalidate> -->
                    <input type="hidden" name='address_uuid' value="{{ $address->uuid ?? '' }}">

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <!-- ---Current Address input field-------  -->
                            <div class="col form-group">
                                <label class="text-muted font-weight-normal ml-3">Address Line 1</label>
                                <input type="text" class="form-control form-control-lg login_input-s" name="address1" value="{{ $address->address1 ?? '' }}" placeholder="Address Line 1" />
                            </div>
                            <!-- -------City Input Field------  -->
                            <div class="col form-group pt-3">
                                <label class="text-muted font-weight-normal ml-3">City</label>
                                <input type="text" class="form-control  login_input-s w-100 p-4" name="city" placeholder="City" value="{{ $address->city ?? '' }}" />
                            </div>
                            <!-- -------Mobile Number Input Field------  -->

                            @if ($profile->profile_type == 'teacher')
                                <div class="col form-group pt-3">
                                    <label class="text-muted font-weight-normal ml-3">Postal Code</label>
                                    <input type="text" class="form-control  login_input-s w-100 p-4" name="post_code" value="{{ $address->zip ?? '' }}" placeholder="Postal Code" />
                                </div>
                            @endif

                            <div class="col form-group pt-3">
                                <label class="text-muted font-weight-normal ml-3">Mobile Number</label><br />
                                <input id="mobile_country_code-d" type="hidden" name="phone_code_2"/>
                                <input id="mobile_phone-d" type="tel" class="form-control w-100 p-4 rounded_border-s intl_tel_input-s" name="phone_number_2" value="@if($profile->phone_code_2){{ $profile->phone_number_2 }}@else{{ $profile->phone_code_2.$profile->phone_number_2 }}@endif" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <!-- ---Permanent Address input field-------  -->
                            <div class="col form-group">
                                <label class="text-muted font-weight-normal ml-3">Address Line 2</label>
                                <input type="text" class="form-control form-control-lg login_input-s" name="address2" placeholder="Address Line 2" value="{{ $address->address2 ?? '' }}" />
                            </div>
                            <!-- -------Country Input Field------  -->
                            <div class="col form-group pt-3">
                                <label class="text-muted font-weight-normal ml-3">Country</label>
                                <input type="text" class="form-control  login_input-s w-100 p-4" name="country" value="{{ $address->country ?? '' }}" placeholder="Country" />
                            </div>

                            <!-- -------Phone Number Input Field------  -->
                            <div class="col form-group pt-3">
                                <label class="text-muted font-weight-normal ml-3">Phone Number</label><br />
                                <input id="phone_country_code-d" type="hidden" name="phone_code"/>
                                <input id="phone_phone-d" type="tel" class="form-control w-100 p-4 rounded_border-s intl_tel_input-s" name="phone_number" value="@if($profile->phone_code){{trim($profile->phone_number)}}@else{{$profile->phone_code.trim($profile->phone_number) }}@endif" />
                            </div>
                        </div>
                    </div>
                <!-- </form> -->

                <!-- ----------Education Form -------  -->
                @if ($profile->profile_type !='parent')
                    <h5 class="p-3">Education</h5>
                    <input type="hidden" name='education_uuid' value="{{ $education->uuid ?? '' }}">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <!-- ---School input field-------  -->
                                <div class="col form-group">
                                    <label class="text-muted font-weight-normal ml-3">Degree Title</label>
                                    <input type="text" class="form-control form-control-lg login_input-s" name="degree_title" value="{{ $education->title ?? '' }}" placeholder="Degree Title" />
                                </div>
                                <!-- -------University Input Field------  -->
                                <div class="col form-group pt-3">
                                    <label class="text-muted font-weight-normal ml-3">University</label>
                                    <input type="text" class="form-control login_input-s w-100 p-4" name="university" value="{{ $education->university ?? '' }}" placeholder="Board or University" />
                                </div>

                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <!-- ---College input field-------  -->
                                <div class="col form-group">
                                    <label class="text-muted font-weight-normal ml-3">Completion Year</label>
                                    <input type="text" class="form-control form-control-lg login_input-s" name="completion_year"value="{{ $education->completed_at ?? '' }}" placeholder="Completion Year" />
                                </div>
                                <!-- -------Other Institute Input Field------  -->
                                <div class="col form-group pt-3 upload_file_container-d">
                                    <div class="file-loading mt-3">
                                        <img id="certificate_thumb-d" src="{{ getFileUrl($education->certification_image ?? null, null, 'certificate') }}" class="rounded square_100p-s mb-2" alt="">
                                        <input type='hidden' name='certification_image' id='hdn_certification_image-d' value="{{ $education->certification_image ?? '' }}" />

                                        <label class='click_certificate_image-d'>
                                            <img src="{{ asset('assets/images/upload_image_icon.svg') }}" alt="upload-certificate"/>
                                        </label>
                                        <input id="upload_certificate_image-d" type="file" onchange="previewUploadedFile(this, '#certificate_thumb-d', '#hdn_certification_image-d', 'certificate');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('certificate') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                <!-- </form> -->

                <!-- ----------Uploading Experience Form -------  -->
                @if ($profile->profile_type == 'teacher')
                    <h5 class="p-3">Experience</h5>
                    <input type="hidden" name='experience_uuid' value="{{ $experience->uuid ?? '' }}">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="col form-group">
                                            <label class="text-muted font-weight-normal ml-3">Job Experience</label>
                                            <input type="text" class="form-control form-control-lg login_input-s" name="job_experience" value="{{ $experience->job_exp ?? '' }}" placeholder="Job Experience" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="col form-group">
                                            <label class="text-muted font-weight-normal ml-3">Teaching Experience</label>
                                            <input type="text" class="form-control form-control-lg login_input-s" name="teaching_experience" value="{{ $experience->teaching_exp ?? '' }}" placeholder="Teaching Experience" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="col form-group pt-3 upload_file_container-d">
                                    <div class="file-loading">
                                        <img id="experience_thumb-d" src="{{ getFileUrl($experience->image ?? null, null, 'certificate') }}" class="rounded square_100p-s mb-2" alt="experience-image">
                                        <input type='hidden' name='experience_image' id='hdn_experience_image-d' value='{{ $experience->image ?? '' }}' />

                                        <label class='click_experience_image-d'>
                                            <img src="{{ asset('assets/images/upload_image_icon.svg') }}" alt="upload-experience"/>
                                        </label>
                                        <input id="upload_experience_image-d" type="file" onchange="previewUploadedFile(this, '#experience_thumb-d', '#hdn_experience_image-d', 'experience');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('experience') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- --------upload experience end--------  -->
                        <!-- ----------Uploading Education Form End-------  -->
                @endif

                <!-- ----------Text Area input Form -------  -->
                @if ($profile->profile_type !='parent')
                    <h5 class="p-3">Interests</h5>
                    <!-- <form action="" class="needs-validation" novalidate> -->
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-12">
                                <!-- ---Interest TextArea-------  -->
                                <div class="col form-group">
                                    @php
                                        $interests = [];
                                        if( isset($profile->interests) && (null != $profile->interests) && ('' != $profile->interests) ){
                                            $interests = explode(',', trim($profile->interests));
                                        }
                                        $dbCategories = getCourseCategories();
                                        $categories = [];
                                        foreach ($dbCategories as $cat) {
                                            $categories[] = $cat->name;
                                        }
                                        // dd($interests, $categories);
                                        $remainingInterests = array_diff($interests, $categories);
                                        // dd($remainingInterests);
                                    @endphp
                                    <select id='ddl_interests' class="form-control tagged_select2" multiple="multiple" name='interests[]' style="width: 100%">
                                        @foreach ($categories as $item)
                                            <option value="{{ $item }}"
                                                @if(in_array($item, $interests))
                                                    selected='selected'
                                                @endif
                                            >
                                                {{ $item }}
                                            </option>
                                        @endforeach

                                        @if(count($remainingInterests))
                                            @foreach ($remainingInterests as $item)
                                                <option value="{{ $item }}" selected="selected">{{ $item }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    <!-- </form> -->
                @endif

                <!-- ----------TextArea input Form End-------  -->

                        <!-- ------User Code------- -->
                    @if ($profile->profile_type != 'teacher')
                        <h5 class="p-3">User Code</h5>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <!-- ---User Code  input field-------  -->
                                <input type="hidden" name='user_code_hidden' value="{{ $profile->uuid ?? '' }}">
                                <div class="col form-group">
                                    <label class="text-muted font-weight-normal ml-3">User Code</label>
                                    <input type="hidden" name="" id="user_code-d" value="{{ $profile->profile_type }}">
                                    <input type="text" class="form-control form-control-lg login_input-s" name="user_code"   value="{{ ($profile->profile_type == 'student') ? $profile->uuid : ''}}" placeholder="0123456"  {{ ($profile->profile_type == 'student') ? 'readonly' : ''}}/>
                                </div>
                                <div class="col form-check pt-3 ml-3 login-checkout-s">
                                    <label class="col form-check-label text-muted">
                                        <input type="checkbox" class="form-check-input" value="1" name='accept_tos' id='cbx_tos-d'>Terms and Condition
                                    </label>
                                </div>
                            </div>
                        </div>
                            <!-- ------End User Code------- -->
                    @endif

                        <!-- ----------Bank account Info Form -------  -->
                    @if ($profile->profile_type == 'teacher')
                        <h5 class="p-3">Bank account Info</h5>
                        <!-- <form action="" class="needs-validation" novalidate> -->
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <!-- ---Account Title input field-------  -->
                                <input type="hidden" name='user_bank_uuid' value="{{ $userBank->uuid ?? '' }}">
                                <div class="col form-group">
                                    <label class="text-muted font-weight-normal ml-3">Account Title</label>
                                    <input type="text" class="form-control form-control-lg login_input-s" name="account_title" value="{{ $userBank->account_title ?? ''}}" placeholder="Account Title" />
                                </div>
                                <!-- -------IBAN Input Field------  -->
                                <div class="col form-group pt-3">
                                    <label class="text-muted font-weight-normal ml-3">IBAN</label>
                                    <input type="text" class="form-control  login_input-s w-100 p-4" name="iban" value="{{ $userBank->iban ?? '' }}" placeholder="IBAN" />
                                </div>
                                <!-- ------- Branch Name Input Field------  -->
                                <div class="col form-group pt-3">
                                    <label class="text-muted font-weight-normal ml-3"> Branch Name</label>
                                    <input type="text" class="form-control  login_input-s w-100 p-4" name="branch_name" value="{{ $userBank->branch_name ?? '' }}" placeholder="Branch Name" />
                                </div>
                                <!-- ------- Swift Code Input Field------  -->
                                <div class="col form-group pt-3">
                                    <label class="text-muted font-weight-normal ml-3"> Swift Code</label>
                                    <input type="text" class="form-control  login_input-s w-100 p-4" name="swift_code" value="{{ $userBank->swift_code ?? '' }}"  placeholder="Swift Code" />
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <!-- ---Bank Name input field-------  -->
                                <div class="col form-group">
                                    <label class="text-muted font-weight-normal ml-3">Bank Name</label>
                                    <input type="text" class="form-control form-control-lg login_input-s" name="bank_name" value="{{ $userBank->bank_name ?? '' }}" placeholder="Bank Name" />
                                </div>
                                <!-- -------Account Number Input Field------  -->
                                <div class="col form-group pt-3">
                                    <label class="text-muted font-weight-normal ml-3">Account Number</label>
                                    <input type="number" class="form-control  login_input-s w-100 p-4" name="account_number" value="{{ $userBank->account_number ?? '' }}" placeholder="Account Number" />
                                </div>
                                <!-- -------Branch Code Input Field------  -->
                                <div class="col form-group pt-3">
                                    <label class="text-muted font-weight-normal ml-3">Branch Code</label>
                                    <input type="number" class="form-control  login_input-s w-100 p-4" name="branch_code" value="{{ $userBank->branch_code ?? '' }}" placeholder="Branch Code" />
                                </div>
                            </div>
                            <div class="col form-check pt-3 ml-4 login-checkout-s">
                                <label class="col form-check-label text-muted fs_large-s">
                                    <input type="checkbox" class="form-check-input zoom_checkbox-s mt-1" value="1" name='accept_tos' id='cbx_tos-d'>Terms and Conditions
                                </label>
                            </div>
                        </div>
                    @endif
                        <!-- ---------- End Bank account Info Form -------  -->


                            <!-- ------Buttons------- -->
                    <div class="col pt-5 login_button-s text-center mb-5">
                        <input type="hidden" name="" id="check_profile_type-d" value="{{ $profile->profile_type }}">
                        <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SAVE</button>
                    </div>
            </div>
        </form>

    @include('user::modals.upload_profile_image')
    @include('common::modals.waiting_popup', ['model_type' => 'Profile'])
@endsection

@section('header-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/phone_input_custom.css') }}" />
@endsection


@section('footer-scripts')

    <script>
        let TEACHER_DASHBOARD_URL_2 = "{{ route('teacher.dashboard') }}"
        let STUDENT_DASHBOARD_URL = "{{ route('student.dashboard') }}"
    </script>
    {{--  Intel-tel-input  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script type="text/javascript" src='{{ asset('modules/common/assets/js/phone_input_custom.js') }}'></script>
    <script type="text/javascript" src='{{ asset('modules/user/assets/js/user.js') }}'></script>
    <script type="text/javascript"></script>
@endsection
