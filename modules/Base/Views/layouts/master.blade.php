<!doctype html>
<html lang="{{ (!empty(\App::getLocale())) ? \App::getLocale() : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
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
        <span class="alert-close"><i class="fas fa-times"></i></span>
        {{ session('error') ?? session('danger') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-primary alert-fade-out" style="display: none" role="alert">
        <span class="alert-close"><i class="fas fa-times"></i></span>
        {{ session('success') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning alert-fade-out" style="display: none" role="alert">
        <span class="alert-close"><i class="fas fa-times"></i></span>
        {{ session('warning') }}
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
<script src="{{ asset('assets/jquery/chart.js') }}"></script>
<script src="{{ asset('assets/jquery/moment.min.js') }}"></script>
<script src="{{ asset('assets/jquery/moment-with-locales.min.js') }}"></script>
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

        /** Show file manager */
        $(".btn-elfinder").click(function () {
            @php
                $locale = session()->get('locale');
                if($locale === 'cn'){
                    $locale = 'zh_TW';
                }
            @endphp
            openElfinder($(this), '{{ route("elfinder.connector") }}', '{{ asset("packages/barryvdh/elfinder/sounds") }}', "{{ $locale }}", '{{ csrf_token() }}');
        })

        pusherNotification("{{ env('PUSHER_APP_KEY') }}", {{ Auth::id() }}, "{{ route("get.member.appointment","") }}");
    });

</script>
@stack('js')
</html>
