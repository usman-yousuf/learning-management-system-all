@extends('user::layouts.master')

@section('profile-content')
    <form action="" class="needs-validation" novalidate >
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 choose_profile_image-s">
                    <div class="row">
                        <div class="col">
                            <div class="text-center py-5 profile_image-s">
                                <img class="profile_img-d" src="{{ asset('assets/images/placeholder_user.png') }}" class="rounded-circle img-fluid" width="35%" alt="">
                                <input type='hidden' name='profile_image' id='hdn_profile_image-d' />
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
                            <input type="text" class="form-control form-control-lg login_input-s" name="first_name" placeholder="Name" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------Last Name Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">Last Name</label>
                            <input type="text" class="form-control  login_input-s w-100 p-4" name="last_name" placeholder="Last Nmae" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <!-- ---------Gender------- -->
                        <div class="col form-group pt-3">
                            <label for="exampleFormControlSelect1" class="text-muted font-weight-normal ml-3">Gender</label>
                            <select class="form-control  input_radius-s" id="exampleFormControlSelect1">
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>

                    <!-- </form> -->
                </div>
            </div>


            <!-- ----------DatePicker------- -->
            <div class="row">
                <div class="col-sm-6 pt-5">
                    <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3 ">Date of Birth</label>
                            <input type="date"  class="form-control input_radius-s" name="date">
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>

            <!-- ----------Complete Address Form -------  -->
            <h5 class="p-3">Complete Address</h5>
            <!-- <form action="" class="needs-validation" novalidate> -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---Current Address input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">Current Address</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="current_address" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------City Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">City</label>
                            <input type="text" class="form-control  login_input-s w-100 p-4" name="city" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------Mobile Number Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">Mobile Number</label>
                            <input type="number" class="form-control  login_input-s w-100 p-4" name="mobile_number" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---Permanent Address input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">Permanent Address</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="Permanent_address" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------Country Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">Country</label>
                            <input type="text" class="form-control  login_input-s w-100 p-4" name="country" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------Phone Number Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">Phone Number</label>
                            <input type="number" class="form-control  login_input-s w-100 p-4" name="phone_number" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                    </div>
                </div>
            <!-- </form> -->

            <!-- ----------Education Form -------  -->
            <h5 class="p-3">Education</h5>
            <!-- <form action="" class="needs-validation" novalidate> -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---School input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">School</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="school" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------University Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">University</label>
                            <input type="text" class="form-control  login_input-s w-100 p-4" name="university" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---College input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">College</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="college" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------Other Institute Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">Other Institute</label>
                            <input type="text" class="form-control  login_input-s w-100 p-4" name="other_institute" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>


                    </div>
                </div>
            <!-- </form> -->

            <!-- ----------Uploading Education Form -------  -->
            <h5 class="p-3">Education</h5>
            <!-- <form action="" class="needs-validation" novalidate> -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---Organization input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">Organization</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="organization" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---date input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3 ">Date</label>
                            <input type="date"  class="form-control input_radius-s" name="date">
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>
                <!-- --------upload education --------  -->
                <div class="row">
                    <div class="col-12">
                        <div class="col">
                            <img id="education_certificate-d" src="{{ asset('assets/images/placeholder_user.png') }}" class="rounded" width="10%" alt="">
                            <div class="file-loading mt-3">
                                <label for="input-b9">
                                    <img src="{{ asset('assets/images/upload_image_icon.svg') }}" alt="upload"/>
                                </label>
                                <input id="input-b9" type='file' onchange="readURL2(this);"  name="input-b9[]" multiple type="file">
                            </div>
                            <div id="kartik-file1-errors"></div>
                        </div>
                    </div>
                </div>
                <!-- --------upload education end--------  -->
            <!-- </form> -->
            <!-- ----------Uploading Education Form End-------  -->

            <!-- ----------Experience input Form -------  -->
            <h5 class="p-3">Experience</h5>
            <!-- <form action="" class="needs-validation" novalidate> -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---Job Experience input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">Job Experience</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="job_experience" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---Teaching Experience input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3 ">Teaching Experience</label>
                            <input type="text"  class="form-control input_radius-s" name="teaching_experience">
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
            <!-- ----------Experience input Form End-------  -->

            <!-- ----------Text Area input Form -------  -->
            <h5 class="p-3">Interests</h5>
            <!-- <form action="" class="needs-validation" novalidate> -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <!-- ---Interest TextArea-------  -->
                        <div class="col form-group">
                            <textarea  class="form-control form-control-lg profile_textarea-s rounded" name="interest"  required>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type spicemen book. </textarea>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
                    <!-- ----------TextArea input Form End-------  -->

                    <!-- ----------Bank account Info Form -------  -->
            <h5 class="p-3">Bank account Info</h5>
            <!-- <form action="" class="needs-validation" novalidate> -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---Account Title input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">Account Title</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="account_title" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------IBAN Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">IBAN</label>
                            <input type="number" class="form-control  login_input-s w-100 p-4" name="iban" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- ------- Branch Name Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3"> Branch Name</label>
                            <input type="text" class="form-control  login_input-s w-100 p-4" name="branch_name" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- ------- Swift Code Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3"> Swift Code</label>
                            <input type="text" class="form-control  login_input-s w-100 p-4" name="swift_code" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ---Bank Name input field-------  -->
                        <div class="col form-group">
                            <label class="text-muted font-weight-normal ml-3">Bank Name</label>
                            <input type="text" class="form-control form-control-lg login_input-s" name="bank_name" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------Account Number Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">Account Number</label>
                            <input type="number" class="form-control  login_input-s w-100 p-4" name="account_number" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- -------Branch Code Input Field------  -->
                        <div class="col form-group pt-3">
                            <label class="text-muted font-weight-normal ml-3">Branch Code</label>
                            <input type="number" class="form-control  login_input-s w-100 p-4" name="branch_code" placeholder="" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <!-- --------checkbox----- -->
                        <div class="col form-check pt-3 ml-3 login-checkout-s">
                            <label class="col form-check-label text-muted">
                                <input type="checkbox" class="form-check-input" value="">Terms and Condition
                            </label>
                        </div>
                </div>
                <!-- ------Buttons------- -->
                <div class="col pt-5 login_button-s text-center mb-5">
                    <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SAVE</button>
                </div>
        </div>
    </form>

    @include('user::modals.upload_profile_image')
@endsection


@section('footer-scripts')
    <script type="text/javascript"></script>
    <script type="text/javascript" src='{{ asset('modules/user/assets/js/user.js') }}'></script>
@endsection
