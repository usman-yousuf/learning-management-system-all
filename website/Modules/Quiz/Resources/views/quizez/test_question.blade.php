@extends('teacher::layouts.teacher')

@section('page-title')
   Test Question
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mx-xl-3 mx-lg-3 flex-column-reverse flex-lg-row">
            <div class="col-12 col-md-12 col-lg-8 col-xl-8">
                <!-- test question top heading - START -->
                <div class="row mt-3">
                    <div class="col-xl-4 col-lg-6 col-md-12 col-12 mt-5">
                        <h5>Landing Page Design</h5>
                    </div>
                    <div class="col-12 mt-4">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to
                        </p>
                    </div>
                    <hr class="col-10 w-100 ml-3 dotted_border_for_hr-s">
                </div>
                <!-- test question top heading - END -->

                <!-- Test Questions - START -->
                <!-- question -1 - start -->
                @forelse ($data->quizzez as $item)
                    @foreach ($item->questions as $question)
                        <div class="row mt-3">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-3">
                                <span>Q: 1</span><br>
                                <span>
                                    <img src="assets/preview/edit_icon.svg" alt="">
                                    <img src="assets/preview/delete_icon.svg" alt="">
                                </span>
                            </div>
                            <div class="col-9">
                                <p>Lorem Ipsum is simply dummy text of the printing and has been the industry's standard dummy text ever since the 1500s?</p>
                            </div>
                            <div class="col-12">
                                <textarea class="w_100-s textarea_h_70px-s br_10px-s " id="test_quiz_ans-d" name="test_quiz_ans">They offer free tutorials in all web development technologies.
                                </textarea>
                            </div>
                        </div>
                    @endforeach
                    <!-- question -1 - end -->
                @empty
                @endforelse

                <!-- Test Questions - END -->
            </div>
            <div class="mt-5 mt-lg-5 mt-xl-5 col-12 col-md-12 col-lg-4 col-xl-4">
                <div class="row mt-5 mt-md-5 mt-lg-5 mt-xl-5 pt-xl-4  pt-lg-4 ">
                    <div class="col-12 px-xl-0 px-lg-0">
                        <div class="card w-auto shadow border-0">
                            <div class="card-body">
                                <h5 class="card-title">Add Question</h5>
                                <textarea class="w-100 min_h_132px-s max_h_132px-s" name="add_question_textarea" id="">Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                                </textarea>
                                <div class="row mt-4 mb-5 justify-content-center">
                                    <div class="col-6 text-center">
                                        <a href="#" class="btn bg_success-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5">Save</a>
                                    </div>
                                    <div class="col-6 text-center">
                                        <a href="#" class="btn bg_info-s text-white br_19px-s px-4 px-md-5 px-lg-4 px-xl-5">Add</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
