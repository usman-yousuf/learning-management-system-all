@extends('teacher::layouts.teacher')
@section('page-title')
    Admin Dashboard
@endsection

@section('content')
    <div class="container-fluid px-lg-5 px-md-4 py-4">
        <div class="row mt-3">
            <div class="col-12">
                <!--Heading-->
                <h4><strong>Non Approved Teachers</strong></h4>
            </div>
        </div>
        <div class="row mx-auto mt-4">
            @forelse ($data as $teacher)
                <!--teacher list-->
                    <div class="col-12 py-2 px-4 my-3 bg_white-s br_10px-s shadow py-3" id="teacher-d{{ $teacher->uuid }}">
                        <div class="row">
                            <div class="col-lg-6 col-md-8 col-6">
                                <!--teacher name-->
                                <h5><strong>Teacher Name </strong>{{ $teacher->first_name }}</h5>
                                <!--registered date-->
                                <span>Registered at {{\Carbon\Carbon::parse($teacher->created_at)->format('d-m-Y')}}</span>
                            </div>
                            <div class="col-lg-6 col-md-4 col-6 text-right align-self-center">
                                <!--approved button-->
                                    <span>
                                        <form action="{{ route('approveTeacher', ['uuid' => $teacher->uuid]) }}" class="frm_approve_teacher-d" method="post">
                                            @csrf
                                            <input type="hidden" name="teacher_uuid" class='teacher_uuid-d' value="{{ $teacher->uuid }}">
                                            <button type="submit">
                                                    <img src="{{ asset('assets/images/tick_mark.svg ') }}" alt="">
                                            </button>
                                        </form>
                                    </span>
                                <!--not approved button -->
                                <span><button data-toggle="modal" data-target="#not_approved_teacher_modal"><img src="{{ asset('assets/images/cancel.svg') }} " alt=""></button></span>
                            </div>
                        </div>
                    </div>
                <!--teacher list end-->
            @empty
                
            @endforelse
            
        </div>
    </div>

    @include('user::modals.approve_teacher')
    @include('user::modals.teacher_not_approved', ['user' => $teacher->user->uuid])
@endsection

@section('footer-scripts')

    <script>
        let ADMIN_URL= "{{ route('adminDashboard') }}";
    </script>
    <script type="text/javascript" src='{{ asset('assets/js/admin.js') }}'></script>

@endsection
