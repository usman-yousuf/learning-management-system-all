@extends('teacher::layouts.teacher')

@section('page-title')
  Pending Quiz Result page
@endsection


@section('content')
    <div class="container-fluid px-xl-5 px-lg-5 px-md-4 px-3 font_family_sans-serif-s">
        <!-- course basics - START -->
        <div class="row pt-5">
            <!--back button-->
            <div class="angle_left-s col-xl-1 col-lg-2 col-md-12 col-sm-12 col-12 text-left pr-0 ">
                <a href="{{ \Auth::user()->profile_type == 'student' ? route('course.view',$data->course->uuid ) : '' }}">
                    <img src="{{ asset('assets/images/angle_left.svg') }}" class="shadow p-3 bg-white rounded" width="60" height="60" alt="back" />
                </a>
            </div>
            <!--back button end-->

            <!--main head-->
            <div class=" col-xl-4 col-lg-6 col-md-7 col-sm-12 col-12 ">
                <div class="">
                    <h2 class='course_detail_title_heading-d'>{{ $data->title ?? '' }}</h2>
                </div>
            </div>
            @php
                $data->duration_mins = (int)$data->duration_mins;
                // echo "+{$data->duration_mins} minutes";
                // $duration = date('M d, Y H:i:s', strtotime("+{$item->duration_mins} minutes"));
                $duration = date('M d, Y H:i:s', strtotime("+5 hour +{$data->duration_mins} minute"));
                $now_date = date('M d, Y H:i:s');
                $now = date('M d, Y H:i:s', strtotime("+5 hour", strtotime($now_date)));
            @endphp

            <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 fs_19px-s text-large offset-xl-1 offset-lg-2 mt-xl-0 mt-lg-0 mt-md-1 mt-3">
                <p>{{ $data->description ?? '' }}</p>
            </div>
            <!--main head end-->
        </div>
        <!--course basics - END -->

        <div class="row py-5">
            <div class="col-10 offset-1">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class='text-muted'>Quiz Type</th>
                            <td class=''>{{ ucwords($data->type ?? '') }}</td>
                        </tr>

                        <tr>
                            <th class='text-muted'>Total Marks</th>
                            <td class=''>{{ ucwords($data->my_attempt->total_marks ?? 0) }}</td>
                        </tr>

                        <tr>
                            <th class='text-muted'>Total Questions</th>
                            <td class=''>{{ ucwords($data->my_attempt->total_questions ?? 0) }}</td>
                        </tr>

                        <tr>
                            <th class='text-muted'>Marks Per Question</th>
                            <td class=''>{{ ($data->my_attempt->marks_per_question ?? 0) }}</td>
                        </tr>

                        @if($data->my_attempt->status == 'marked')
                            <tr>
                                <th class='text-muted'>Total Correct Answers</th>
                                <td class=''>{{ ($data->my_attempt->total_correct_answers ?? 0) }}</td>
                            </tr>

                            <tr>
                                <th class='text-muted'>Total Wrong Answers</th>
                                <td class=''>{{ ($data->my_attempt->total_wrong_answers ?? 0) }}</td>
                            </tr>

                            <tr>
                                <th class='text-muted'>Marks Obtained</th>
                                <td class=''>{{ ($data->my_attempt->marks_per_question * $data->my_attempt->total_correct_answers) }}</td>
                            </tr>

                            <tr>
                                <th class='text-muted'>Status</th>
                                <td class=''>{{ ucwords('Marked') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    <script>

    </script>
    {{-- <script src="{{ asset('assets/js/student.js') }}"></script> --}}
@endsection
