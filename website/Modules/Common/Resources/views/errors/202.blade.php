@extends('common::layouts.error')

@section('error-code')
    202
@endsection

@section('content')
    <div class="container text-center">
        <div class="row">
            <div class="col-10 offset-1">
                <h1>{{ '202' }}</h1>
                <p>Please wait while Admin Approves your Account</p>

                <p>Go <a href="{{ route('home') }}">Back</a></p>
            </div>
        </div>
    </div>
@endsection
