@extends('teacher::layouts.teacher')

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
                            <input type="search" class="search_input-s" name="" id="" placeholder="Search User...">
                            <span class="search_icon_position-s">
                                <a href="javascript:void">
                                    <img class="img_search_icon-s" src="{{ asset('assets/images/chat_search_icon.svg') }}" alt="">
                                </a>
                            </span>
                        </div>
                    </div>
                    <!-- chat search bar - END -->

                    <!-- chat members list - START -->
                    <div class="row">
                        <div class="col scroll_chat_container-s">
                            @include('chat::partials/chat_users_listing', ['listing_nature' => 'chat_sidebar'])
                        </div>
                    </div>
                    <!-- chat members list - END -->
                </div>
            </div>
            <!-- Chat Sidebar - END -->

            <!-- Chat  Messages Container - START -->
            <div class="col-12 col-xl-8 col-lg-8 col-md-7  b_1px-s">

                <!-- top head of chat messages container - START -->
                <div class="row py-2 border-bottom d-flex">
                    <div class="col-12">
                        <a href="javascript:void">
                            <img class="dp_img_38px-s" src="{{ getFileUrl($item->profile->profile_image ?? null, null, 'profile') }}" alt="user-image" />
                        </a>
                        <span class="ml-1">Jannifer Lawerence</span>
                    </div>
                </div>
                <!-- top head of chat messages container - END-->

                <!-- chat container -START -->
                <div class="row">
                    <div class="col mb-5 scroll_chat_container-s">
                        @include('chat::partials/chat_messages_listing')
                    </div>
                </div>
                <!-- chat container -END -->

                <!-- chat Messages input - START -->
                <div class="row">
                    <div class="col-12 bg_light-s pt-4 pb-4 position-absolute fixed-bottom">
                        <div class="input-group">
                            <div class="input-group-append">
                                {{--  <input type="hidden" name="" value="">
                                <span class="input-group-text attach_btn bg_light-s border-0">
                                    <i class="fa fa-paperclip"></i>
                                </span>  --}}
                            </div>
                            <textarea name="chat_message" class="form-control type_msg border-0 mr-3 px-3 pt-3 pb-2" style="border-radius: 30px;     min-height: 47px; max-height: 48px;" placeholder="Type your message..."></textarea>
                            <span class="mt-3 mr-4">
                                <input type="hidden" name="chat_uuid" value="" />
                                <button type="submit" class="no_btn-s p-0">
                                    Send
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- chat Messages input - START -->
            </div>
            <!-- Chat  Messages Container - END -->
        </div>
    </div>

    <div class="modal fade newMessage modal-small" id="modal_new_message-d" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title chat_edit_modal_title-s">New Messages</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- chat search bar - START -->
                    <div class="row">
                        <div class="col chat_search-s mt-4 mb-4">
                            <input type="search" class="search_input-s" name="" id="" placeholder="Search User...">
                            <span class="search_icon_position-s">
                                <a href="javascript:void">
                                    <img class="img_search_icon-s" src="{{ asset('assets/images/chat_search_icon.svg') }}" alt="search-icon" />
                                </a>
                            </span>
                        </div>
                    </div>
                    <!-- chat search bar - END -->
                    <div class="chat_search_list-s">
                        <!-- chat members list - START -->
                        <div class="row">
                            <div class="col scroll_list_of_members_in_modal-s">
                                @include('chat::partials/chat_users_listing', ['listing_nature' => 'new_chat_modal'])
                            </div>
                        </div>
                        <!-- chat members list - END -->
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
