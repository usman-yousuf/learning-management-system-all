
@php
    $showForm = (isset($page) && ('dashboard' == $page || 'edit' == $page));
    $dataColClass = ($showForm)? "col-lg-8 col-12" : "col-12";
    $dataCol2Class = ($showForm)? "col-lg-4 col-12" : "hidden";
@endphp

<div class="row flex-column-reverse flex-md-row">
    <div class="{{ $dataColClass }}">
        <div class="row video_course_content_container-d">
            {{-- course content will appear here dynamically --}}
        </div>
    </div>
    <div class="{{ $dataCol2Class }}">
        <!-- Form Start -->
        <form action="{{ route('course.video-content') }}" method="post" id="video_course_content_form-d" class="" method="POST" enctype="multipart/form-data">
            <div class="card shadow border-0 mt-4 ">
                <div class="card-body ">
                    <!-- Input Fields Start -->
                    <div class="row ">
                        <div class="col-12 ">
                            <label class="custom-label-s " for="content_title">Title of Conetnt</label>
                            <div class="mb-3">
                                <input type="text" name="content_title" class="form-control form-control-lg custom-input-s " id="title_handout-d" placeholder="Layout Designing" required>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="form-group d-inline-flex">
                            <div class="col-md-6">
                                <label class="text-muted font-weight-normal ml-3" for="duration_hrs-d">Duration Hrs</label>
                                <input type="number" class="form-control form-control-lg login_input-s" name="duration_hrs" id="duration_hrs-d" min="0" placeholder="Duration Hours" />
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted font-weight-normal ml-3" for="duration_mins-d">Duration Mins</label>
                                <input type="number" class="form-control form-control-lg login_input-s" name="duration_mins" id="duration_mins-d" min="0" max="59" placeholder="Duration Minutes" />
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-12 mt-3 ">
                            <label class="custom-label-s" for="url_link">URL LINK</label>
                            <div class="mb-3">
                                <input type="text" name="url_link" class="form-control form-contol-lg custom-input-s " id="url_link-d" placeholder="www.pdf.com" />
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-12 mt-2">
                            <div class="file-loading mt-3">
                                <input type='hidden' name='content_image' id='hdn_content_image-d' value='{{ $content->content_image ?? '' }}' />
                                <img id='trigger_video_course_upload-d'src="{{ asset('assets/images/modal_upload_img_icon.svg') }}" alt="upload-icon">
                                <img id="content_image-d" src="{{ getFileUrl($content->content_image ?? null, null, 'course') }}" class="upload_image-s img_90x70-s" alt="">
                                <input id="upload_course_content-d" type="file" onchange="previewUploadedFile(this, '#content_image-d', '#hdn_content_image-d', 'course');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('course') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Input Fields End -->

                    <!-- Card Buttons -->
                    <div class="text-center mt-5 px-1">
                        <input type='hidden' name='video_course_content_uuid' id='hdn_video_course_content_uuid-d' value='{{ $content->video_course_content_uuid ?? '' }}' />
                        <button type="submit" class="custom-card-button1-s pl-4 pr-4 pl-lg-4 pr-lg-4 pl-xl-5 pr-xl-5 mr-xl-0 border border-white">Save</button>
                    </div>
                    <!-- Card Buttons End -->
                </div>
            </div>
        </form>
        <!-- Form End     -->
    </div>
</div>

<div class="cloneables_container-d" style='display:none;'>
    <div class="col-sm-6 col-12 video_course_single_container-d" id="cloneable_video_course_content-d">
        <div class="card shadow mt-4 customs_card-s">
            <div>
                <img class="video_img-s img-fluid video_course_content_thumbnail-d" src="{{ getFileUrl(null, null, 'video') }}" alt="video thumbnail" />
                <img class="video_play_btn-s" src="{{ asset('assets/images/play_icon.svg') }}" alt="play video icon" />
            </div>
            <div class="card-body">
                <h5 class="card-title custom_handout_title-s video_course_title-d">
                    <a href="javascript:void(0)" class='video_course_link-d'>Website Designing</a>
                </h5>
                <div class="row pt-2">
                    <div class="col d-flex justify-content-between align-self-center">
                        <span class="course_video_time-d video_course_duration-d">3:20 min</span>
                        <input type="hidden" class="course_video_uuid-d" value='{{ $item->uuid ?? '' }}'/>
                        <span>
                            <a href="javascript:void(0)" class='delete_video_content-d'>
                                <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-outline" />
                            </a>
                            <a href="javascript:void(0)" class='edit_video_content-d'>
                                <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-outline" />
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
