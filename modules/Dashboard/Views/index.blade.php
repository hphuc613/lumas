@extends('Base::layouts.master')

@section('content')
    {{  var_dump(App::getLocale()) }}
    {{  \Carbon\Carbon::createFromTimestamp(time())->format('H:i') }}
    {{ Str::random(6) }}
@endsection
