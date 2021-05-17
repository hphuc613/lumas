@extends('Base::layouts.master')
@section('content')
    @php
        $page = App\AppHelpers\Helper::segment(1) === 'profile' ? 'Profile' : 'Update User';
    @endphp
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.user.list') }}">{{ trans('User') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans($page) }}</li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Update User") }}</h3></div>
        </div>
    </div>

    <div id="user" class="card">
        <div class="card-body">
            @include('User::_form')
        </div>
    </div>
@endsection
