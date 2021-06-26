@php
    $chat = (isset($chat) && (null != $chat))? $chat : null;
@endphp



    @if(null == $chat)
        <!-- preloader - START -->
        <div class="row pt-4">
            <div class="col-12 d-flex justify-content-center">
                <div class="loader"></div>
            </div>
        </div>
        <!-- preloader - END -->
    @else
        @if(count($chat->messages))
            @foreach ($chat->messages as $item)
                @php
                    // dd($chat->messages);
                    $request = app('request');
                    $current_profile_id = $request->user()->profile_id;
                    $current_profile_uuid = $request->user()->profile->uuid;
                    $current_profile_image = $request->user()->profile->profile_image;
                @endphp
                <!-- timeline of chat - START -->
                {{--  <div class="row py-4">
                    <div class="col-12 d-flex justify-content-center">
                        <span class="ft_12px-s"> TODAY AT 4:30 PM </span>
                        @php
                            // print_array($msg);
                        @endphp
                    </div>
                </div>  --}}
                <!-- timeline of chat - END -->

                {{--  I am reciever of the message  --}}
                @if($current_profile_id == $item->sender_id)
                    <div class="row py-4 single_message_container-d uuid_{{ $item->uuid ?? '' }}" data-uuid="{{ $item->uuid ?? '' }}">
                        <div class="col-xl-1 col-lg-1 col-md-1 mr-md-3 mr-xl-0">
                            <a href="javascript:void">
                                <img class="dp_img_38px-s sender_image-d" src="{{ getFileUrl($item->sender->profile_image ?? null, null, 'profile') }}" alt="" />
                            </a>
                        </div>
                        <div class="col-xl-6 col-lg-8 col-md-8 col-9 ml-3 ml-lg-2 pt-2 pb-2 bg_light-s br_10x10-s">
                            <p class="message_body-d">
                                {{ $item->message ?? '' }}
                            </p>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8 col-8 pl-0 offset-lg-1 offset-xl-1 offset-md-2">
                            <span class="ft_12px-s ml-3 ml-lg-4 ml-xl-2 message_time-d"> {{ getRelativeTime($item->created_at ?? 'now') }}</span>
                        </div>
                        <span class='chat_uuid-d d-none'>{{ $item->chat->uuid ?? '' }}</span>
                        <span class='sender_uuid-d d-none'>{{ $item->sender->uuid ?? '' }}</span>
                        <span class='sender_image-d d-none'>{{ $item->sender->profile_image ?? '' }}</span>
                    </div>
                @else
                    <div class="row py-4 pr-4 single_message_container-d uuid_{{ $item->uuid ?? '' }}" data-uuid="{{ $item->uuid ?? '' }}">
                        <div class="col-xl-5 col-lg-5 col-md-3 col-2"></div>
                        <div class="col-xl-7 col-lg-7 col-md-9 col-10 pt-2 pb-2 bg_success-s br_10x10_left-s">
                            <p class="text-white message_body-d">
                                {{ $item->message ?? '' }}
                            </p>
                        </div>
                        <div class="col-12 pr-0 text-right">
                            <span class="ft_12px-s"> {{ getRelativeTime($item->created_at ?? 'now') }}</span>
                        </div>
                        <span class='chat_uuid-d d-none'>{{ $item->chat->uuid ?? '' }}</span>
                        <span class='sender_uuid-d d-none'>{{ $current_profile_uuid }}</span>
                        <span class='sender_image-d d-none'>{{ $current_profile_image }}</span>
                    </div>
                @endif
            @endforeach
        @endif
    @endif










