@extends('teacher::layouts.teacher')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('chat.name') !!}
    </p>
@endsection
