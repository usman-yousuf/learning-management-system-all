
    <div class="modal fade" id="add_video_modal" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container mb-3 pb-3 ">
                        <!--Modal Header-->
                        <div class="row ml-3 ">
                            <div class="mt-3 mb-3 ">
                                <h4 class="modal-title text-success" id="modal-head">Course Content</h4>
                            </div>
                        </div>
                        <!--Modal Header End-->
                        <!--modal body-->
                        <div class="row d-flex flex-lg-row  flex-column-reverse ">
                            <div class=" col-xl-8 col-lg-8 col-md-12  ">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 ">
                                        <div class="card shadow mt-4 customs_card-s">
                                            <div>
                                                <img alt="video" class="video_img-s img-fluid" src="assets/preview/card1.png">
                                                <img alt="video" class="video_play_btn-s" src="assets/preview/video_play_btn.svg" data-toggle="modal" data-target="#video_modal-d">
                                            </div>
                                            <div class="card-body ">
                                                <h5 class="card-title custom_handout_title-s ">Website Designing</h5>
                                                    <div class="row pt-2">
                                                        <div class="col  d-flex justify-content-between align-self-center">
                                                            <span class=" " id="course_video_time-d">3:20 min</span>
                                                            <span>
                                                                <a href="javascript:void(0)"><img class="pl-1 pr-1 pt-1 pb-1" src="assets/preview/delete_icon.svg" alt=""></a>
                                                                <a href="javascript:void(0)"><img class="pl-1 pr-1 pt-1 pb-1" src="assets/preview/edit_icon.svg" alt=""></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="col-xl-6 col-lg-6 ">
                                        <div class="card shadow mt-4 customs_card-s">
                                            <div>
                                                <img alt="video" class="video_img-s img-fluid" src="assets/preview/card1.png">
                                                <img alt="video" class="video_play_btn-s" src="assets/preview/video_play_btn.svg" data-toggle="modal" data-target="#video_modal-d">
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title custom_handout_title-s">Layout Designing</h5>
                                                    <div class="row pt-2">
                                                        <div class="col  d-flex justify-content-between align-self-center">
                                                            <span class=" " id="course_video_time-d">3:20 min</span>
                                                            <span>
                                                                <a href="javascript:void(0)"><img class="pl-1 pr-1 pt-1 pb-1" src="assets/preview/delete_icon.svg" alt=""></a>
                                                                <a href="javascript:void(0)"><img class="pl-1 pr-1 pt-1 pb-1" src="assets/preview/edit_icon.svg" alt=""></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 ">
                                <!-- Form Start -->
                                <form action=" " method="post" id="course_content_form-d" class="" novalidate>
                                    <div class="card shadow border-0 mt-4 ">
                                        <div class="card-body ">
                                            <!-- Input Fields Start -->
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="text-success text-center">ADD New</h5>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 ">
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
                                            <div class="row ">
                                                <div class="col-12 ">
                                                    <label class="custom-label-s " for="handout_title">Title of Video</label>
                                                    <div class=" mb-3 ">
                                                        <input type="text" name="handout_title" class="form-control form-control-lg custom-input-s " id="title_handout-d" placeholder="Layout Designing" required>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-xl-6 col-lg-12 col-md-6 pb-3">
                                                    <label class="custom-label-s" for="hours">Duration</label>
                                                    <input type="number" class="form-control form-control-lg login_input-s" name="hours" id="duration-d" placeholder="Hours" />
                                                </div>
                                                <div class="col-xl-6 col-lg-12 col-md-6  pb-3">
                                                    <label class="custom-label-s" for="minutes">Duration</label>
                                                    <input type="number" class="form-control form-control-lg login_input-s" name="minutes" id="minutes-d" placeholder="Minutes" />
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-12 ">
                                                    <label class="custom-label-s " for="url_link">URL LINK</label>
                                                    <div class=" mb-3 ">
                                                        <input type="text" name="url_link" class="form-control form-control-lg custom-input-s " id="url_link-d" placeholder="https://www.youtube.com" required>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Input Fields End -->

                                            <!-- Card Buttons -->
                                            <div class="d-flex justify-content-between align-self-center  mt-4">
                                                <a href="javascript:void(0)" class="btn handout-card-button1-s pl-4 pr-4   border border-white">Save</a>
                                                <a href="javascript:void(0)" class="btn handout-card-button2-s pl-4 pr-4   border border-white">Add</a>
                                            </div>
                                            <!-- Card Buttons End -->
                                        </div>
                                    </div>
                                </form>
                                <!-- Form End     -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="video_modal-d">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal_border-s">
                <div class="container">
                <!-- Modal body -->
                    <div class="modal-body ">
                        <video width="100%" controls>
                            <source id="video_stop-d" src="assets/preview/videofile.mp4" type="video/mp4" >
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
