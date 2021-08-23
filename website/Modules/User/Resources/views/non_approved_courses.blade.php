@extends('teacher::layouts.teacher')
@section('page-title')
    Non Approved Courses
@endsection

@section('content')
    <div class="container-fluid px-lg-5 px-md-4 py-4">
        <div class="row mt-3">
            <div class="col-12">
                <!--Heading-->
                <h4><strong>Non Approved Courses</strong></h4>
            </div>
        </div>
        <div class="row mx-auto mt-4 non_approved_courses_container-d">
            @forelse ($models as $item)
                <div class="col-12 py-2 px-4 my-3 bg_white-s br_10px-s shadow py-3 single_course_container-d uuid_{{ $item->uuid ?? '' }}" data-uuid="{{ $item->uuid ?? '' }}">
                    <div class="row">
                        <div class="col-lg-6 col-md-8 col-6">
                            <h4 class="title-d text-break text-wrap"> <!--Course name-->
                                <strong>{{ getTruncatedString(ucwords($item->title ?? ''), 35) }}</strong>
                            </h4>
                            <h5 class="fg-success-s"> <!--course category-->
                                {{ getTruncatedString(ucwords($item->category->name), 25) }}
                            </h5>
                        </div>
                        <div class="col-lg-6 col-md-4 col-6 text-right align-self-center">
                            <form action="{{ route('approveCourse', $item->uuid) }}" class="frm_approve_teacher_course-d" method="post">
                                @csrf
                                <input type="hidden" name="course_uuid" class="course_uuid-d" value="{{ $item->uuid }}">
                                <button type="submit" class="border-0 bg-white pr-0">
                                    <img src="{{ asset('assets/images/tick_mark.svg') }}" alt="Approve" />
                                </button>
                                <button class="border-0 bg-white pl-0 reject_course_approval-d" href="javascript:void(0)" type="button">
                                    <img src="{{ asset('assets/images/cancel.svg') }}" alt="X" />
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-12 pt-2">
                            <h6>
                                Cost:
                                <span>{{ $item->is_course_free ? 'Free' : getCoursePriceWithUnit($item) }}</span>
                            </h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12 pt-2">
                            <h6>
                                By:
                                <a href='javascript:void(0)' class='no_link-s text_primary-s'>{{ $item->teacher->full_name ?? '' }}</a>
                            </h6>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-wrap text-break p-5 m-5 no_items_container-d">
                    <strong>
                        No Courses Left to Approve / Reject
                    </strong>
                </div>
            @endforelse

        </div>
    </div>

    @include('user::modals.approve_course')
    @include('user::modals.courses_not_approved')
    <div class="cloneables_container-d" style='display:none;'>
        <div class="col-12 text-center text-wrap text-break p-5 m-5 no_items_container-d" id='cloneable_no_items_container-d'>
            <strong>
                No Courses Left to Approve / Reject
            </strong>
        </div>
    </div>
@endsection

@section('footer-scripts')

    <script type="text/javascript" src='{{ asset('assets/js/admin.js') }}'></script>

@endsection
