@extends('Base::layouts.master')

@section('content')
    @env('local')
        <div class="text-capitalize">{{  gg_trans("Hello", "vi") }}</div>
        {{  var_dump(App::getLocale()) }}
        {{  \Carbon\Carbon::createFromTimestamp(time())->format('H:i') }}
        {{ Str::random(6) }}
    @endenv
@endsection
