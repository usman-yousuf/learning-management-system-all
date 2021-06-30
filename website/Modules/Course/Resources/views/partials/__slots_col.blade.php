@php
    $is_activity_listing = isset($is_activity_listing)? $is_activity_listing : false;
@endphp

    <div class="@if($is_activity_listing) col-sm-6 @else col-12 @endif pl-0 pr-2 mb-4 border rounded ">
        <div class="row">
            <div class="pt-1 col-1">
                <div class="for_display_radio_button-s w_20px-s h_20px-s bg_light_dark-s br_19px-s slot_option-d slot_option_{{ $item->uuid ?? '' }} " data-slot_option_uuid="{{ $item->uuid ?? '' }}"></div>
            </div>
        </div>
        <div class="row mt-3 text-center">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xl-3 col-6">
                <div>
                    <span class="custom_slots_title-s">Start Date</span>
                </div>
                <div class="mt-3">
                    <span class="slot_start_date-d" data-slot_start_date="{{ date('Y-m-d', strtotime($item->slot_start)) }}">{{ date('d M', strtotime($item->slot_start)) }}</span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                <div>
                    <span class="custom_slots_title-s">Start Time</span>
                </div>
                <div class=" mt-3">
                    <span class="slot_start_time-d" data-slot_start_time="{{ date('H:i', strtotime($item->slot_start)) }}">{{ date('h:i A', strtotime($item->slot_start)) }}</span>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6  ">
                <div>
                    <span class="custom_slots_title-s">End Date</span>
                </div>
                <div class="mt-3">
                    <span class="slot_end_date-d" data-slot_end_date="{{ date('Y-m-d', strtotime($item->slot_end)) }}">{{ date('d M', strtotime($item->slot_end)) }}</span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                <div>
                    <span class="custom_slots_title-s">End Time</span>
                </div>
                <div class="mt-3">
                    <span class="slot_end_time-d" data-slot_end_time="{{ date('H:i', strtotime($item->slot_end)) }}">{{ date('h:i A', strtotime($item->slot_end)) }}</span>
                </div>
            </div>
        </div>
        <div class="row mb-3 pt-3">
            <div class="col d-flex @if(!$is_activity_listing) ml-sm-3 ml-lg-4 ml-xl-5 ml-3 @endif">
                <div class="mr-1 slot_day-d @if(strpos($item->day_nums, '6') !== false) custom_day_sign_active-s @else custom_day_sign-s @endif" data-day_num="6"><span>S</span></div>
                <div class="mr-1 slot_day-d @if(strpos($item->day_nums, '0') !== false) custom_day_sign_active-s @else custom_day_sign-s @endif" data-day_num="0"><span>M</span></div>
                <div class="mr-1 slot_day-d @if(strpos($item->day_nums, '1') !== false) custom_day_sign_active-s @else custom_day_sign-s @endif" data-day_num="1"><span>T</span></div>
                <div class="mr-1 slot_day-d @if(strpos($item->day_nums, '2') !== false) custom_day_sign_active-s @else custom_day_sign-s @endif" data-day_num="2"><span>W</span></div>
                <div class="mr-1 slot_day-d @if(strpos($item->day_nums, '3') !== false) custom_day_sign_active-s @else custom_day_sign-s @endif" data-day_num="3"><span>T</span></div>
                <div class="mr-1 slot_day-d @if(strpos($item->day_nums, '4') !== false) custom_day_sign_active-s @else custom_day_sign-s @endif" data-day_num="4"><span>F</span></div>
                <div class="mr-1 slot_day-d @if(strpos($item->day_nums, '5') !== false) custom_day_sign_active-s @else custom_day_sign-s @endif" data-day_num="5"><span>S</span></div>
            </div>

            @if(isset($item) && (null != $item->last_enrolment) )
                <div class="col d-flex @if(!$is_activity_listing) ml-sm-3 ml-lg-4 ml-xl-5 ml-3 @else px-0 @endif">
                    <div class="mr-1 w-100">
                        <img class='img_25_x_25-s rounded-circle' src='{{ getFileUrl($item->last_enrolment->student->profile_image, null, 'profile') }}' alt='{{ $item->last_enrolment->student->first_name . ' Profile' }}' />
                        Enrolled at: <strong class=''>{{ date('d M Y', strtotime($item->last_enrolment->created_at)) }}</strong>
                    </div>
                </div>
            @endif

            @if(!$is_activity_listing)
                <div class="float-right pr-sm-3 pr-lg-4 pr-xl-5 pr-3">
                    @if((\Auth::user()->profile_type != 'student') && (\Auth::user()->profile_type != 'parent') )
                        <input type="hidden" class="course_slot_uuid-d" value='{{ $item->uuid ?? '' }}' />
                        <input type="hidden" class="listing_course_day_nums-d" value='{{ $item->day_nums ?? '' }}'/>
                        <a href="javascript:void(0)" class='delete_slot-d'>
                            <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-slot" />
                        </a>
                        <a href="javascript:void(0)" class='edit_slot-d'>
                            <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-slot" />
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
