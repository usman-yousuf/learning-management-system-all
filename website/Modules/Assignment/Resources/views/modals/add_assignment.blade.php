@php

@endphp

<div class="modal" id="modal_add_assignment-d">
    <div class="modal-dialog modal-xl">
        <div class="modal-content d-flex">
            <!-- Modal Header -->
            <div class="modal-header custom-header-s align-self-center mt-3 w-100">
                <h5 class="modal-title font-weight-bold w-100 text-center fg_green-s">Add Assignment</h5>
                <a data-dismiss="modal">
                    <img class="float-right" src="{{ asset('assets/images/modal_close_icon.svg') }}" alt="X">
                </a>
            </div>


            <!-- Modal Body Start -->
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="">
                        <!-- assignments inputs - START -->
                        <div class="row justify-content-xl-around justify-content-lg-around mt-4 mt-md-4 mt-lg-4 mt-xl-4">
                            <div class="w-100 col-xl-4 col-lg-4">
                                <label class="font-weight-normal course_textarea-s ml-3" for="course_uuid">Course Name</label>
                                <select class="form-control input_radius-s" id="course_uuid-d" name="course_uuid">
                                    <option>Video Course</option>
                                    <option>Website Designing</option>
                                </select>
                            </div>
                            <div class="w-100 col-xl-4 col-lg-4 mt-3 mt-md-3 mt-lg-0 mt-xl-0">
                                <label class="font-weight-normal course_textarea-s ml-3" for="slot_uuid">Slot</label>
                                <select class="form-control input_radius-s" id="course_slot-d" name="course_slot"></select>
                            </div>
                        </div>

                        <div class="row justify-content-xl-around justify-content-lg-around mt-3 mt-md-3 mt-lg-5 mt-xl-5">
                            <div class="w-100 col-xl-4 col-lg-4">
                                <label class="font-weight-normal course_textarea-s ml-3" for="start_date">Start Date</label>
                                <input type="date" class="form-control form-control-lg login_input-s" name="start_date" id="assignment_start_date-d" />
                            </div>
                            <div class="w-100 col-xl-4 col-lg-4 mt-3 mt-md-3 mt-lg-0 mt-xl-0">
                                <label class="font-weight-normal course_textarea-s ml-3" for="due_date">Due Date</label>
                                <input type="date" class="form-control form-control-lg login_input-s" name="due_date" id="assignment_due_date-d" />
                            </div>
                        </div>


                        <div class="row mt-3 mt-md-3 mt-lg-5 mt-xl-5">
                            <div class="w-100 col-xl-4 col-lg-4 offset-xl-1 offset-lg-1">
                                <label class="font-weight-normal course_textarea-s ml-3" for="total_marks">Total Marks</label>
                                <input type="text" class="form-control form-control-lg login_input-s" name="total_marks" id="total_marks-d" placeholder="e.g 100">
                            </div>
                        </div>
                        <div class="row mt-4 mt-md-4 mt-lg-5 mt-xl-5">
                            <div class="w-100 col-xl-4 col-lg-4 offset-xl-1 offset-lg-1">
                                <div class="file-loading mt-3">
                                    <input type='hidden' name='media_1' id='hdn_assignment_media_1-d' class='hdn_assignment_media_1-d' value='{{ $assignment->media_1 ?? '' }}' />
                                    <img id='trigger_assignment_media_upload-d'src="{{ asset('assets/images/modal_upload_img_icon.svg') }}" alt="upload-icon" />
                                    <img id="media_1-d" src="{{ getFileUrl($assignment->media_1 ?? null, null, 'assignment') }}" data-default_path="{{ getFileUrl(null, null, 'assignment') }}" class="upload_image-s img_90x70-s preview_img assignment_image-d" alt="default-image" />
                                    <input id="upload_assignment_media-d" type="file" onchange="previewUploadedFile(this, '.assignment_image-d', '.hdn_assignment_media_1-d', 'assignment');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('assignment') }}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- assignments inputs - END -->
            </div>
            <!-- Modal Body End -->


            <!-- Modal footer -->
            <div class="row">
                <div class="col-12">
                    <div class=" col-3 w-100 mx-auto align-self-center modal-footer border-0 mb-4">
                        <button type="submit " class="py-xl-3 py-lg-2 py-md-2 py-2 w-100 text-white bg_success-s br_27px-s custom-button border border-white " data-dismiss="modal ">Add</button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer End -->
        </div>
    </div>
</div>
