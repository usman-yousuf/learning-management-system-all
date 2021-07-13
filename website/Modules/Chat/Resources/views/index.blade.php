@extends('teacher::layouts.teacher')

@section('page-title')
    Chat - LMS
@endsection

@section('header-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row my-5 mx-3 bg-white justify-content-around flex-column-reverse flex-md-row">
            <!-- Chat Sidebar - START -->
            <div class="col-12 col-xl-4 col-lg-4 col-md-5  b_1px-s chat_sidebar-s ">
                <div class="" id="chat_sidebar-d">
                    <!-- ---  chat heading --- -->
                    <div class="row mt-3">
                        <div class="col-8">
                            <h4 class="font-weight-normal">Messages</h4>
                        </div>
                        <div class=" col-4 col-md-4 col-lg-4 col-xl-4 w-100">
                            <div class="float-right">
                                <a href="#modal_new_message-d" role="button" data-toggle="modal">
                                    <img src="{{ asset('assets/images/chat_edit_icon.svg') }}" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--  chat heading - END -->

                    <!-- chat search bar - START -->
                    <div class="row">
                        <div class="col chat_search-s mt-4 mb-4">
                            <form id="frm_search_existing_chat_users-d" action="javascript:void(0)" method="POST">
                                <span class="search_icon_position-s">
                                    <button type="submit" class="no_btn-s">
                                        <img class="img_search_icon-s" src="{{ asset('assets/images/chat_search_icon.svg') }}" alt="">
                                    </button>
                                </span>
                                <input type="search" class="search_input-s" name="keywords" placeholder="Search User...">
                            </form>
                        </div>
                    </div>
                    <!-- chat search bar - END -->

                    <!-- chat members list - START -->
                    <div class="row">
                        <div class="col scroll_chat_container-s existing_chat_users_listing_container-d">
                            @include('chat::partials/chat_users_listing', ['listing_nature' => 'chat_sidebar', 'chats' => $chats])
                        </div>
                    </div>
                    <!-- chat members list - END -->
                </div>
            </div>
            <!-- Chat Sidebar - END -->

            <!-- Chat  Messages Container - START -->
            <div class="col-12 col-xl-8 col-lg-8 col-md-7 b_1px-s">
                @php
                    $myChat = null;
                    if($chats->total_chats){
                        $myChats = $chats->chats;
                        if(count($myChats)){
                            $myChat = $myChats[0];
                        }
                    }
                    // chat member
                    $myChatMember = null;
                    // dd($myChat->members);
                    if(count($myChat->members)){
                        foreach ($myChat->members as $index => $m) {
                            $myChatMember = $m->profile;
                            break;
                            // dd($m, $loop->iteration);
                        }
                    }
                @endphp
                <div class="row py-2 border-bottom d-flex">
                    <div class="col-12 chat_header-d chat_uuid-d" data-chat_uuid="{{ $myChat->uuid ?? '' }}">
                        <a href="javascript:void">
                            <img class="dp_img_38px-s chat_image-d" src="{{ getFileUrl($myChatMember->profile_image ?? null, null, 'profile') }}" alt="user-image" />
                        </a>
                        <span class="ml-1 chat_title-d">{{ $myChatMember->first_name ?? '' . $myChatMember->last_name ?? '' }}</span>
                    </div>
                </div>

                <!-- chat container -START -->
                <div class="row">
                    <div class="col mb-5 scroll_chat_container-s chat_messages_content_container-d">
                        @include('chat::partials/chat_messages_listing', ['chat' => $myChat])
                    </div>
                </div>
                <!-- chat container -END -->

                <!-- chat Messages input - START -->
                <div class="row">
                    <div class="col-12 bg_light-s pt-4 pb-4 position-absolute fixed-bottom">
                        <form id="frm_send_message-d" action="javascript:void(0)" method="POST">
                            <div class="input-group">
                                <textarea name="chat_message" class="form-control type_msg border-0 mr-3 px-3 pt-3 pb-2" style="border-radius: 30px;  min-height: 47px; max-height: 48px;" placeholder="Type your message..."></textarea>
                                <input type="hidden" name="chat_uuid" id='hdn_chat_uuid-d' value="" />
                                <input type="hidden" name="reciever_uuid" id='hdn_reciever_uuid-d' value="" />
                                <button type="submit" class="no_btn-s p-0">
                                    <span class="mt-3 mr-4">
                                        Send
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- chat Messages input - START -->
            </div>
            <!-- Chat  Messages Container - END -->
        </div>
    </div>

    @include('chat::modals/new_message', ['users' => $newUsers])

@endsection



@section('footer-scripts')
@php
    // dd( json_decode($online_courses_graph_data) );
@endphp
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js" integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let get_chat_messages_url = "{{ route('chat.getChatMessages') }}";
        let delete_chat_url = "{{ route('chat.deleteChat') }}";
    </script>
    <script src="{{ asset('assets/js/manage_chats.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection
