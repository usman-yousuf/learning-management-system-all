@php
    $listing_nature = (isset($listing_nature) && ('' != $listing_nature))? $listing_nature : '';
@endphp

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
            <a href="javascript:void">
                <img class="dp_img_38px-s" src="{{ getFileUrl($item->profile->profile_image ?? null, null, 'profile') }}" alt="" />
            </a>
        </div>
        <div class="col-xl-6 col-lg-8 col-md-8 col-9 ml-3 ml-lg-2 pt-2 pb-2 bg_light-s br_10x10-s">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum bibendum justo at magna pulvinar.
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
            <span class="ft_12px-s"> 05:43 pm</span>
        </div>
    </div>
    <!-- second person in chat - END -->

    @if('chat_sidebar' == $listing_nature)

    @elseif('new_chat_modal' == $listing_nature)

    @endif

