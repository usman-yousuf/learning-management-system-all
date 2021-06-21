@php
// dd($students);
@endphp


<div class="row mt-3">
    @forelse ($students as $item)
        <div class="col-xl-3 col-md-6 col-12 mb-3 pr-1 pl-1 single_enrolment_container-d">
            <div class="body shadow">
                <div class="card body align-items-center">
                    <img class="card-img-top rounded-circle img_120x120-s mt-4 mb-4 student_profile_image-d" src="{{ getFileUrl($item->student->profile_image ?? null, null, 'profile') }}" alt="{{ $item->first_name ?? 'profile' .' profile image' }}" />
                    <div class="card-block text-center mb-5">
                        <h5 class="text-success"><a href='javascript:void(0)' class='no_link-s student_name-d'>{{ $item->student->first_name ?? '' }}</a></h5>
                        <span class="">Enrolled At: <strong class='enrolment_date-d'>{{ date('d M Y', strtotime($item->created_at)) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse
</div>


<div class="cloneables_container-d" style='display:none;'>
    <div class="col-xl-3 col-md-6 col-12 mb-3 pr-1 pl-1 single_enrolment_container-d" id='cloneable_single_enrolment_container-d'>
        <div class="body shadow">
            <div class="card body align-items-center">
                <img class="card-img-top rounded-circle img_120x120-s mt-4 mb-4 student_profile_image-d" src="{{ getFileUrl($item->student->profile_image ?? null, null, 'profile') }}" alt="{{ $item->first_name ?? 'profile' .' profile image' }}" />
                <div class="card-block text-center mb-5">
                    <h5 class="text-success"><a href='javascript:void(0)' class='no_link-s student_name-d'>{{ $item->student->first_name ?? '' }}</a></h5>
                    <span class="">Enrolled At: <strong class='enrolment_date-d'>{{ date('d M Y', strtotime($item->created_at ?? 'now')) }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
