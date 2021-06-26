@php
    $listing_nature = (isset($listing_nature) && ('' != $listing_nature))? $listing_nature : '';
    $chats = (isset($chats) && $chats->total_chats)? $chats->chats : [];
@endphp

    @if('chat_sidebar' == $listing_nature)
        @if(!empty($chats))
            @foreach ($chats as $item)
                @php
                    $item->members = (array)$item->members;
                    $member = $item->members[0]->profile;
                @endphp
                <!-- --- chat list member 1 - start --- -->
                <div class="row py-3 border-bottom d-flex chat_list_members-s existing_chat_single_container-d @if($loop->iteration == 1) active @endif uuid_{{ $item->uuid ?? '' }}" data-uuid="{{ $item->uuid ?? '' }}">
                    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                        @php
                            // print_array($item);
                        @endphp
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-2 mr-xl-0 mr-md-3">
                                <a href="javascript:void">
                                    <img class="dp_img_38px-s chat_member_profile_image-d" src="{{ getFileUrl($member->profile_image ?? null, null, 'profile') }}" alt="user-profile" />
                                </a>
                            </div>
                            <div class="col-xl-10 col-lg-8 col-8">
                                <h6 class="mb-0 ml-1 chat_member_profile_name-d">{{ getTruncatedString($member->first_name ?? ' ' . $member->last_name ?? '') }}</h6>
                                <span class="ft_12px-s ml-1 chat_last-d">{{ getTruncatedString($item->last_message->message ?? 'no message yet') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                        <div class="dropdown">
                            <i class="fa fa-2x fa-angle-down dropdown_menu_on_left-s text-right" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item text-danger ft_12px-s py-2" href="javascript:void(0)">
                                    <i class="fa fa-trash"></i> Delete Chat
                                </a>
                            </div>
                        </div>
                        <span class="list_member_last_online_date-s ft_12px-s float-right">{{ date('Y-m-d', strtotime($item->last_message->created_at ?? '-1 minute')) }}</span>
                    </div>
                </div>
                <!-- --- chat list member 1 - end --- -->
            @endforeach
        @else
            <div class="row no_chat_user_container-d">
                <div class="col-12 py-3">
                    <p class="mt-3 text-center">
                        <strong>No User Chat Found</strong>
                    </p>
                </div>
            </div>
        @endif
    @elseif('new_chat_modal' == $listing_nature)
        @php
            $people = [];
            if(!is_array($users)){
                if($users->total_profiles){
                    $people = $users->profiles;
                }
            }
        @endphp
        @if(!empty($people))
            @foreach ($people as $item)
                <div class="row py-3 px-1 border-bottom d-flex chat_list_members-s new_chat_user_single_container-d uuid_{{ $item->uuid ?? '' }}" data-uuid="{{ $item->uuid ?? '' }}">
                    <div class="col-9">
                        <div class="row">
                            <div class="col-3 col-sm-2">
                                <a href="javascript:void">
                                    <img class="dp_img_46px-s profile_image-d" src="{{ getFileUrl($item->profile_image ?? null, null, 'profile') }}" alt="user-image" />
                                </a>
                            </div>
                            <div class="col-9 col-sm-10">
                                <h6 class="mb-0 ml-1 profile_name-d">{{ getTruncatedString($item->first_name ?? '' . $item->last_name ?? '') }}</h6>
                                <strong class="ft_12px-s ml-1 profile_type-d">{{ ucwords($item->profile_type ?? '') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <span class="ft_12px-s float-right">
                            <a href="javascript:void" class='send_new_message-d'>
                                <img src="{{ asset('assets/images/chat_new_message.svg') }}" alt="new-sms-icon" />
                            </a>
                        </span>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row no_chat_user_container-d">
                <div class="col-12 py-3">
                    <p class="mt-3 text-center">
                        <strong>No New User Found</strong>
                    </p>
                </div>
            </div>
        @endif
    @endif

