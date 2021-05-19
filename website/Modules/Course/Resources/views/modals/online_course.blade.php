<div class="modal fade" id="course_details_modal-d">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header model_header-s">
                        <!-- Modal Header Start -->
                        <section id="tabs">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <nav>
                                            <div class="nav nav-pills" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active px-lg-0 px-xl-3 mt-xl-3 " id="nav-home-tab" data-toggle="tab" href="#nav_course_detail" role="tab" aria-controls="nav-home" aria-selected="true">Course Details</a>
                                                <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3" id="nav-profile-tab" data-toggle="tab" href="#nav_course_outline" role="tab" aria-controls="nav-profile" aria-selected="false">Course Outline</a>
                                                <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3" id="nav-contact-tab" data-toggle="tab" href="#nav_course_slots" role="tab" aria-controls="nav-about" aria-selected="false">Course Slots</a>
                                                <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3" id="nav-contact-tab" data-toggle="tab" href="#nav_course_content" role="tab" aria-controls="nav-contact" aria-selected="false">Course Content</a>
                                                <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3" id="nav-about-tab" data-toggle="tab" href="#nav_handout_content" role="tab" aria-controls="nav-about" aria-selected="false">Handout Content</a>
                                                <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3" id="nav-about-tab" data-toggle="tab" href="#nav_course_fee" role="tab" aria-controls="nav-about" aria-selected="false">Course Fee</a>
                                            </div>
                                        </nav>
                                        <!-- COURSE DETAIL START  -->


                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Modal Header End -->
                        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="tab-content  px-3 px-sm-0" id="nav-tabContent">
                            <!-- COURSE DETAIL START -->
                            <div class="tab-pane fade show active" id="nav_course_detail" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="container w-100">
                                    <form action="" id="course_detail_form-d">
                                        <div class="row">
                                            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 col-12">
                                                <div class="col text-center">
                                                    <h6 class="upload_thumbnail-s">Upload Thumbnail</h6>
                                                    <img id="education_certificate-d" src="assets/1.jpg" class="upload_image-s" alt="">
                                                    <div class="file-loading mt-3">
                                                        <label for="input-b9">
                                                        <a class="btn btn- upload_btn-s"><img src="assets/preview/camera.svg" width="15" alt=""><span
                                                            class="ml-3 ">Upload Image</span></a>
                                                        </label>
                                                        <!-- <label for="input-b9">
                                                            <img src="assets/login/upload_icon.svg" />
                                                          </label> -->
                                                        <input id="input-b9" type='file' onchange="readURL2(this);" name="input-b9[]" class="d-none" multiple type="file">
                                                    </div>
                                                    <div id="kartik-file1-errors"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 col-12">
                                                <!-- Course Name Input type  -->
                                                <div class="col form-group">
                                                    <label class="font-weight-normal ml-3 course_textarea-s">Course Name</label>
                                                    <input type="text" class="form-control form-control-lg login_input-s w-75" name="course_name" placeholder="Website Designing">

                                                </div>

                                                <!-- ---------Course Category------- -->
                                                <div class="col form-group pt-3">
                                                    <label for="course_category" class="font-weight-normal ml-3 course_textarea-s">Course
                                                        Category
                                                    </label>
                                                    <select class="form-control  input_radius-s w-75" id="course_category" name="course_category">
                                                        <option>Video Course</option>
                                                        <option>Website Designing</option>
                                                    </select>
                                                </div>
                                                <!-- -------Course Description textarea input type----  -->
                                                <div class="col form-group pt-3">
                                                    <label for="comment" class="ml-3 course_textarea-s">Course Description</label>
                                                    <textarea class="form-control course_des_textarea-s" rows="5" id="comment" name="comment_text"></textarea>
                                                </div>
                                                <!-- ------Buttons------- -->
                                                <div class="col py-4 text-right">
                                                    <button type="submit" class="btn  course_detail_btn-s course_detail_btn-d pt-lg-3 pb-lg-3 " data-target="#nav_course_outline">Next</button>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- COURSE DETAIL END  -->

                            <!-- COURSE OUTLINE START  -->
                            <div class="tab-pane fade online_course_outline-d" id="nav_course_outline" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div class="w-100">
                                    <!-- course outline detail 1 start-->
                                    <div class="row">
                                        <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 col-12">
                                            <!-- course outline detail 1 start  -->
                                            <div class="row py-3">
                                                <div class="col-xl-12 col-lg-12 col-sm-9 col-9 d-inline-flex course_outline_text-s">
                                                    <div class="col-sm-10 col-12">
                                                        <div class="d-flex flex-wrap float-lg-right">
                                                            <div class="mx-3">
                                                                <span>01</span>
                                                            </div>
                                                            <div>
                                                                <span style="word-break: break-word;">Layout Designing ………………………………………………………………………….</span>
                                                            </div>&nbsp;
                                                            <div>
                                                                <span>3 : 20 Mints</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-12 align-self-center">
                                                        <a href="">
                                                            <img src="assets/preview/delete_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_delete-d">
                                                        </a>
                                                        <a href="">
                                                            <img src="assets/preview/edit_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- course outline detail 1 end  -->

                                            <!-- course outline detail 2 start  -->
                                            <div class="row py-4">
                                                <div class="col-xl-12 col-lg-12 col-sm-9 col-9 d-inline-flex course_outline_text-s">
                                                    <div class="col-sm-10 col-12">
                                                        <div class="d-flex flex-wrap float-lg-right">
                                                            <div class="mx-3">
                                                                <span>02</span>
                                                            </div>
                                                            <div>
                                                                <span style="word-break: break-word;">Make to gif file in Photoshop…………………………………………………</span>
                                                            </div>&nbsp;
                                                            <div>
                                                                <span>3 : 20 Mints</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-12 align-self-center">
                                                        <a href="">
                                                            <img src="assets/preview/delete_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_delete-d">
                                                        </a>
                                                        <a href="">
                                                            <img src="assets/preview/edit_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- course outline detail 2 end  -->

                                            <!-- course outline detail 3 start  -->
                                            <div class="row py-4">
                                                <div class="col-xl-12 col-lg-12 col-sm-9 col-9 d-inline-flex course_outline_text-s">
                                                    <div class="col-sm-10 col-12">
                                                        <div class="d-flex flex-wrap float-lg-right">
                                                            <div class="mx-3">
                                                                <span>03</span>
                                                            </div>
                                                            <div>
                                                                <span style="word-break: break-word;">How to Design logo……………………………………………………………………..</span>
                                                            </div>&nbsp;
                                                            <div>
                                                                <span>3 : 20 Mints</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-12 align-self-center">
                                                        <a href="">
                                                            <img src="assets/preview/delete_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_delete-d">
                                                        </a>
                                                        <a href="">
                                                            <img src="assets/preview/edit_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- course outline detail 3 end  -->

                                            <!-- course outline detail 4 start  -->
                                            <div class="row py-4">
                                                <div class="col-xl-12 col-lg-12 col-sm-9 col-9 d-inline-flex course_outline_text-s">
                                                    <div class="col-sm-10 col-12">
                                                        <div class="d-flex flex-wrap float-lg-right">
                                                            <div class="mx-3">
                                                                <span>04</span>
                                                            </div>
                                                            <div>
                                                                <span style="word-break: break-word;">How to Design logo……………………………………………………………………..</span>
                                                            </div>&nbsp;
                                                            <div>
                                                                <span>3 : 20 Mints</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-12 align-self-center">
                                                        <a href="">
                                                            <img src="assets/preview/delete_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_delete-d">
                                                        </a>
                                                        <a href="">
                                                            <img src="assets/preview/edit_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- course outline detail 4 end  -->

                                            <!-- course outline detail 5 start  -->
                                            <div class="row py-4">
                                                <div class="col-xl-12 col-lg-12 col-sm-9 col-9 d-inline-flex course_outline_text-s">
                                                    <div class="col-sm-10 col-12">
                                                        <div class="d-flex flex-wrap float-lg-right">
                                                            <div class="mx-3">
                                                                <span>05</span>
                                                            </div>
                                                            <div>
                                                                <span style="word-break: break-word;">How to Design logo……………………………………………………………………..</span>
                                                            </div>&nbsp;
                                                            <div>
                                                                <span>3 : 20 Mints</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-12 align-self-center">
                                                        <a href="">
                                                            <img src="assets/preview/delete_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_delete-d">
                                                        </a>
                                                        <a href="">
                                                            <img src="assets/preview/edit_icon.svg" alt="">
                                                            <input type="hidden" id="course_outline_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- course outline detail 5 end  -->
                                        </div>
                                        <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 col-12">
                                            <form action="" id="course_outline_form-d" class="pt-4 shadow rounded" novalidate>
                                                <!-- ----First name & Last name----  -->
                                                <div class="form-group d-inline-flex">
                                                    <div class="col-md-6">
                                                        <label class="text-muted font-weight-normal ml-3" for="hours">Duration</label>
                                                        <input type="number" class="form-control form-control-lg login_input-s" name="hours" id="duration-d" placeholder="Hours" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="text-muted font-weight-normal ml-3" for="minutes">Duration</label>
                                                        <input type="number" class="form-control form-control-lg login_input-s" name="minutes" id="minutes-d" placeholder="Minutes" />
                                                    </div>


                                                </div>
                                                <!-- -----Title input field---- -->
                                                <div class="col form-group pt-3">
                                                    <label class="text-muted font-weight-normal ml-3" for="title">Title</label>
                                                    <input type="text" class="form-control form-control-lg login_input-s" id="title" name="title" placeholder="Layout Designing" required>

                                                </div>
                                                <!-- ----- Button------ -->
                                                <div class="col py-5 login_button-s">
                                                    <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SAVE</button>
                                                    <a href="sign_up.html" class="btn btn- shadow float-right pt-lg-3 pb-lg-3">Add</a>
                                                </div>
                                            </form>
                                            <div class="pt-5 pb-5 pr-5 login_button-s text-right">
                                                <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- course outline detail 1 end -->


                                </div>
                            </div>
                            <!-- COURSE OUTLINE END  -->

                            <!-- COURSE SLOTS START -->
                            <div class="tab-pane fade" id="nav_course_slots" role="tabpanel" aria-labelledby="nav-about-tab">
                                <div class="container-fluid">
                                    <div class="row flex-column-reverse flex-md-row p-3">
                                        <div class="h-100 col-xl-7 col-lg-7 col-12 ml-lg-3 mt-4">
                                            <div class="row border rounded">
                                                <div class="col-12">
                                                    <div class="row mt-3 text-center">
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xl-3 col-6">
                                                            <div>
                                                                <span class="custom_slots_title-s">Start Date</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01 Feb</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6  ">
                                                            <div>
                                                                <span class="custom_slots_title-s">End Date</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01 Feb</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                                                            <div>
                                                                <span class="custom_slots_title-s">Start Time</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01:00 am</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                                                            <div>
                                                                <span class="custom_slots_title-s">End Time</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">03:00 am</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3 pt-3">
                                                        <div class="col d-flex ml-sm-3 ml-lg-4 ml-xl-5 ml-3">
                                                            <div class="mr-2 custom-day-sign-s"><span>M</span></div>
                                                            <div class="mr-2 custom-day-sign-s"><span>T</span></div>
                                                            <div class="mr-2 custom-day-sign-s"><span>W</span></div>
                                                        </div>
                                                        <div class="float-right pr-sm-3 pr-lg-4 pr-xl-5 pr-3">
                                                            <a href="">
                                                                <img src="assets/course-slots/delete@2x.svg" alt="">
                                                                <input type="hidden" id="slots_delete-d">
                                                            </a>
                                                            <a href="">
                                                                <img src="assets/course-slots/edit@2x.svg" alt="">
                                                                <input type="hidden" id="slots_edit-d">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row border rounded mt-5">
                                                <div class="col-12">
                                                    <div class="row mt-3 text-center">
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xl-3 col-6">
                                                            <div>
                                                                <span class="custom_slots_title-s">Start Date</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01 Feb</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6  ">
                                                            <div>
                                                                <span class="custom_slots_title-s">End Date</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01 Feb</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                                                            <div>
                                                                <span class="custom_slots_title-s">Start Time</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01:00 am</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                                                            <div>
                                                                <span class="custom_slots_title-s">End Time</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">03:00 am</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3 pt-3">
                                                        <div class="col d-flex ml-sm-3 ml-lg-4 ml-xl-5 ml-3">
                                                            <div class="mr-2 custom-day-sign-s"><span>M</span></div>
                                                            <div class="mr-2 custom-day-sign-s"><span>T</span></div>
                                                            <div class="mr-2 custom-day-sign-s"><span>W</span></div>
                                                        </div>
                                                        <div class="float-right pr-sm-3 pr-lg-4 pr-xl-5 pr-3">
                                                            <a href="">
                                                                <img src="assets/course-slots/delete@2x.svg" alt="">
                                                                <input type="hidden" id="slots_delete-d">
                                                            </a>
                                                            <a href="">
                                                                <img src="assets/course-slots/edit@2x.svg" alt="">
                                                                <input type="hidden" id="slots_edit-d">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row border rounded mt-5">
                                                <div class="col-12">
                                                    <div class="row mt-3 text-center">
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xl-3 col-6">
                                                            <div>
                                                                <span class="custom_slots_title-s">Start Date</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01 Feb</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6  ">
                                                            <div>
                                                                <span class="custom_slots_title-s">End Date</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01 Feb</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                                                            <div>
                                                                <span class="custom_slots_title-s">Start Time</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">01:00 am</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                                                            <div>
                                                                <span class="custom_slots_title-s">End Time</span>
                                                            </div>
                                                            <div class=" mt-3">
                                                                <span class="">03:00 am</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3 pt-3">
                                                        <div class="col d-flex ml-sm-3 ml-lg-4 ml-xl-5 ml-3">
                                                            <div class="mr-2 custom-day-sign-s"><span>M</span></div>
                                                            <div class="mr-2 custom-day-sign-s"><span>T</span></div>
                                                            <div class="mr-2 custom-day-sign-s"><span>W</span></div>
                                                        </div>
                                                        <div class="float-right pr-sm-3 pr-lg-4 pr-xl-5 pr-3">
                                                            <a href="">
                                                                <img src="assets/course-slots/delete@2x.svg" alt="">
                                                                <input type="hidden" id="slots_delete-d">
                                                            </a>
                                                            <a href="">
                                                                <img src="assets/course-slots/edit@2x.svg" alt="">
                                                                <input type="hidden" id="slots_edit-d">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-lg-4 ml-lg-4 ml-xl-5">
                                            <!-- Form Start -->
                                            <form action="" method="" id="course_slots_form-d" class="" novalidate>
                                                <div class="card shadow border-0 row mt-4">
                                                    <div class="card-body">
                                                        <!-- Input Fields Start -->
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6  ">
                                                                <label class="custom-label-s" for="start_date">Start Date</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="date" class="form-control form-control-lg custom-slots-input-s" name="start_date" id="start_date-d" placeholder="">

                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6 ">
                                                                <label class="custom-label-s " for="end_date">End Date</label>
                                                                <div class="input-group mb-3 ">
                                                                    <input type="date" class="form-control form-control-lg custom-slots-input-s" name="end_date" id="end_date-d" placeholder="">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row ">
                                                            <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6 ">
                                                                <label class="custom-label-s " for="start_time">Start Time</label>
                                                                <div class="input-group mb-3 ">
                                                                    <input type="time" class="form-control form-control-lg custom-slots-input-s" name="start_time" id="start_time-d" placeholder="">

                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
                                                                <label class="custom-label-s " for="end_time">End Time</label>
                                                                <div class="input-group mb-3 ">
                                                                    <input type="time" class="form-control form-control-lg custom-slots-input-s" name="end_time" id="end_time-d" placeholder="">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Input Fields End -->
                                                        <div class="row mt-4">
                                                            <div class="col d-flex ml-lg-2 ml-3 ">
                                                                <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-s"><span>S</span></div>
                                                                <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-active-s"><span>M</span></div>
                                                                <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-s"><span>T</span></div>
                                                                <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-active-s"><span>W</span></div>
                                                                <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-s"><span>T</span></div>
                                                                <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-active-s"><span>F</span></div>
                                                                <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-s"><span>S</span></div>
                                                            </div>
                                                        </div>


                                                        <!-- Card Buttons -->
                                                        <div class="text-center mt-5 px-1">
                                                            <button type="submit" class="slots-card-button1-s pl-4 pr-4 pl-lg-4 pr-lg-4 mr-xl-5 border border-white">Save</button>
                                                            <button type="submit" class="slots-card-button2-s pl-4 pr-4 pl-lg-4 pr-lg-4 border border-white">Add</button>
                                                        </div>
                                                        <!-- Card Buttons End -->
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- Form End     -->
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer align-self-right custom-footer-s pr-lg-2 pr-xl-4 mb-4">
                                    <button type="submit " class="custom-button-s mr-5 border border-white " data-dismiss="modal ">Next</button>
                                </div>
                            </div>
                            <!-- COURSE SLOTS END -->

                            <!-- COURSE CONTENT START -->
                            <div class="tab-pane fade" id="nav_course_content" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card shadow mt-4 customs_card-s">
                                                <iframe class="card-img-top custom-card1-img-s" src="assets/course-content/pdf.svg"></iframe>
                                                <div class="card-body">
                                                    <h5 class="card-title custom_content_title-s ">Website Designing</h5>
                                                    <div class="float-right ">
                                                        <a href=" ">
                                                            <img src="assets/course-content/delete@2x.svg " alt=" ">
                                                            <input type="hidden" id="course_content_website_delete-d">
                                                        </a>
                                                        <a href=" ">
                                                            <img src="assets/course-content/edit@2x.svg " alt=" ">
                                                            <input type="hidden" id="course_content_website_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 ">
                                            <div class="card shadow mt-4 customs_card-s ">
                                                <iframe class="card-img-top custom-card2-img-s " src="assets/course-content/laptop.svg "></iframe>
                                                <div class="card-body ">
                                                    <h5 class="card-title custom_content_title-s ">Layout Designing</h5>
                                                    <div class="float-right ">
                                                        <a href=" ">
                                                            <img src="assets/course-content/delete@2x.svg " alt=" ">
                                                            <input type="hidden" id="course_content_layout_delete-d">
                                                        </a>
                                                        <a href=" ">
                                                            <img src="assets/course-content/edit@2x.svg " alt=" ">
                                                            <input type="hidden" id="course_content_layout_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 ">
                                            <!-- Form Start -->
                                            <form action=" " method="post" id="course_content_form-d" class="" novalidate>
                                                <div class="card shadow border-0 mt-4 ">
                                                    <div class="card-body ">
                                                        <!-- Input Fields Start -->
                                                        <div class="row ">
                                                            <div class="col-12 ">
                                                                <label class="custom-label-s " for="handout_title">Title of Handout</label>
                                                                <div class=" mb-3 ">
                                                                    <input type="text" name="handout_title" class="form-control form-control-lg custom-input-s " id="title_handout-d" placeholder="Layout Designing" required>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row ">
                                                            <div class="col-12 mt-3 ">
                                                                <label class="custom-label-s " for="time">Duration</label>
                                                                <div class=" mb-3 ">
                                                                    <input type="time" name="time" class="form-control form-control-lg custom-input-s " id="time-d" placeholder="3:20 Mints" required>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row ">
                                                            <div class="col-12 mt-3 ">
                                                                <label class="custom-label-s " for="url_link">URL LINK</label>
                                                                <div class=" mb-3 ">
                                                                    <input type="text" name="url_link" class="form-control form-contol-lg custom-input-s " id="url_link-d" placeholder="www.pdf.com" required>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row ">
                                                            <div class="col-12 mt-5">
                                                                <div class=" d-flex mb-3 ">
                                                                    <!-- <input type="file " class="form-control " id=" " placeholder=" "> -->
                                                                    <div>
                                                                        <label class='click_profile_image-d' for="upload_file">
                                                                            <img src="assets/course-content/upload-file.svg " alt="upload" />
                                                                        </label>
                                                                        <input id="upload_img_preview-d" name="upload_file" type='file' onchange="readURL(this);" style="display: none;" />
                                                                    </div>
                                                                    <img id="preview-output" class="img-preview ml-sm-2 ml-2 pb-1 " src="http://placehold.it/180 " alt="image" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Input Fields End -->

                                                        <!-- Card Buttons -->
                                                        <div class="text-center mt-5 px-1">
                                                            <button type="submit" class="custom-card-button1-s pl-4 pr-4 pl-lg-4 pr-lg-4 pl-xl-5 pr-xl-5 mr-xl-0 border border-white">Save</button>
                                                            <button type="submit" class="custom-card-button2-s pl-4 pr-4 pl-lg-4 pr-lg-4 pl-xl-5 pr-xl-5 mr-xl-0 border border-white">Add</button>
                                                        </div>
                                                        <!-- Card Buttons End -->
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- Form End     -->
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer align-self-right custom-footer-s pr-lg-2 pt-xl-5 pt-lg-5 pr-xl-4 mb-4 ">
                                    <button type="submit " class="custom-button-s mr-5 border border-white " data-dismiss="modal ">Next</button>
                                </div>
                            </div>
                            <!-- COURSE CONTENT END -->

                            <!-- HANDOUT CONTENT START -->
                            <div class="tab-pane fade" id="nav_handout_content" role="tabpanel" aria-labelledby="nav-about-tab">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="card shadow mt-4 customs_card-s">
                                                <img class="card-img-top custom-card1-img-s" style="height: 190px;" src="assets/preview/pdf.svg" alt="Card image cap">
                                                <div class="card-body">
                                                    <h5 class="card-title custom_handout_title-s">Website Designing</h5>
                                                    <div class="float-right">
                                                        <a href="">
                                                            <img src="assets/preview/delete_icon.svg" alt="">
                                                            <input type="hidden" id="handout_website_delete-d">
                                                        </a>
                                                        <a href="">
                                                            <img src="assets/preview/edit_icon.svg" alt="">
                                                            <input type="hidden" id="handout_website_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="card shadow mt-4 customs_card-s">
                                                <img class="card-img-top custom-card2-img-s" style="height: 190px;" src="assets/preview/laptop.svg" alt="Card image cap">
                                                <div class="card-body">
                                                    <h5 class="card-title custom_handout_title-s">Layout Designing</h5>
                                                    <div class="float-right">
                                                        <a href="">
                                                            <img src="assets/preview/delete_icon.svg" alt="">
                                                            <input type="hidden" id="handout_layout_delete-d">
                                                        </a>
                                                        <a href="">
                                                            <img src="assets/preview/edit_icon.svg" alt="">
                                                            <input type="hidden" id="handout_layout_edit-d">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="card shadow border-0 mt-4">
                                                <div class="card-body">
                                                    <!-- Form Start -->
                                                    <form action="" method="post" id="handout_content_form-d" class="" novalidate>
                                                        <!-- Input Fields Start -->
                                                        <div class="row ">
                                                            <div class="col-12">
                                                                <label class="custom-label-s" for="handout">Title of Handout</label>
                                                                <div class="mb-3">
                                                                    <input type="text" class="form-control form-control-lg custom-input-s" name="handout" id="handout-d" placeholder="Layout Designing">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row  ">
                                                            <div class="col-12 mt-3 ">
                                                                <label class="custom-label-s " for="link">URL LINK</label>
                                                                <div class="mb-3 ">
                                                                    <input type="text " class="form-control form-control-lg custom-input-s" name="link" id="link-d" placeholder="www.pdf.com">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Input Fields End -->

                                                        <!-- Card Buttons -->
                                                        <div class="d-flex ml-md-5 ml-lg-0 mt-5">
                                                            <button type="submit" class="handout-card-button1-s px-4 px-md-5 px-lg-4 px-xl-5 mr-xl-5 border border-white">Save</button>
                                                            <button type="submit" class="handout-card-button2-s px-4 px-md-5 px-lg-4 px-xl-5 ml-lg-3 ml-md-5 ml-xl-0 border border-white">Add</button>
                                                        </div>
                                                        <!-- Card Buttons End -->
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer align-self-right custom-footer-s mb-5 mt-5 pt-5 ">
                                        <button type="submit " class="custom-button-s mr-5 border border-white " data-dismiss="modal ">Next</button>
                                    </div>
                                </div>
                            </div>
                            <!-- HANDOUT COONTENT END -->

                            <!-- COURSE FEE START -->
                            <div class="tab-pane fade" id="nav_course_fee" role="tabpanel" aria-labelledby="nav-about-tab">
                                <div class="container-fluid ml-lg-4">
                                    <div class="row pl-3">
                                        <div class="col mt-2">
                                            <h4>Course Type</h4>
                                        </div>
                                    </div>
                                    <!-- Course Type Radio Buttons -->
                                    <div class="row mt-2 pl-3">
                                        <div class="col">
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                            <input type="radio" class="form-check-input" id="hide_detail-d" onclick="showHideCourseInfo()" name="optradio">Free
                                        </label>
                                            </div>
                                            <div class=" ml-lg-5 pl-lg-5 form-check-inline">
                                                <label class="form-check-label">
                                            <input type="radio" class="form-check-input" id="show_detail-d" onclick="showHideCourseInfo()" name="optradio">Paid
                                        </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Course Type Radio Button End -->
                                    <div id="handout_section-d">
                                        <div class="row mt-2 pl-3">
                                            <div class="col">
                                                <span class=" handout-class">Handout</span>
                                            </div>
                                        </div>

                                        <!-- Handout Radio Buttons Start -->

                                        <div class="row mt-2 pl-3">
                                            <div class="col">
                                                <div class=" form-check-inline">
                                                    <label class="form-check-label">
                                            <input type="radio" class="form-check-input" id="hide_handout-d"  name="optradio">Free
                                        </label>
                                                </div>
                                                <div class=" ml-lg-5 pl-lg-5 form-check-inline">
                                                    <label class="form-check-label">
                                            <input type="radio" class="form-check-input" id="show_handout-d" name="optradio">Paid
                                        </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Handout Radio Buttons End -->
                                    </div>

                                    <Form action="" id="course_fee_form-d" class="" novalidate>
                                        <!-- Input Fields Start -->
                                        <div id="course_detail-d">
                                            <div class="row mt-4 pl-lg-3 mr-sm-4">
                                                <div class="col-lg-4 col-sm-6">
                                                    <label class="custom-label-s" for="fee_USD">Course Fee In USD</label>
                                                    <div class=" mb-3">
                                                        <input type="text" class="form-control form-control-lg custom-input-s" name="fee_USD" id="Fee_USD-d" placeholder="USD">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 ">
                                                    <label class="custom-label-s " for="discount_USD">Discount</label>
                                                    <div class="mb-3 ">
                                                        <input type="text " class="form-control form-control-lg custom-input-s" name="discount_USD" id="discount_USD-d" placeholder="25%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-lg-4 pl-lg-3 mr-sm-4 ">
                                                <div class="col-lg-4 col-sm-6 ">
                                                    <label class="custom-label-s " for="fee_PKR">Course Fee In PKR</label>
                                                    <div class=" mb-3 ">
                                                        <input type="text " class="form-control form-control-lg custom-input-s" name="fee_PKR" id="fee_PKR-d" placeholder="PKR">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 ">
                                                    <label class="custom-label-s " for="discount_PKR">Discount</label>
                                                    <div class=" mb-3 ">
                                                        <input type="text " class="form-control form-control-lg custom-input-s" name="discount_PKR" id="discount_PKR-d" placeholder="25%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer col-xl-7 col-lg-8 col-md-9 col-12  align-self-center custom-footer-s mb-5 ">
                                            <button type="submit" class="custom-button-s border border-white " data-dismiss="modal ">Save</button>
                                        </div>
                                        <!-- Input Fields End -->
                                    </Form>

                                </div>


                            </div>
                            <!-- COURSE FEE END -->
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div> -->

                </div>
            </div>
        </div>
