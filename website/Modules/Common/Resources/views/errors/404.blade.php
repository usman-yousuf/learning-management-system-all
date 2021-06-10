@extends('common::layouts.error')

@section('error-code')
    404
@endsection

@section('content')
    <div class="container-fluid text-center position-fixed py-5 h-100">
        <div class="h-100 pt-5">
            <strong class="display-1 font_size_250px-s">404</strong>
            <p class="pt-4 font_size_larger-s">The Page you request cannot be found at the moment. please contact your site admin</p>
            <a href="javascript:void(0)" class="btn btn pt-2 pb-2 pl-3 pr-3 add_course_btn-s mt-5">
                <img src="assets/preview/add_button.svg" width="20" id="add_video-d" class="ml-2 mr-2" alt="">
                <span class="ml-2 mr-2 text-white">Go Back</span>
            </a>
        </div>
    </div>
@endsection
