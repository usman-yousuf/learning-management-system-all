@extends('teacher::layouts.teacher')

@section('page-title')
    Term and Services
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-4 mx-auto">
            <div class="col-12">
                <h4 class="font_w_700-s">Term and Services</h4>
            </div>
            <div class="col-12">
                
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
