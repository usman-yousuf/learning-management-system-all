      <!--quiz conformation modal-->
      <div class="modal" id="confirmation_modal-d">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content d-flex">
                <!-- Modal Header -->
                <div class="modal-header border-0 align-self-center pt-5 mt-5">
                    <a href="javascript:void(0)">
                        <img class="img_h_300px w-100" src="{{ asset('assets/images/student_login_img.svg') }}" alt="congratulation-image" />
                    </a>
                </div>
                <!-- Modal Header End -->

                <!-- Modal body -->
                <div class="modal-body  text-center">
                    <span class="fs_30px-s  ">
                        Are you sure to start quiz?
                    </span>
                </div>
                <!-- Modal body End -->
                {{-- @php
                    // $duration = date('M d, Y H:i:s', strtotime("+{$item->duration_mins} minutes"));
                    $duration = date('M d, Y H:i:s', strtotime("+{$item->duration_mins} minutes"));
                    echo $duration;
                @endphp --}}

                <!-- Modal footer -->
                <div class="modal-footer align-self-center border-0 pb-5">
                    <a href="javascript:void(0)" class="btn bg_success-s br_21px-s text-white px-5 mr-xl-5 mr-lg-5 mr-md-5 mr-2" id="start_test_quiz-d">Yes</a>
                    <a href="javascript:void(0)" class="btn br_21px-s courses_delete_btn-s px-5 ml-2 ml-xl-5 ml-lg-5 ml-md-5" data-dismiss="modal">No</a>
                    {{-- <input type="hidden" name="" id="duration" value="{{ $duration }}">  --}}
                </div>
                <!-- Modal footer End -->
            </div>
        </div>
    </div>
<!--quiz conformation modal end-->
