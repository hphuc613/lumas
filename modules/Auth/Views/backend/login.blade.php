@extends('Auth::backend.master')

@section('content')
<div class="content container" id="login">
    <div class="d-flex justify-content-center login-box">
        <div class="card">
            <div class="card-header">
                <h4>Login</h4>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="form-group">
                    <label for="password">{{ trans('Language') }}</label>
                    <select class="select2 form-control" id="change-language" data-href="{{ route('change_locale','') }}">
                        <option value="en" @if(session()->get('locale') === 'en') selected @endif>{{ trans('English') }}
                            (US)
                        </option>
                        <option value="cn" @if(session()->get('locale') === 'cn') selected @endif>{{ trans('Chinese') }}
                            (Traditional)
                        </option>
                    </select>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ trans('Email') }}</label>
                        <input type="email" id="email" name="email" class="form-control"
                               @if(session('email')) value="{{ session('email') }}" @endif>
                    </div>
                    <div class="form-group">
                        <label for="password">{{ trans('Password') }}</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <div class="form-group checkbox">
                        <input type="checkbox" class="checkbox-style" name="remember" checked>
                        <span class="checkbox-option pl-2">{{ trans('Remember me?') }}</span>
                    </div>
                    <div class="btn-group d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-100">{{ trans('Login') }}</button>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('get.logout.forgot_password') }}" class="text-info">{{ trans('Forget Password?') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
