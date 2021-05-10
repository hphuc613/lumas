<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Elite Admin Template - The Ultimate Multipurpose admin template</title>
    <link href="{{ asset('assets/frontend/assets/node_modules/calendar/dist/fullcalendar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/frontend/dist/css/style.css') }}" rel="stylesheet">
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
</head>

<body class="skin-blue fixed-layout">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">Elite admin</p>
    </div>
</div>
<div id="main-wrapper">
    @include('Base::frontend.topbar')
    @include('Base::frontend.left_sidebar')
    <div class="page-wrapper">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger  alert-fade-out" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-fade-out" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="{{ asset('assets/frontend/assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/waves.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/sticky-kit-master/sticky-kit.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/custom.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/moment/moment.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/calendar/dist/cal-init.js') }}"></script>
<script src="{{ asset('assets/frontend/js/main.js') }}"></script>
</body>
<script>
    if ($('.alert-success').html() !== undefined) {
        $('.alert-danger').css('top', '120px');
    }
    slideAlert($('.alert-fade-out'));
</script>
</html>
