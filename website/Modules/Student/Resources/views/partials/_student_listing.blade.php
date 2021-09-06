@php

@endphp

@forelse ($students as $item)
    <div class="col-xl-3 col-lg-4 col-lg-6 col-md-6 col-sm-12 py-3">
        <div class="card shadow ">
            <div class="card-body py-3">
                <div class="mb-4">
                    <img class="rounded-circle mx-auto d-block img_size-s" src="{{ getFileUrl($item->profile_image ?? null, null, 'profile') }}" alt="{{ $item->first_name ?? '' }}" />
                </div>
                <div class="text-center fg-success-s">
                    <h6 class="card-title">{{ $item->full_name }}</h6>
                </div>
                <div class="text-center">
                    @php
                        $last_enrollment_text = 'No Enrollment found';
                        if(isset($item->student_courses) && ($item->student_courses != null)) {
                            $last_enrollment_text = 'Last Enrollment: '. date('d M Y', strtotime($item->student_courses->created_at ?? 'now'));
                        }
                    @endphp
                    <span class="font_size_small-s">{{ $last_enrollment_text }}</span>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 py-3">
        <div class="card shadow ">
            <div class="card-body py-3">
                <div class="">
                    <div class="row">
                        <div class="col-12 text-center mt-5 mb-5">
                            No Record Found
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforelse
