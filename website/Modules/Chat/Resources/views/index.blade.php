@extends('teacher::layouts.teacher')

@section('page-title')
    Chat - LMS
@endsection

@section('header-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

    <!-- <div class="container-fluid"> -->
        <div class="row my-5 mx-3 bg-white justify-content-around flex-column-reverse flex-md-row pb-5">
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
                                <input type="search" class="search_input-s existing_chat_search_input-d" name="keywords" placeholder="Search User...">
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
                    if(isset($myChat->members)){
                        if(count($myChat->members)){
                            foreach ($myChat->members as $index => $m) {
                                $myChatMember = $m->profile;
                                break;
                                // dd($m, $loop->iteration);
                            }
                        }
                        $member_fname = $myChatMember->first_name ?? '';
                        $member_lname = $myChatMember->last_name ?? '';
                        $member_name = $member_fname . ' ' . $member_lname;
                    }
                @endphp
                <div class="row py-2 border-bottom d-flex">
                    <div class="col-12 chat_header-d chat_uuid-d" data-chat_uuid="{{ $myChat->uuid ?? '' }}">
                        <a href="javascript:void">
                            <img class="dp_img_38px-s chat_image-d" src="{{ getFileUrl($myChatMember->profile_image ?? null, null, 'profile') }}" alt="user-image" />
                        </a>
                        <span class="ml-1 chat_title-d">{{ $member_name ?? '' }}</span>
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
                        <form id="frm_send_message-d" action="{{ route('chat.sendChatMessage') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <textarea name="chat_message" class="txt_chat_message-d form-control scrollbar_hide-s type_msg border-0 mr-3 px-3 pt-3 pb-2" style="border-radius: 30px;  min-height: 47px; max-height: 48px; resize: none;" placeholder="Type your message..."></textarea>
                                <input type="hidden" name="chat_uuid" id='hdn_chat_uuid-d' value="{{ $myChat->uuid ?? '' }}" />
                                <input type="hidden" name="chat_type" id='hdn_chat_type-d' value="single" />
                                <input type="hidden" name="reciever_uuid" id='hdn_reciever_uuid-d' value="{{ $myChatMember->profile->uuid ?? '' }}" />
                                <button type="submit" class="no_btn-s p-0">
                                    <span class="mt-3 mr-4">
                                        Send
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- chat Messages input - START -->
            </div>
            <!-- Chat  Messages Container - END -->
        </div>
    <!-- </div> -->

    @include('chat::modals/new_message', ['users' => $newUsers])

    <div class="cloneable_containers-d" style="display: none;">
        {{-- existing chat users listing - START --}}
        <div class="row py-3 border-bottom d-flex chat_list_members-s existing_chat_single_container-d" data-uuid="{{ $item->uuid ?? '' }}" id='cloneable_existing_chat_single_container-d'>
            <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                @php
                    // print_array($item);
                @endphp
                <div class="row">
                    <div class="col-xl-2 col-lg-2 col-md-2 col-2 mr-xl-0 mr-md-3">
                        <a href="javascript:void">
                            <img class="dp_img_38px-s chat_member_profile_image-d" src="{{ getFileUrl(null, null, 'profile') }}" alt="user-profile" />
                        </a>
                    </div>
                    <div class="col-xl-10 col-lg-8 col-8">
                        <h6 class="mb-0 ml-1 chat_member_profile_name-d">Gulab Mehak</h6>
                        <span class="ft_12px-s ml-1 chat_last-d">no message yet</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                <div class="dropdown">
                    <i class="fa fa-2x fa-angle-down dropdown_menu_on_left-s text-right" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item text-danger ft_12px-s py-2 delete_chat-d" href="javascript:void(0)">
                            <i class="fa fa-trash"></i> Delete Chat
                        </a>
                    </div>
                </div>
                <span class="list_member_last_online_date-s ft_12px-s float-right message_time-d"> {{ getRelativeTime($item->created_at ?? '-1 minute') }}</span>
            </div>
        </div>
        {{-- existing chat users listing - END --}}

        {{-- new chat users listing - START --}}
        <div class="row py-3 px-1 border-bottom d-flex chat_list_members-s new_chat_user_single_container-d" data-uuid="{{ $item->uuid ?? '' }}" id='cloneable_new_chat_user_single_container-d'>
            <div class="col-9">
                <div class="row">
                    <div class="col-3 col-sm-2">
                        <a href="javascript:void">
                            <img class="dp_img_46px-s profile_image-d" src="{{ getFileUrl(null, null, 'profile') }}" alt="user-image" />
                        </a>
                    </div>
                    <div class="col-9 col-sm-10">
                        <h6 class="mb-0 ml-1 profile_name-d">Code Builder</h6>
                        <strong class="ft_12px-s ml-1 profile_type-d">Teacher</strong>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <span class="ft_12px-s float-right">
                    <a href="javascript:void(0)" class='send_new_message-d'>
                        <img src="{{ asset('assets/images/chat_new_message.svg') }}" alt="new-sms-icon" />
                    </a>
                </span>
            </div>
        </div>
        {{-- new chat users listing - END --}}

        {{-- send new message - START --}}
        <div class="row py-4 pr-4 single_message_container-d" id='cloneable_send_message_container-d'>
            <div class="col-xl-5 col-lg-5 col-md-3 col-2"></div>
            <div class="col-xl-7 col-lg-7 col-md-9 col-10 pt-2 pb-2 bg_success-s br_10x10_left-s">
                <p class="text-white text-wrap text-break message_body-d">
                    {{ $item->message ?? '' }}
                </p>
            </div>
            <div class="col-12 pr-0 text-right">
                <span class="ft_12px-s chat_message_time-d"> {{ getRelativeTime($item->created_at ?? 'now') }}</span>
            </div>
            <span class='chat_uuid-d d-none'>{{ $item->chat->uuid ?? '' }}</span>
            <span class='sender_uuid-d d-none'>{{ 'current_profile_uuid' }}</span>
            <span class='sender_image-d d-none'>{{ 'profile_image' }}</span>
        </div>
        {{-- send new message - END --}}
    </div>

@endsection


@push('header-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.1/socket.io.min.js"></script>
    <script>
        var socket = io('{{ config('app.socket') }}');
    </script>
@endpush

@section('footer-scripts')
@php
    // dd( json_decode($online_courses_graph_data) );
@endphp
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js" integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let get_chat_messages_url = "{{ route('chat.getChatMessages') }}";
        let delete_chat_url = "{{ route('chat.deleteChat') }}";
        let get_chatted_users_url = "{{ route('chat.getChattedUsers') }}";
        let get_not_chatted_users_url = "{{ route('chat.getNotChattedUsers') }}";
    </script>
    <script src="{{ asset('assets/js/manage_chats.js') }}"></script>
@endsection

@section('header-css')
    <link rel="stylesheet" href="{{ asset('assets/css/course.css') }}" />
@endsection
