@extends('Base::layouts.master')
@php
    $dir = "vendor/barryvdh/elfinder";
    $locale = session()->get('locale');
    if($locale === 'cn'){
        $locale = 'zh_TW';
    }elseif($locale === 'en'){
        $locale = 'el';
    }
@endphp
@push('css')
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/theme.css') }}">
@endpush
@section('content')
    <div id="elfinder"></div>
@endsection
@push('js')
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="{{ asset($dir.'/js/elfinder.full.js') }}"></script>

    @if($locale)
        <script src="{{ asset($dir."/js/i18n/elfinder.$locale.js") }}"></script>
    @endif

    <script type="text/javascript" charset="utf-8">
        $().ready(function () {
            $('#elfinder').elfinder({
                @if($locale)
                lang: '{{ $locale }}',
                @endif
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ route("elfinder.connector") }}',
                soundPath: '{{ asset($dir.'/sounds') }}'
            });
        });
    </script>
@endpush
