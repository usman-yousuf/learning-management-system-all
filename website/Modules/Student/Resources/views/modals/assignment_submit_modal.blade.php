   <!--assignment submit modal-->
   <div class="modal fade" id="assignment_submit-d" tabindex="-1"  aria-labelledby="view-head" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" >
        <div class="modal-content ">
            <div class="modal-header d-block">    
                <div class="container pb-5">
                    <!--modal header-->
                    <div class="row">
                        <div class="col-12 text-right">
                            <a class="close pt-3 pr-0" data-dismiss="modal" aria-label="Close">
                                <img class="float-right" src="{{ asset('assets/images/cancel_circle.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <!--modal header end-->

                    <!-- MODAL BODY-->
                    <form action="{{ route('student.uploadAssignment') }}" id="student_submit_assignment-d" method="post">
                        <div class="modal-body ">
                            <div class="row pb-4">
                                <div class="col-12 text-center">
                                    <h2 class="fg-success-s pb-5">Assignment</h2>
                                </div>
                            </div>
                            <div class="row pb-3 px-xl-5">
                                <div class="col-xl-4 col-lg-6 col-md-12  col-12">
                                    <a class='no_link-s link-d'href="javascript:void(0)">
                                        <h4 class=" title-d assignment_title-d">
                                            
                                        </h4>
                                    </a>
                                </div>
                                <div class="col-xl-8 col-lg-6 col-md-12 col-12 mt-1 text-xl-right text-lg-right">
                                    <span >
                                        <img class="img_25_x_25-s" src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="calendar">
                                    </span>
                                    <span class="pl-2 due_date_assignment-d">
                                        
                                    </span>
                                </div>
                            </div>
                            <div class="row pt-3 px-xl-5">
                                <div class="col-12 d-md-flex">
                                    <div class="text-center">
                                        <div class="col form-group pt-3 upload_file_container-d">
                                            <div class="file-loading">
                                                <img id="experience_thumb-d" src="{{ getFileUrl($experience->image ?? null, null, 'assignment') }}" class="rounded square_100p-s mb-2" alt="experience-image">
                                                <input type='hidden' name='upload_assignment_image' id='hdn_experience_image-d' value='{{ $experience->image ?? '' }}' />
                                                    
                                                <label class='click_experience_image-d'>
                                                    <img src="{{ asset('assets/images/upload_image_icon.svg') }}" alt="upload-assignment"/>
                                                </label>
                                                <input id="upload_experience_image-d" type="file" onchange="previewUploadedFile(this, '#experience_thumb-d', '#hdn_experience_image-d', 'assignment');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('assignment') }}">
                                            </div>
                                        </div>
                                        {{-- <div class="file-loading">
                                            <img id="upload_thumb-d" src="{{ getFileUrl('' ?? null, null, 'certificate') }}" class="rounded square_100p-s mb-2" alt="experience-image">
                                            <input type='hidden' name='upload_assignment_image' id='hdn_upload_assignment_image-d' value='{{ $experience->image ?? '' }}' />
        
                                            <label class='click_upload_assignment_image-d'>
                                                <img src="{{ asset('assets/images/upload_image_icon.svg') }}" alt="upload-assignment"/>
                                            </label>
                                            <input id="upload_assignment_image-d" type="file" onchange="previewUploadedFile(this, '#upload_thumb-d', '#hdn_upload_assignment_image-d', 'experience');" data-allowed_fileExtensions="{{ getAllowedFileExtensions('experience') }}">
                                        </div> --}}

                                        {{-- <label class='doc_upload-d' for="">
                                            <img src="{{ asset('assets/images/upload_icon.svg') }} "class="img_200x175-s " alt="upload" />
                                        </label> --}}
                                        {{-- <input id="upload_doc_preview-d" type="file" name="upload_file" style="display: none;"/> --}}
                                    </div>
                                    
                                    {{-- <div class="ml-md-3 mt-md-0 mt-3 text-center">
                                        <img id="education_certificate-d" src="{{ asset('assets/images/card1.png') }}" class="img_200x175-s obj_fit_cover-s"  alt="">
                                    </div> --}}
                                </div>
                                
                            </div>     
                        </div>
                        <!-- modal body end-->  
                        <!-- Modal footer -->
                        <div class="modal-footer border-0 mb-4 mt-3  justify-content-center">
                            <input type="hidden" name="course_uuid"  class="get_course_uuid-d" >
                            <input type="hidden" name="assignment_uuid" class="get_assignment_uuid-d" >
                            <button type="submit" class="btn bg_success-s br_24-s py-2  text-white w_315px-s border border-white" >
                                Submit
                            </button>
                        </div>
                        <!-- Modal footer End -->      
                    </form>  
                </div>
            </div>
        </div>
    </div>          
</div>
<!--assignment submit modal end-->