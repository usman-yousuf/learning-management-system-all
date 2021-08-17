@php
    $courses = getAllApprovedCourses();
@endphp

    @if($courses->count())

        <!-- For extra-LARGE SCREEN - START -->
        <div class="col-12 d-none d-xl-block">

            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($courses->chunk(3) as $three)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row my-4">
                                @foreach ($three as $item)
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <div class="card shadow border-0 br_10px-s">
                                            <img class="card-img-top br_top_right_left_10px-s border_bottom_gradient_color-s h_175px-s img-thumbnail object_fit_contain-s" src="{{ getFileUrl($item->image ?? null, null, 'course') }}" alt="teacher image">
                                            <div class="card-body">
                                                <h6 class="card-title text-wrap text-break">{{ getTruncatedString(ucwords($item->title ?? '')) }}</h6>
                                                <div class="d-flex">
                                                    <div class="fg_light_grey-s">
                                                        {{ get_padded_number($item->students_count) }} Students
                                                    </div>
                                                    <div class="pl-2 fg_light_grey-s text-right">
                                                        {{ get_padded_number($item->slots_count) }} Slots
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-8">
                                                        <div class="d-flex">
                                                            <div>
                                                                @php
                                                                    $raters_count = $item->total_rater_count;
                                                                    if($raters_count < 1){
                                                                        $raters_count = 1;
                                                                    }
                                                                @endphp
                                                                {!! getStarRatingHTML($item->total_rating_count / $raters_count) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-md-right pt-1 align-self-center">
                                                        <span>{{ getCoursePriceWithUnit($item) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!--For Extra-LARGE SCREEn End-->

        <!--FOR LARGE SCREEN START-->
        <div class="col-12 d-none d-xl-none d-lg-block">
            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($courses->chunk(3) as $three)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row my-4">
                                @foreach ($three as $item)
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <div class="card shadow border-0 br_10px-s">
                                            <img class="card-img-top br_top_right_left_10px-s border_bottom_gradient_color-s h_175px-s img-thumbnail object_fit_contain-s" src="{{ getFileUrl($item->image ?? null, null, 'course') }}" alt="teacher image">
                                            <div class="card-body">
                                                <h6 class="card-title text-wrap text-break">{{ getTruncatedString(ucwords($item->title ?? '')) }}</h6>
                                                <div class="d-flex">
                                                    <div class="fg_light_grey-s">
                                                        {{ get_padded_number($item->students_count) }} Students
                                                    </div>
                                                    <div class="pl-2 fg_light_grey-s text-right">
                                                        {{ get_padded_number($item->slots_count) }} Slots
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-8">
                                                        <div class="d-flex">
                                                            <div>
                                                                @php
                                                                    $raters_count = $item->total_rater_count;
                                                                    if($raters_count < 1){
                                                                        $raters_count = 1;
                                                                    }
                                                                @endphp
                                                                {!! getStarRatingHTML($item->total_rating_count / $raters_count) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-md-right pt-1 align-self-center">
                                                        <span>{{ getCoursePriceWithUnit($item) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!--FOR LARGE SCREEN END-->

        <!--FOR MEDIUM SCREEN START-->
        <div class="col-12 d-none d-xl-none d-lg-none d-md-block">
            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($courses->chunk(2) as $two)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row my-4">
                                @foreach ($two as $item)
                                    <div class="col-md-6 col-12">
                                        <div class="card shadow border-0 br_10px-s">
                                            <img class="card-img-top br_top_right_left_10px-s border_bottom_gradient_color-s h_175px-s img-thumbnail object_fit_contain-s" src="{{ getFileUrl($item->image ?? null, null, 'course') }}" alt="teacher image">
                                            <div class="card-body">
                                                <h6 class="card-title text-wrap text-break">{{ getTruncatedString(ucwords($item->title ?? '')) }}</h6>
                                                <div class="d-flex">
                                                    <div class="fg_light_grey-s">
                                                        {{ get_padded_number($item->students_count) }} Students
                                                    </div>
                                                    <div class="pl-2 fg_light_grey-s text-right">
                                                        {{ get_padded_number($item->slots_count) }} Slots
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-8">
                                                        <div class="d-flex">
                                                            <div>
                                                                @php
                                                                    $raters_count = $item->total_rater_count;
                                                                    if($raters_count < 1){
                                                                        $raters_count = 1;
                                                                    }
                                                                @endphp
                                                                {!! getStarRatingHTML($item->total_rating_count / $raters_count) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-md-right pt-1 align-self-center">
                                                        <span>{{ getCoursePriceWithUnit($item) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!--FOR MEDIUM SCREEN END-->

        <!--FOR SMALL SCREEN START-->
        <div class="col-12 d-block d-sm-none">
            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">

                <div class="carousel-inner">
                    @foreach ($courses as $item)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row my-4">
                                <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                    <div class="card shadow border-0 br_10px-s">
                                        <img class="card-img-top br_top_right_left_10px-s border_bottom_gradient_color-s h_175px-s img-thumbnail object_fit_contain-s" src="{{ getFileUrl($item->image ?? null, null, 'course') }}" alt="teacher image">
                                        <div class="card-body">
                                            <h6 class="card-title text-wrap text-break">{{ getTruncatedString(ucwords($item->title ?? '')) }}</h6>
                                            <div class="d-flex">
                                                <div class="fg_light_grey-s">
                                                    {{ get_padded_number($item->students_count) }} Students
                                                </div>
                                                <div class="pl-2 fg_light_grey-s text-right">
                                                    {{ get_padded_number($item->slots_count) }} Slots
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-8">
                                                    <div class="d-flex">
                                                        <div>
                                                            @php
                                                                $raters_count = $item->total_rater_count;
                                                                if($raters_count < 1){
                                                                    $raters_count = 1;
                                                                }
                                                            @endphp
                                                            {!! getStarRatingHTML($item->total_rating_count / $raters_count) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4 text-md-right pt-1 align-self-center">
                                                    <span>{{ getCoursePriceWithUnit($item) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!--FOR SMALL SCREEN END-->

    @else
        <div class="col-12 text-center mt-4 mb-4">
            <p class="w-100 text-center">
                <strong>
                    No Course(s) Found
                </strong>
            </p>
        </div>
    @endif
