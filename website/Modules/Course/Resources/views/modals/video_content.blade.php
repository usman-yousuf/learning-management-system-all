
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
                        @include('course::partials.video_course_content', ['page' => 'dashboard', 'contents' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="open_video_modal-d">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal_border-s">
                <div class="container">
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                @php
                                    $additionalChecks = '&autoplay=1&mute=1';
                                @endphp
                                <iframe class='iframe_play_video-d'
                                    width="100%" height="500"
                                    src="" frameborder="0" allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
