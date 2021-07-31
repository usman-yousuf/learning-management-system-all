@extends('teacher::layouts.teacher')

@section('page-title')
    Sales Report
@endsection

@section('content')
<div class="container-fluid px-5">
    <div class="row pt-5">
        <div class="col float-left ">
            <h2 class="">Sales Report</h2>
        </div>
    </div>
    <div class="row d-flex flex-lg-row  flex-column-reverse ">
        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mt-3 sales_report_conatiner-d">
            @forelse ($data->payment_histories as $item)
                <!-- Sales Report Single Container - START -->
                <div class="row sales_report_single_container-d">
                    <div class="col-12  mb-2 mt-1">
                        <div class="card shadow ">
                            <div class="card body">
                                <div class="container-fluid  mb-4 mt-4">
                                    <div class="row ml-1">
                                        <div class="col-xl-4 col-lg-4 col-md-4 ">
                                            <h6 class="text-muted">Course Status</h6>
                                            <h6 class="course_status-d mt-3 font_w_700-s">{{ $item->course->is_course_free ? 'Free' : 'Paid' }}</h6>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4">
                                            <h6 class="text-muted">Course Title</h6>
                                            <h6 class="course_title-d mt-3 font_w_700-s">{{ $item->course->title ?? '' }}</h6>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 ">
                                            <h6 class="text-muted">Student Name</h6>
                                            <h6 class="course_student_name-d mt-3 font_w_700-s">{{ ucwords($item->payee[0]->first_name) ?? '' }}</h6>
                                        </div>
                                    </div>
                                    <div class="row ml-1 mt-4">
                                        <div class="col-xl-4 col-lg-4 col-md-4  ">
                                            <h6 class="text-muted">Amount Paid</h6>
                                            <h6 class="mt-3 font_w_700-s">
                                                <span class='payment_amount_unit-d'>PKR</span>
                                                <span class='payment_amount-d'>{{ $item->amount?? '' }}</span>
                                            </h6>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 ">
                                            <h6 class="text-muted">Trx ID</h6>
                                            <h6 class="mt-3 font_w_700-s payment_trx_id-d">
                                                @php
                                                    if($item->stripe_trans_id)
                                                    {
                                                        echo "<img src= ".asset('assets/images/easypaisa_icon.svg').">".' '.$item->stripe_trans_id;
                                                    }
                                                    else if($item->easypaisa_trans_id)
                                                    {
                                                        echo $item->easypaisa_trans_id;
                                                    }
                                                    else {
                                                        echo 'Not Set';
                                                    }
                                                @endphp
                                            </h6>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4   ">
                                            <h6 class="text-muted">Date</h6>
                                            <h6 class="course_category-d mt-3 font_w_700-s payment_date-d">{{ date('M d Y', strtotime($item->created_at)) }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Sales Report Single Container - END -->
            @empty
            <div class="row sales_report_single_container-d">
                <div class="col-12  mb-2 mt-1">
                    <div class="card shadow ">
                        <div class="card body">
                            <div class="container-fluid  mb-4 mt-4">
                                <div class="row">
                                    <div class="col-12 text-center mt-5 mb-5">
                                        No Record Found
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <div class="col-xl-4 col-lg-4 col-md-12 ">
            <div class="card shadow border-0  mt-3">
                <div class="card-body">
                    <!-- Form Start -->
                    <form action="{{ route('report.sales') }}" method="post">
                        @csrf
                        <div class="row pl-1">
                            <div class="col">
                                <h4 class="">Filter</h4>
                            </div>
                        </div>
                        <!-- Input Fields Start -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="custom-label-s" for="course">Course </label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-control-lg custom-input-s" name="course_title" id="course_title" placeholder="Logo Designing" value="{{ isset($data->requestFilters['course_title'])? $data->requestFilters['course_title'] : '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 ">
                                <div class="col-xl-6 col-lg-12 col-md-6 pb-3">
                                    <label class="custom-label-s" for="startdate">Start Date</label>
                                    <input type="date" class="form-control form-control-lg login_input-s" name="startdate" id="startdate" placeholder="" />
                                </div>
                                <div class="col-xl-6 col-lg-12 col-md-6  pb-3">
                                    <label class="custom-label-s" for="enddate">End date</label>
                                    <input type="date" class="form-control form-control-lg login_input-s" name="enddate" id="startdate" placeholder="" />
                                </div>
                            </div>
                        <!-- Input Fields End -->

                            <!-- Card Buttons -->
                            <div class="row  mt-5 mb-3">
                                <div class="col text-center">
                                    <button class="btn sale_search_btn-s br_24-s fg_white-s bg-success-s text-white  pl-5 pr-5 btn-lg col">Search</button>
                                </div>
                            </div>
                        <!-- Card Buttons End -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer-scripts')
    <script src="{{ asset('assets/js/manage_courses.js') }}"></script>
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
