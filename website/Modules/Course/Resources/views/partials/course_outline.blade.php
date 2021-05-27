
@php
    $showForm = (isset($page) && ('dashboard' == $page || 'edit' == $page));
    $dataColClass = ($showForm)? "col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12" : "col-12";
    $dataCol2Class = ($showForm)? "col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12" : "hidden";
@endphp

<div class="row flex-md-row flex-sm-column-reverse">
    <div class="{{ $dataColClass }}">
        <div class="outlines_container-d">
            <div class="row box shadow no_item_container-d mr-3">
                <p class='w-100 text-center mt-5 mb-5'>
                    <strong>
                        No Record Found
                    </strong>
                </p>
            </div>
        </div>
    </div>
    <div class="{{ $dataCol2Class }}">
        <div class="container pl-0 pr-3">
            <div class="row">
                <form action="{{ route('course.outline') }}" id="course_outline_form-d" class="pt-4 shadow rounded" novalidate>
                    <div class="form-group d-inline-flex">
                        <div class="col-md-6">
                            <label class="text-muted font-weight-normal ml-3" for="duration_hrs-d">Duration Hrs</label>
                            <input type="number" class="form-control form-control-lg login_input-s" name="duration_hrs" id="duration_hrs-d" min="0" placeholder="Duration Hours" />
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted font-weight-normal ml-3" for="duration_mins-d">Duration Mins</label>
                            <input type="number" class="form-control form-control-lg login_input-s" name="duration_mins" id="duration_mins-d" min="0" max="59" placeholder="Duration Minutes" />
                        </div>
                    </div>
                    <!-- -----Title input field---- -->
                    <div class="col-12 form-group pt-2">
                        <label class="text-muted font-weight-normal ml-3" for="title">Title</label>
                        <input type="text" class="form-control form-control-lg login_input-s" id="outline_title-d" name="outline_title" placeholder="Layout Designing" required>
                    </div>
                    <!-- ----- Button------ -->
                    <div class="col-12 pb-5 pt-4 login_button-s">
                        <input type='hidden' name="course_outline_uuid" id='hdn_course_outline-d' value="{{ $course->outline->uuid ?? '' }}" />
                        <button type="submit" class="btn btn- pt-lg-3 pb-lg-3">SAVE</button>
                        {{-- <a href="sign_up.html" class="custom-card-button2-s shadow float-right pt-lg-3 pb-lg-3 text-center">Add</a> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="cloneables_container-d" style='display:none;'>
    <div class="row single_outline_container-d align-items-center pb-4" id='cloneable_outline-d'>
        <div class="col-10">
            <div class="row align-items-center align-items-center">
                <div class="col-2 outline_serial-d">01</div>
                <div class="col-7 text-wrap text-break outline_title-d">Make to gif file in Photoshop…………………………………………………</div>
                <div class="col-3 outline_duration-d">04:49 Hrs</div>
            </div>
        </div>
        <div class="col-2 px-0">
            <input type="hidden" class="course_outline_uuid-d" value='{{ $item->uuid ?? '' }}'/>
            <a href="javascript:void(0)" class='delete_outline-d'>
                <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-outline" />
            </a>
            <a href="javascript:void(0)" class='edit_outline-d'>
                <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-outline" />
            </a>
        </div>
    </div>
</div>