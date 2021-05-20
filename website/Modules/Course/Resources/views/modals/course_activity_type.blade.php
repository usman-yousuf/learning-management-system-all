@php
    $online_course_icon_url = asset('assets/images/course_popup_online_icon.svg');
    $video_course_icon_url = asset('assets/images/course_popup_video_icon.svg');
@endphp
<div class="modal" id="activity_type_modal-d">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-model-content-s d-flex" style="margin-top: 100px;">

            <!-- Modal Header -->
            <div class="modal-header custom-header-s align-self-center">
                <h5 class="modal-title custom-title-s font-weight-bold">Activity Type</h5>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>
            <div>
                <a data-dismiss="modal"><img class=" custom-close-s float-right" src="assets/group@2x.svg" alt=""></a>
            </div>
            <!-- Modal body -->

            <div class="modal-body d-flex justify-content-center justify-content-around" style="width:100%; padding: 60px;">
                <div id="online_course-d" class="card activity_card-s activity_card-d mr-md-3" style=" border: none; width: 40%;">
                    <div id="" class="card-body custom-card-body-s text-center shadow bg-body rounded">
                        <img id="online_course_img-d" src="{{ $online_course_icon_url }}" alt="online-course" class="filter-green">
                        <h6 class="custom-text-s mt-4">Online Course</h6>
                    </div>
                </div>

                <div id="course-d" class="card activity_card-s activity_card-d ml-md-3" style=" border: none; width: 40%;">
                    <div id="video_course-d" class=" card-body custom-card-body-s text-center shadow bg-body rounded ">
                        <img id="video_course_img-d" src="{{ $video_course_icon_url }}" alt="video-course">
                        <h6 class="custom-text-s mt-4">Video Course</h6>
                    </div>
                </div>
            </div>


            <!-- Modal footer -->
            <div class="modal-footer align-self-center custom-footer-s mb-5">
                <input type="hidden" name="course_nature" id="hdn_course_nature_selection-d" value="" />
                <button type="button " class="custom-button-s border border-white btn_activity_modal_next-d">
                    Next
                </button>
            </div>

        </div>
    </div>
</div>

@push('header-scripts')
    <script>
        let activity_modal_online_course_icon_url = "{{ $online_course_icon_url }}";
        let activity_modal_video_course_icon_url = "{{ $video_course_icon_url }}";
    </script>
@endpush
