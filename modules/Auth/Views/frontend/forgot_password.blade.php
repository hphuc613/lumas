@extends('Auth::frontend.master')

@section('content')
    <div class="content container" id="login">
        <div class="d-flex justify-content-center">
            <div class="card" style="margin-top: 150px;">
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
                            <label
                                    for="email">{{ trans('Enter your registered email below, We will send you a new password') }}</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <div class="btn-group d-flex justify-content-center">
                            <button type="submit" class="btn btn-main-color w-100">{{ trans('Confirm') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
