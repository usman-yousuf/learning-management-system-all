
@php
    $showForm = (isset($page) && ('dashboard' == $page || 'edit' == $page));
    $dataColClass = ($showForm)? "col-xl-7 col-lg-7 col-12 ml-lg-3 mt-4" : "col-12";
    $dataCol2Class = ($showForm)? "col-lg-4 ml-lg-4 ml-xl-5" : "d-none";

// dd($slots);
    $slots = isset($slots)? $slots : [];

@endphp

<div class="row flex-column-reverse flex-md-row">
    <div class="{{ $dataColClass }} h-100 slots_container-d">
        @forelse ($slots as $item)
        {{--  Single Slot Container - START  --}}
        <div class="row single_slot_container-d">
            <div class="col-12 pl-0 pr-2 mb-4 border rounded ">
                <div class="row mt-3 text-center">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xl-3 col-6">
                        <div>
                            <span class="custom_slots_title-s">Start Date</span>
                        </div>
                        <div class="mt-3">
                            <span class="slot_start_date-d">01 Feb</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                        <div>
                            <span class="custom_slots_title-s">Start Time</span>
                        </div>
                        <div class=" mt-3">
                            <span class="slot_start_time-d">01:00 am</span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6  ">
                        <div>
                            <span class="custom_slots_title-s">End Date</span>
                        </div>
                        <div class="mt-3">
                            <span class="slot_end_date-d">01 Feb</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                        <div>
                            <span class="custom_slots_title-s">End Time</span>
                        </div>
                        <div class="mt-3">
                            <span class="slot_end_time-d">03:00 am</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 pt-3">
                    <div class="col d-flex ml-sm-3 ml-lg-4 ml-xl-5 ml-3">
                        <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="6"><span>S</span></div>
                        <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="0"><span>M</span></div>
                        <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="1"><span>T</span></div>
                        <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="2"><span>W</span></div>
                        <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="3"><span>T</span></div>
                        <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="4"><span>F</span></div>
                        <div class="mr-1 custom-day-sign-s slot_day-d" data-day_num="5"><span>S</span></div>
                    </div>
                    <div class="float-right pr-sm-3 pr-lg-4 pr-xl-5 pr-3">
                        <input type="hidden" class="course_slot_uuid-d" value='{{ $item->uuid ?? '' }}'/>
                        <input type="hidden" class="listing_course_day_nums-d" value='{{ $item->day_nums ?? '' }}'/>
                        <a href="javascript:void(0)" class='delete_slot-d'>
                            <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-slot" />
                        </a>
                        <a href="javascript:void(0)" class='edit_slot-d'>
                            <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-slot" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{--  Single Slot Container - END  --}}
        @empty

        @endforelse
    </div>


    <div class="{{ $dataCol2Class }}">
        <!-- Form Start -->
        <form action="{{ route('course.slot') }}" method="post" id="course_slots_form-d" class="" novalidate>
            <div class="card shadow border-0 row mt-4">
                <div class="card-body">
                    <!-- Input Fields Start -->
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6 ">
                            <label class="custom-label-s" for="start_date">Start Date</label>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control form-control-lg custom-slots-input-s" name="start_date" id="course_slot_start_date-d" placeholder="Start Date">

                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6 ">
                            <label class="custom-label-s " for="end_date">End Date</label>
                            <div class="input-group mb-3 ">
                                <input type="date" class="form-control form-control-lg custom-slots-input-s" name="end_date" id="course_slot_end_date-d" placeholder="End data">
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6 ">
                            <label class="custom-label-s" for="start_time">Start Time</label>
                            <div class="input-group mb-3">
                                <input type="time" class="form-control form-control-lg custom-slots-input-s" name="start_time" id="course_slot_start_time-d" placeholder="Start Time">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
                            <label class="custom-label-s" for="end_time">End Time</label>
                            <div class="input-group mb-3 ">
                                <input type="time" class="form-control form-control-lg custom-slots-input-s" name="end_time" id="course_slot_end_time-d" placeholder="End Time">
                            </div>
                        </div>
                    </div>
                    <!-- Input Fields End -->
                    <div class="row mt-4">
                        <div class="col d-flex ml-lg-2 ml-3 mb-3">
                            <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom_day_sign-s slot_day-d" data-day_num="6"><span>S</span></div>
                            <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom_day_sign-s slot_day-d" data-day_num="0"><span>M</span></div>
                            <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom_day_sign-s slot_day-d" data-day_num="1"><span>T</span></div>
                            <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom_day_sign-s slot_day-d" data-day_num="2"><span>W</span></div>
                            <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom_day_sign-s slot_day-d" data-day_num="3"><span>T</span></div>
                            <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom_day_sign-s slot_day-d" data-day_num="4"><span>F</span></div>
                            <div class="mr-1 mr-xl-4 mr-lg-1 mr-md-3 custom-day-sign-s slot_day-d" data-day_num="5"><span>S</span></div>
                        </div>
                        <input type='hidden' name='day_nums' id='course_slot_selected_days-d' />
                    </div>

                    <!-- Card Buttons -->
                    <div class="text-center mt-5 px-1">
                        <input type='hidden' name='course_slot_uuid' id='course_slot_uuid-d' />
                        <button type="submit" class="slots-card-button1-s pl-4 pr-4 pl-lg-4 pr-lg-4 mr-xl-5 border border-white">Save</button>
                        <input type="reset" class="handout-card-button2-s px-4 px-md-5 px-lg-4 px-xl-5 ml-lg-3 ml-md-5 ml-xl-0 border border-white reset_form-d" value="Reset" />
                    </div>
                    <!-- Card Buttons End -->
                </div>
            </div>
        </form>
        <!-- Form End     -->
    </div>
</div>

<div class="cloneables_container-d" style='display:none;'>
    <div class="row single_slot_container-d" id='cloneable_coourse_slot_container-d'>
        <div class="col-12 pl-0 pr-2 mb-4 border rounded ">
            <div class="row mt-3 text-center">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xl-3 col-6">
                    <div>
                        <span class="custom_slots_title-s">Start Date</span>
                    </div>
                    <div class="mt-3">
                        <span class="slot_start_date-d">01 Feb</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                    <div>
                        <span class="custom_slots_title-s">Start Time</span>
                    </div>
                    <div class=" mt-3">
                        <span class="slot_start_time-d">01:00 am</span>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6  ">
                    <div>
                        <span class="custom_slots_title-s">End Date</span>
                    </div>
                    <div class="mt-3">
                        <span class="slot_end_date-d">01 Feb</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3 col-6 mt-3 mt-lg-0 mt-md-0 ">
                    <div>
                        <span class="custom_slots_title-s">End Time</span>
                    </div>
                    <div class="mt-3">
                        <span class="slot_end_time-d">03:00 am</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3 pt-3">
                <div class="col d-flex ml-sm-3 ml-lg-4 ml-xl-5 ml-3">
                    <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="6"><span>S</span></div>
                    <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="0"><span>M</span></div>
                    <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="1"><span>T</span></div>
                    <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="2"><span>W</span></div>
                    <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="3"><span>T</span></div>
                    <div class="mr-1 custom_day_sign-s slot_day-d" data-day_num="4"><span>F</span></div>
                    <div class="mr-1 custom-day-sign-s slot_day-d" data-day_num="5"><span>S</span></div>
                </div>
                <div class="float-right pr-sm-3 pr-lg-4 pr-xl-5 pr-3">
                    <input type="hidden" class="course_slot_uuid-d" value='{{ $item->uuid ?? '' }}'/>
                    <input type="hidden" class="listing_course_day_nums-d" value='{{ $item->day_nums ?? '' }}'/>
                    <a href="javascript:void(0)" class='delete_slot-d'>
                        <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-slot" />
                    </a>
                    <a href="javascript:void(0)" class='edit_slot-d'>
                        <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-slot" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
