@php
    $slots = isset($slots)? $slots : [];
@endphp
    <div class="modal fade" id="add_video_modal" tabindex="-1" role="tabpanel" aria-labelledby="modal-head" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="diaglog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container mb-3 pb-3 ">
                        <!--Modal Header-->
                        <div class="row ml-3 ">
                            <div class="mt-3 mb-3 ">
                                <h4 class="modal-title text-success" id="modal-head">Course Slots</h4>
                            </div>
                        </div>
                        <!--Modal Header End-->
                        <!--modal body-->
                        @include('course::partials.course_slot', ['page' => 'details', 'slots' => $slots])
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
