@php

@endphp

    @if(count($teachers))
        @foreach ($teachers as $item)
            <div class="col-xl-3 col-lg-4 col-md-6 col-12 mt-3">
                <div class="card border-0" >
                    <img class="h_175px-s object_fit_contain-s" src="{{ getFileUrl($item->profile_image ?? null, null, 'profile') }}" alt="Profile Image" />
                    <div class="card-body pl-2 mx-auto">
                        <h5 class="card-title mb-1">
                            <a href='{{ route('viewTeacherProfile', ['uuid' => $item->uuid ?? '']) }}'>{{ $item->full_name ?? '' }}</a>
                        </h5>
                        @php
                            $raters = $item->total_rater_count;
                            if($raters < 1){
                                $raters = 1;
                            }
                        @endphp
                        <h6 class="fg_stone_color-s text-center">{!! getStarRatingHTML($item->total_rating_count / $raters) !!}</h6>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12 text-center mt-4 mb-4">
            <p class="w-100 text-center">
                <strong>
                    No Teacher(s) Found
                </strong>
            </p>
        </div>
    @endif
