@php
    $carousal_title = (isset($section) && ('' != $section))? $section : "section";
    $carousal_title = strtolower(str_replace('-', '_', $carousal_title)) . '_';

    $given_courses = $courses;
@endphp
    @if(isset($section) && ('courses_by_nature' == $section))
        @if($given_courses->total_count)
            @foreach ($given_courses->courses as $item)
                <div class="col-xl-4 col-lg-6 col-md-12  col-12 mb-3">
                    <div class="card carousal_card-s">
                        @if(!$item->is_course_free)
                            <div class="price_tag_container-s">
                                <img src="{{ asset('assets/images/price_tag-01.svg') }}" class="w_100px-s" alt="price tag">
                                <div class="centered text-white text-center ml-1 price_tag_text-s">{{ getCoursePriceWithUnit($item) }}</div>
                            </div>
                        @endif
                        <div class="carousal_item_image-s">
                            <img class="w-100" src="{{ getFileUrl($item->course_image, null, 'course') }}" alt="course-image" />
                            @if (null == $item->approver_id)
                                <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                            @endif
                        </div>
                        <!-- ------card content---- -->
                        <div class="">
                            <div class="d-flex mt-3 card_design_text-s">
                                <div class="container">
                                    {{--  title and category - START  --}}
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-12">
                                                    @php
                                                        if(\Auth::check()){
                                                            $view_url = route('course.view', ['uuid' => $item->uuid]);
                                                            if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') ){
                                                                if($item->my_enrollment_count < 1){
                                                                    $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                }
                                                            }
                                                        }
                                                        else{
                                                            $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                        }
                                                    @endphp
                                                    <h6><a href="{{ $view_url }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 15 ) }}</a></h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 10 ) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col text-right">
                                            @if(!isset($section) || ($section != 'student-enrollments-listing'))
                                                <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                            @endif
                                        </div>
                                    </div>
                                    {{--  title and category - END  --}}

                                    <div class="row pt-3 pb-3">
                                        <div class="col-6 mb-3x">
                                            <div class="row">
                                                <div class="col-12">
                                                    <img src="{{ getIconUrl($item->nature, 'course_nature') }}" class="" alt="">
                                                    <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                    <br />
                                                    <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                    <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                    <br />
                                                    <img src="{{ getIconUrl('dollar_icon', 'is_course_free') }}" width="18" class="mr-xl-1 mr-lg-0 mr-md-0 mr-1" alt="" />
                                                    <span class="mx-xl-2 mx-lg-0 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="row">
                                                <div class="col-12">
                                                    <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                    <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                </div>

                                                <div class="col-12 pr-xl-3">
                                                    <h6 class=" mb-0">to</h6>
                                                </div>

                                                <div class="col-12">
                                                    <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                    <span class="ml-2">{{ date('d M Y', strtotime($item->end_date ?? 'now')) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--  footer content of card -START  --}}
                                    <div class="row pb-3">
                                        @if(isset($section) && ($section == 'student-enrollments-listing'))
                                            <div class="col text-center">
                                                <a href="{{ route('course.preview', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a>
                                            </div>
                                        @elseif(isset($section) && ($section == 'student-side-course-listing'))
                                            <div class="col">
                                                <div class="d-flex justify-content-between text-align-center  my-3">
                                                    <a href="javascript:void(0)"class="btn btn_orange-s w-50 br_21px-s mr-3 setup_enroll_student_modal-d" data-target_url="{{ route('course.get', ['uuid' => $item->uuid]) }}" data-course_uuid="{{ $item->uuid }}">Enroll</a>
                                                    <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    {{--  footer content of card - END  --}}
                                </div>
                            </div>
                        </div>
                        <!-- ------card content End---- -->
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center mt-4 mb-4">
                <p class="w-100 text-center">
                    <strong>
                        No Course(s) Found
                    </strong>
                </p>
            </div>
        @endif
    @else
        @if($given_courses->total_count)
            <div class="row">
                @php
                    // $given_courses->courses = (object)$given_courses->courses;
                    // dd($given_courses);
                @endphp
                <!-- For Extra LARGE SCREEN - START -->
                <div class="col-12 d-none d-xl-block">
                    <div id="{{ $carousal_title }}carouselExampleIndicatorsLarge" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach (array_chunk($given_courses->courses, 3) as $three)
                                @php
                                    // dd($three);
                                @endphp
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($three as $item)
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-4 mb-3">
                                                <div class="card carousal_card-s">
                                                    @if(!$item->is_course_free)
                                                        <div class="price_tag_container-s">
                                                            <img src="{{ asset('assets/images/price_tag-01.svg') }}" class="w_100px-s" alt="price tag">
                                                            <div class="centered text-white text-center ml-1 price_tag_text-s">{{ getCoursePriceWithUnit($item) }}</div>
                                                        </div>
                                                    @endif
                                                    <div class="carousal_item_image-s">
                                                        <img class="w-100" src="{{ getFileUrl($item->course_image, null, 'course') }}" alt="course-image" />
                                                        @if (null == $item->approver_id)
                                                            <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                        @endif
                                                    </div>
                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                @php
                                                                                    if(\Auth::check()){
                                                                                        $view_url = route('course.view', ['uuid' => $item->uuid]);
                                                                                        if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') ){
                                                                                            if($item->my_enrollment_count < 1){
                                                                                                $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    else{
                                                                                        $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                    }
                                                                                @endphp
                                                                                <h6><a href="{{ $view_url }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 20) }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 20 ) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        @if(!isset($section) || ($section != 'student-enrollments-listing'))
                                                                            <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ getIconUrl($item->nature, 'course_nature') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ getIconUrl('dollar_icon', 'is_course_free') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="" />
                                                                                <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3">
                                                                                <h6 class=" mb-0">to</h6>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->end_date ?? 'now')) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{--  footer content of card -START  --}}
                                                                <div class="row pb-3">
                                                                    @if(isset($section) && ($section == 'student-enrollments-listing'))
                                                                    {{-- {{ dd($item) }} --}}
                                                                        <div class="col text-center">
                                                                            @if ($item->enrolled_students)
                                                                                <a href="{{ route('course.preview', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a>
                                                                            @else
                                                                                <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                            @endif
                                                                            {{-- <a href="{{ route('course.view', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a> --}}
                                                                        </div>
                                                                    @elseif(isset($section) && ($section == 'student-side-course-listing'))
                                                                        {{-- {{ dd('ok') }} --}}
                                                                        <div class="col">
                                                                            <div class="d-flex justify-content-between text-align-center  my-3">
                                                                                <a href="javascript:void(0)"class="btn btn_orange-s w-50 br_21px-s mr-3 setup_enroll_student_modal-d" data-target_url="{{ route('course.get', ['uuid' => $item->uuid]) }}" data-course_uuid="{{ $item->uuid }}">Enroll</a>
                                                                                <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                {{--  footer content of card - END  --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ------card content End---- -->
                                                </div>
                                            </div>
                                            <!-- carousal item End -->
                                        @endforeach
                                    </div>
                                </div>
                                {{--  show 3 item for large screen - END  --}}
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- For Extra LARGE SCREEN - END -->

                <!-- For LARGE SCREEN - START -->
                <div class="col-12 d-none d-xl-none d-lg-block">
                    <div id="{{ $carousal_title }}carouselExampleIndicatorsLarge" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach (array_chunk($given_courses->courses, 2) as $two)
                                @php
                                    // dd($two);
                                @endphp
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($two as $item)
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-6 mb-3">
                                                <div class="card carousal_card-s">
                                                    @if(!$item->is_course_free)
                                                        <div class="price_tag_container-s">
                                                            <img src="{{ asset('assets/images/price_tag-01.svg') }}" class="w_100px-s" alt="price tag">
                                                            <div class="centered text-white text-center ml-1 price_tag_text-s">{{ getCoursePriceWithUnit($item) }}</div>
                                                        </div>
                                                    @endif
                                                    <div class="carousal_item_image-s">
                                                        <img class="w-100" src="{{ getFileUrl($item->course_image, null, 'course') }}" alt="course-image" />
                                                        @if (null == $item->approver_id)
                                                            <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                        @endif
                                                    </div>
                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                @php
                                                                                    if(\Auth::check()){
                                                                                        $view_url = route('course.view', ['uuid' => $item->uuid]);
                                                                                        if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') ){
                                                                                            if($item->my_enrollment_count < 1){
                                                                                                $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    else{
                                                                                        $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                    }
                                                                                @endphp
                                                                                <h6><a href="{{ $view_url }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 20) }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 20 ) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        @if(!isset($section) || ($section != 'student-enrollments-listing'))
                                                                            <a href="javascript:void(0)" class="btn {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ getIconUrl($item->nature, 'course_nature') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ getIconUrl('dollar_icon', 'is_course_free') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="" />
                                                                                <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3">
                                                                                <h6 class=" mb-0">to</h6>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->end_date ?? 'now')) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{--  footer content of card -START  --}}
                                                                <div class="row pb-3">
                                                                    @if(isset($section) && ($section == 'student-enrollments-listing'))
                                                                    {{-- {{ dd($item) }} --}}
                                                                        <div class="col text-center">
                                                                            @if ($item->enrolled_students)
                                                                                <a href="{{ route('course.preview', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a>
                                                                            @else
                                                                                <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                            @endif
                                                                            {{-- <a href="{{ route('course.view', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a> --}}
                                                                        </div>
                                                                    @elseif(isset($section) && ($section == 'student-side-course-listing'))
                                                                        {{-- {{ dd('ok') }} --}}
                                                                        <div class="col">
                                                                            <div class="d-flex justify-content-between text-align-center  my-3">
                                                                                <a href="javascript:void(0)"class="btn btn_orange-s w-50 br_21px-s mr-3 setup_enroll_student_modal-d" data-target_url="{{ route('course.get', ['uuid' => $item->uuid]) }}" data-course_uuid="{{ $item->uuid }}">Enroll</a>
                                                                                <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                {{--  footer content of card - END  --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ------card content End---- -->
                                                </div>
                                            </div>
                                            <!-- carousal item End -->
                                        @endforeach
                                    </div>
                                </div>
                                {{--  show 2 item for large screen - END  --}}
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- For LARGE SCREEN - END -->

                <!-- FOR MEDIUM SCREEN - START -->
                <div class="col-12 d-none d-sm-block d-lg-none">
                    <div id="{{ $carousal_title }}carouselExampleIndicatorsMedium" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach (array_chunk($given_courses->courses, 2) as $two)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        @foreach ($two as $item)
                                            <!-- carousal item - show 3 at a time -->
                                            <div class="col-md-6 mb-3">
                                                <div class="card carousal_card-s">
                                                    @if(!$item->is_course_free)
                                                        <div class="price_tag_container-s">
                                                            <img src="{{ asset('assets/images/price_tag.svg') }}" class="w_100px-s" alt="price tag" />
                                                            <div class="centered text-white text-center ml-1 price_tag_text-s">{{ getCoursePriceWithUnit($item) }}</div>
                                                        </div>
                                                    @endif
                                                    <div class="carousal_item_image-s">
                                                        <img class="w-100" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}" />
                                                        @if (null == $item->approver_id)
                                                            <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                        @endif
                                                    </div>
                                                    <!-- ------card content---- -->
                                                    <div class="">
                                                        <div class="d-flex mt-3 card_design_text-s">
                                                            <div class="container">
                                                                {{--  title and category - START  --}}
                                                                <div class="row">
                                                                    <div class="col-7">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                @php
                                                                                    if(\Auth::check()){
                                                                                        $view_url = route('course.view', ['uuid' => $item->uuid]);
                                                                                        if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') ){
                                                                                            if($item->my_enrollment_count < 1){
                                                                                                $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    else{
                                                                                        $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                    }
                                                                                @endphp
                                                                                <h6><a href="{{ $view_url }}" class='no_link-s hover_effect-s' title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 12) }}</a></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 12 ) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        @if(!isset($section) || ($section != 'student-enrollments-listing'))
                                                                            <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                {{--  title and category - END  --}}

                                                                <div class="row pt-3 pb-3">
                                                                    <div class="col-6 mb-3x">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ getIconUrl($item->nature, 'course_nature') }}" class="" alt="">
                                                                                <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                                <br />
                                                                                <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                                <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                                <br />
                                                                                <img src="{{ getIconUrl('dollar_icon', 'is_course_free') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="price tag" />
                                                                                <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 text-center text-lg-center text-xl-right">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                            </div>

                                                                            <div class="col-12 pr-xl-3">
                                                                                <h6 class=" mb-0">to</h6>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                                <span class="ml-2">{{ date('d M Y', strtotime($item->end_date ?? 'now')) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{--  footer content of card - START  --}}
                                                                <div class="row pb-3">
                                                                    @if(isset($section) && ($section == 'student-enrollments-listing'))
                                                                    <div class="col text-center">
                                                                        @if ($item->enrolled_students)
                                                                            <a href="{{ route('course.preview', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a>
                                                                        @else
                                                                            <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                        @endif
                                                                        {{-- <a href="{{ route('course.view', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a> --}}
                                                                    </div>
                                                                    @elseif(isset($section) && ($section == 'student-side-course-listing'))
                                                                    <div class="col">
                                                                        <div class="d-flex justify-content-between text-align-center  my-3">
                                                                            <a href="javascript:void(0)" class="btn btn_orange-s w-50 br_21px-s mr-3 setup_enroll_student_modal-d"  data-target_url="{{ route('course.get', ['uuid' => $item->uuid]) }}" data-course_uuid="{{ $item->uuid }}">Enroll</a>
                                                                            <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                {{--  footer content of card - END  --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- ------card content End---- -->
                                                </div>
                                            </div>
                                            <!-- carousal item End -->
                                        @endforeach
                                    </div>
                                </div>
                                {{--  show 3 item for large screen - END  --}}
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- FOR MEDIUM SCREEN - END -->

                <!-- FOR SMALL SCREEN - START -->
                <div class="col-12 d-block d-sm-none">
                    <div id="mobile_screenCarousal-d" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($given_courses->courses as $item)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <div class="row">
                                        <!-- carousal item - show 3 at a time -->
                                        <div class="col-md-12 mb-3">
                                            <div class="card carousal_card-s">
                                                @if(!$item->is_course_free)
                                                    <div class="price_tag_container-s">
                                                        <img src="{{ asset('assets/images/price_tag.svg') }}" class="w_100px-s" alt="price tag">
                                                        <div class="centered text-white text-center ml-1 price_tag_text-s">{{ getCoursePriceWithUnit($item) }}</div>
                                                    </div>
                                                @endif
                                                <div class="carousal_item_image-s">
                                                    <img class="w-100" alt="course-image" src="{{ getFileUrl($item->course_image, null, 'course') }}" />
                                                    @if (null == $item->approver_id)
                                                        <div class="text-center position-absolute py-1 under_review-label-s">Under Review</div>
                                                    @endif
                                                </div>
                                                <div class="">
                                                    <div class="d-flex mt-3 card_design_text-s">
                                                        <div class="container">
                                                            {{--  title and category - START  --}}
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            @php
                                                                                if(\Auth::check()){
                                                                                    $view_url = route('course.view', ['uuid' => $item->uuid]);
                                                                                    if((\Auth::user()->profile_type == 'student') || (\Auth::user()->profile_type == 'parent') ){
                                                                                        if($item->my_enrollment_count < 1){
                                                                                            $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                        }
                                                                                    }
                                                                                }
                                                                                else{
                                                                                    $view_url = route('course.preview', ['uuid' => $item->uuid]);
                                                                                }
                                                                            @endphp
                                                                            <h6><a href="{{ $view_url }}" class='no_link-s hover_effect-s'  title="{{ $item->title ?? '(not set)' }}" >{{ getTruncatedString($item->title ?? '(not set)', 12) }}</a></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span title="{{ ucwords($item->category->name ?? '(category not set)' ) }}">{{ getTruncatedString(ucwords($item->category->name ?? '(category not set)'), 12 ) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col text-right">
                                                                    @if(!isset($section) || ($section != 'student-enrollments-listing'))
                                                                        <a href="javascript:void(0)" class="btn btn px-lg-1 px-xl-3 {{ $item->is_course_free ? 'course_free_btn-s' : 'course_pay_btn-s' }}" disbaled="disbaled">{{ $item->is_course_free ? 'Free' : 'Paid' }}</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            {{--  title and category - END  --}}

                                                            <div class="row pt-3 pb-3">
                                                                <div class="col-6 mb-3x">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <img src="{{ getIconUrl($item->nature, 'course_nature') }}" class="" alt="">
                                                                            <span class="mx-2">{{ ucwords($item->nature) }}</span>

                                                                            <br />
                                                                            <img src="{{ asset('assets/images/enrollment_icon.svg') }}" class="" alt="">
                                                                            <span class="mx-2"><strong>{{ getPeopleCount($item->students_count ?? 10) }}</strong> Students</span>

                                                                            <br />
                                                                            <img src="{{ getIconUrl('dollar_icon', 'is_course_free') }}" width="18" class="mr-xl-1 mr-lg-1 mr-md-0 mr-1" alt="" />
                                                                            <span class="mx-xl-2 mx-lg-2 mx-md-0 mx-2">{{ getCoursePriceWithUnit($item) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 text-center text-lg-center text-xl-right">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                            <span class="ml-2">{{ date('d M Y', strtotime($item->start_date)) }}</span>
                                                                        </div>

                                                                        <div class="col-12 pr-xl-3">
                                                                            <h6 class=" mb-0">to</h6>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <img src="{{ asset('assets/images/calendar_course_icon.svg') }}" class="" alt="">
                                                                            <span class="ml-2">{{ date('d M Y', strtotime($item->end_date ?? 'now')) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{--  footer content of card - START  --}}
                                                            <div class="row pb-3">
                                                                @if(isset($section) && ($section == 'student-enrollments-listing'))
                                                                    <div class="col text-center">
                                                                        @if ($item->enrolled_students)
                                                                            <a href="{{ route('course.preview', ['uuid'=>$item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a>
                                                                        @else
                                                                            <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                        @endif
                                                                        {{-- <a href="{{ route('course.view', ['uuid' => $item->uuid]) }}" class='btn btn px-2 w-50 course_pay_btn-s'>View</a> --}}
                                                                    </div>
                                                                @elseif(isset($section) && ($section == 'student-side-course-listing'))
                                                                    <div class="col">
                                                                        <div class="d-flex justify-content-between text-align-center  my-3">
                                                                            <a href="javascript:void(0)" class="btn btn_orange-s w-50 br_21px-s mr-3 setup_enroll_student_modal-d"  data-target_url="{{ route('course.get', ['uuid' => $item->uuid]) }}" data-course_uuid="{{ $item->uuid ?? '' }}">Enroll</a>
                                                                            <a href="{{ $view_url }}" class='btn br_21px-s w-50  btn_purple-s ml-3'>Details</a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            {{--  footer content of card - END  --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ------card content End---- -->
                                            </div>
                                        </div>
                                        <!-- carousal item End -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- FOR SMALL SCREEN  - END -->
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center mt-4 mb-4">
                    <p class="w-100 text-center">
                        <strong>
                            No Course(s) Found
                        </strong>
                    </p>
                </div>
            </div>
        @endif
    @endif
