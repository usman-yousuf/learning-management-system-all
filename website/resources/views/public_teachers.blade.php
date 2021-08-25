@extends('layouts.landing_page')

@section('page-title')
    Teachers
@endsection

@section('content')

        {{-- Asesome teachers - START --}}
        <section class="py-5">
            <div class="row mt-4">
                @php
                    $teachers = getAllApprovedTeachers();
                @endphp
                @include('partials.teachers', ['teachers' => $teachers])
            </div>
        </section>
        {{-- Asesome teachers - END --}}
@endsection
