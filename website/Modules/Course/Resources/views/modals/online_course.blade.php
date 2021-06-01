<div class="modal" id="course_details_modal-d">
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
                                        <a class="nav-item nav-link active px-lg-0 px-xl-3 mt-xl-3 nav_item_trigger_link-d" id="nav-home-tab" data-toggle="tab" href="#nav_course_detail" role="tab" aria-controls="nav-home" aria-selected="true">Course Details</a>
                                        <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3 nav_item_trigger_link-d  @if(!isset($course->details->uuid) || ('' == $details->uuid)) disabled  @endif" id="nav-course-outline" data-toggle="tab" href="#nav_course_outline" role="tab" aria-controls="nav-profile" aria-selected="false">Course Outline</a>
                                        <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3 nav_item_trigger_link-d  @if(!isset($course->details->uuid) || ('' == $details->uuid)) disabled  @endif" id="nav-course-slot" data-toggle="tab" href="#nav_course_slots" role="tab" aria-controls="nav-about" aria-selected="false">Course Slots</a>
                                        <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3 nav_item_trigger_link-d  @if(!isset($course->details->uuid) || ('' == $details->uuid)) disabled  @endif" id="nav-course-video-content" data-toggle="tab" href="#nav_course_content" role="tab" aria-controls="nav-contact" aria-selected="false">Course Content</a>
                                        <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3 nav_item_trigger_link-d  @if(!isset($course->details->uuid) || ('' == $details->uuid)) disabled  @endif" id="nav-course-handout" data-toggle="tab" href="#nav_handout_content" role="tab" aria-controls="nav-about" aria-selected="false">Handout Content</a>
                                        <a class="nav-item nav-link px-lg-1 p-xl-4 mx-lg-2 mx-xl-3 nav_item_trigger_link-d  @if(!isset($course->details->uuid) || ('' == $details->uuid)) disabled  @endif" id="nav-course-fee" data-toggle="tab" href="#nav_course_fee" role="tab" aria-controls="nav-about" aria-selected="false">Course Fee</a>
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
                    {{--  Course Details - START  --}}
                    <div class="tab-pane fade show active" id="nav_course_detail" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="container">
                            <form id="frm_course_details-d" action="{{ route('course.update') }}" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 col-12">
                                        <div class="col text-center">
                                            <h6 class="upload_thumbnail-s">Upload Thumbnail</h6>
                                            <div class="file-loading mt-3">
                                                <img id="course_image-d" src="{{ getFileUrl($details->course_image ?? null, null, 'course') }}" class="upload_image-s img_200x175-s" alt="">
                                                <input type='hidden' name='course_image' id='hdn_course_image-d' value='{{ $details->course_image ?? '' }}' />
                                                <br />
                                                <label class='mt-3 click_course_image-d'>
                                                    <a class="btn upload_btn-s"><img src="{{ asset('assets/images/camera_icon_white.svg') }}" width="15" alt="upload-icon"><span
                                                        class="">&nbsp; Upload Image</span></a>
                                                    </label>
                                                </label>
                                                <input id="upload_course_image-d" type="file" onchange="previewUploadedFile(this, '#course_image-d', '#hdn_course_image-d', 'course');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('course') }}">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 col-12">
                                        <!-- Course Name Input type  -->
                                        <div class="col form-group">
                                            <label class="font-weight-normal ml-3 course_textarea-s">Course Name</label>
                                            <input type="text" class="form-control form-control-lg login_input-s w-75 ft_15px-s" name="title" placeholder="e.g Website Designing" value="{{ $details->title ?? '' }}" />
                                        </div>

                                        <div class="col form-group">
                                            <label class="font-weight-normal ml-3 course_textarea-s">Starts From</label>
                                            <input type="date" class="form-control form-control-lg login_input-s w-75 ft_15px-s" name="start_date" placeholder="Staring Date" value="{{ $details->start_date ?? '' }}" />
                                        </div>

                                        <div class="col form-group">
                                            <label class="font-weight-normal ml-3 course_textarea-s">Ends At</label>
                                            <input type="date" class="form-control form-control-lg login_input-s w-75 ft_15px-s" name="end_date" placeholder="Staring Date" value="{{ $details->end_date ?? '' }}" />
                                        </div>

                                        <!-- ---------Course Category------- -->
                                        <div class="col form-group pt-3">
                                            <label for="course_category_uuid" class="font-weight-normal ml-3 course_textarea-s">
                                                Course Category
                                            </label>
                                            @php
                                                $categories = getCourseCategories();
                                            @endphp
                                            <select class="form-control input_radius-s w-75 ft_15px-s" id="course_category_uuid" name="course_category_uuid">
                                                @forelse ($categories as $item)
                                                    <option value='{{ $item->uuid }}' @if(isset($course->details->course_category_uuid) && ($details->course_category_uuid == $item->uuid)) selected="selected" @endif>{{ $item->name }}</option>
                                                @empty
                                                    <option value=''>Select an Option</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <!-- -------Course Description textarea input type----  -->
                                        <div class="col form-group pt-3">
                                            <label for="description" class="ml-3 course_textarea-s">Course Description</label>
                                            <textarea class="form-control course_des_textarea-s" rows="5" id="description" name="description" placeholder="Something about this Course" value="{{ $details->description ?? '' }}">{{ $details->description ?? '' }}</textarea>
                                        </div>
                                        <!-- ------Buttons------- -->
                                        <div class="col py-4 text-right">
                                            <input type='hidden' name='teacher_uuid' id='hdn_teacher_uuid-d' value="{{ $details->teacher_uuid ?? '' }}" />
                                            <input type='hidden' name='course_uuid' class='hdn_course_uuid-d' value="{{ $details->course_uuid ?? '' }}" />
                                            <input type='hidden' name='nature' value="online" />
                                            <button type="submit" class="btn course_detail_btn-s course_detail_btn-d pt-lg-3 pb-lg-3 ">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{--  Course Details - END  --}}


                    <!-- COURSE OUTLINE START  -->
                    <div class="tab-pane fade online_course_outline-d" id="nav_course_outline" role="tabpanel" aria-labelledby="nav-course-outline">
                        <div class="container ml-lg-4">
                            <!-- course outline - start-->
                            @include('course::partials.course_outline', ['page' => 'dashboard'])
                            <!-- course outline - end -->
                            <div class="modal-footer align-self-right custom-footer-s pr-lg-2 pt-xl-5 pt-lg-5 pr-xl-4 mb-4 ">
                                <button type="button " class="custom-button-s mr-5 border border-white " data-dismiss="modal ">Next</button>
                            </div>
                        </div>
                    </div>
                    <!-- COURSE OUTLINE END  -->

                    <!-- COURSE SLOTS START -->
                    <div class="tab-pane fade" id="nav_course_slots" role="tabpanel" aria-labelledby="nav-course-slot">
                        <div class="container-fluid">
                            <!-- course outline - start-->
                            @include('course::partials.course_slot', ['page' => 'dashboard'])
                            <!-- course outline - end -->

                            <div class="modal-footer align-self-right custom-footer-s pr-lg-2 pr-xl-4 mb-4">
                                <button type="submit " class="custom-button-s mr-5 border border-white " data-dismiss="modal ">Next</button>
                            </div>
                        </div>
                    </div>
                    <!-- COURSE SLOTS END -->

                    <!-- COURSE CONTENT START -->
                    <div class="tab-pane fade" id="nav_course_content" role="tabpanel" aria-labelledby="nav-course-video-content">
                        <div class="container-fluid">
                            <!-- video course content - start -->
                            @include('course::partials.video_course_content', ['page' => 'dashboard'])
                            <!-- video course content - END -->
                        </div>
                        <div class="modal-footer align-self-right custom-footer-s pr-lg-2 pt-xl-5 pt-lg-5 pr-xl-4 mb-4 ">
                            <button type="submit " class="custom-button-s mr-5 border border-white " data-dismiss="modal ">Next</button>
                        </div>
                    </div>
                    <!-- COURSE CONTENT END -->

                    <!-- HANDOUT CONTENT START -->
                    <div class="tab-pane fade" id="nav_handout_content" role="tabpanel" aria-labelledby="nav-course-handout">
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
                    <div class="tab-pane fade" id="nav_course_fee" role="tabpanel" aria-labelledby="nav-course-fee">
                        <div class="container ml-lg-4">
                            <Form action="{{ route('course.update') }}" id="frm_course_fee-d" class="" novalidate>
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
                                                <input type="radio" class="form-check-input rb_course_free-d" id="rb_is_course_free-d" name="is_course_free" value="1" @if(!isset($course) || (0 != (int)$details->is_course_free)) checked="checked"  @endif />
                                                Free
                                            </label>
                                        </div>
                                        <div class=" ml-lg-5 pl-lg-5 form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input rb_course_free-d" id="rb_is_course_paid-d" name="is_course_free" value="0" @if(isset($course) && (0 == (int)$details->is_course_free)) checked="checked" @endif />
                                                Paid
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Course Type Radio Button End -->
                                <div id="handout_section-d">
                                    <div class="row mt-2 pl-3">
                                        <div class="col">
                                            <span class="handout-class">Handout</span>
                                        </div>
                                    </div>
                                    <div class="row mt-2 pl-3">
                                        <div class="col">
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="is_handout_free" value="1" @if(!isset($course) || (0 != (int)$details->is_handout_free)) checked="checked" @endif />
                                                    Free
                                                </label>
                                            </div>
                                            <div class="ml-lg-5 pl-lg-5 form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="is_handout_free" value="0" @if(isset($course) && (0 == (int)$details->is_handout_free)) checked="checked" @endif />
                                                    Paid
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Fields Start -->
                                <div id="course_detail-d">
                                    <div class="row mt-4 pl-lg-3 mr-sm-4">
                                        <div class="col-lg-4 col-sm-6">
                                            <label class="custom-label-s" for="price_usd">Course Fee In USD</label>
                                            <div class=" mb-3">
                                                <input type="number" class="form-control form-control-lg custom-input-s" name="price_usd" id="price_usd-d" placeholder="e.g $500" />
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 ">
                                            <label class="custom-label-s" for="discount_usd">Discount</label>
                                            <div class="mb-3 ">
                                                <input type="number" class="form-control form-control-lg custom-input-s" name="discount_usd" id="discount_usd-d" placeholder="e.g 10%" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-lg-4 pl-lg-3 mr-sm-4 ">
                                        <div class="col-lg-4 col-sm-6 ">
                                            <label class="custom-label-s" for="price_pkr">Course Fee In PKR</label>
                                            <div class=" mb-3 ">
                                                <input type="number" class="form-control form-control-lg custom-input-s" name="price_pkr" id="price_pkr-d" placeholder="e.g PKR 2500">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 ">
                                            <label class="custom-label-s" for="discount_usd">Discount</label>
                                            <div class="mb-3">
                                                <input type="number" class="form-control form-control-lg custom-input-s" name="discount_pkr" id="discount_pkr-d" placeholder="e.g 25%">
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


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
    </script>
@endpush
