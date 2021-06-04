
    <div class="modal fade" id="" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
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
                        @include('course::partials.video_course_content', ['page' => 'dashboard', 'contents' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="course_queries_modal-d">
        <div class="modal-dialog modal-xl">
            <div class="modal-content d-flex">

                <!-- Modal Header Start -->
                <div class="container-fluid">
                    <div class="row">
                        <div class=" col-12 modal-header border-0">
                            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                            <span class="w-100">
                                <a data-dismiss="modal"><img class="custom-close float-right" src="assets/Group@2x.svg" alt=""></a>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Modal Header End -->


                <!-- Modal Body Start -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="row ml-4 justify-content-around">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-9 ml-3">
                                                <span>
                                            <a href=""><img class="w_25px-s" src="../website/public/assets/images/add_quiz_teacher_icon.png" alt=""></a>
                                        </span>
                                                <span class="fg-success-s">liesah</span>
                                            </div>
                                            <div class="col-9 ml-3">
                                                <p>
                                                    Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="ml-3">
                                                    <span>Ans:</span> <span>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard</span>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <span><a href=""><img src="assets/preview/cancel.svg " alt=""></a></span>
                                                <span><a href=""><img src="assets/preview/tick_mark.svg " alt=""></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ml-4 mt-4 justify-content-around">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-9 ml-3">
                                                <span>
                                            <a href=""><img class="w_25px-s" src="../website/public/assets/images/add_quiz_teacher_icon.png" alt=""></a>
                                        </span>
                                                <span class="fg-success-s">liesah</span>
                                            </div>
                                            <div class="col-9 ml-3">
                                                <p>
                                                    Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="ml-3">
                                                    <!-- <span>Ans:</span> <span>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard</span> -->
                                                    <textarea class="bg_light-s w-100 min_height_75px-s max_height_71px-s" name="general_question_textarea" id="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="col-2 text-left">
                                                <span><a href=""><img src="assets/preview/send_icon.svg " alt=""></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ml-4 mt-4 justify-content-around">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-9 ml-3">
                                                <span>
                                            <a href=""><img class="w_25px-s" src="../website/public/assets/images/add_quiz_teacher_icon.png" alt=""></a>
                                        </span>
                                                <span class="fg-success-s">liesah</span>
                                            </div>
                                            <div class="col-9 ml-3">
                                                <p>
                                                    Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-10">
                                                <div class="ml-3">
                                                    <!-- <span>Ans:</span> <span>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard</span> -->
                                                    <textarea class="bg_light-s w-100 min_height_75px-s max_height_71px-s" name="general_question_textarea" id="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="col-2 text-left">
                                                <span><a href=""><img src="assets/preview/send_icon.svg " alt=""></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Modal Body End -->
            </div>
        </div>
    </div>
