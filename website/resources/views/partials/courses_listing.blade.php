@php

@endphp

    @if($courses->count())
        @foreach ($courses as $item)
            @php
                $view_url = route('course.preview', ['uuid' => $item->uuid]);
                $raters_count = $item->total_rater_count;
                if($raters_count < 1){
                    $raters_count = 1;
                }
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6 col-12 mt-3">
                <div class="card shadow border-0 br_10px-s">
                    <img class="card-img-top br_top_right_left_10px-s border_bottom_gradient_color-s h_175px-s img-thumbnail object_fit_contain-s" src="{{ getFileUrl($item->image ?? null, null, 'course') }}" alt="teacher image">
                    <div class="card-body">
                        <h6 class="card-title text-wrap text-break fg_green-s">
                            <a href='{{ $view_url }}' class='no_link-s hover_effect-s'>
                                <strong>{{ getTruncatedString(ucwords($item->title ?? '')) }}</strong>
                            </a>
                        </h6>
                        <div class="d-flex">
                            <div class="fg_light_grey-s">
                                {{ get_padded_number($item->students_count ?? 0) }} Students
                            </div>
                            <div class="pl-2 fg_light_grey-s text-right">
                                {{ get_padded_number($item->slots_count ?? 0) }} Slots
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-8">
                                <div class="d-flex">
                                    <div>
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
    @else
        <div class="col-12 text-center mt-4 mb-4">
            <p class="w-100 text-center">
                <strong>
                    No Course(s) Found
                </strong>
            </p>
        </div>
    @endif
