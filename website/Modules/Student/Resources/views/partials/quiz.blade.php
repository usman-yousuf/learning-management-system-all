

    <!--quiz list-->
    <div class="row pt-5 pb-4 quiz_main_container-d course_details_container-d">
        @forelse ($data->quizzes as $item)
            <div class="col-12 my-2 bg_white-s br_10px-s single_quiz_container-d shadow">
                <div class="row py-3">
                    <div class="col-xl-7 col-lg-6 col-md-12 col-12">
                        <a class='no_link-s link-d'href="javascript:void(0)">
                            <h4 class=" title-d">
                                {{ $item->title }}
                            </h4>
                            <h5 class="fg-success-s">
                                {{ $item->course->title }}
                            </h5>
                        </a>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-md-12 col-12  ">
                        <span class="text_muted-s">
                            Quiz Type
                        </span> 
                        <span class="ml-3 font-weight-bold  ">
                            {{ $item->type }}  
                        </span>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-11 fg_dark-s">
                            <p>{{ $item->description }}</p>
                        </div>
                    </div>
                <div class="row py-3">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 fg_dark-s mb-2 ">
                        <a href="" class="btn bg_success-s text-white w-50 br_21px-s" data-toggle="modal" data-target="#conformation_modal-d-{{ $item->uuid }}">
                            Start
                        </a>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 fg_dark-s mt-2 text-xl-right">
                        <span>
                            <img src="{{ asset('assets/images/student_quiz_clock.svg') }}" alt="clock">
                        </span>
                        <span class="pl-2 ">
                            <strong>{{ $item->duration_mins }}</strong> Minutes
                        </span>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 fg_dark-s mt-2 ">                       
                        <span >
                            <img  src="{{ asset('assets/images/student_quiz_calender.svg') }}" alt="clock">
                        </span>
                        <span class="pl-2">
                            {{ $item->modal_due_date }}
                        </span>
                    </div>
                </div>
            </div>

            <!--quiz conformation modal-->
              @include('student::modals.quiz_confirmation_modal');
            <!--quiz conformation modal end-->
        @empty
        
        @endforelse
    </div>
    <!--quiz list end-->


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_student_courses.js') }}"></script>
@endsection