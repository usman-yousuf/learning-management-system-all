@php
// dd($reviews);
@endphp


<div class="row mt-3">
    @forelse ($reviews as $item)
        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12 pt-5 mt-5 student_review_single_container-d uuid_{{ $item->uuid ?? '' }}" data-uuid="{{ $item->uuid ?? '' }}">
            <div class="container px-1">
                <div class="row ">
                    <div class="col-12 px-0 pr-xl-3">
                        <div class="body shadow ">
                            <div class="card body align-items-center">
                                <img class="card-img-top rounded-circle review_img-s img_120x120-s "src="{{ getFileUrl($item->student->profile_image ?? null, null, 'profile') }}" alt="{{ $item->student->first_name ?? 'Profile' . ' Image' }}" />
                                    <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                        <h5 class="text-success student_name-d">{{ $item->student->first_name ?? '' }}</h5>
                                        <span class="">Reviewed At: <strong class='review_date-d'>{{ date('d M Y', strtotime($item->created_at ?? 'now')) }}</strong></span>
                                        <div class="mt-3">
                                            {!! getStarRatingHTML($item->star_rating ?? 0) !!}
                                            <span class="rating_star-d">{{ $item->star_rating ?? 0 }}</span>
                                        </div>
                                        <div class="mt-3">
                                            <p>{{ $item->body ?? '' }}</p>
                                        </div>
                                        <div class="mt-3">
                                            <p class='w-100 text-center'>
                                                @if((\Auth::user()->profile_type == 'student') && ($item->student_id == \Auth::user()->profile_id))
                                                    <a href="javascript:void(0)" class='delete_review-d' data-uuid="{{ $item->uuid ?? '' }}"><img src="{{ asset('assets/images/delete_icon.svg') }}" alt="delete-review" /></a>
                                                    <a href="javascript:void(0)" class='edit_review-d' data-uuid="{{ $item->uuid ?? '' }}"><img src="{{ asset('assets/images/edit_icon.svg') }}" alt="edit-review" /></a>
                                                @endif
                                            </p>
                                        </div>

                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
</div>


<div class="cloneables_container-d" style='display:none;'>
    <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12 pt-5 mt-5 student_review_single_container-d" id="cloneable_single_review-container-d">
        <div class="container px-1">
            <div class="row ">
                <div class="col-12 px-0 pr-xl-3">
                    <div class="body shadow ">
                        <div class="card body align-items-center">
                            <img class="card-img-top rounded-circle review_img-s img_120x120-s" src="{{ getFileUrl($item->student->profile_image ?? null, null, 'profile') }}" alt="{{ $item->student->first_name ?? 'Profile' . ' Image' }}" />
                            <div class="card-block text-center mt-3 ml-3 mr-3  mb-3">
                                <h5 class="text-success student_name-d">{{ $item->student->first_name ?? '' }}</h5>
                                <span class="">Reviewed At: <strong class='review_date-d'>{{ date('d M Y', strtotime($item->created_at ?? 'now')) }}</strong></span>
                                <div class="mt-3">
                                    {!! getStarRatingHTML($item->star_rating ?? 0) !!}
                                    <span class="rating_star-d">{{ $item->star_rating ?? 0 }}</span>
                                </div>
                                <div class="mt-3">
                                    <p>{{ $item->body ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
