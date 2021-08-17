
@php
    $showForm = (isset($page) && ('dashboard' == $page || 'edit' == $page));
    $dataColClass = ($showForm)? "col-lg-8 col-12" : "col-12";
    $dataCol2Class = ($showForm)? "col-lg-4 col-12" : "d-none";

    $formId = ($showForm)? "course_handout_content_form-d" : "";
    $handouts = (isset($handouts) && !empty($handouts))? $handouts : [];
@endphp

@if('preview' == $page)

        @forelse ($handouts as $item)
            <div class="row single_handout_container-d {{ 'uuid_'.$item->uuid ?? ''}} align-items-center pb-4">
                <div class="col-11">
                    <div class="row align-items-center align-items-center">
                        <div class="col-1 handout_serial-d">{{ get_padded_number($loop->iteration) }}</div>
                        <div class="col-md-8 col-6 text-left text-wrap text-break handout_title-d">
                            <a href='{{ $item->url_link ?? 'javascript:void(0)' }}' @if(isset($item->url_link) && ('' != $item->url_link)) target="_blank" @endif class='no-link-s'>{{ $item->title ?? '' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{--  <div class="row box shadow no_item_container-d mr-3">
                <p class='w-100 text-center mt-5 mb-5'>
                    <strong>
                        No Record Found
                    </strong>
                </p>
            </div>  --}}
        @endforelse

@else
    <div class="row flex-column-reverse flex-md-row">
        <div class="{{ $dataColClass }}">
            <div class="row course_handout_container-d">
                @forelse($handouts as $item)
                    @if (('teacher' == \Auth::user()->profile_type) || ('admin' == \Auth::user()->profile_type))
                        <div class="col-sm-6 col-12 @if(isset($page) &&('details' == $page)) col-xl-3 col-lg-3 col-md-4 @endif course_handout_single_container-d uuid_{{ $item->uuid ?? '' }}">
                            <div class="card shadow mt-4 customs_card-s">
                                <img class="card-img-top custom-card1-img-s" style="height: 190px;" src="{{ getFileurl($item->url_link ?? null, null, 'office') }}" alt="handout Content">
                                <div class="card-body">
                                    <h5 class="card-title custom_handout_title-s">
                                        <a href="{{ $item->url_link ?? 'javascript:void(0)' }}" class='no_link-s course_handout_link-d'>
                                            <span class='handout_title-d text-wrap text-break' data-title="{{ $item->title ?? 'Handout Title' }}">{{ getTruncatedString($item->title ?? 'Handout Title', 20) }}</span>
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
                    @else
                        {{-- <div class="col-sm-6 col-12 @if(isset($page) &&('details' == $page)) col-md-4 @endif course_handout_single_container-d uuid_{{ $item->uuid ?? '' }}"> --}}
                            {{-- <div class="row"> --}}

                                <div class="col-xl-3 col-md-6 col-12 mb-4">
                                    <div class="card custom_card-s mt-4 br_19px-s">
                                        {{-- <img class="img-fluid mx-auto br_top_19px-s" alt="course-image" src="{{ asset('assets/images/card2.png') }}"> --}}
                                        <img class="img-fluid mx-auto br_top_19px-s" style="height: 190px;" src="{{ getFileurl($item->url_link, null, 'office') }}" alt="hanodut Image">
                                        <!-- ------card content---- -->
                                        <!-- <div class="d-flex mt-3 card_design_text-s"> -->
                                            <div class="container card_design_text-s">
                                                <div class="row pt-3">
                                                    <div class="col-12 d-flex text-wrap text-break" style="min-height: 15px;">
                                                        <h6><a href="javascript:void(0)" class='no_link-s' title="{{ $item->title ?? 'Handout Title' }}">{{ getTruncatedString($item->title ?? 'Handout Title') }}</a></h6>
                                                    </div>
                                                </div>
                                                <div class="row pb-2">
                                                    <div class="col-12">
                                                        {{-- <span>{{ dd($item) }}</span> --}}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="d-md-block d-lg-flex d-flex  justify-content-between mt-3 mb-3">
                                                            <a href="{{ $item->url_link }}" class="btn bg-primary-s text-white br_21px-s w-100" download="filename" target="_blank" >View</a>
                                                            &nbsp;&nbsp;
                                                            {{-- <a href="javascript:void(0)" class="btn  courses_delete_btn-s br_21px-s w-100 ">Download</a> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </div> -->
                                        <!-- ------card content End---- -->
                                    </div>
                                </div>
                            {{-- </div> --}}
                        {{-- </div> --}}
                    @endif
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
                                    <input type="text " class="form-control form-control-lg custom-input-s" name="url_link" id="link-d" placeholder="http://www.link-to-your-content.com">
                                </div>
                            </div>
                        </div>
                        <!-- Input Fields End -->

                        <!-- Card Buttons -->
                        <div class="d-flex ml-md-5 ml-lg-0 mt-5">
                            <input type='hidden' name='handout_content_uuid' id='hdn_handout_content_uuid-d' value='{{ $handout->uuid ?? '' }}' />
                            <input type="hidden" class="course_uuid-d" name="course_uuid" value='{{ $course->uuid ?? '' }}' />

                            <button type="submit" class="handout-card-button1-s px-4 px-md-5 px-lg-4 px-xl-5 mr-xl-3 border border-white">Save</button>
                            <button type="reset" class="handout-card-button2-s px-4 px-md-5 px-lg-4 px-xl-5 ml-lg-2 ml-md-3 ml-xl-0 border border-white reset_form-d">Reset</button>
                        </div>
                        <!-- Card Buttons End -->
                    </form>

                </div>
            </div>
        </div>

        <!--handout modal-->
        <div class="modal fade" id="view_document-d" tabindex="-1" role="document" aria-labelledby="view-head" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header d-block">
                        <div class="container-fluid">
                            <!-- modal head-->
                            <div class="row ">
                                <div class="col">
                                    <h4 class="modal-title pl-5 mt-3" id="view-head">Website Designing</h4>
                                </div>
                            </div>
                            <!--modal head end-->

                            <!--MODAL BODY-->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <ul>
                                            <li class="py-4">loreum Ipsum is simply dummy text of the printing and typesetting industry.loreum Ipsum has been the industry's standard dummy text every since th 1500s,when an unknown printer took a gallery of type and scrambled
                                                it to make a type specimen group.</li>
                                            <li class="py-4">loreum Ipsum is simply dummy text of the printing and typesetting industry.loreum Ipsum has been the industry's standard dummy text every since th 1500s,when an unknown printer took a gallery of type and scrambled
                                                it to make a type specimen group.</li>
                                            <li class="py-4">loreum Ipsum is simply dummy text of the printing and typesetting industry.loreum Ipsum has been the industry's standard dummy text every since th 1500s,when an unknown printer took a gallery of type and scrambled
                                                it to make a type specimen group.</li>
                                            <li class="py-4">loreum Ipsum is simply dummy text of the printing and typesetting industry.loreum Ipsum has been the industry's standard dummy text every since th 1500s,when an unknown printer took a gallery of type and scrambled
                                                it to make a type specimen group.</li>
                                            <li class="py-4">loreum Ipsum is simply dummy text of the printing and typesetting industry.loreum Ipsum has been the industry's standard dummy text every since th 1500s,when an unknown printer took a gallery of type and scrambled
                                                it to make a type specimen group.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--modal body end-->
                            <!-- Modal footer -->
                            <div class="modal-footer border-0 mb-3 mt-sm-3 mt-4 justify-content-center">
                                <a href="" class="btn add_course_btn-s w_315px-s" download>
                                    <img src="../assets/downlaod.svg" width="20" id="add_video-d" class="ml-2 mr-2" alt="">
                                    <span class="ml-2 mr-2 text-white">Downlaod pdf</span>
                                </a>
                            </div>
                            <!-- Modal footer End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--handout modal end-->
    </div>

    <div class="cloneables_container-d" style='display:none;'>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 course_handout_single_container-d" id="cloneable_course_handout_content-d">
            <div class="card shadow mt-4 customs_card-s">
                <img class="card-img-top custom-card1-img-s" style="height: 190px;" src="{{ getFileurl($item->url_link ?? null, null, 'office') }}" alt="hanodut content">
                <div class="card-body">
                    <h5 class="card-title custom_handout_title-s">
                        <a href="{{ $item->url_link ?? 'javascript:void(0)' }}" class='no_link-s course_handout_link-d'>
                            <span class='handout_title-d text-wrap text-break' title="{{ $item->title ?? 'Handout Title' }}">{{ getTruncatedString($item->title ?? 'Handout Title', 20) }}</span>
                        </a>
                    </h5>
                    <div class="float-right">
                        @if((\Auth::user()->profile_type != 'student') && (\Auth::user()->profile_type != 'parent') )
                            <input type="hidden" class="handout_uuid-d" value='{{ $item->uuid ?? '' }}'/>
                            <span>
                                <a href="javascript:void(0)" class='delete_handout_content-d'>
                                    <img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-handout-content" />
                                </a>
                                <a href="javascript:void(0)" class='edit_handout_content-d'>
                                    <img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-handout-content" />
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


