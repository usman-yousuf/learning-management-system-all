@php
    // $quizzes =
@endphp

@forelse ($quizzez as $item)
    <div class="col-12 my-2 bg_white-s br_10px-s single_quiz_container-d shadow">
        <div class="row py-3 px-xl-5">
            <div class="col-xl-4 col-lg-6 col-md-12 col-12">
                <a class='no_link-s link-d'href="javascript:void(0)">
                    <h4 class=" title-d">
                        {{ ucwords($item->title ?? '') }}
                    </h4>
                    <h5 class="fg-success-s">
                        {{ ucwords($item->course->title ?? '') }}
                    </h5>
                </a>
            </div>
            <div class="col-xl-8 col-lg-6 col-md-12 col-12 mt-1 text-xl-right text-lg-right ">
                <span class="text_muted-s">
                    Quiz Type
                </span>
                <span class="ml-3 font-weight-bold">
                    {{ ucwords($item->type ?? '') }}
                </span>
            </div>
        </div>
            <div class="row px-xl-5">
                <div class="col-12 fg_dark-s">
                    <p>{{ $item->description ?? '' }}</p>
                </div>
            </div>
        <div class="row py-3 px-xl-5 flex-column-reverse flex-lg-row">
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 fg_dark-s mt-xl-0 mt-lg-0 mt-3 mb-xl-0 mb-lg-0 mb-3">
                <a href="javascript:void(0)" class="btn bg_success-s text-white w-50 br_21px-s start_quiz-d" data-quiz_url="{{ route('student.getQuiz', $item->uuid ?? '') }}">
                    Start
                </a>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 fg_dark-s mt-2 mb-2 d-flex justify-content-lg-end ">
                <div>
                    <span>
                        <img src="{{ asset('assets/images/student_quiz_clock.svg') }}" alt="clock" />
                    </span>
                    <span class="pl-2 ">
                        <strong>{{ $item->duration_mins ?? 0 }}</strong> Minutes
                    </span>
                </div>
                <div class="ml-5">
                    <span>
                        <img src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="clock" />
                    </span>
                    <span class="pl-2">
                        {{ date('M d, Y', strtotime($item->modal_due_date)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@empty

@endforelse
