<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/datetimepicker/css/datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/main.css') }}">
    <title>Login Page</title>
</head>
<body  style="background-image: url('/logo/bg-login.jpg'); background-size: cover; background-repeat: no-repeat">
<div class="content container" id="login">
    <div class="d-flex justify-content-center" style="margin-top: 150px">
        <div class="card">
            <div class="card-header">
                <h4>{{ trans('Reset Password') }}</h4>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ trans('Enter your registered email below, We will send you a new password') }}</label>
                        <input type="email" id="email" name="email" class="form-control" @if(session('email')) value="{{ session('email') }}" @endif>
                    </div>
                    <div class="btn-group d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-100">{{ trans('Confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script src="{{ asset('assets/jquery/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap/datetimepicker/js/datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/backend/jquery/main.js') }}"></script>
</html>
