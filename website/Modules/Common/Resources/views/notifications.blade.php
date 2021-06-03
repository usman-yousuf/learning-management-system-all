@extends('teacher::layouts.teacher')

@section('page-title')
    Notifications
@endsection

@section('content')
    <div class="container-fluid px-5">
        <div class="row pt-4">
            <div class="col">
                <h4 class="font_w_700-s">Notifications</h4>
            </div>
        </div>
        <div class="row pt-5 pl-2">
            @forelse ($data->notifications as $item)
                @if($item->is_activity == 0 )   
                    <div class="col-12 mt-3 notification_listing_container-d">
                        <div class="row single_notification-s px-2 py-4 pr-2 align-items-center @if( isset($item) && ($item->is_read == 0)) active @endif ">
                            <!--notification image-->
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-1 col-3  text-center">
                                <img class="notification_img-s img_w_50px-s" src="{{ getFileUrl($item->sender->profile_image ?? null, null, 'profile') }}" alt="student img">
                            </div>
                            <!--notification image end-->
                            <!--notification text-->
                            <div class="col-xl-10 col-lg-10 col-md-8 col-sm-10 col-7  text-break text-wrap">
                                <strong ><a class="no_link-s" href="javascript:void(0)">{{ $item->receiver->first_name }}</a></strong>
                                <br>
                                <span>Enrolled into Graphic designing</span>
                            </div>
                            <!--notification text end-->
                            <!--notification dropdown button-->
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-1 col-2">
                                <div class="custom_dropdown-s  text-center">
                                    <img class="dropbtn" src="{{ asset('assets/images/vertical_dots.svg') }}" alt="button img">
                                    <div class="custom_dropdown_content-s shadow bg-white text-left ">
                                        <div class="py-2 pl-2">
                                            <a class="no_link-s" href="{{route('notifications.read', ['uuid' => $item->uuid])}}">Mark Read</a>
                                        </div>
                                        <div class="py-2 pl-2">
                                            <a class="no_link-s" href="{{route('notifications.delete', ['uuid' => $item->uuid])}}">Delete</a>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <!--notification dropdown button-->
                        </div>
                    </div>                  
                @endif
            @empty
                
            @endforelse
        </div>
    </div>
@endsection


@section('footer-scripts')
    {{--  <script src="{{ asset('assets/js/manage_courses.js') }}"></script>  --}}
@endsection

@section('header-css')
    {{--  <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />  --}}
@endsection


@push('header-scripts')
    <script>
        // let modal_delete_outline_url = "{{ route('course.delete-outline') }}";
        // let modal_delete_slot_url = "{{ route('course.delete-slot') }}";
        // let modal_delete_video_content_url = "{{ route('course.delete-video-content') }}";
    </script>
@endpush
