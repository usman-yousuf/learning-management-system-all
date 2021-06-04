@extends('teacher::layouts.teacher')

@section('page-title')
    Quizzez
@endsection

@section('content')
<div class="container-fluid px-5">
    <div class="row py-4">
        <div class="col-xl-6 col-lg-6 col-md-4 col-sm-12 col-12 align-self-center ">
            <h3 class="top_courses_text-s mx-3 ">Quiz</h3>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 col-12 ">
            <div class="float-md-right ">
                <a href=" " class="btn btn py-3 px-5 add_course_btn-s " data-toggle="modal " data-target="#courses_outline_modal-d">
                    <img src="assets/preview/add_button.svg " width="20 " id="add_outline_btn-d " class="mx-2 " alt=" ">
                    <span class="mx-1 text-white ">Add Test</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quiz list - START -->
    <div class="row pb-4">
        <!-- List Item 1 - START -->
        @forelse ($data->quizzes as $key => $item)
            @if ($item->type == 'mcqs')
                <div class="col-12 my-2 bg_white-s br_10px-s">
                    <div class="row py-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                            <h5 class="fg-success-s">{{ $item->course->title }}</h5>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12 fg-success-s text--xl-right">
                            <h6>Type: {{ strtoupper($item->type) }} <span><img class="pl-3" src="assets/preview/clock_icon.svg" alt=""></span> {{ $item->duration_mins }} Minutes</h6>
                        </div>
                    </div>
                    @foreach ($item->questions as $question)
                        <div class="row">
                            <div class="col-11 fg_dark-s">
                                <p>{{ $question->body }}</p>
                                @break;
                            </div>
                        </div>
                    @endforeach
                    <div class="row py-3">
                        <div class="col-xl-3 col-lg-6 col-md-5 col-12 fg_dark-s">
                            <span>Tottal Students: {{ $item->students_count }}</span>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-7 col-12 fg_dark-s">
                            <span>Attending Test Student: {{ $item->students_count }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if ($item->type == 'test')
                <div class="col-12 my-2 bg_white-s br_10px-s">
                    <div class="row py-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                            <h5 class="fg-success-s">{{ $item->course->title }}</h5>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12 fg-success-s text--xl-right">
                            <h6>Type: {{ strtoupper($item->type) }} <span><img class="pl-3" src="assets/preview/clock_icon.svg" alt=""></span> {{ $item->duration_mins }} Minutes</h6>
                        </div>
                    </div>
                    @foreach ($item->questions as $question)
                        <div class="row">
                            <div class="col-11 fg_dark-s">
                                <p>{{ $question->body }}</p>
                                @break;
                            </div>
                        </div>
                    @endforeach
                    <div class="row py-3">
                        <div class="col-xl-3 col-lg-6 col-md-5 col-12 fg_dark-s">
                            <span>Tottal Students: {{ $item->students_count }}</span>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-7 col-12 fg_dark-s">
                            <span>Attending Test Student: {{ $item->students_count }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if ($item->type == 'boolean')
                <div class="col-12 my-2 bg_white-s br_10px-s">
                    <div class="row py-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                            <h5 class="fg-success-s">{{ $item->course->title }}</h5>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-12 fg-success-s text--xl-right">
                            <h6>Type: {{   ucwords($item->type == 'boolean'? 'true false' : '') }} <span><img class="pl-3" src="assets/preview/clock_icon.svg" alt=""></span> {{ $item->duration_mins }} Minutes</h6>
                        </div>
                    </div>
                    @foreach ($item->questions as $question)
                        <div class="row">
                            <div class="col-11 fg_dark-s">
                                <p>{{ $question->body }}</p>
                                @break;
                            </div>
                        </div>
                    @endforeach
                    <div class="row py-3">
                        <div class="col-xl-3 col-lg-6 col-md-5 col-12 fg_dark-s">
                            <span>Tottal Students: {{ $item->students_count }}</span>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-7 col-12 fg_dark-s">
                            <span>Attending Test Student: {{ $item->students_count }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            
        @endforelse
        <!-- List Item 1 - END -->
    </div>
    <!-- Quiz list - END -->
</div>
@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/quiz.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection


@push('header-scripts')
    <script>
        let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
    </script>
@endpush
