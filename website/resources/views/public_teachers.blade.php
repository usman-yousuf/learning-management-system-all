@php
    $target_layout = (\Auth::check()) ? 'teacher::layouts.teacher' : 'layouts.landing_page';
@endphp

@extends($target_layout)

@section('page-title')
    Teachers
@endsection

@php
    $teachers = getAllApprovedTeachers();
@endphp

@section('content')

        {{-- Asesome teachers - START --}}
        <section class="py-5">
            @if(\Auth::check())
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="h3 ml-5">Our Teachers</div>
                    </div>
                </div>
            @endif
            <div class="row mt-2">
                @include('partials.teachers', ['teachers' => $teachers])
            </div>
        </section>
        {{-- Asesome teachers - END --}}
@endsection
