@php
    $listing_nature = (isset($listing_nature) && ('' != $listing_nature))? $listing_nature : '';
@endphp

    @if('chat_sidebar' == $listing_nature)
        <!-- --- chat list member 1 - start --- -->
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
                    <i class="fa fa-2x fa-angle-down dropdown_menu_on_left-s text-right" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item text-danger ft_12px-s" href="#">
                            <i class="fa fa-trash"></i> Delete Chat
                        </a>
                    </div>
                </div> <span class="list_member_last_online_date-s ft_12px-s float-right">30 Apr 2019 </span>
            </div>
        </div>
        <!-- --- chat list member 1 - end --- -->
    @elseif('new_chat_modal' == $listing_nature)
        <!-- --- chat list member 1 - start --- -->
        <div class="row py-3 px-1 border-bottom d-flex chat_list_members-s">
            <div class="col-9">
                <div class="row">
                    <div class="col-3 col-sm-2">
                        <a href="javascript:void">
                            <img class="dp_img_46px-s" src="{{ getFileUrl($item->profile_image ?? null, null, 'profile') }}" alt="user-image" />
                        </a>
                    </div>
                    <div class="col-9 col-sm-10">
                        <h6 class="mb-0 ml-1">jannifer killers</h6>
                        <span class="ft_12px-s ml-1">Lorem ipsum dolor sit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <span class="ft_12px-s float-right">
                    <img src="{{ asset('assets/images/chat_new_message.svg') }}" alt="new-sms-icon" />
                </span>
            </div>
        </div>
        <!-- --- chat list member 1 - end --- -->
    @endif

