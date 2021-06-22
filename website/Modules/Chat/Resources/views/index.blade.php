@extends('teacher::layouts.teacher')

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
                                        <a href="#exampleModal" role="button" data-toggle="modal"><img src="assets/preview/chat_edit_icon.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <!--  chat heading - END -->

                            <!-- chat search bar - START -->
                            <div class="row">
                                <div class="col chat_search-s mt-4 mb-4">
                                    <input type="search" class="search_input-s" name="" id="" placeholder="Search Chat...">
                                    <span class="search_icon_position-s">
                                        <a href="javascript:void">
                                            <img class="img_search_icon-s" src="assets/preview/search_icon.svg" alt="">
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <!-- chat search bar - END -->

                            <!-- chat members list - START -->
                            <div class="row">
                                <div class="col">

                                    <!-- --- chat list member 4 - start --- -->
                                    <div class="row py-3 border-bottom d-flex chat_list_members-s">
                                        <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-2 col-md-2 col-2 mr-xl-0 mr-md-3">
                                                    <a href="javascript:void">
                                                        <img class="dp_img_38px-s" src="{{ getFileUrl($item->profile->profile_image ?? null, null, 'profile') }}" alt="user-profile" />
                                                    </a>
                                                </div>
                                                <div class="col-xl-10 col-lg-8 col-8">
                                                    <h6 class="mb-0 ml-1">Jannifer Lawerence</h6>
                                                    <span class="ft_12px-s ml-1">Lorem ipsum dolor sit</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                                            <div class="dropdown">
                                                <i class="fa fa-angle-down dropdown_menu_on_left-s ml-5" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item text-danger ft_12px-s" href="#">
                                                        <i class="fa fa-trash"></i> Delete Chat
                                                    </a>
                                                </div>
                                            </div> <span class="list_member_last_online_date-s ft_12px-s float-right">30 Apr 2019 </span>
                                        </div>
                                    </div>
                                    <!-- --- chat list member 4 - end --- -->
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
                            <div class="col scroll_chat_container-s">
                                <!-- preloader - START -->
                                <div class="row pt-4">
                                    <div class="col-12 d-flex justify-content-center">
                                        <div class="loader"></div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center mt-3">
                                        <span class="ft_12px-s"> TODAY AT 4:30 PM </span>
                                    </div>
                                </div>
                                <!-- preloader - END -->

                                <!-- second person in chat - START -->
                                <div class="row py-4 pr-4">
                                    <div class="col-xl-5 col-lg-5 col-md-3 col-2"></div>
                                    <div class="col-xl-7 col-lg-7 col-md-9 col-10 pt-2 pb-2 bg_success-s br_10x10_left-s">
                                        <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar.</p>
                                    </div>
                                    <div class="col-12 pr-0 text-right">
                                        <span class="ft_12px-s"> 12:43 pm</span>
                                    </div>
                                </div>
                                <!-- second person in chat - END -->

                                <!-- first person in chat - START -->
                                <div class="row py-4">
                                    <div class="col-xl-1 col-lg-1 col-md-1 mr-md-3 mr-xl-0">
                                        <a href="javascript:void"><img class="dp_img_38px-s" src="../website/public/assets/images/add_quiz_teacher_icon.png" alt=""></a>
                                    </div>
                                    <div class="col-xl-6 col-lg-8 col-md-8 col-9 ml-3 ml-lg-2  pt-2 pb-2 bg_light-s br_10x10-s">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar.</p>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-8 pl-0 offset-lg-1 offset-xl-1 offset-md-2">
                                        <span class="ft_12px-s ml-3 ml-lg-4 ml-xl-2"> 12:43 pm</span>
                                    </div>
                                    <div class="col-xl-5 col-lg-5 col-md-3 col-2"></div>
                                </div>
                                <!-- first person in chat - END -->

                                <!-- timeline of chat - START -->
                                <div class="row py-4">
                                    <div class="col-12 d-flex justify-content-center">
                                        <span class="ft_12px-s"> TODAY AT 4:30 PM </span>
                                    </div>
                                </div>
                                <!-- timeline of chat - END -->

                                <!-- first person in chat - START -->
                                <div class="row py-4">
                                    <div class="col-xl-1 col-lg-1 col-md-1 mr-md-3 mr-xl-0">
                                        <a href="javascript:void"><img class="dp_img_38px-s" src="../website/public/assets/images/add_quiz_teacher_icon.png" alt=""></a>
                                    </div>
                                    <div class="col-xl-6 col-lg-8 col-md-8 col-9 ml-3 ml-lg-2 pt-2 pb-2 bg_light-s br_10x10-s">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar.
                                        </p>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-8 pl-0 offset-lg-1 offset-xl-1 offset-md-2">
                                        <span class="ft_12px-s ml-3 ml-lg-4 ml-xl-2"> 12:43 pm</span>
                                    </div>
                                    <div class="col-xl-5 col-lg-5 col-md-3 col-2"></div>
                                </div>
                                <!-- first person in chat - END -->

                                <!-- second person in chat - START -->
                                <div class="row py-4 pr-4">
                                    <div class="col-xl-5 col-lg-5 col-md-3 col-2"></div>
                                    <div class="col-xl-7 col-lg-7 col-md-9 col-10 pt-2 pb-2 bg_success-s br_10x10_left-s">
                                        <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar.</p>
                                    </div>
                                    <div class="col-12 pr-0 text-right">
                                        <span class="ft_12px-s"> 12:43 pm</span>
                                    </div>
                                </div>
                                <!-- second person in chat - END -->

                                <!-- first person in chat - START -->
                                <div class="row py-4">
                                    <div class="col-xl-1 col-lg-1 col-md-1 mr-md-3 mr-xl-0">
                                        <a href="javascript:void"><img class="dp_img_38px-s" src="../website/public/assets/images/add_quiz_teacher_icon.png" alt=""></a>
                                    </div>
                                    <div class="col-xl-6 col-lg-8 col-md-8 col-9 ml-3 ml-lg-2 pt-2 pb-2 bg_light-s br_10x10-s">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar.</p>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-8 pl-0 offset-lg-1 offset-xl-1 offset-md-2">
                                        <span class="ft_12px-s ml-3 ml-lg-4 ml-xl-2"> 12:43 pm</span>
                                    </div>
                                    <div class="col-xl-5 col-lg-5 col-md-3 col-2"></div>
                                </div>
                                <!-- first person in chat - END -->
                            </div>
                        </div>
                        <!-- chat container -END -->

                        <!-- chat Messages input - START -->
                        <div class="row">
                            <div class="col-12 bg_light-s pt-5 pb-3">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text attach_btn bg_light-s border-0"><i class="fa fa-paperclip"></i>
                                        <input type="hidden" name="" value="">
                                        </span>
                                    </div>
                                    <textarea name="" class="form-control type_msg border-0 mr-3" style="border-radius: 30px;     min-height: 47px; max-height: 48px;" placeholder="Type your message..."></textarea>
                                    <span class="mt-3 mr-4"><input type="hidden" name="" value="">send</span>
                                </div>
                            </div>
                        </div>
                        <!-- chat Messages input - START -->
                    </div>
                    <!-- Chat  Messages Container - END -->
                </div>
            </div>

@endsection
