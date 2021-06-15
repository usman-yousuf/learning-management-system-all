
@php
    $showForm = (isset($page) && ('dashboard' == $page || 'edit' == $page));
    $dataColClass = ($showForm)? "col-lg-8 col-12" : "col-12";
    $dataCol2Class = ($showForm)? "col-lg-4 col-12" : "d-none";

    $formId = ($showForm)? "course_handout_content_form-d" : "";
    $handouts = (isset($handouts) && !empty($handouts))? $handouts : [];
@endphp

<div class="row flex-column-reverse flex-md-row">
    <div class="{{ $dataColClass }}">
        <div class="row course_handout_container-d">
            @forelse($handouts as $item)
                <div class="col-sm-6 col-12 @if(isset($page) &&('details' == $page)) col-md-4 @endif course_handout_single_container-d uuid_{{ $item->uuid ?? '' }}">
                    <div class="card shadow mt-4 customs_card-s">
                        <img class="card-img-top custom-card1-img-s" style="height: 190px;" src="{{ getFileurl(null, null, 'office') }}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title custom_handout_title-s">
                                <a href="{{ $item->url_link ?? 'javascript:void(0)' }}" class='no_link-s course_handout_link-d'>
                                    <span class='handout_title-d'>{{ $item->title ?? 'Handout Title' }}</span>
                                </a>
                            </h5>
                            <div class="float-right">
                                <input type="hidden" class="handout_uuid-d" value='{{ $item->uuid ?? '' }}'/>
                                <span>
                                    <a href="javascript:void(0)" class='delete_handout_content-d'>
                                        <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-handout-content" />
                                    </a>
                                    <a href="javascript:void(0)" class='edit_handout_content-d'>
                                        <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-handout-content" />
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
    <div class="{{ $dataCol2Class }}">
        <div class="card shadow border-0 mt-4">
            <div class="card-body">
                <!-- Form Start -->
                <form action="{{ route('course.handout') }}" method="post" id="{{ $formId }}" class="" novalidate>
                    <!-- Input Fields Start -->
                    <div class="row ">
                        <div class="col-12">
                            <label class="custom-label-s" for="handout">Title of Handout</label>
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg custom-input-s" name="handout_title" id="handout-d" placeholder="Layout Designing">
                            </div>
                        </div>
                    </div>
                    <div class="row  ">
                        <div class="col-12 mt-3 ">
                            <label class="custom-label-s" for="link">URL LINK</label>
                            <div class="mb-3">
                                <input type="text " class="form-control form-control-lg custom-input-s" name="url_link" id="link-d" placeholder="www.pdf.com">
                            </div>
                        </div>
                    </div>
                    <!-- Input Fields End -->

                    <!-- Card Buttons -->
                    <div class="d-flex ml-md-5 ml-lg-0 mt-5">
                        <input type='hidden' name='handout_content_uuid' id='hdn_handout_content_uuid-d' value='{{ $handout->uuid ?? '' }}' />
                        <input type="hidden" class="course_uuid-d" name="course_uuid" value='{{ $course->uuid ?? '' }}' />

                        <button type="submit" class="handout-card-button1-s px-4 px-md-5 px-lg-4 px-xl-5 mr-xl-5 border border-white">Save</button>
                        <button type="reset" class="handout-card-button2-s px-4 px-md-5 px-lg-4 px-xl-5 ml-lg-3 ml-md-5 ml-xl-0 border border-white reset_form-d">Reset</button>
                    </div>
                    <!-- Card Buttons End -->
                </form>

            </div>
        </div>
    </div>
</div>

<div class="cloneables_container-d" style='display:none;'>
    <div class="col-md-4 col-sm-6 col-12 course_handout_single_container-d" id="cloneable_course_handout_content-d">
        <div class="card shadow mt-4 customs_card-s">
            <img class="card-img-top custom-card1-img-s" style="height: 190px;" src="{{ getFileurl(null, null, 'office') }}" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title custom_handout_title-s">
                    <a href="{{ $item->url_link ?? 'javascript:void(0)' }}" class='no_link-s course_handout_link-d'>
                        <span class='handout_title-d'>{{ $item->title ?? 'Handout Title' }}</span>
                    </a>
                </h5>
                <div class="float-right">
                    <input type="hidden" class="handout_uuid-d" value='{{ $item->uuid ?? '' }}'/>
                    <span>
                        <a href="javascript:void(0)" class='delete_handout_content-d'>
                            <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-handout-content" />
                        </a>
                        <a href="javascript:void(0)" class='edit_handout_content-d'>
                            <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-handout-content" />
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
