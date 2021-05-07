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
    </div>

    <div id="user" class="card">
        <div class="card-body">
            @include('User::_form')
        </div>
    </div>
@endsection
