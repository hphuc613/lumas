<!doctype html>
<html lang="{{ \App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/datetimepicker/css/datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/main.css') }}">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/barryvdh/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/barryvdh/elfinder/css/theme.css') }}">
    <title>{{ trans('Lumas System - Administration') }}</title>
    @stack('css')
</head>
<body>

<!-- Header -->
<header class="topbar d-flex justify-content-between">
    @include('Base::layouts.topbar')
</header>
<!-- Left Sidebar -->
@include('Base::layouts.left_sidebar')
<!-- Content -->
<div class="page-wrapper clearfix">
    <div class="container-fluid page-content">
        @yield('content')
    </div>
</div>
@if (session('error') || session('danger'))
    <div class="alert alert-danger alert-fade-out" style="display: none" role="alert">
        {{ session('error') ?? session('danger') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-primary alert-fade-out" style="display: none" role="alert">
        {{ session('success') }}
    </div>
@endif
<!-- Footer -->
</body>
<script src="{{ asset('assets/jquery/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/datetimepicker/js/datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/datetimepicker/js/locales/bootstrap-datetimepicker.zh-TW.js') }}"></script>
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="{{ asset('vendor/barryvdh/elfinder/js/elfinder.full.js') }}"></script>
<script src="{{ asset("vendor/barryvdh/elfinder/js/i18n/elfinder.zh_TW.js") }}"></script>
<script src="{{ asset('assets/backend/jquery/main.js') }}"></script>
<script src="{{ asset('assets/backend/jquery/modal.js') }}"></script>
<script src="{{ asset('assets/backend/jquery/menu.js') }}"></script>
<script src="{{ asset('assets/backend/jquery/custom.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('.change-language').select2();
        $('[data-toggle="tooltip"]').tooltip()
        if ($('.alert-primary').html() !== undefined) {
            $('.alert-danger').css('top', '120px');
        }
        slideAlert($('.alert-fade-out'));
        $(".btn-elfinder").click(function () {
            @php
                $locale = session()->get('locale');
                if($locale === 'cn'){
                    $locale = 'zh_TW';
                }
            @endphp
            openElfinder($(this), '{{ route("elfinder.connector") }}', '{{ asset("packages/barryvdh/elfinder/sounds") }}', "{{ $locale }}", '{{ csrf_token() }}');
        })
    });
</script>
@stack('js')
</html>
