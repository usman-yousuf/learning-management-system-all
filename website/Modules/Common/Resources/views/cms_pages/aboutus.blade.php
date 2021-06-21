@extends('teacher::layouts.teacher')

@section('page-title')
    About Us
@endsection

@section('content')
<div class="container-fluid px-0 ">
    <div class="row mt-4">
        <div class="col offset-1">
            <h4 class="font_w_700-s">About Us</h4>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 col-10 offset-1">
            <p>Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.It has survived not only five centuries,but also the leap into electronic typesetting,remaining essentially unchnaged.</p>
            <p class="mt-3">Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.It has survived not only five centuries,but also the leap into electronic typesetting,remaining essentially unchnaged.</p>
            <p class="mt-3">Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.It has survived not only five centuries,but also the leap into electronic typesetting,remaining essentially unchnaged.</p>
            <p class="mt-3">Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.It has survived not only five centuries,but also the leap into electronic typesetting,remaining essentially unchnaged.</p>
            <p class="mt-3">Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.It has survived not only five centuries,but also the leap into electronic typesetting,remaining essentially unchnaged.</p>
            <p class="mt-3">Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.It has survived not only five centuries,but also the leap into electronic typesetting,remaining essentially unchnaged.</p>
            <p class="mt-3">Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. when an unknown printer took as galley of type and scambled it to make a type specimen book.It has survived not only five centuries,but also the leap into electronic typesetting,remaining essentially unchnaged.</p>
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
