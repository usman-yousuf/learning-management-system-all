@php
    $model_type == (isset($model_type) && ('' != $model_type))? $model_type : 'Profile';
@endphp


<div class="modal" id="waiting_popup-d">
    <div class="modal-dialog modal-xl">
        <div class="modal-content d-flex">
            <!-- Modal Header -->
            <div class="modal-header border-0 align-self-center  pt-5 mt-5 ">
                <a href="javascript:void(0)">
                    <img class="img_w_200px-s" src="{{ asset('assets/images/sand_clock_icon.svg') }}" alt="review-image" />
                </a>
            </div>
            <!-- Modal Header End -->

            <!-- Modal body -->
            <div class="modal-body  text-center">
                <span class="text_size_20px-s">
                    Be patient while Adminn approves your <strong class='model_nature-d'>{{ $model_type }}</strong>
                </span>
            </div>
            <!-- Modal body End -->


            <!-- Modal footer -->
            <div class="modal-footer align-self-center  border-0 mb-3 mt-5 mt-sm-3 pt-5">
                <a href="{{ route('signout') }}" class="btn bg_success-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5 wait_modal_redirect_url-d">
                    DONE
                </a>
            </div>
            <!-- Modal footer End -->
        </div>
    </div>
</div>
