@php
// dd($students);
@endphp

    <form action="{{ route('course.update') }}" id='frm_course_setting-d'>
        <!-- Course Details and Status - START -->
        <div class="row py-5">
            <div class="col-12 ">
                <h4 class="font-weight-bold">Course Details</h4>
            </div>
        </div>
        <div class="row py-5">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <h4 class="">Upload Thumbnail</h4>
                <div class="file-loading mt-3 text-center col-xl-8 col-12">
                    <img id="course_cover_image-d" src="{{ getFileUrl($course->course_image ?? null, null, 'course') }}" class="upload_image-s thumbnail_image_size-s" alt="course image">
                    <input type='hidden' name='course_image' id='hdn_course_cover_image-d' value='{{ $course->course_image ?? '' }}' />
                    <br />
                    <label class='mt-3 click_course_image-d'>
                        <a class="btn upload_btn-s"><img src="{{ asset('assets/images/camera_icon_white.svg') }}" width="15" alt="upload-icon"><span
                            class="">&nbsp; Upload Image</span></a>
                        </label>
                    </label>
                    <input id="upload_course_image-d" type="file" onchange="previewUploadedFile(this, '#course_cover_image-d', '#hdn_course_cover_image-d', 'course');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('course') }}">
                </div>
            </div>

            <div class='col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12'>
                <div class="row pt-xl-5 pt-lg-5">
                    <div class="col-12 col-lg-12 mt-xl-4 mt-lg-4 pt-lg-2 pt-xl-2 text-left">
                        <h6 class="upload_thumbnail-s font_size_larger-s mb-4">Course Status</h6>
                    </div>
                </div>

                <div class="row py-1">
                    <div class="col-12">
                        <div class="row mb-3">
                            <div class="col-6">
                                <button type="button" class="btn btn_success_hover-s fg_light-s br_light-s py-3 ft_12px-s w-s course_status-d @if(isset($course) && ('active' == $course->course_status)) active @endif fs_16px-s" data-status='active'>Active</button>
                            </div>
                            <div class=" col-6 ">
                                <button type="button" class="btn btn_success_hover-s fg_light-s br_light-s py-3 ft_12px-s w-s course_status-d @if(isset($course) && ('inactive' == $course->course_status)) active @endif fs_16px-s" data-status='inactive'>In Active</button>
                            </div>
                        </div>
                        <div class=" row ">
                            <div class="col-6 ">
                                <button type="button" class="btn btn_success_hover-s fg_light-s br_light-s py-3 ft_12px-s w-s course_status-d @if(isset($course) && ('draft' == $course->course_status)) active @endif fs_16px-s" data-status='draft'>Draft</button>
                            </div>
                            <div class=" col-6 ">
                                <button type="button" class="btn btn_success_hover-s fg_light-s br_light-s py-3 ft_12px-s w-s course_status-d @if(isset($course) && ('published' == $course->course_status)) active @endif fs_16px-s" data-status='published'>Published</button>
                            </div>
                        </div>
                        <input type="hidden" name='course_status' id='hdn_course_status-d' value="{{ $course->course_status ?? '' }}" />
                    </div>
                </div>
            </div>
        </div>
        <!-- Course Details and Status - END -->

        <!-- Course Inputs - START -->
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <!-- Course Name Input type  -->
                    <div class="col-xl-6 col-lg-6 my-3">
                        <label class="font-weight-normal ml-3 course_textarea-s">Course Title</label>
                        <input type="text" class="bg-light-s form-control form-control-lg login_input-s " name="title" id="course_title-d" placeholder="Website Designing" value="{{ $course->title ?? '' }}" />
                    </div>

                    <!-- ---------Course Category------- -->
                    <div class="col-xl-6 col-lg-6 my-3">
                        <label for="course_category" class="font-weight-normal ml-3 course_textarea-s">Course Category</label>
                        @php
                            $categories = getCourseCategories();
                        @endphp
                        <select class="form-control bg-light-s input_radius-s" id="course_category_uuid-d" name="course_category_uuid">
                            @forelse ($categories as $item)
                                <option value='{{ $item->uuid }}' @if(isset($course) && ($course->category->uuid == $item->uuid)) selected="selected" @endif>{{ $item->name }}</option>
                            @empty
                                <option value=''>Select an Option</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-12 pl-4 pt-3">
                        <h5>Course Duration</h5>
                    </div>
                    
                    <div class="col-xl-6 col-lg-6 my-3">
                        <div class="form-group">
                            <label class="font-weight-normal ml-3 course_textarea-s">Starts From</label>
                            <input type="date" class="form-control form-control-lg login_input-s  ft_15px-s course_starts_at-t course_starts_at-d" name="start_date" placeholder="Starting Date" value="{{ $course->start_date ?? '' }}" min="{{ date('Y-m-d', strtotime($course->start_date ?? '-15 days')) }}" />

                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 my-3">
                        <div class="form-group">
                            <label class="font-weight-normal ml-3 course_textarea-s">Ends At</label>
                            <input type="date" class="form-control form-control-lg login_input-s  ft_15px-s course_ends_at-t course_end_at-d" name="end_date" placeholder="Endind Date" value="{{ $course->end_date ?? '' }}" min="{{ date('Y-m-d', strtotime($course->start_date ?? 'now')) }}" />
                        </div>
                    </div>
                    
                    <!-- -------Course Description textarea input type----  -->
                    <div class="col-xl-12 form-group my-3">
                        <label for="description" class="ml-3 course_textarea-s">Course Description</label>
                        <textarea class="form-control course_des_textarea-s bg-light-s textarea_h-s" rows="5" id="description-d" name="description" placeholder="Something about this Course" value="{{ $course->description ?? '' }}">{{ $course->description ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- Course Inputs - END -->

        <!-- Course Radio button - START -->
        <div class="row">
            <div class="col-xl-6 my-3">
                <div class="row">
                    <div class="col-6">
                        <h5>Course</h5>
                    </div>
                </div>
                <!-- Couorse Option Button - START -->
                <div class="row">
                    <div class="col">
                        <div class=" form-check-inline">
                            <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="rb_course_course_free-d" value="1" @if(isset($course) && ('1' == $course->is_course_free)) checked="checked" @endif name="is_course_free" />Free
                        </label>
                        </div>
                        <div class=" ml-lg-5 pl-lg-5 form-check-inline">
                            <label class="form-check-label">
                            <input type="radio" class="form-check-input" id="rb_course_is_paid-d" value="0" @if(isset($course) && ('0' == $course->is_course_free)) checked="checked" @endif name="is_course_free" />Paid
                        </label>
                        </div>
                    </div>
                </div>
                <!-- Course Option Button - START -->
            </div>
        </div>
            
        <!-- Course Radio button - START -->
        
        <div id="course_detail-d">
            <div class="row mt-4 pl-lg-3 mr-sm-4">
                <div class="col-lg-4 col-sm-6">
                    {{-- <label class="custom-label-s" for="price_usd">Course Fee In USD</label>
                    <div class=" mb-3">
                        <input type="number" class="form-control form-control-lg custom-input-s" name="price_usd" id="price_usd-d" placeholder="e.g $500" />
                    </div> --}}
                    <label class="custom-label-s" for="price_usd">Amount</label>
                    <div class=" mb-3">
                        <input type="number" class="form-control form-control-lg custom-input-s" name="price" id="price" placeholder="e.g $500" />
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 ">
                    <label class="custom-label-s" for="discount_usd">Discount</label>
                    <div class="mb-3 ">
                        {{-- <input type="number" class="form-control form-control-lg custom-input-s" name="discount_usd" id="discount_usd-d" placeholder="e.g 10%" /> --}}
                        <input type="number" class="form-control form-control-lg custom-input-s" name="discount" id="discount" placeholder="e.g 10%" />
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 ">
                    <label class="custom-label-s" for="discount_usd">Payment Options</label>
                    <div class="mb-3">
                        @php
                            $paymentOptions = listCurrencies();
                        @endphp
                        <select class="form-control form-control-lg custom-input-s" name="currency" aria-label="Default select example">
                        @foreach ($paymentOptions as $option => $currency)
                                <option  value="{{ $option }}">{{  $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            {{-- <div class="row mt-lg-4 pl-lg-3 mr-sm-4 ">
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
            </div> --}}
        </div> 
        <!-- Handout Radio button - START -->
        <div class="row">
            <div class="col-xl-6 my-3">
                <div class="row">
                    <div class="col-6">
                        <h5>Handouts</h5>
                    </div>
                </div>
                <!-- Handout Option Button - START -->
                <div class="row">
                    <div class="col">
                        <div class=" form-check-inline">
                            <label class="form-check-label">
                            <input type="radio" class="form-check-input"  value="1" @if(!isset($course) || ('1'== $course->is_handout_free)) checked="checked" @endif name="is_handout_free" />Free
                        </label>
                        </div>
                        <div class=" ml-lg-5 pl-lg-5 form-check-inline">
                            <label class="form-check-label">
                            <input type="radio" class="form-check-input" value="0" @if(!isset($course) || ('0'== $course->is_handout_free)) checked="checked" @endif name="is_handout_free" />Paid
                        </label>
                        </div>
                    </div>
                </div>
                <!-- Handout Option Button - START -->
            </div>
        </div>
        <!-- Handout Radio button - START -->

        <!-- Save button - START -->
        <div class="row">
            <div class="col text-center my-3">
                <div class="">
                    <input type="hidden" name='course_uuid' class='course_uuid' id='hdn_course_setting_uuid-d' value="{{ $course->uuid ?? '' }}" />

                    <input type="hidden" name='teacher_uuid' value="{{ $course->teacher->uuid ?? '' }}" />
                    <input type="hidden" name='nature' value="{{ $course->nature ?? '' }}" />

                    <input type="hidden" name='is_handout_free' value="{{ $course->is_handout_free ?? '' }}" />
                    <input type="hidden" name='price_usd' value="{{ $course->price_usd ?? '' }}" />
                    <input type="hidden" name='discount_usd' value="{{ $course->discount_usd ?? '' }}" />
                    <input type="hidden" name='price_pkr' value="{{ $course->price_pkr ?? '' }}" />
                    <input type="hidden" name='discount_pkr' value="{{ $course->discount_pkr ?? '' }}" />
                    <input type="hidden" name='start_date' value="{{ $course->start_date ?? '' }}" />
                    <input type="hidden" name='end_date' value="{{ $course->end_date ?? '' }}" />

                    <button type="submit" class=" border border-white bg-success-s text-white w-25 py-2 py-xl-3 br_27px-s fg_white-s">Save</button>
                </div>
            </div>
        </div>
        <!-- Save button - END -->
    </form>
