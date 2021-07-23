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
        <div class="row mx-auto mt-4">
            @forelse ($data as $teacher)
                <div class="row">
                        <!--teacher name-->
                        <h5><strong>Teacher Name </strong>{{ $teacher->first_name }}</h5>
                </div>
                @foreach ($teacher->teacher_course as $course)
                    {{-- {{ dd($course) }} --}}
                    @if (null == $course->approver_id)
                          <!--course list-->
                            <div class="col-12 py-2 px-4 my-3 bg_white-s br_10px-s shadow py-3">
                                <div class="row">
                                    <div class="col-lg-6 col-md-8 col-6">
                                        
                                        <!--Course name-->
                                        <h4 class=" title-d">
                                            <strong>{{ $course->title }}</strong>
                                        </h4>
                                        <!--course category-->
                                        <h5 class="fg-success-s">
                                            {{ $course->category->name }}
                                        </h5>
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-6 text-right align-self-center">
                                        <!--approved button-->
                                        <span>
                                            <form action="{{ route('approveCourse', $course->uuid) }}" id="frm_approve_teacher_course-d" method="post">
                                                @csrf
                                                <input type="hidden" name="course_uuid" value="{{ $course->uuid }}">
                                                <button type="submit">
                                                        <img src="{{ asset('assets/images/tick_mark.svg ') }}" alt="">
                                                </button>
                                            </form>
                                        </span>
                                        <!--not approved button -->
                                        <span><button href="" data-toggle="modal" data-target="#not_approved_teacher_course_modal"><img src="{{ asset('assets/images/cancel.svg') }} " alt=""></button></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <!--course cost-->
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-12 pt-2">
                                        <h6>
                                            cost:
                                            <span>{{ ($course->price_pkr > 0.00) ? $course->price_pkr : 'Free'  }}</span>
                                        </h6>  
                                    </div>
                                    <!--course duration-->
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-12 pt-2">
                                        <h6>
                                            Dutation:
                                            <span>{{ $course->total_duration  }} months</span>    
                                        </h6> 
                                    </div>
                                </div>
                            </div>
                        <!--course list end-->
                    @endif
                   
                @endforeach
                
            @empty
                
            @endforelse
           
        </div>
    </div>

    @include('user::modals.approve_course')
    @include('user::modals.courses_not_approved')
@endsection

@section('footer-scripts')

    <script type="text/javascript" src='{{ asset('assets/js/admin.js') }}'></script>

@endsection
