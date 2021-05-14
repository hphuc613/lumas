@extends('Base::layouts.master')

@section('content')
    @env('local')
        {{  var_dump(App::getLocale()) }}
        {{  \Carbon\Carbon::createFromTimestamp(time())->format('H:i') }}
        {{ Str::random(6) }}
    @endenv
@endsection
