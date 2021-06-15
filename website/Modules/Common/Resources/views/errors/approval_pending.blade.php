@extends('common::layouts.error')

@section('error-code')
    202
@endsection

@section('content')
    <div class="container text-center">
        <div class="row">
            <div class="col-10 offset-1">
                <h1>{{ '403' }}</h1>
                <p>You are not authorized to access this area</p>

                <p>Go <a href='{{ route('home') }}'>Back</a></p>
            </div>
        </div>
    </div>
@endsection
