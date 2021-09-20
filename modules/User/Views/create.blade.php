@extends('Base::layouts.master')

@section('content')
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.user.list') }}">{{ trans('User') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('Create User') }}</li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Create User") }}</h3></div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>

    <div id="user" class="card">
        <div class="card-body">
            @include('User::_form')
        </div>
    </div>
@endsection
