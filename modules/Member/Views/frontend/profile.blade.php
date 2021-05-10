@extends('Base::frontend.master')

@section('content')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('Profile') }}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('Profile') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>{{ trans('Name') }}</label>
                                <input type="text" class="form-control form-control-line" name="name" value="{{ $member->name }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('Email') }}</label>
                                <input type="email" class="form-control form-control-line" name="email" value="{{ $member->email }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('Username') }}</label>
                                <input type="text" class="form-control form-control-line" name="username"
                                       value="{{ $member->username }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ trans('Password') }}</label>
                                <input type="password" class="form-control form-control-line" name="password">
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">
                                    {{ trans('Submit') }}
                                </button>
                                <button type="submit" class="btn btn-inverse waves-effect waves-light">
                                    {{ trans('Reset') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
