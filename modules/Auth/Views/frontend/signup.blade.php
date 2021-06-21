@extends('Auth::frontend.master')

@section('content')
    <div class="content container" id="login">
        <div class="d-flex justify-content-center login-box">
            <div class="card">
                <div class="card-header">
                    <h4>{{ trans('Sign Up') }}</h4>
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
                        <label for="change-language">{{ trans('Language') }}</label>
                        <select class="select2 form-control" id="change-language"
                                data-href="{{ route('change_locale','') }}">
                            <option value="en"
                                    @if(session()->get('locale') === 'en') selected @endif>{{ trans('English') }}
                                (US)
                            </option>
                            <option value="cn"
                                    @if(session()->get('locale') === 'cn') selected @endif>{{ trans('Chinese') }}
                                (Traditional)
                            </option>
                        </select>
                    </div>
                    <form action="" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ trans('Username') }}</label>
                            <input type="text" id="username" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">{{ trans('Email') }}</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">{{ trans('Password') }}</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="re-enter-password">{{ trans('Re-enter Password') }}</label>
                            <input type="password" id="re-enter-password" name="password_re_enter" class="form-control">
                        </div>
                        <div class="btn-group d-flex justify-content-center">
                            <button type="submit" class="btn btn-main-color w-100">{{ trans('Sign Up') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    {!! JsValidator::formRequest('Modules\Auth\Http\Requests\SignUpValidation') !!}
@endpush
