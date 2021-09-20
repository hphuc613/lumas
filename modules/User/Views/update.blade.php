@extends('Base::layouts.master')
@php
    $segment = App\AppHelpers\Helper::segment(1);
    $page = ($segment === 'profile') ? trans('Profile') : trans('Update User')
@endphp
@section('content')
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.user.list') }}">{{ trans('User') }}</a></li>
                    <li class="breadcrumb-item active">{{ $page }}</li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ $page }}</h3></div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>
    <div id="user" class="card">
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" href="javascript:">{{ $page }}</a>
                </li>
                @if(!$user->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" id="salary-tab"
                           href="{{ route('get.user.salary', [$user->id, 'previous_page' => $segment]) }}">{{ trans('Salary') }}</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content p-4">
                <div class="tab-pane fade show active" id="profile">
                    @include('User::_form')
                </div>
            </div>
        </div>
    </div>
@endsection
