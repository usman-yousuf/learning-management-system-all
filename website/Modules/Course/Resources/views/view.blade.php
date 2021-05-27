@extends('teacher::layouts.teacher')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('course.name') !!}
    </p>
@endsection
